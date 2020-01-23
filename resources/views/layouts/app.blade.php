<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    @yield('css')

    <style>
        .btn-success {
            background-color: rgb(255, 98, 0);
            border: 1px solid rgb(255, 98, 0);
        }

        .btn-success:hover {
            background-color: rgba(255, 98, 0, 0.8);
        }

        .bg-success {
            background-color: rgb(255, 98, 0) !important;
            color: white;
        }
    </style>
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        @auth

                        @if (auth()->user()->isSubmitter())

                        <li class="nav-item"><a class="nav-link" href="{{ route('ideas.create') }}">Register new
                                idea</a></li>
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                My ideas<span class="caret"></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('ideas.my-active-ideas') }}">My active
                                    ideas</a>
                                <a class="dropdown-item" href="{{ route('ideas.my-all-ideas') }}">My all
                                    ideas</a>

                            </div>
                        </li>

                        @endif

                        @if (auth()->user()->canSeeAllIdeas())

                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                All ideas<span class="caret"></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('ideas.all-ideas') }}">All ideas</a>
                                <a class="dropdown-item" href="{{ route('ideas.all-active') }}">All active
                                    ideas</a>

                            </div>
                        </li>

                        @endif

                        @if (auth()->user()->isIdeaProcessor())

                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                Personal Queue<span class="caret"></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('ideas.personal-que-active') }}">Personal Queue
                                    (Active)</a>
                                <a class="dropdown-item" href="{{ route('ideas.personal-que-all') }}">Personal Queue
                                    (All)</a>
                            </div>
                        </li>

                        @endif

                        <li class="nav-item"><a class="nav-link" href="{{ route('notifications.index') }}">
                                Notifications
                                @if (auth()->user()->unreadNotifications->count() > 0)
                                <span class="badge badge-info" style="background-color: rgb(255, 98, 0);color: white;">
                                    {{ auth()->user()->unreadNotifications->count() }} unread
                                </span>
                                @endif
                            </a></li>

                        @if (auth()->user()->isPrivilegedUser())
                        @if (auth()->user()->isIam())
                        <li class="nav-item"><a class="nav-link" href="{{ route('users.index') }}">Admin area</a></li>
                        @else
                        <li class="nav-item"><a class="nav-link" href="{{ route('change-types.index') }}">Admin area</a>
                        </li>
                        @endif

                        @endif

                        @endauth
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                        @endif
                        @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route("users.change-password-form") }}">Change
                                    password</a>
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="my-0">
                @foreach ($errors->all() as $error)
                <li class="list-unstyled">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if (session()->has("success"))
        <div class="alert alert-success">
            <ul class="my-0">
                <li class="list-unstyled">
                    {{ session()->get("success") }}
                </li>
            </ul>
        </div>
        @endif

        @if (session()->has("error"))
        <div class="alert alert-danger">
            <ul class="my-0">
                <li class="list-unstyled">
                    {{ session()->get("error") }}
                </li>
            </ul>
        </div>
        @endif

        <main class="py-4">
            <div class="container">
                @yield('content')
            </div>
            @yield('table')
        </main>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>

    @yield('scripts')
</body>

</html>