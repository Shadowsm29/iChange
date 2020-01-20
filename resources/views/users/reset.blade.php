@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-md-3">
        @include('partials.admin-sidebar')
    </div>
    <div class="col-md-9">
        <div class="card">
            <div class="card-header bg-success">
                Reset password
            </div>
            <div class="card-body">

                <form action="{{ route("users.reset", $user->id)}}" method="POST">

                    @csrf
                    @method("PUT")

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input type="password" id="password" name="password" class="form-control" value="password">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="repeat_password">Repeat Password:</label>
                                <input type="password" id="repeat_password" name="repeat_password" class="form-control" value="password">
                            </div>
                        </div>
                    </div>

                    <div>
                        <button
                            class="btn btn-success">Reset password</button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

@endsection