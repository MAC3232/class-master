@extends(backpack_view('layouts.auth'))

@section('content')
    <div class="page page-center">
        <div class="container container-tight py-4">
            <div class="text-center mb-4 display-6 auth-logo-container">
                <!-- {!! backpack_theme_config('project_logo') !!} -->
            </div>
            <div style="display: flex; justify-content: center; align-items: center;" class="card card-md">
                <img style="width: 20rem; margin:10px;margin-top:20px"  src="{{asset('img/icon.png')}}" alt="">
                <div class="card-body pt-0">
                    @include(backpack_view('auth.login.inc.form'))
                </div>
            </div>

        </div>
    </div>
@endsection
