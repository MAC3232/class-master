@extends(backpack_view('blank'))

@php
    $index = 0;
    $defaultBreadcrumbs = [
        trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
        $crud->entity_name_plural => url($crud->route),
        trans('backpack::crud.list') => false,
    ];

    // if breadcrumbs aren't defined in the CrudController, use the default breadcrumbs
    $breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
    

    $cards = [
        ["title" => "Etica Profesional", "code" => "068", "catalog" => "21CDCCU", "color" => "#4c6ef5"],
        ["title" => "Seminario de Investigacion", "code" => "067", "catalog" => "123CINV", "color" => "#9747ff"],
        ["title" => "Seminario de Actualizacion 2", "code" => "065", "catalog" => "167CSIS", "color" => "#0EB07B"],
        ["title" => "Practica Formativa", "code" => "064", "catalog" => "131CSIS", "color" => "#f7b23b"],
        ["title" => "Optativa 3", "code" => "063", "catalog" => "6CCOP", "color" => "#ff5733"],
        ["title" => "Microprocesadores", "code" => "062", "catalog" => "163CSIS", "color" => "#12AF9E"],
        ["title" => "Gestion de la Seguridad Informativa", "code" => "061", "catalog" => "12CSIS", "color" => "#3377ff"],
        ["title" => "Electiva Disciplinaria V", "code" => "060", "catalog" => "166CSIS", "color" => "#ff33a1"]
    ];

    $cardIcons = [
        "Etica Profesional" => '<svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3H7c-2 0-3 2-3 4v10c0 2 1 3 3 3h10c2 0 3-1 3-3V7c0-2-1-4-3-4z"></path><path d="M17 21v-8H7v8"></path><line x1="7" y1="7" x2="21" y2="7"></line></svg>',
        "Seminario de Investigacion" => '<svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3h18v18H3z" stroke="none"/><path d="M8.5 15l-3-5.5l4-5.5l4 5.5l-3 5.5zM15.5 15l-3-5.5l4-5.5l4 5.5l-3 5.5z"/></svg>',
        "Seminario de Actualizacion 2" => '<svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 17l-6-6 6-6"/><path d="M9 17l6-6-6-6"/></svg>',
        "Practica Formativa" => '<svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 8v-2a2 2 0 0 1 2 -2h2" /><path d="M4 16v2a2 2 0 0 0 2 2h2" /><path d="M16 4h2a2 2 0 0 1 2 2v2" /><path d="M16 20h2a2 2 0 0 0 2 -2v-2" /><path d="M12 12m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M12 12m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /></svg>',
        "Optativa 3" => '<svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 6v6m0 6v-6"/><path d="M12 3l-4 6h8z"/><path d="M12 21l-4-6h8z"/><path d="M4 9l-2 3h4"/><path d="M20 9l2 3h-4"/><path d="M4 15l-2-3h4"/><path d="M20 15l2-3h-4"/></svg>',
        "Microprocesadores" => '<svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M5 12v-5h5"/><path d="M19 12v5h-5"/><path d="M12 5v14"/><circle cx="12" cy="12" r="3"/></svg>',
        "Gestion de la Seguridad Informativa" => '<svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" /></svg>',
        "Electiva Disciplinaria V" => '<svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>',
    ];
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
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); 
        gap: 20px;
        margin-top: 50px;
        height: auto; 
    }

    .card:hover{
        transform: scale(1.02);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease-in-out;
    }

    .card {
        border-radius: 10px;
        padding: 20px;
        color: white;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: left;
        overflow: hidden;
        position: relative;
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

</style>
@endpush

@section('content')
<div class="search-filter-container">
    <input type="text" class="search-input" placeholder="Buscar Asignatura...">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="search-icon"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
    <button class="filter-button" onclick="toggleFilterDropdown()">Filtros</button>
    <ul class="filter-dropdown-menu" id="filterDropdownMenu">
        <li class="filter-dropdown-item">Prueba Filtro 1</li>
        <li class="filter-dropdown-item">Prueba Filtro 2</li>
        <li class="filter-dropdown-item">Prueba Filtro 3</li>
    </ul>
</div>

<div class="card-container">
    @foreach($cards as $card)
    <div class="card" style="background-color: {{ $card['color'] }};">
        <div class="card-dropdown-container">
            <ul class="card-dropdown-menu" id="cardDropdownMenu{{ $loop->index }}">
                <li class="card-dropdown-item" onclick="cardActionView()">Ver</li>
                <li class="card-dropdown-item" onclick="cardActionEdit()">Editar</li>
                <li class="card-dropdown-item" onclick="cardActionDelete()">Eliminar</li>
            </ul>
        </div>
        <div class="options-icon" onclick="toggleDropdown('cardDropdownMenu{{ $loop->index }}')">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-dots-vertical">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <path d="M12 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                <path d="M12 19m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                <path d="M12 5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
            </svg>
        </div>
        <div class="card-icon-container">
            {!! $cardIcons[$card['title']] !!}
        </div>
        <div class="card-content">
            <h3 class="card-title">{{ $card['title'] }}</h3>
            <p class="card-info">Código: {{ $card['code'] }}</p>
            <p class="card-info">Catálogo: {{ $card['catalog'] }}</p>
        </div>
    </div>
    @endforeach
</div>
@endsection

@push('after_scripts')
<script>
    
    $.ajax({
    url: 'http://127.0.0.1:8000/admin/searchCourses',
    type: 'GET',
    dataType: 'json',
    success: function(data) {
        console.log('Datos recibidos:', data);
    },
    error: function(jqXHR, textStatus, errorThrown) {
        console.error('Error en la petición:', textStatus, errorThrown);
    }
});


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
