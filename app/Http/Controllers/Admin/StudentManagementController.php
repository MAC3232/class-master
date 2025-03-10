<?php

namespace App\Http\Controllers\Admin;

use App\Imports\StudentsImport;
use App\Models\Carrera;
use App\Models\Estudiantes;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class StudentManagementController extends CrudController
{
    public function index()
    {

        $this->crud->setModel('App\Models\Estudiantes'); // Define el modelo
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/estudiantes'); // Define la ruta
        $this->crud->setEntityNameStrings('estudiante', 'estudiantes'); // Define el nombre de la entidad

        $carreras = Carrera::all();

        return view('admin.students.manage', ['crud' => $this->crud, 'carreras' => $carreras]);
    }

    public function storeSingleStudent(Request $request)
    {
        


        $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|unique:users,email',
            'code' => 'required|string|unique:estudiantes,codigo_estudiantil|max:50',
            'cedula' => 'required|string|unique:estudiantes,cedula|max:20',
            'carrera_id' => 'required',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'code.required' => 'El código del estudiante es obligatorio.',
            'code.unique' => 'El código ya está registrado.',
            'cedula.unique' => 'La identificacion ya está registrada.',
            'career.required' => 'La carrera es obligatoria.',
        ]);




        try {

            // Crear el usuario
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' =>  bcrypt($request->cedula)
            ]);
            $user->assignRole('estudiante');

            // Crear el estudiante y asociar el user_id
            Estudiantes::create([
                'cedula' => $request->cedula,

                'codigo_estudiantil' => $request->code,
                'carrera_id'         => $request->carrera_id,
                'user_id'            => $user->id,
            ]);

            return redirect()->back()->with('success', 'Estudiante añadido exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Ocurrió un error inesperado: ' . $e->getMessage());
        }

    }

    public function importStudents(Request $request)
{
    $request->validate([
        'file' => 'required|file|mimes:csv,xlsx',
    ]);

    // Limpiar errores previos
    Session::forget('import_errors');

    try {
        Excel::import(new StudentsImport($request->career_id), $request->file('file'));

        // Verificar si hubo errores en la sesión
        $errors = Session::get('import_errors', []);
        $success = Session::get('import_success', []);


        return redirect()->back()->with([
            'successImport' => 'Proceso de importación completado.',
            'import_errors' => $errors,
            'import_success' => $success
        ]);



        return redirect()->back()->with('successImport', '✅ Estudiantes importados correctamente.');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', '❌ Error al importar el archivo: '. $e );
    }
}
}
