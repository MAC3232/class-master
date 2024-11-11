@extends(backpack_view('blank'))

@php

       
@endphp

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- <link rel="stylesheet" href="{{ asset('fullcalendar/core/main.css')}}">
    <link rel="stylesheet" href="{{ asset('fullcalendar/daygrid/main.css')}}">
    <link rel="stylesheet" href="{{ asset('fullcalendar/list/main.css')}}">
    <link rel="stylesheet" href="{{ asset('fullcalendar/timegrid/main.css')}}"> -->

    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">--}}
    
    
    <script src="{{ asset('fullcalendar/core/main.js')}}" defer></script>
    <script src="{{ asset('fullcalendar/interaction/main.js')}}" defer></script>
    <script src="{{ asset('fullcalendar/daygrid/main.js')}}" defer></script>
    <script src="{{ asset('fullcalendar/list/main.js')}}" defer></script>
    <script src="{{ asset('fullcalendar/timegrid/main.js')}}" defer></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
    <style>
      html, body {
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
      .modal-content{
        z-index: 1 !important;
      }
      #calendar {
        max-width: 900px;
        margin: 40px auto;
      }

      .fc-daygrid-event-harness-abs {
          right: 0 !important;
      }
    </style>

    <!-- jQuery primero -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

    <!-- Código de FullCalendar -->
        <script>
          document.addEventListener('DOMContentLoaded', function() {
            var asignatura_id = {{ $asignatura_id }};
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
              initialView: 'dayGridMonth',
              headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
              },
              locale: 'es',


              dateClick: function(info) {

              limpiarFormulario();

              $('#txtFecha').val(info.dateStr);
              $("#btnAgregar").prop("disabled", false);
              $("#btnModificar").prop("disabled", true);
              $("#btnBorrar").prop("disabled", true);

              var myModal = new bootstrap.Modal(document.getElementById('dateModal'));
              myModal.show();
          },

              eventClick: function(info) {
                $("#btnAgregar").prop("disabled", true);
                $("#btnModificar").prop("disabled", false);
                $("#btnBorrar").prop("disabled", false);
                $('#txtId').val(info.event.id);
                $('#txtTitulo').val(info.event.title);
                $('#textDescripcion').val(info.event.extendedProps.descripcion || '');
                $('#txtColor').val(info.event.color || '#000000');

                var fechaInicio = info.event.start;
                var mes = (fechaInicio.getMonth() + 1);
                var dia = fechaInicio.getDate();
                var anio = fechaInicio.getFullYear();

                var hora = fechaInicio.getHours();
                var minutos = fechaInicio.getMinutes();

              
                mes = (mes < 10) ? "0" + mes : mes;
                dia = (dia < 10) ? "0" + dia : dia;
                hora = (hora < 10) ? "0" + hora : hora; 
                minutos = (minutos < 10) ? "0" + minutos : minutos;

                var horario = (hora + ":" + minutos);

                $('#txtFecha').val(anio + "-" + mes + "-" + dia);
                $('#txtHora').val(horario);

                var myModal = new bootstrap.Modal(document.getElementById('dateModal'));
                myModal.show();
        },

              events: "{{ url('/admin/assignment/calendario/'. $asignatura_id) }}"
              
            });

            calendar.render();

            $('#btnAgregar').click(function() {
              let objEvento = recolectarDatosGUI('POST');

              EnviarInformacion('/admin/assignment/calendario/', objEvento, function(nuevoEvento) {
                calendar.addEvent(nuevoEvento);
                limpiarFormulario(); 

              });
            });

            $('#btnModificar').click(function() {
              let objEvento = recolectarDatosGUI('PUT');

              EnviarInformacion('/admin/assignment/calendario/' + $('#txtId').val(), objEvento, function(nuevoEvento) {

                let eventToUpdate = calendar.getEventById($('#txtId').val());
                if (eventToUpdate) {

                  eventToUpdate.setProp('title', objEvento.title);
                  eventToUpdate.setExtendedProp('descripcion', objEvento.descripcion);
                  eventToUpdate.setStart(objEvento.start);
                  eventToUpdate.setEnd(objEvento.end);
                  eventToUpdate.setProp('color', objEvento.color);
                }

                limpiarFormulario(); 
              });
            });


            $('#btnBorrar').click(function() {
              let eventoId = $('#txtId').val(); 
              EnviarInformacion('/admin/assignment/calendario/' + eventoId, { _method: 'DELETE' }, function() {
                
                  let eventToRemove = calendar.getEventById(eventoId);
                  if (eventToRemove) {
                      eventToRemove.remove(); 
                  }
                  limpiarFormulario(); 
              });
          });


            function recolectarDatosGUI(method) {
              return {
                id: $('#txtId').val(),
                title: $('#txtTitulo').val(),
                descripcion: $('#textDescripcion').val(),
                color: $('#txtColor').val(),
                textColor: "#FFFFFF",
                start: $('#txtFecha').val() + 'T' + $('#txtHora').val(),
                end: $('#txtFecha').val() + 'T' + $('#txtHora').val(),
                asignatura_id: asignatura_id,
                '_token': $("meta[name='csrf-token']").attr("content"),
                '_method': method
              };
            } 

            function EnviarInformacion(url, objEvento, callback) {
              $.ajax({
                type: 'POST',
                url: url,
                data: objEvento,
                headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(msg) {
                  console.log("Evento procesado con éxito: ", msg);
                  if (callback) callback(msg); 
                  var modal = bootstrap.Modal.getInstance(document.getElementById('dateModal'));
                  modal.hide();
                },
                error: function(xhr, status, error) {
                  alert("Completa todo los campos");
                }
              });
            }

            function limpiarFormulario() {
              $('#txtId').val('');
              $('#txtTitulo').val('');
              $('#textDescripcion').val('');
              $('#txtColor').val('#000000');
              $('#txtHora').val('7:00');
              $('#txtFecha').val('');
              document.getElementById('selectedDate').innerText = '';
            }
          });
        </script>

<div class="row" bp-section="crud-operation-list">
    <div class="col-md-12 bg-light">
        {{-- Contenedor principal con fondo blanco --}}
        <div id='calendar'></div>

<!-- Modal de Bootstrap -->
<div class="modal fade" id="dateModal" tabindex="-1" aria-labelledby="dateModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="dateModalLabel">Detalles de la fecha</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="d-none">
          <label for="txtId" class="form-label">ID</label>
          <input type="number" class="form-control" name="txtID" id="txtId">
      
          <label for="txtFecha" class="form-label">Fecha</label>
          <input type="date" class="form-control" name="txtFecha" id="txtFecha">
        </div>
        <div class="mb-3">
          <label for="txtTitulo" class="form-label">Título</label>
          <input type="text" class="form-control" name="txtTitulo" id="txtTitulo">
        </div>
        <div class="mb-3">
          <label for="txtHora" class="form-label">Hora</label>
          <input type="time" min="7:00" max="19:00"step="600" class="form-control" name="txtHora" id="txtHora">
        </div>
        <div class="mb-3">
          <label for="textDescripcion" class="form-label">Descripción</label>
          <textarea class="form-control" name="textDescripcion" id="textDescripcion" cols="30" rows="4"></textarea>
        </div>
        <div class="mb-3">
          <label for="txtColor" class="form-label">Color</label>
          <input type="color" class="form-control" name="txtColor" id="txtColor">
        </div>
        <p class="d-none">Has seleccionado la fecha: <span id="selectedDate"></span></p>
      </div>
      <div class="modal-footer">
        <button id="btnAgregar" class="btn btn-success">Agregar</button>
        <button id="btnModificar" class="btn btn-warning">Modificar</button>
        <button id="btnBorrar" class="btn btn-danger">Borrar</button>
        <button id="btnCancelar" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>

        </div>
        </div>

@endsection
