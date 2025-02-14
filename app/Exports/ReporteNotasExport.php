<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;

class ReporteNotasExport implements
    FromCollection,
    WithHeadings,
    WithStyles,
    WithTitle,
    WithEvents,
    WithCustomStartCell
{
    protected $asignatura;
    protected $datos;

    /**
     * Arreglo para almacenar los grupos (bloques) por estudiante.
     * Cada elemento tendrá ['start' => X, 'end' => Y].
     */
    protected $groups = [];

    /**
     * @param mixed  $asignatura (puede ser el modelo o un string con el nombre)
     * @param array  $datos      Estructura: [[nombre, codigo, actividad, nota], ...]
     */
    public function __construct($asignatura, array $datos)
    {
        $this->asignatura = $asignatura;
        $this->datos      = $datos;
    }

    /**
     * Prepara la colección y agrupa las filas por estudiante,
     * guardando los rangos de filas para luego hacer merge en AfterSheet.
     */
    public function collection()
    {
        // 1. Filtrar (si tienes filas de 'Total'), y separar si quieres ponerlos al final
        $isTotal = fn($row) => isset($row[0]) && strtolower($row[0]) === 'promedio general';
        $nonTotalData = array_filter($this->datos, fn($row) => !$isTotal($row));
        $totalRows    = array_filter($this->datos, fn($row) =>  $isTotal($row));

        // 2. Ordenar los datos por código (columna 1) o nombre (columna 0), según tu preferencia
        //    Aquí ordenamos por código (columna 1)
        $sortedData = collect($nonTotalData)->sortBy(function($row) {
            return $row[1]; // Ordenar por código
        })->values(); // ->values() para reindexar desde 0

        $processed = [];
        $currentCode = null;
        $startIndex  = null; // Para marcar inicio del bloque
        $rowCounter  = 0;    // Contará las filas en $processed

        // 3. Construir el array final y capturar rangos
        foreach ($sortedData as $row) {
            $rowCounter++;
            [$name, $code, $activity, $note] = $row;

            // ¿Cambiamos de estudiante?
            if ($code !== $currentCode) {
                // Si había un bloque previo, lo cerramos
                if ($currentCode !== null && $startIndex !== null) {
                    // Guardamos el rango de filas que pertenecen al estudiante anterior
                    $this->groups[] = [
                        'start' => $startIndex,
                        'end'   => $rowCounter - 1, // hasta la fila anterior
                    ];
                }
                // Abrimos un nuevo bloque
                $startIndex  = $rowCounter;
                $currentCode = $code;
            }

            $processed[] = $row;
        }

        // Cerrar el último bloque si hay datos
        if (!empty($processed) && $startIndex !== null) {
            $this->groups[] = [
                'start' => $startIndex,
                'end'   => $rowCounter,
            ];
        }

        // 4. Agregar filas de Total (si las hay) al final
        foreach ($totalRows as $totalRow) {
            $processed[] = $totalRow;
            $rowCounter++;
            // Si quieres que el "Total" no se mezcle con merges,
            // no lo incluyas en $this->groups
        }

        return new Collection($processed);
    }

    /**
     * Encabezados en la fila 2 (la fila 1 la usamos para el título de la asignatura).
     */
    public function headings(): array
    {
        return ["Estudiante", "Código", "Actividad", "Nota"];
    }

    /**
     * Iniciamos la data a partir de A2.
     */
    public function startCell(): string
    {
        return 'A2';
    }

    /**
     * Estilos básicos para la tabla.
     */
    public function styles(Worksheet $sheet)
    {
        // Estilo para los encabezados (fila 2)
        $sheet->getStyle('A2:D2')->applyFromArray([
            'font' => [
                'bold'  => true,
                'color' => ['argb' => 'FFFFFFFF'],
            ],
            'fill' => [
                'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF4F81BD'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color'       => ['argb' => 'FF000000'],
                ],
            ],
        ]);

        // Alinear al centro el contenido de la tabla (desde A2 hasta la última fila)
        $highestRow = $sheet->getHighestRow();
        $sheet->getStyle("A2:D{$highestRow}")
              ->getAlignment()
              ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        return [];
    }

    /**
     * El nombre de la asignatura (o su propiedad 'nombre') será el título de la hoja.
     */
    public function title(): string
    {
        return is_string($this->asignatura) ? $this->asignatura : $this->asignatura->nombre;
    }

    /**
     * Eventos para combinar celdas y aplicar estilos finales.
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // 1. Combinar celdas de A1 a D1 para el título
                $sheet->mergeCells('A1:D1');
                $sheet->setCellValue('A1', $this->title());

                // 2. Estilo para la fila 1 (título)
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold'  => true,
                        'size'  => 16,
                        'color' => ['argb' => 'FF000000'],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);
                $sheet->getRowDimension('1')->setRowHeight(30);

                // 3. Bordes para todo el rango
                $highestRow = $sheet->getHighestRow();
                $sheet->getStyle("A1:D{$highestRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color'       => ['argb' => 'FF000000'],
                        ],
                    ],
                ]);

                // 4. Ajustar el ancho de las columnas
                foreach (range('A', 'D') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                // 5. Ahora hacemos el "merge" de las columnas A y B para cada grupo.
                //    Recuerda que la fila de encabezados es la 2, por lo que la data
                //    comienza en la fila 3 en Excel.
                //    Si un bloque es start=1, end=3, significa que se trata de las filas
                //    (3 a 5) en el Excel real.
                $dataStartRow = 2; // La fila 2 tiene encabezados, así que la primera fila de datos es la 3
                foreach ($this->groups as $group) {
                    $start = $group['start'] + $dataStartRow;
                    $end   = $group['end']   + $dataStartRow;

                    // Evita merge si start == end (solo 1 fila)
                    if ($start < $end) {
                        // Merge en la columna A (Estudiante)
                        $sheet->mergeCells("A{$start}:A{$end}");
                        // Merge en la columna B (Código)
                        $sheet->mergeCells("B{$start}:B{$end}");

                        // Alinear verticalmente centrado
                        $sheet->getStyle("A{$start}:A{$end}")
                              ->getAlignment()
                              ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                        $sheet->getStyle("B{$start}:B{$end}")
                              ->getAlignment()
                              ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                    }
                }
            },
        ];
    }
}
