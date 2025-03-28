<!-- PLANTILLA BASE DE BACKPACK -->
@extends(backpack_view('blank'))

<!-- Migajas de pan -->

@php
    $index = 0;
    $defaultBreadcrumbs = [
        trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
        $crud->entity_name_plural => url($crud->route),
        trans('PANEL') => false,
    ];


    $breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;



@endphp


<!-- Configuracion de estulilos -->
@push('after_styles')

    <!-- Recomendable. el archivo se encuentra en public->css -->
    <link rel="stylesheet" href="{{asset('css/panel-asignatura.css')}}">
    <style>

    </style>
@endpush

<!-- iNFORMACION DEL HEADER EJ: NOMBRE DE LA ASIGNATURA, ... -->
@section('header')
    <section class="header">
        <!-- Header -->
        <div class="header-info">
            <h1>Drama II</h1>
            <div class="course-details">
                <span>Código: 0975</span>
                <span class="separator">•</span>
                <span>Catálogo: 504164M</span>
                <span class="separator">•</span>
                <span>Créditos: 3</span>
            </div>
        </div>
        <div class="header-actions">
            <button class="btn btn-outline btn-calendar">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="#4338CA" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect width="18" height="18" x="3" y="4" rx="2" ry="2"></rect>
                    <line x1="16" x2="16" y1="2" y2="6"></line>
                    <line x1="8" x2="8" y1="2" y2="6"></line>
                    <line x1="3" x2="21" y1="10" y2="10"></line>
                </svg>
                Calendario
            </button>
            <button class="btn btn-outline btn-excel">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="#2A8D4F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <path d="M8 13h2"></path>
                    <path d="M8 17h2"></path>
                    <path d="M14 13h2"></path>
                    <path d="M14 17h2"></path>
                </svg>
                Exportar a Excel
            </button>
            <button class="btn btn-outline btn-attendance">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="#1D4ED8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                </svg>
                Asistencia
            </button>
        </div>
    </section>
@endsection

<!-- Contenido o cuerpo -->
@section('content')
    <!-- Contenido o cuerpo -->
    <div class="container">
        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div>
                    <p class="stat-label">Estudiantes</p>
                    <p class="stat-value">32</p>
                </div>
                <div class="stat-icon stat-icon-blue">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </div>
            </div>

            <div class="stat-card">
                <div>
                    <p class="stat-label">Asistencia Promedio</p>
                    <p class="stat-value">85%</p>
                </div>
                <div class="stat-icon stat-icon-green">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
                        <path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                        <path d="M9 14l2 2l4 -4" />
                    </svg>
                </div>
            </div>

            <div class="stat-card">
                <div>
                    <p class="stat-label">Actividades</p>
                    <p class="stat-value">12</p>
                </div>
                <div class="stat-icon stat-icon-blue">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                    </svg>
                </div>
            </div>

            <div class="stat-card">
                <div>
                    <p class="stat-label">Promedio General</p>
                    <p class="stat-value">4.2</p>
                </div>
                <div class="stat-icon stat-icon-yellow">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="star-icon">
                        <polygon
                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                        </polygon>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="tabs">
            <div class="tabs-list">
                <button class="tab-trigger active" data-tab="estudiantes">Gestión de Estudiantes</button>
                <button class="tab-trigger" data-tab="actividades">Actividades</button>
                <button id="syllab" class="btn btn-primary">Ver syllabus</button>
             </div>

            <div class="tab-content active" id="estudiantes-content">
                <div class="content-card">
                    <!-- Search and Actions -->
                    <div class="search-actions">
                        <div class="search-container">
                            <input type="text" placeholder="Buscar..." class="search-input">
                            <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"></circle>
                                <path d="m21 21-4.3-4.3"></path>
                            </svg>
                        </div>
                        <button class="btn-outline">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                            </svg>
                            Filtros
                        </button>
                        <button class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            Agregar
                        </button>
                        <button class="btn-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 4l0 10" />
                                <path d="M12 4l4 4" />
                                <path d="M12 4l-4 4" />
                                <path d="M4 20l16 0" />
                            </svg>
                            Importar
                        </button>
                        <button class="btn-third">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" x2="12" y1="8" y2="16"></line>
                                <line x1="8" x2="16" y1="12" y2="12"></line>
                            </svg>
                            Seleccionar
                        </button>
                    </div>


                    <!-- Table -->
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Estudiante</th>
                                    <th>Código</th>
                                    <th class="asistencia">Asistencia</th>
                                    <th class="calificacion">Calificación</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="student-info">
                                            <div class="student-avatar">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                                                    <circle cx="12" cy="7" r="4"></circle>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="student-name">Estudiante 1</p>
                                                <p class="student-email">estudiante1@email.com</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>EST1001</td>
                                    <td class="attendance-cell"><span class="attendance-badge">85%</span></td>
                                    <td class="grade">4.5</td>
                                    <td>
                                        <div class="action-buttons">
                                            <!-- Evaluar estudiantes icon -->
                                            <button class="action-btn">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path
                                                        d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
                                                    <path
                                                        d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                                                    <path d="M9 14l2 2l4 -4" />
                                                </svg>
                                            </button>
                                            <!-- Reportes icon -->
                                            <button class="action-btn">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path>
                                                    <path d="M22 12A10 10 0 0 0 12 2v10z"></path>
                                                </svg>
                                            </button>
                                            <!-- View icon (original) -->
                                            <button class="action-btn">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                            </button>
                                            <!-- Edit icon (original) -->
                                            <button class="action-btn">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path>
                                                </svg>
                                            </button>
                                            <!-- Delete icon (original) -->
                                            <button class="action-btn delete-btn">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M3 6h18"></path>
                                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="student-info">
                                            <div class="student-avatar">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                                                    <circle cx="12" cy="7" r="4"></circle>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="student-name">Estudiante 1</p>
                                                <p class="student-email">estudiante1@email.com</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>EST1001</td>
                                    <td class="attendance-cell"><span class="attendance-badge">85%</span></td>
                                    <td class="grade">4.5</td>
                                    <td>
                                        <div class="action-buttons">
                                            <!-- Evaluar estudiantes icon -->
                                            <button class="action-btn">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path
                                                        d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
                                                    <path
                                                        d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                                                    <path d="M9 14l2 2l4 -4" />
                                                </svg>
                                            </button>
                                            <!-- Reportes icon -->
                                            <button class="action-btn">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path>
                                                    <path d="M22 12A10 10 0 0 0 12 2v10z"></path>
                                                </svg>
                                            </button>
                                            <!-- View icon (original) -->
                                            <button class="action-btn">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                            </button>
                                            <!-- Edit icon (original) -->
                                            <button class="action-btn">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path>
                                                </svg>
                                            </button>
                                            <!-- Delete icon (original) -->
                                            <button class="action-btn delete-btn">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M3 6h18"></path>
                                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="student-info">
                                            <div class="student-avatar">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                                                    <circle cx="12" cy="7" r="4"></circle>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="student-name">Estudiante 1</p>
                                                <p class="student-email">estudiante1@email.com</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>EST1001</td>
                                    <td class="attendance-cell"><span class="attendance-badge">85%</span></td>
                                    <td class="grade">4.5</td>
                                    <td>
                                        <div class="action-buttons">
                                            <!-- Evaluar estudiantes icon -->
                                            <button class="action-btn">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path
                                                        d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
                                                    <path
                                                        d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                                                    <path d="M9 14l2 2l4 -4" />
                                                </svg>
                                            </button>
                                            <!-- Reportes icon -->
                                            <button class="action-btn">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path>
                                                    <path d="M22 12A10 10 0 0 0 12 2v10z"></path>
                                                </svg>
                                            </button>
                                            <!-- View icon (original) -->
                                            <button class="action-btn">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                            </button>
                                            <!-- Edit icon (original) -->
                                            <button class="action-btn">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path>
                                                </svg>
                                            </button>
                                            <!-- Delete icon (original) -->
                                            <button class="action-btn delete-btn">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M3 6h18"></path>
                                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="pagination-container">
                        <p class="pagination-info">Mostrando 1-10 de 50 estudiantes</p>
                        <div class="pagination-controls">
                            <button class="pagination-btn" id="prev-btn">Anterior</button>
                            <button class="pagination-btn pagination-btn-active" data-page="1">1</button>
                            <button class="pagination-btn" data-page="2">2</button>
                            <button class="pagination-btn" data-page="3">3</button>
                            <button class="pagination-btn" id="next-btn">Siguiente</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-content" id="actividades-content">
                <div class="content-card">
                    <!-- Actividades Header -->
                    <div class="activities-header">
                        <h2>Gestión de Actividades</h2>
                        <button class="btn-nueva-actividad">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 5v14"></path>
                                <path d="M5 12h14"></path>
                            </svg>
                            Nueva Actividad
                        </button>
                    </div>

                    <!-- Activity Card -->
                    <div class="activity-card">
                        <div class="activity-info">
                            <h3 class="activity-title">Actividad 1</h3>
                            <p class="activity-date">Fecha de entrega: 2024/03/11</p>
                            <div class="activity-tags">
                                <span class="activity-tag tag-task">Tarea</span>
                                <span class="activity-tag tag-percentage">10%</span>
                            </div>
                        </div>
                        <div class="action-buttons">
                            <button class="action-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>
                            <button class="action-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path>
                                </svg>
                            </button>
                            <button class="action-btn delete-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M3 6h18"></path>
                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

@endsection

        <!-- Script js -->
        @push('after_scripts')
            <!-- Recomendable. el archivo se encuentra en: public->js -->
            <script src="{{asset('js/panel-asignatura.js')}}"></script>
            <script></script>
        @endpush
