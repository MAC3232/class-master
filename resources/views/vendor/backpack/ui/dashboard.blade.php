@extends(backpack_view('blank'))

@php



@endphp

@section('content')
<div class="bg-light p-5">

    <div class="text-center " style="margin-top: 20px; margin-bottom: 50px;">
        <img src="{{ asset('img/img_logo.png') }}" alt="Imagen de Class Master" class="img-fluid mb-4" style="max-width: 10rem; height: auto; margin-bottom: 40px;">
        <h1
  style="
    font-family: 'roboto', serif;
    font-size: 3.5rem;
    font-weight: 700;
      color:rgb(0, 0, 0);
    text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);

    text-align: center;
    margin-top: 20px;
  ">
  Bienvenidos a Class Master 📕👋
</h1>

        <p style="font-size: 1rem;">Automatiza las funciones de evaluación de los docentes para mejorar la calidad educativa.</p>


    </div>

</div>
@endsection
