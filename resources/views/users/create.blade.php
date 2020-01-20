@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-md-3">
        @include('partials.admin-sidebar')
    </div>
    <div class="col-md-9">
        <div class="card">
            <div class="card-header bg-success">
                {{ isset($user) ? "Edit user" : "Create new user" }}
            </div>
            <div class="card-body">

                <form action="{{ isset($user) ? route("users.update", $user->id) : route('users.store') }}"
                    method="POST">

                    @csrf
                    @if (isset($user))
                    @method("PUT")
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Name:</label>
                                <input type="text" id="name" name="name" class="form-control"
                                    value="{{ isset($user) ? $user->name : 'Test user' }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">E-mail:</label>
                                <input type="text" id="email" name="email" class="form-control"
                                    value="{{ isset($user) ? $user->email : 'email@test.com' }}"
                                    {{ isset($user) ? "disabled" : "" }}>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-check-label">Role:</label><br>
                            <div class="form-check">
                                @if (isset($user))
                                    @foreach ($roles as $role)
                                        <input type="checkbox" class="form-check-input" name="role_ids[]" value="{{ $role->id }}" id="role-{{$role->id}}"
                                        @foreach ($user->roles as $userRole)
                                            @if ($role->name == $userRole->name)
                                            checked
                                            @endif
                                        @endforeach
                                        ><label for="role-{{$role->id}}">{{ $role->name }}</label> <br>
                                    @endforeach
                                @else
                                    @foreach ($roles as $role)
                                    <input type="checkbox" class="form-check-input" name="role_ids[]" value="{{ $role->id }}" id="role-{{$role->id}}"><label for="role-{{$role->id}}">{{ $role->name }}</label> <br>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        @if (!isset($user))
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">Password:</label>
                                    <input type="password" id="password" name="password" class="form-control"
                                        value="{{ isset($user) ? "" : "password" }}">
                                </div>
                                <div class="form-group">
                                    <label for="repeat_password">Repeat Password:</label>
                                    <input type="password" id="repeat_password" name="repeat_password" class="form-control"
                                        value="{{ isset($user) ? "" : "password" }}">
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="text-center">
                        <button class="btn btn-success">{{ isset($user) ? "Update user" : "Add user" }}</button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

@endsection