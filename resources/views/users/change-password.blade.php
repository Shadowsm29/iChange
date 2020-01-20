@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-md-8 offset-2">
        <div class="card">
            <div class="card-header bg-success">
                Change password
            </div>
            <div class="card-body">

                <form action="{{ route("users.change-password")}}" method="POST">

                    @csrf
                    @method("PUT")

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="old_password">Old Password:</label>
                                <input type="password" id="old_password" name="old_password" class="form-control" value="password">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="new_password">Password:</label>
                                <input type="password" id="new_password" name="new_password" class="form-control" value="password">
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
                            class="btn btn-success">Change password</button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

@endsection