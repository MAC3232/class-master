


    let currentMode = '';

    function showCustomModal(mode, criterio_id, nivel_id) {
        currentMode = mode;
        criterio = criterio_id
        nivel = nivel_id
        document.getElementById("addElementForm").reset();
        document.getElementById("criteriaInputs").style.display = mode === 'criterion' ? 'block' : 'none';
        document.getElementById("levelInputs").style.display = mode === 'level' ? 'block' : 'none';
        document.getElementById("descripcionInputs").style.display = mode === 'descripcion' ? 'block' : 'none';

        if (currentMode == 'calificar') {
            document.getElementById("CalificarInputs").style.display = mode === 'calificar' ? 'block' : 'none';
            document.getElementById("customModalLabel").innerText = 'Calificar al estudiante';

        }

        if (currentMode === 'descripcion') {

            document.getElementById("customModalLabel").innerText = 'Agregar nueva descripcion';
        }
        if (currentMode === 'level') {

            document.getElementById("customModalLabel").innerText = 'Agregar nuevo nivel';
        }
        if (currentMode === 'criterion') {

            document.getElementById("customModalLabel").innerText = 'Agregar nuevo criterio';
        }

        // Show Bootstrap modal
        $('#customModal').modal('show');
    }

    function submitCustomModal() {

        if (currentMode === 'criterion') {
            const criterionDesc = document.getElementById('criterionDesc').value;
            const rubrica = document.getElementById('rubrica').value;
            const data = {
                descripcion: criterionDesc,
                rubrica_actividad_id: rubrica
            };






            EnviarInformacion('/admin/criterios', data, function(response) {
                addCriterion(response.descripcion, response.id); // Asegúrate de manejar la respuesta


            });
        } else if (currentMode === 'level') {
            const levelDesc = document.getElementById('levelDesc').value;
            const scoreRangeFrom = document.getElementById('scoreRangeFrom').value;

            const rubrica1 = document.getElementById('rubrica').value;

            const data = {
                nombre: levelDesc,
                puntos: scoreRangeFrom,
                rubrica_actividad_id: rubrica1,
            };



            EnviarInformacion('/admin/niveles', data, function(response) {
                window.location.reload();

                addLevel(response.nombre); // Asegúrate de manejar la respuesta
            });
        } else if (currentMode === 'descripcion'){

            const descripcion_input = document.getElementById('descripcion_input').value;

            const data = {
                descripcion: descripcion_input,
                nivel_desempeno_id: nivel,
                criterio_id: criterio
            }




            EnviarInformacion('/admin/descripcionacriterionivel', data, function(response) {
                window.location.reload();


                addDescription(criterio, nivel, response.descripcion); // Asegúrate de manejar la respuesta
            });







        }else if(currentMode === 'calificar'){
            const estudiante_id = document.getElementById('estudiante').value;
            const actividad_id = document.getElementById('actividad').value;
            const CalificarDesc = document.getElementById('CalificarDesc').value;

            const data = {
                estudiante_id: estudiante_id,
                actividad_id: actividad_id,
                nota: CalificarDesc
            }



            EnviarInformacion('/admin/valoracion', data, function(response) {
                window.location.reload();
            });


        }
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

                if (callback) callback(msg);
                var modal = bootstrap.Modal.getInstance(document.getElementById('customModal'));
                modal.hide();
            },
            error: function(xhr, status, error) {
                alert("Completa todos los campos");
            }
        });
    }

    function addCriterion(description, criterio) {
        const table = document.getElementById('rubricTable').getElementsByTagName('tbody')[0];
        const row = table.insertRow();
        const criterioId = criterio; // ID del criterio pasado como parámetro

        // Agrega la celda de descripción del criterio
        row.insertCell(0).textContent = description;

        // Obtén los ID de nivel desde las celdas del encabezado
        const headerCells = document.getElementById('headerRow').cells;
        for (let i = 1; i < headerCells.length; i++) { // Empieza en 1 para omitir la primera celda de "Descripción del Criterio"
            const nivelId = headerCells[i].getAttribute('data-nivel-id'); // Obtén el ID del nivel desde el encabezado

            const cell = row.insertCell(i);
            cell.setAttribute('data-criterio-id', criterioId);
            cell.setAttribute('data-nivel-id', nivelId);

            // Crear el botón "Agregar descripción aquí"
            const button = document.createElement('button');
            button.className = 'btn btn-sm btn-link';
            button.innerHTML = '<i class="la la-plus"></i> Agregar descripción aquí';
            button.onclick = function () {
                showCustomModal('descripcion', criterioId, nivelId);
            };

            // Añadir el botón a la celda
            cell.appendChild(button);
        }
    }



    function addLevel(name) {
        const headerRow = document.getElementById('headerRow');
        const newHeaderCell = headerRow.insertCell(-1);
        newHeaderCell.className = 'text-center';
        newHeaderCell.textContent = name;

        // Agrega una nueva celda a cada fila en el cuerpo de la tabla
        const tableBody = document.getElementById('rubricTable').getElementsByTagName('tbody')[0];
        for (let i = 0; i < tableBody.rows.length; i++) {
            const newCell = tableBody.rows[i].insertCell(-1);
            newCell.textContent = ''; // O algún valor predeterminado
        }
    }

    function addDescription(criterioId, nivelId, description) {
        const table = document.getElementById('rubricTable');
        const rows = table.getElementsByTagName('tbody')[0].rows;

        for (let i = 0; i < rows.length; i++) {
            const row = rows[i];

            // Busca la fila correcta por criterio ID
            if (row.getAttribute('data-criterio-id') == criterioId) {

                // Encuentra la celda correcta por nivel ID
                const cells = row.cells;
                for (let j = 1; j < cells.length; j++) {  // Empezamos en 1 porque la primera celda es la descripción del criterio
                    const cell = cells[j];

                    if (cell.getAttribute('data-nivel-id') == nivelId) {
                        // Inserta la descripción en la celda correcta
                        cell.textContent = description;
                        return;
                    }
                }
            }
        }
    }

   function addCalificacion(name){
     document.getElementById('CalificarDesc').value = 4;

    }



