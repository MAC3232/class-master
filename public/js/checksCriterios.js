





let elementoAnterior = {};

document.querySelectorAll("td.seleccionado").forEach(td => {
    let criterioId = td.getAttribute("data-criterio-id");
    let nivelId = td.getAttribute("data-nivel-id");

    if (criterioId && nivelId) {
        elementoAnterior[criterioId] = nivelId;
    }
});


const marcarCriterio = (criterioId, nivelDesempenoId, usuarioId, rubrica_actividad) => {



    if (elementoAnterior[criterioId]) {

        document.getElementById(`seleccionar_criterio${criterioId}${elementoAnterior[criterioId]}`).classList.remove("seleccionado");
    }
    console.log(actividad);
    elementoAnterior[criterioId] = nivelDesempenoId;
    let criterio = document.getElementById(`seleccionar_criterio${criterioId}${nivelDesempenoId}`);

    criterio.classList.add("seleccionado");




    $.ajax({
        url: '/admin/selectcriterios',
        type: 'POST',
        data: {
            usuario_id: usuarioId,
            criterio_id: criterioId,
            nivel_desempeno_id: nivelDesempenoId,
            rubrica_id: rubrica_actividad,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {

            console.log(response);


            $.ajax({
                url: `/admin/actividad/${actividad}/evaluatestudent/${response.data.estudiante_id}`,
                type: 'GET',
                dataType: 'json',
                success: function(response) {

                    document.getElementById('note-view').innerHTML = response;
                    document.getElementById('CalificarDesc').value = response;


                },
                error: function(xhr, status, error) {
                    console.error("Error en la solicitud:", error);
                }
            });

        },
        error: function(xhr, status, error) {
            console.error("Error al guardar:", error);
            Swal.fire('Error', 'No se pudo guardar el desempeño.', 'error');
        }
    });
}
