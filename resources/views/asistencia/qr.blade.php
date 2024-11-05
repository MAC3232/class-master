@extends(backpack_view('blank'))
@section('header')
<style>
    html,
    body {
        margin: 0;
        padding: 0;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 14px;
    }

    .modal-backdrop.show {
        pointer-events: none !important;
        z-index: 0 !important;
        opacity: 0 !important;
    }


</style>

<section class="content-header">
    <h1 class="text-light">
        Diseñador de Rúbrica: 
    </h1>
    <ol class="breadcrumb m-2">
        <li><a href="{{ backpack_url() }}">Panel</a></li>

        <li class="active"></li>
        <li>

        <button onclick="window.history.back();" class="btn-link p-2">
                < Volver
            </button>
        </li>



    </ol>
</section>


@endsection

@section('content')
<div class="row" bp-section="crud-operation-list">
    <div class="col-md-12">
        {{-- Contenedor principal con fondo blanco --}}
        <div class="card p-4 shadow-sm bg-light">

            <div class="container text-center">
                <h1>Escanea el código QR para marcar asistencia</h1>
                <div>{!! $qrCode !!}</div>
                <p>Código QR válido desde {{ $qrAsistencia->fecha_inicio }} hasta {{ $qrAsistencia->fecha_fin }}</p>
            </div>

        </div>

    </div>
</div>
@endsection
