@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-md-3">
        @include('partials.admin-sidebar')
    </div>
    <div class="col-md-9">
        <div class="card">
            <div class="card-header bg-success">
                {{ isset($changeType) ? "Edit Change Type" : "Create New Change Type" }}
            </div>
            <div class="card-body">

                <form action="{{ isset($changeType) ? route("change-types.update", $changeType->id) : route('change-types.store') }}" method="POST">

                    @csrf
                    @if (isset($changeType))
                    @method("PUT")
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Name:</label>
                                <input type="text" id="name" name="name" class="form-control" value="{{ isset($changeType) ? $changeType->name : "" }}">
                            </div>
                        </div>
                    </div>

                    <div>
                        <button class="btn btn-success">{{ isset($changeType) ? "Update Change Type" : "Add Change Type" }}</button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

@endsection