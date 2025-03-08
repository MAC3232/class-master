<?php

use App\Http\Controllers\Admin\AsignaturasCrudController;
use App\Http\Controllers\admin\AsistenciaController;
use App\Http\Controllers\Admin\AssignmentStudentController;
use App\Http\Controllers\admin\CourseController;
use App\Http\Controllers\admin\CriterioActividadController;
use App\Http\Controllers\Admin\CriterioController;
use App\Http\Controllers\admin\DescripcionCriterioNivelController;
use App\Http\Controllers\admin\EjeContenidoController;
use App\Http\Controllers\admin\EstrategiaController;
use App\Http\Controllers\Admin\EstudiantesCrudController;
use App\Http\Controllers\Admin\EvaluarEstudianteController;
use App\Http\Controllers\admin\LevelController;
use App\Http\Controllers\admin\QrAsistenciaController;
use App\Http\Controllers\admin\RAController;
use App\Http\Controllers\admin\RubricaActividadController;
use App\Http\Controllers\Admin\StudentManagementController;
use App\Http\Controllers\ReportesController;
use App\Http\Controllers\RubricaController;
use App\Models\Asignaturas;
use Illuminate\Support\Facades\Route;



// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\CRUD.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin'),

    ),
    'namespace' => 'App\Http\Controllers\Admin',
], function () {

    // custom admin routes
    Route::get('students/manage', [StudentManagementController::class, 'index'])->name('students.manage');
    Route::post('students/manage/onlystudent', [StudentManagementController::class, 'storeSingleStudent'])->name('only.students');
    Route::post('students/manage/importStudents', [StudentManagementController::class, 'importStudents'])->name('import.students');


    Route::get('/studentsassigment', [AssignmentStudentController::class, 'ListCheckEstudentsView']);
    Route::delete('/asignaturas/{asignatura_id}/estudiantes/{estudiante_id}', [AssignmentStudentController::class, 'DeleteStudentAsigment']);
    Route::post('/estudiantes/materia', [AssignmentStudentController::class, 'AssigmentStoreEstudents']);
    Route::get('assignment/{id}/students', [AssignmentStudentController::class, 'index'])->name('assignment.students');

    Route::post('assignment/{id}/students/import', [AssignmentStudentController::class, 'import'])->name('assignment.students.import');

    Route::get('/facultad/{id}/carreras', [AsignaturasCrudController::class, 'getCarrerasByFacultad']);
    Route::get('/carrera/{id}/asignaturas', [AsignaturasCrudController::class, 'getAsignaturasByCarrera']);

    Route::get('/reportes/', [ReportesController::class, 'index'])->name('reportes');
    Route::get('/estudianteReport/{id}/estudents', [ReportesController::class, 'estudianteReport'])->name('estudianteReport');
    Route::get('/estudianteReport/{id}/estudents/{student}', [ReportesController::class, 'graph'])->name('graph');

    Route::get('admin/api/asignaturas', [AsignaturasCrudController::class, 'getAsignaturas']);

    Route::get('courses', [CourseController::class, 'index']);
    Route::get('{id}/panel-asignatura', [CourseController::class, 'panelCourse']);
    Route::get('searchCourses', [CourseController::class, 'searchAsignatura']);


    Route::crud('asignaturas', 'AsignaturasCrudController');

    Route::crud('estudiantes', 'EstudiantesCrudController');
    Route::crud('user', 'UserCrudController');
    Route::crud('facultad', 'FacultadCrudController') ;
    Route::crud('carrera', 'CarreraCrudController');
    Route::get('estudiantes/filter-assignments', [EstudiantesCrudController::class, 'obtenerAsignaturas'])->name('filter.assignments');


    Route::get('asignaturas/{id}/rubrica', [RubricaController::class, 'showDisenador'])
        ->name('rubrica.disenador');
    Route::get('asignaturas/{id}/rubrica/editor', [RubricaController::class, 'editor'])->name('rubrica.editor');
    Route::get('asignaturas/{id}/rubrica/create', [RubricaController::class, 'create'])->name('rubrica.create');
    Route::post('asignaturas/rubrica/store', [RubricaController::class, 'store'])->name('rubrica.store');

    Route::get('asignaturas/{id}/rarubrica/create', [RAController::class, 'create'])->name('rarubrica.create');
    Route::post('asignaturas/{id}/rarubrica/store/{id_rubrica}', [RAController::class, 'store'])->name('rarubrica.store');
    Route::get('ra/edit/{id}', [RAController::class, 'edit'])->name('ra.edit');
    Route::put('ra/update/{id}/{id_rubrica}', [RAController::class, 'update'])->name('ra.update');
    Route::delete('ra/destroy/{id}', [RAController::class, 'destroy'])->name('ra.destroy');


    Route::get('asignaturas/{id}/criteriorubrica/create', [CriterioController::class, 'create'])->name('criterio.create');
    Route::post('asignaturas/{id}/criteriorubrica/store', [CriterioController::class, 'store'])->name('criterio.store');
    Route::get('asignaturas/{id}/criteriorubrica/{criterio_id}/edit', [CriterioController::class, 'edit'])->name('criterio.edit');
    Route::put('asignaturas/criteriorubrica/{criterio_id}/update', [CriterioController::class, 'update'])->name('criterio.update');
    Route::delete('criterio/destroy/{id}', [CriterioController::class, 'destroy'])->name('criterio.destroy');

    Route::post('asignaturas/{id}/metodoaprendizaje/store', [CriterioController::class, 'store'])->name('enseñanza.store');

    // Rutas para estrategias de aprendizaje
    Route::get('estrategias/create/{ra_id}', [EstrategiaController::class, 'create'])->name('estrategia.create');
    Route::post('estrategias/store/{ra_id}', [EstrategiaController::class, 'store'])->name('estrategia.store');
    Route::get('estrategias/edit/{id}', [EstrategiaController::class, 'edit'])->name('estrategia.edit');
    Route::put('estrategias/update/{id}', [EstrategiaController::class, 'update'])->name('estrategia.update');
    Route::delete('estrategias/destroy/{id}', [EstrategiaController::class, 'destroy'])->name('estrategia.destroy');
    // rutas de eje de contenido
    Route::get('/ejes-contenido/create/{ra_id}', [EjeContenidoController::class, 'create'])->name('eje_contenido.create');
    Route::post('/ejes-contenido/store/{ra_id}', [EjeContenidoController::class, 'store'])->name('eje_contenido.store');
    Route::get('/ejes-contenido/edit/{id}', [EjeContenidoController::class, 'edit'])->name('eje_contenido.edit');
    Route::put('/ejes-contenido/update/{id}', [EjeContenidoController::class, 'update'])->name('eje_contenido.update');
    Route::delete('/ejes-contenido/destroy/{id}', [EjeContenidoController::class, 'destroy'])->name('eje_contenido.destroy');

    Route::post('/selectcriterios', [EvaluarEstudianteController::class,  'storeSelectCriterioEstudent'] );
    Route::get('/actividad/{actividad}/evaluatestudent/{student}', [EvaluarEstudianteController::class,  'getNoteStudent'] );

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


    Route::crud('actividad', 'ActividadCrudController');

    Route::get('actividad/{id}/rubrica/', [RubricaActividadController::class, 'show'])
        ->name('rubrica_actividad.index');


    Route::get('actividad/{id}/rubrica/create', [RubricaActividadController::class, 'create'])->name('rubrica_actividad.create');
    Route::post('actividad/rubrica/store', [App\Http\Controllers\admin\RubricaActividadController::class, 'store'])->name('rubrica_actividad.store');

    // criterio actividad

    Route::post('/criterios', [CriterioActividadController::class, 'store'])->name('criterios.store');
    Route::get('/criterios', [CriterioActividadController::class, 'index'])->name('criterios.index');
    Route::get('/criterios/{id}', [CriterioActividadController::class, 'show'])->name('criterios.show');
    Route::put('/criterios/{id}', [CriterioActividadController::class, 'update'])->name('criterios.update');
    Route::delete('/criterios/{id}', [CriterioActividadController::class, 'destroy'])->name('criterios.destroy');

// Niveles de evaluacion actividad
    Route::post('/niveles', [LevelController::class, 'store'])->name('niveles.store');
    Route::get('/niveles', [LevelController::class, 'index'])->name('niveles.index');
    Route::get('/niveles/{id}', [LevelController::class, 'show'])->name('niveles.show');
    Route::put('/niveles/{id}', [LevelController::class, 'update'])->name('niveles.update');
    Route::delete('/niveles/{id}', [LevelController::class, 'destroy'])->name('niveles.destroy');

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

    // Route::get('/calendario/{id}', [CalendarController::class, 'index'])->name('calendario');
     Route::resource('/assignment/calendario', 'CalendarController');


}); // this should be the absolute last line of this file

/**
 * DO NOT ADD ANYTHING HERE.
 */
