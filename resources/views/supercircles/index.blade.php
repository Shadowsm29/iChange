@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-md-3">
        @include('partials.admin-sidebar')
    </div>
    <div class="col-md-9">
        <div class="card">
            <div class="card-header bg-success">
                Supercircles
                <form action="{{ route('supercircles.create') }}" method="GET" class="float-right">
                    @csrf
                    <button class="btn btn-secondary btn-sm">Add new supercircle</button>
                </form>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Actions</th>
                    </thead>
                    <tbody>
                        @foreach ($supercircles as $supercircle)
                        <tr>
                            <td>{{ $supercircle->id }}</td>
                            <td>{{ $supercircle->name }}</td>
                            <td>

                                <form action="{{ route('supercircles.edit', $supercircle->id) }}" method="GET" class="d-inline-block">

                                    @csrf

                                    <button class="btn btn-primary btn-sm">Edit</button>

                                </form>

                                <form action="{{ route('supercircles.destroy', $supercircle->id) }}" method="POST"
                                    class="d-inline-block">

                                    @csrf
                                    @method("DELETE")

                                    <button class="btn btn-danger btn-sm">Delete</button>

                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection