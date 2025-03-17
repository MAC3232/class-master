<?php

use App\Http\Controllers\Admin\AsignaturasCrudController;
use App\Http\Controllers\Admin\AsistenciaController;
use App\Http\Controllers\Admin\AssignmentStudentController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\CriterioActividadController;
use App\Http\Controllers\Admin\CriterioController;
use App\Http\Controllers\Admin\DescripcionCriterioNivelController;
use App\Http\Controllers\Admin\EjeContenidoController;
use App\Http\Controllers\Admin\EstrategiaController;
use App\Http\Controllers\Admin\EstudiantesCrudController;
use App\Http\Controllers\Admin\EvaluarEstudianteController;
use App\Http\Controllers\Admin\LevelController;
use App\Http\Controllers\Admin\QrAsistenciaController;
use App\Http\Controllers\Admin\RAController;
use App\Http\Controllers\Admin\RubricaActividadController;
use App\Http\Controllers\Admin\StudentManagementController;
use App\Http\Controllers\ReportesController;
use App\Http\Controllers\RubricaController;
use Illuminate\Support\Facades\Route;


// --------------------------
// Custom Backpack Routes
// --------------------------
// Este archivo se carga automáticamente por Backpack\CRUD.
// Las rutas generadas por Backpack\Generators se colocarán aquí.

Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace' => 'App\Http\Controllers\Admin',
], function () {


    // Rutas de gestión de estudiantes
    Route::get('students/manage', [StudentManagementController::class, 'index'])
         ->name('students.manage');
    Route::post('students/manage/onlystudent', [StudentManagementController::class, 'storeSingleStudent'])
         ->name('only.students');
    Route::post('students/manage/importStudents', [StudentManagementController::class, 'importStudents'])
         ->name('import.students');

    // Reportes
    Route::get('/exportar-excel/{id}', [ReportesController::class, 'exportReporte'])
         ->name('reportes.general');
    Route::get('/reportes/', [ReportesController::class, 'index'])
         ->name('reportes');
    Route::get('/estudianteReport/{id}/estudents', [ReportesController::class, 'estudianteReport'])
         ->name('estudianteReport');
    Route::get('/estudianteReport/{id}/estudents/{student}', [ReportesController::class, 'graph'])
         ->name('graph');

    // API para Asignaturas
    Route::get('admin/api/asignaturas', [AsignaturasCrudController::class, 'getAsignaturas']);


    Route::get('courses', [CourseController::class, 'index'])->name('courses.index');
    Route::get('searchCourses', [CourseController::class, 'searchAsignatura']);



    // Rutas CRUD con Backpack (tanto admin como super-admin tendrán acceso, asumiendo que en el seeder el super-admin tiene todos los permisos)

    Route::crud('asignaturas', 'AsignaturasCrudController');
    Route::crud('estudiantes', 'EstudiantesCrudController');
    Route::crud('user', 'UserCrudController');
    Route::crud('facultad', 'FacultadCrudController');
    Route::crud('carrera', 'CarreraCrudController');  // CRUD para carreras
    Route::get('estudiantes/filter-assignments', [EstudiantesCrudController::class, 'obtenerAsignaturas'])
         ->name('filter.assignments');

    // Rutas para Rubricas
    Route::get('asignaturas/{id}/rubrica', [RubricaController::class, 'showDisenador'])
         ->name('rubrica.disenador');
    Route::get('asignaturas/{id}/rubrica/editor', [RubricaController::class, 'editor'])
         ->name('rubrica.editor');
    Route::get('asignaturas/{id}/rubrica/create', [RubricaController::class, 'create'])
         ->name('rubrica.create');
    Route::post('asignaturas/rubrica/store', [RubricaController::class, 'store'])
         ->name('rubrica.store');

    // Rutas para RA Rubrica
    Route::get('asignaturas/{id}/rarubrica/create', [RAController::class, 'create'])
         ->name('rarubrica.create');
    Route::post('asignaturas/{id}/rarubrica/store/{id_rubrica}', [RAController::class, 'store'])
         ->name('rarubrica.store');
    Route::get('ra/edit/{id}', [RAController::class, 'edit'])
         ->name('ra.edit');
    Route::put('ra/update/{id}/{id_rubrica}', [RAController::class, 'update'])
         ->name('ra.update');
    Route::delete('ra/destroy/{id}', [RAController::class, 'destroy'])
         ->name('ra.destroy');

    // Rutas para Criterio Rubrica
    Route::get('asignaturas/{id}/criteriorubrica/create', [CriterioController::class, 'create'])
         ->name('criterio.create');
    Route::post('asignaturas/{id}/criteriorubrica/store', [CriterioController::class, 'store'])
         ->name('criterio.store');
    Route::get('asignaturas/{id}/criteriorubrica/{criterio_id}/edit', [CriterioController::class, 'edit'])
         ->name('criterio.edit');
    Route::put('asignaturas/criteriorubrica/{criterio_id}/update', [CriterioController::class, 'update'])
         ->name('criterio.update');
    Route::delete('criterio/destroy/{id}', [CriterioController::class, 'destroy'])
         ->name('criterio.destroy');

    Route::post('asignaturas/{id}/metodoaprendizaje/store', [CriterioController::class, 'store'])
         ->name('enseñanza.store');

    // Rutas para Estrategias de Aprendizaje
    Route::get('estrategias/create/{ra_id}', [EstrategiaController::class, 'create'])
         ->name('estrategia.create');
    Route::post('estrategias/store/{ra_id}', [EstrategiaController::class, 'store'])
         ->name('estrategia.store');
    Route::get('estrategias/edit/{id}', [EstrategiaController::class, 'edit'])
         ->name('estrategia.edit');
    Route::put('estrategias/update/{id}', [EstrategiaController::class, 'update'])
         ->name('estrategia.update');
    Route::delete('estrategias/destroy/{id}', [EstrategiaController::class, 'destroy'])
         ->name('estrategia.destroy');

    // Rutas de Eje de Contenido
    Route::get('/ejes-contenido/create/{ra_id}', [EjeContenidoController::class, 'create'])
         ->name('eje_contenido.create');
    Route::post('/ejes-contenido/store/{ra_id}', [EjeContenidoController::class, 'store'])
         ->name('eje_contenido.store');
    Route::get('/ejes-contenido/edit/{id}', [EjeContenidoController::class, 'edit'])
         ->name('eje_contenido.edit');
    Route::put('/ejes-contenido/update/{id}', [EjeContenidoController::class, 'update'])
         ->name('eje_contenido.update');
    Route::delete('/ejes-contenido/destroy/{id}', [EjeContenidoController::class, 'destroy'])
         ->name('eje_contenido.destroy');

    // Rutas para Evaluación de Estudiantes
    Route::post('/selectcriterios', [EvaluarEstudianteController::class, 'storeSelectCriterioEstudent']);
    Route::get('/actividad/{actividad}/evaluatestudent/{student}', [EvaluarEstudianteController::class, 'getNoteStudent']);
    Route::get('asignaturas/{id}/evaluar-estudiante', [EvaluarEstudianteController::class, 'evaluarEstudiantes'])
         ->name('evaluar.estudiante');
    Route::get('asignaturas/{actividad_id}/evaluar-estudiante/{id}/Evaluar', [EvaluarEstudianteController::class, 'evaluar'])
         ->name('evaluar.estudiante_asignatura');
    Route::get('asignaturas/{actividad_id}/evaluar-estudiante/{id}/Evaluar_actividad', [EvaluarEstudianteController::class, 'Evaluar_actividad'])
         ->name('Evaluar_actividad.evaluar');
    Route::get('asignaturas/{actividad_id}/evaluar-estudiante/{id}/actividades', [EvaluarEstudianteController::class, 'actividades'])
         ->name('actividades_estudiante.show');
    Route::post('/valoracion', [EvaluarEstudianteController::class, 'store'])
         ->name('save.valoracion');

    // Rutas para Actividad
    Route::crud('actividad', 'ActividadCrudController');
    Route::get('actividad/{id}/rubrica/', [RubricaActividadController::class, 'show'])
         ->name('rubrica_actividad.index');
    Route::get('actividad/{id}/rubrica/create', [RubricaActividadController::class, 'create'])
         ->name('rubrica_actividad.create');
    Route::post('actividad/rubrica/store', [RubricaActividadController::class, 'store'])
         ->name('rubrica_actividad.store');

    // Rutas para Criterio de Actividad
    Route::post('/criterios', [CriterioActividadController::class, 'store'])
         ->name('criterios.store');
    Route::get('/criterios', [CriterioActividadController::class, 'index'])
         ->name('criterios.index');
    Route::get('/criterios/{id}', [CriterioActividadController::class, 'show'])
         ->name('criterios.show');
    Route::put('/criterios/{id}', [CriterioActividadController::class, 'update'])
         ->name('criterios.update');
    Route::delete('/criterios/{id}', [CriterioActividadController::class, 'destroy'])
         ->name('criterios.destroy');

    // Rutas para Niveles de Evaluación de Actividad
    Route::post('/niveles', [LevelController::class, 'store'])
         ->name('niveles.store');
    Route::get('/niveles', [LevelController::class, 'index'])
         ->name('niveles.index');
    Route::get('/niveles/{id}', [LevelController::class, 'show'])
         ->name('niveles.show');
    Route::put('/niveles/{id}', [LevelController::class, 'update'])
         ->name('niveles.update');
    Route::delete('/niveles/{id}', [LevelController::class, 'destroy'])
         ->name('niveles.destroy');

    // Rutas para Descripción de Niveles junto al Criterio
    Route::post('/descripcionacriterionivel', [DescripcionCriterioNivelController::class, 'store'])
         ->name('descripcionacriterionivel.store');
    Route::get('/descripcionacriterionivel', [DescripcionCriterioNivelController::class, 'index'])
         ->name('descripcionacriterionivel.index');
    Route::get('/descripcionacriterionivel/{id}', [DescripcionCriterioNivelController::class, 'show'])
         ->name('descripcionacriterionivel.show');
    Route::put('/descripcionacriterionivel/{id}', [DescripcionCriterioNivelController::class, 'update'])
         ->name('descripcionacriterionivel.update');
    Route::delete('/descripcionacriterionivel/{id}', [DescripcionCriterioNivelController::class, 'destroy'])
         ->name('descripcionacriterionivel.destroy');

    // Rutas para Asistencia
    Route::get('/asistencia/generar/{asignatura_id}', [QrAsistenciaController::class, 'generarQrAsistencia'])
         ->name('asistencia.generar');

    Route::get('/asistencias/{asignatura_id}', [AsistenciaController::class, 'index'])
         ->name('asistencias.index');

    // Rutas para Calendario
    Route::resource('/assignment/calendario', 'CalendarController');


// Descripcion de el nivel junto al criterio
    Route::post('/descripcionacriterionivel', [DescripcionCriterioNivelController::class, 'store'])->name('descripcionacriterionivel.store');
    Route::get('/descripcionacriterionivel', [DescripcionCriterioNivelController::class, 'index'])->name('descripcionacriterionivel.index');
    Route::get('/descripcionacriterionivel/{id}', [DescripcionCriterioNivelController::class, 'show'])->name('descripcionacriterionivel.show');
    Route::put('/descripcionacriterionivel/{id}', [DescripcionCriterioNivelController::class, 'update'])->name('descripcionacriterionivel.update');
    Route::delete('/descripcionacriterionivel/{id}', [DescripcionCriterioNivelController::class, 'destroy'])->name('descripcionacriterionivel.destroy');

    // rutas para asistencia
    Route::get('/asistencia/generar/{asignatura_id}', [QrAsistenciaController::class, 'generarQrAsistencia'])->name('asistencia.generar');
    Route::get('/asistencias/{asignatura_id}', [AsistenciaController::class, 'index'])->name('asistencias.index');

    // Calendario
     Route::resource('/assignment/calendario', 'CalendarController');


    // Ruta para Descargar Plantilla
    Route::get('/descargar-plantilla', function () {
        return response()->download(storage_path('app/public/PLANTILLA.xlsx'));
    })->name('descargar.plantilla');

    // Ruta para Reportes (Gráfico General)
    Route::get('reportes/graphGeneral/{id}', [ReportesController::class, 'graphGeneral'])
         ->name('graphGeneral')
         ->middleware(['web']);

         Route::get('/studentsassigment', [AssignmentStudentController::class, 'ListCheckEstudentsView']);
         Route::delete('/asignaturas/{asignatura_id}/estudiantes/{estudiante_id}', [AssignmentStudentController::class, 'DeleteStudentAsigment']);
         Route::delete('/asignaturas/{asignatura_id}/estudiantes/delete/{studentsList}', [AssignmentStudentController::class, 'deleteStudents']);

         Route::post('/estudiantes/materia', [AssignmentStudentController::class, 'AssigmentStoreEstudents']);
         Route::get('assignment/{id}/students', [AssignmentStudentController::class, 'index'])->name('assignment.students');

         Route::post('assignment/{id}/students/import', [AssignmentStudentController::class, 'import'])->name('assignment.students.import');


    Route::get('/asignaturas/{asignatura_id}/asistencia/generar', [AsistenciaController::class, 'tomar'])->name('asistencia.generar');




// Formulario para tomar asistencia
Route::get('/asignaturas/{asignatura_id}/asistencia/tomar', [AsistenciaController::class, 'tomarAsistencia'])
     ->name('asistencia.tomar');


     Route::group(['prefix' => 'admin'], function() {
        // Otras rutas de administración...
        Route::post('/asignaturas/{asignatura_id}/asistencias', [AsistenciaController::class, 'guardar'])
             ->name('asistencia.guardar');
    });




});
/**
 * DO NOT ADD ANYTHING HERE.
 */

