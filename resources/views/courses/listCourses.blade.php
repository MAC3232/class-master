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
        margin-top: 2px;
        gap:0.5rem;
        position: relative;
    }


    .search-input {
        padding: 8px 12px 8px 35px;
        border-radius: 5px;
        width: 100%;
        border: 2px solid #E7E6E7;
        position: relative;
    }
    .search-input:focus{
        border: 2px solid #ADA1F5;  /* Color del borde */
        box-shadow: 0 0 5px #DED9FB; /* Color de la sombra */
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
        background-color: #7C69EF;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        width:10rem;
        position: relative;
     }

    .card-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 300px));
        gap: 20px;
        justify-content: center;
        position: relative;
    }

    .card:hover{
        transform: scale(1.02);
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.5);
        transition: transform 0.3s ease-in-out;
    }


    .card {
       height:10rem;
       max-width: 300px;
       width: 100%;
       border-radius: 10px;
       padding: 20px;

       display: flex;
       cursor: pointer;
       flex-direction: column;
       align-items: center;
       text-align: left;
       overflow: hidden;
       position: relative;
       background-color: #F1F1F1;
       border:1px solid #c8c8c8;
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
        margin-top: 8px;
        justify-content: space-between;
    }

    .card-title {
        font-size: 1.2em;
        font-weight: bold;
        margin-bottom: 5px;

    }

    .card-info {
        font-size: 0.9em;
        color: #707070;
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

    .spinner {
    font-size: 28px;
    position: relative;
    display: inline-block;
    width: 1em;
    height: 1em;
}

.spinner.center {
    position: absolute;
    left: 0;
    right: 0;
    top: 0;
    bottom: 0;
    margin: auto;
}

.spinner .spinner-blade {
    position: absolute;
    left: 0.4629em;
    bottom: 0;
    width: 0.074em;
    height: 0.2777em;
    border-radius: 0.0555em;
    background-color: #0D8C81; /* Color del blade */
    -webkit-transform-origin: center -0.2222em;
    -ms-transform-origin: center -0.2222em;
    transform-origin: center -0.2222em;
    animation: spinner-fade9234 0.4s infinite linear;
}

.spinner .spinner-blade:nth-child(1) {
    -webkit-animation-delay: 0s;
    animation-delay: 0s;
    -webkit-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    transform: rotate(0deg);
}

.spinner .spinner-blade:nth-child(2) {
    -webkit-animation-delay: 0.083s;
    animation-delay: 0.083s;
    -webkit-transform: rotate(30deg);
    -ms-transform: rotate(30deg);
    transform: rotate(30deg);
}

.spinner .spinner-blade:nth-child(3) {
    -webkit-animation-delay: 0.166s;
    animation-delay: 0.166s;
    -webkit-transform: rotate(60deg);
    -ms-transform: rotate(60deg);
    transform: rotate(60deg);
}

.spinner .spinner-blade:nth-child(4) {
    -webkit-animation-delay: 0.249s;
    animation-delay: 0.249s;
    -webkit-transform: rotate(90deg);
    -ms-transform: rotate(90deg);
    transform: rotate(90deg);
}

.spinner .spinner-blade:nth-child(5) {
    -webkit-animation-delay: 0.332s;
    animation-delay: 0.332s;
    -webkit-transform: rotate(120deg);
    -ms-transform: rotate(120deg);
    transform: rotate(120deg);
}

.spinner .spinner-blade:nth-child(6) {
    -webkit-animation-delay: 0.415s;
    animation-delay: 0.415s;
    -webkit-transform: rotate(150deg);
    -ms-transform: rotate(150deg);
    transform: rotate(150deg);
}

.spinner .spinner-blade:nth-child(7) {
    -webkit-animation-delay: 0.498s;
    animation-delay: 0.498s;
    -webkit-transform: rotate(180deg);
    -ms-transform: rotate(180deg);
    transform: rotate(180deg);
}

.spinner .spinner-blade:nth-child(8) {
    -webkit-animation-delay: 0.581s;
    animation-delay: 0.581s;
    -webkit-transform: rotate(210deg);
    -ms-transform: rotate(210deg);
    transform: rotate(210deg);
}

.spinner .spinner-blade:nth-child(9) {
    -webkit-animation-delay: 0.664s;
    animation-delay: 0.664s;
    -webkit-transform: rotate(240deg);
    -ms-transform: rotate(240deg);
    transform: rotate(240deg);
}

.spinner .spinner-blade:nth-child(10) {
    -webkit-animation-delay: 0.747s;
    animation-delay: 0.747s;
    -webkit-transform: rotate(270deg);
    -ms-transform: rotate(270deg);
    transform: rotate(270deg);
}

.spinner .spinner-blade:nth-child(11) {
    -webkit-animation-delay: 0.83s;
    animation-delay: 0.83s;
    -webkit-transform: rotate(300deg);
    -ms-transform: rotate(300deg);
    transform: rotate(300deg);
}

.spinner .spinner-blade:nth-child(12) {
    -webkit-animation-delay: 0.913s;
    animation-delay: 0.913s;
    -webkit-transform: rotate(330deg);
    -ms-transform: rotate(330deg);
    transform: rotate(330deg);
}

@keyframes spinner-fade9234 {
    0% {
        background-color: #69717d;
    }

    100% {
        background-color: transparent;
    }
}

.loading-indicator {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%); /* Centra el loading-indicator */
    z-index: 1000; /* Asegura que esté encima de otros elementos */
}

.p-4.shadow-sm.bg-light.mt-4 {
    border-radius: 5px;
    position: relative;
}

.pagination-wrapper {
    position: relative;
    padding: 40px 0;
}

.pagination-container {
    position: absolute;
    bottom: 0;
    right: 0;
    display: flex;
    align-items: center;
}

.pagination-container button {
    padding: 8px 12px;
    margin: 0 5px;
    border: 1px solid #ddd;
    background-color: #F9FAFB;
    color: #333;
    cursor: pointer;
    border-radius: 5px; /* Bordes redondeados */
    font-size: 1em; /* Tamaño de fuente */
}

.pagination-container button:disabled {
    background-color: #ddd;
    color: #999;
    cursor: default;
}

.pagination-container button.current-page {
    background-color: #7C69EF; /* Fondo azul */
    color: white; /* Texto blanco */
    border: 1px solid #4c6ef5;
}

</style>
@endpush

@section('content')
<div class="p-4 shadow-sm   mt-4">
    <div class="search-filter-container">
        <input type="text" class="search-input" placeholder="Buscar Asignatura..." onkeyup="searchCourses()">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="search-icon"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>

        @if (backpack_user()->hasRole(['admin', 'super-admin']))

        <button class="create-button" onclick="createCourse()">Crear Asignatura</button> {{-- Botón de Crear --}}
        @endif
    </div>
    <div class="card-container">
        <div class="loading-indicator" style="display: none;">
            <div class="spinner center">
                <div class="spinner-blade"></div>
                <div class="spinner-blade"></div>
                <div class="spinner-blade"></div>
                <div class="spinner-blade"></div>
                <div class="spinner-blade"></div>
                <div class="spinner-blade"></div>
                <div class="spinner-blade"></div>
                <div class="spinner-blade"></div>
                <div class="spinner-blade"></div>
                <div class="spinner-blade"></div>
                <div class="spinner-blade"></div>
                <div class="spinner-blade"></div>
            </div>
        </div>
    </div>
    <div class="no-results-message" id="message-nothing-course" style="display: none; text-align: center; width: 100%; font-size: 1.2em;">
        No hay asignaturas D:
    </div>
    <div class="pagination-wrapper"> <div class="pagination-container" style="text-align: right;"></div>
    </div>
</div>
@endsection

@push('after_scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
let cardsData = [];
let currentPage = 1;
const cardsPerPage = 9;

// Cargar datos iniciales y renderizar la primera página
$.ajax({
    url: '/admin/searchCourses',
    type: 'GET',
    dataType: 'json',
    success: function(data) {

        if (data.length === 0) {

           document.getElementById('message-nothing-course').style.display = 'block';


        }

        cardsData = data;
        renderCards(cardsData, currentPage);
        updatePagination(cardsData);
    },
});

function searchCourses() {
    let searchQuery = $('.search-input').val().trim();

    $('.loading-indicator').show();
    $('.card-container').empty();

    $.ajax({
        url: '/admin/searchCourses',
        type: 'GET',
        data: { search: searchQuery },
        dataType: 'json',
        success: function (data) {
            $('.loading-indicator').hide();
            cardsData = data;

            if (data.length === 0) {
                $('.card-container').hide();
                $('.no-results-message').show();
                $('.pagination-container').empty();
            } else {
                $('.card-container').show();
                $('.no-results-message').hide();
                currentPage = 1;
                renderCards(cardsData, currentPage);
                updatePagination(cardsData);
            }
        },
        error: function () {
            $('.loading-indicator').text("Error").show();
        }
    });
}

function renderCards(cards, page = 1) {
    let cardContainer = $('.card-container');
    cardContainer.empty();

    const startIndex = (page - 1) * cardsPerPage;
    const endIndex = startIndex + cardsPerPage;
    const pageCards = cards.slice(startIndex, endIndex);

    let existingCards = cardContainer.children('.card');

    pageCards.forEach(function(card, index) {
        if (existingCards[index]) {
            $(existingCards[index]).find('.card-title').text(card.nombre);
            $(existingCards[index]).find('.card-info').eq(0).text(`Catálogo: ${card.catalogo}`);
            $(existingCards[index]).find('.card-info').eq(1).text(`Código: ${card.codigo}`);
        } else {
            let cardHtml = `
                <div class="card bg-light" data-id="${card.id}" style="background-color: ${getCardColor(index)};">
                    <div class="card-dropdown-container">
                        <ul class="card-dropdown-menu" id="cardDropdownMenu${index}">
                            <li class="card-dropdown-item" onclick="cardActionView(${card.id}, event)">Ver</li>
                            @if (backpack_user()->hasRole(['admin', 'super-admin']))
                            <li class="card-dropdown-item" onclick="cardActionEdit(${card.id})">Editar</li>
                            <li class="card-dropdown-item" onclick="cardActionDelete(${card.id}, '{{ route('asignaturas.destroy', ['id' => 'alg']) }}'.replace('alg', ${card.id}))">Eliminar</li>
                            @endif
                        </ul>
                    </div>
                    <div class="options-icon" onclick="toggleDropdown('cardDropdownMenu${index}', event)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#232121" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-dots-vertical">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M12 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"/>
                            <path d="M12 19m-1 0a1 1 0 1 0 2 0a1 1 0 -2 0"/>
                            <path d="M12 5m-1 0a1 1 0 1 0 2 0a1 1 0 -2 0"/>
                        </svg>
                    </div>
                    <div class="card-content">
                        <h3 class="">${card.nombre}</h3>
                        <p class="card-info">Catálogo: ${card.catalogo}</p>
                        <p class="card-info">Código: ${card.codigo}</p>
                    </div>
                </div>
            `;
            cardContainer.append(cardHtml);

            let newCard = cardContainer.children('.card').last();
            newCard.click(function(event) {
                if (!$(event.target).closest('.options-icon').length) {
                    cardActionView(card.id);
                }
            });
        }
    });

    if (existingCards.length > cards.length) {
        existingCards.slice(cards.length).remove();
    }
}

function updatePagination(cards) {
    const totalPages = Math.ceil(cards.length / cardsPerPage);
    let paginationHtml = '';

    // Botón "Anterior" siempre presente, deshabilitado en la primera página
    paginationHtml += `<button ${currentPage === 1 ? 'disabled' : ''} onclick="changePage(${currentPage - 1})">Anterior</button>`;

    for (let i = 1; i <= totalPages; i++) {
        paginationHtml += `<button class="${currentPage === i ? 'current-page' : ''}" onclick="changePage(${i})">${i}</button>`;
    }

    // Botón "Siguiente" siempre presente, deshabilitado en la última página
    paginationHtml += `<button ${currentPage === totalPages ? 'disabled' : ''} onclick="changePage(${currentPage + 1})">Siguiente</button>`;

    $('.pagination-container').html(paginationHtml);
}

function changePage(page) {
    currentPage = page;
    renderCards(cardsData, currentPage);
    updatePagination(cardsData);
}

function getCardColor(index) {
    return "#F1F1F1";
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

window.onclick = function(event) {
    const isClickInsideCardDropdown = event.target.closest('.card-dropdown-menu');
    const isClickOnOptionsIcon = event.target.closest('.options-icon');

    if (!isClickInsideCardDropdown && !isClickOnOptionsIcon) {
        document.querySelectorAll('.card-dropdown-menu.show').forEach(function(openDropdown) {
            openDropdown.classList.remove('show');
            openDropdown.style.display = 'none';
        });
    }
}

function cardActionView(cardId) {
    if (event) {
        event.stopPropagation();
    }
    window.location.href = `/admin/asignaturas/${cardId}/show`;
}

function createCourse() {
    if (event) {
        event.stopPropagation();
    }
    window.location.href = `/admin/asignaturas/create`;

}

function cardActionEdit(id) {
    if (event) {
        event.stopPropagation();

    }

    window.location.href = `/admin/asignaturas/${id}/edit`;
}

function cardActionDelete(id, url) {
    if (event) {
        event.stopPropagation();

    }
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción eliminará el elemento.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(url, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').getAttribute('content')
                }
            })
            .then(response => {
                if (response.ok) {
                    Swal.fire(
                        'Eliminado!',
                        'El elemento ha sido eliminado.',
                        'success'
                    ).then(() => window.location.reload());
                } else {
                    Swal.fire(
                        'Error!',
                        'No se pudo eliminar el elemento.',
                        'error'
                    );
                }
            })
            .catch(error => {
                Swal.fire(
                    'Error!',
                    'Hubo un error en la petición DELETE.',
                    'error'
                );
            });
        }
    });
}


</script>
@endpush