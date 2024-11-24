{{-- Archivo de menú en Backpack --}}

<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> Inicio</a></li>

@if (  backpack_user()->hasRole('docente')   )
<x-backpack::menu-item title="Asignaturas" icon="la la-book" :link="backpack_url('asignaturas')" />
{{-- Nueva opción de menú personalizada fuera de Backpack --}}
<li class="nav-item">
    <a class="nav-link" href="{{ route('reportes') }}">
        <i class="la la-chart-bar nav-icon"></i> Reportes
    </a>
</li>
@else

<x-backpack::menu-item title="Asignaturas" icon="la la-book" :link="backpack_url('asignaturas')" />
{{-- Visible para otros roles --}}
<x-backpack::menu-item title="Usuarios" icon="la la-user" :link="backpack_url('user')" />
<x-backpack::menu-item title="Estudiantes" icon="la la-users" :link="backpack_url('estudiantes')" />
<x-backpack::menu-item title="Facultades" icon="la la-university" :link="backpack_url('facultad')" />
<x-backpack::menu-item title="Carreras" icon="la la-graduation-cap" :link="backpack_url('carrera')" />

@endif
    {{-- Solo visible para docentes --}}


