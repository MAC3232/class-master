@extends(backpack_view('blank'))

@php
    $index = 0;
    $defaultBreadcrumbs = [
        trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
        $crud->entity_name_plural => url($crud->route),
        trans('backpack::crud.list') => false,
    ];

    
    $breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
    

    
@endphp

@section('header')
<section>
    
</section>
@endsection

@push('after_styles')
<style>

    .search-filter-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        margin-top: 30px;
        gap:0.5rem;
        position: relative; 
    }


    .search-input {
        padding: 8px 12px 8px 35px; 
        border-radius: 5px;
        width: 100%;
        background-color: #1F2937;
        border: 2px solid #374151;
        color: white; 
        position: relative; 
    }
    .search-input:focus{
        border: 2px solid blue;
        outline: none;
    }

    .search-icon {
        position: absolute; 
        left: 10px; 
        top: 50%; 
        transform: translateY(-50%); 
        color: #9CA3AF; 
        width: 20px;
        height: 20px;
    }

     .create-button{
        padding: 8px 16px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        width:10rem;
        position: relative; 
     }

     /* Botón de Filtro */
    .filter-button {
        padding: 8px 16px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        width:10rem;
        position: relative; 
    }

    /* Dropdown de Filtros */
    .filter-dropdown-menu {
        list-style: none;
        padding: 0;
        margin: 0;
        background-color: #374151;
        border-radius: 5px;
        overflow: hidden;
        display: none; 
        position: absolute; 
        top: 100%; 
        right:0; 
        width: auto; 
        min-width: 10rem;
        z-index: 110; 
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }


    .filter-dropdown-menu.show {
        display: block; 
    }

    .filter-dropdown-item {
        padding: 10px 15px;
        cursor: pointer;
        color: white;
        text-align: left; 
        white-space: nowrap; 
    }

    .filter-dropdown-item:hover {
        background-color: #4B5563;
    }

    .filter-dropdown-item:not(:last-child) {
        border-bottom: 1px solid #4B5563;
    }


    .card-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 300px)); 
        gap: 20px;
        justify-content: center;
    }

    .card:hover{
        transform: scale(1.02);
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.5); 
        transition: transform 0.3s ease-in-out;
    }

    .card {
       max-width: 300px; 
       width: 100%; 
       border-radius: 10px;
       padding: 20px;
       color: white;
       display: flex;
       flex-direction: column;
       align-items: center;
       text-align: left;
       overflow: hidden;
       position: relative;
       background-color: #1F2937; 
       box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3); 
    }

    .card-icon-container {
        background-color: rgba(255, 255, 255, 0.15);
        padding: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        width: 210px;
        height: 100px;
        border-radius: 8px;
        margin-bottom: 10px;
    }

    .card-content {
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        margin-top: 8px;
    }

    .card-title {
        font-size: 1.2em;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .card-info {
        font-size: 0.9em;
        color: #C7D2FE;
    }

    .options-icon {
        position: absolute;
        top: 10px;
        right: 1px;
        cursor: pointer;
    }

    
    .card-dropdown-container {
        position: absolute;
        top: 10px;
        right: 25px;
        z-index: 100;
    }

    .card-dropdown-menu {
        list-style: none;
        padding: 0;
        margin: 0;
        background-color: #374151;
        border-radius: 5px;
        overflow: hidden;
        display: none; 
    }

    .card-dropdown-menu.show {
        display: block;
    }

    .card-dropdown-item {
    padding: 10px 15px;
    cursor: pointer;
    color: white;
    text-align: center;
    z-index: 100;
    }

    .card-dropdown-item:hover {
        background-color: #4B5563;
    }

    .card-dropdown-item:not(:last-child) {
        border-bottom: 1px solid #4B5563;
    }
    .loading-indicator {
    text-align: center;
    font-size: 1.2rem;
    color: #0D8C81;
    padding: 20px;
    width: 100%;
}

</style>
@endpush

@section('content')
<div class="search-filter-container">
    <input type="text" class="search-input" placeholder="Buscar Asignatura..." onkeyup="searchCourses()">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="search-icon"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
    <button class="filter-button" onclick="toggleFilterDropdown()">Filtros</button>
    <ul class="filter-dropdown-menu" id="filterDropdownMenu">
        <li class="filter-dropdown-item">Prueba Filtro 1</li>
        <li class="filter-dropdown-item">Prueba Filtro 2</li>
        <li class="filter-dropdown-item">Prueba Filtro 3</li>
    </ul>
    <button class="create-button" onclick="createCourse()">Crear</button> {{-- Botón de Crear --}}
</div>

<div class="p-4 shadow-sm bg-light">
    <div class="card-container">
        <div class="loading-indicator" style="display: none;">Buscando...</div>
    </div>
</div>

@endsection

@push('after_scripts')
<script>
    let cardsData = [];
   
    $.ajax({
        url: '/admin/searchCourses',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            console.log('Datos recibidos:', data);
            cardsData = data;
            renderCards(cardsData);
        },
    });
    function searchCourses() {
    let searchQuery = $('.search-input').val().trim(); 

    $('.loading-indicator').show(); // Mostrar el indicador de carga
    $('.card-container .card').remove(); // Eliminar tarjetas antiguas mientras se carga

    $.ajax({
        url: '/admin/searchCourses',
        type: 'GET',
        data: { search: searchQuery },
        dataType: 'json',
        success: function (data) {
            $('.loading-indicator').hide(); // Ocultar el indicador de carga
            renderCards(data); 
        },
        error: function () {
            $('.loading-indicator').text("Error al cargar datos").show();
        }
    });
}



    function renderCards(cards) {
    let cardContainer = $('.card-container');

    
    let existingCards = cardContainer.children('.card');

    cards.forEach(function(card, index) {
        if (existingCards[index]) {
            // Si la tarjeta ya existe, solo actualiza su contenido
            $(existingCards[index]).find('.card-title').text(card.nombre);
            $(existingCards[index]).find('.card-info').eq(0).text(`Catálogo: ${card.catalogo}`);
            $(existingCards[index]).find('.card-info').eq(1).text(`Código: ${card.codigo}`);
        } else {
            // Si la tarjeta no existe, crea una nueva y la agrega al container
            let cardHtml = `
                <div class="card" style="background-color: ${getCardColor(index)};">
                    <div class="card-dropdown-container">
                        <ul class="card-dropdown-menu" id="cardDropdownMenu${index}">
                            <li class="card-dropdown-item" onclick="cardActionView()">Ver</li>
                            <li class="card-dropdown-item" onclick="cardActionEdit()">Editar</li>
                            <li class="card-dropdown-item" onclick="cardActionDelete()">Eliminar</li>
                        </ul>
                    </div>
                    <div class="options-icon" onclick="toggleDropdown('cardDropdownMenu${index}')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-dots-vertical">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M12 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                            <path d="M12 19m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                            <path d="M12 5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                        </svg>
                    </div>
                    <div class="card-content">
                        <h3 class="card-title">${card.nombre}</h3>
                        <p class="card-info">Catálogo: ${card.catalogo}</p>
                        <p class="card-info">Código: ${card.codigo}</p>
                    </div>
                </div>
            `;
            cardContainer.append(cardHtml);
        }
    });

    // Elimina las tarjetas que no se encuentran en la lista de datos recibida
    if (existingCards.length > cards.length) {
        existingCards.slice(cards.length).remove();
    }
}

    // Agrega el color de Fondo para todas las tarjetas.
    function getCardColor(index) {
        const colors = ["#4c6ef5"];
        return colors[index % colors.length];
    }

    function toggleDropdown(dropdownId) {
        const dropdownMenu = document.getElementById(dropdownId);
        if (dropdownMenu.style.display === 'block') {
            dropdownMenu.style.display = 'none';
        } else {
            dropdownMenu.style.display = 'block';

            document.querySelectorAll('.card-dropdown-menu.show').forEach(function(otherDropdown) {
                if (otherDropdown.id !== dropdownId) {
                    otherDropdown.style.display = 'none';
                    otherDropdown.classList.remove('show');
                }
            });
        }
        dropdownMenu.classList.toggle('show');
    }

    function toggleFilterDropdown() {
        const filterDropdownMenu = document.getElementById('filterDropdownMenu');
        if (filterDropdownMenu.style.display === 'block') {
            filterDropdownMenu.style.display = 'none';
        } else {
            filterDropdownMenu.style.display = 'block';

            document.querySelectorAll('.filter-dropdown-menu.show').forEach(function(otherDropdown) {
                if (otherDropdown.id !== 'filterDropdownMenu') {
                    otherDropdown.style.display = 'none';
                    otherDropdown.classList.remove('show');
                }
            });

            document.querySelectorAll('.card-dropdown-menu.show').forEach(function(cardDropdown) {
                cardDropdown.style.display = 'none';
                cardDropdown.classList.remove('show');
            });
        }
        filterDropdownMenu.classList.toggle('show');
    }

    window.onclick = function(event) {
        const isClickInsideCardDropdown = event.target.closest('.card-dropdown-menu');
        const isClickOnOptionsIcon = event.target.closest('.options-icon');
        const isClickInsideFilterDropdown = event.target.closest('.filter-dropdown-menu');
        const isClickOnFilterButton = event.target.closest('.filter-button');

        if (!isClickInsideCardDropdown && !isClickOnOptionsIcon && !isClickInsideFilterDropdown && !isClickOnFilterButton) {
            document.querySelectorAll('.card-dropdown-menu.show').forEach(function(openDropdown) {
                openDropdown.classList.remove('show');
                openDropdown.style.display = 'none';
            });
            document.querySelectorAll('.filter-dropdown-menu.show').forEach(function(openFilterDropdown) {
                openFilterDropdown.classList.remove('show');
                openFilterDropdown.style.display = 'none';
            });
        }
    }

    function cardActionView() {
        alert('View action clicked');
    }

    function cardActionEdit() {
        alert('Edit action clicked');
    }


    function cardActionDelete() {
        alert('Delete action clicked');
    }
</script>
@endpush