@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-md-3">
        @include('partials.admin-sidebar')
    </div>
    <div class="col-md-9">
        <div class="card">
            <div class="card-header bg-success">
                Users
                @if (!isset($trashed))
                <form action="{{ route('users.create') }}" method="GET" class="float-right">
                    @csrf
                    <button class="btn btn-secondary btn-sm">Add new user</button>
                </form>
                @endif
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Roles</th>
                        <th>Actions</th>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @foreach ($user->roles as $role)
                                <p>{{ $role->name }}</p>
                                @endforeach
                            </td>
                            <td>
                                @if (!isset($trashed))

                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                <a href="{{ route('users.reset-form', $user->id) }}" class="btn btn-warning btn-sm">PWD
                                    reset</a>
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-danger btn-sm delete-button" data-toggle="modal"
                                    data-target="#delete-user-modal" user-id={{ $user->id }}
                                    user-name={{ $user->name }}>
                                    Delete
                                </button>

                                @else

                                <form action="{{ route('users.restore', $user->id) }}" method="POST" class="d-inline-block">

                                    @csrf
                                    @method("PUT")

                                    <button class="btn btn-primary btn-sm">Restore</button>

                                </form>

                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @if (isset($trashed) && count($users) == 0)
                <div class="text-center">
                    <h3>No trashed users currently.</h3>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if (!isset($trashed))
<!-- Modal -->
<div class="modal fade" id="delete-user-modal" tabindex="-1" role="dialog" aria-labelledby="delete-user-modal"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="delete-user-modal-title">Delete user</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Would you like to delete user <span id="delete-user-modal-name"></span>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>

                <form action="{{ route('users.destroy', '*') }}" method="POST" class="d-inline-block"
                    id="delete-button-form">

                    @csrf
                    @method("DELETE")

                    <button class="btn btn-danger">Delete</button>

                </form>

            </div>
        </div>
    </div>
</div>
@endif

@endsection

@section('scripts')

<script src="https://code.jquery.com/jquery-3.4.1.min.js"
    integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous">
</script>

<script>
    $( document ).ready(function() {
    
    $(".delete-button").click(function () { 
    
        userID = $(this).attr("user-id");
        userName = $(this).attr("user-name");
    
        $("#delete-user-modal-name").text(userName);

        action = $("#delete-button-form").attr("action");
        action = action.replace("*", userID);
        $("#delete-button-form").attr("action", action);
    });

});

</script>

@endsection