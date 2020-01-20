@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-md-3">
        @include('partials.admin-sidebar')
    </div>
    <div class="col-md-9">
        <div class="card">
            <div class="card-header bg-success">
                {{ isset($circle) ? "Edit Circle" : "Create New Circle" }}
            </div>
            <div class="card-body">

                <form action="{{ isset($circle) ? route("circles.update", $circle->id) : route('circles.store') }}"
                    method="POST">

                    @csrf
                    @if (isset($circle))
                    @method("PUT")
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Name:</label>
                                <input type="text" id="name" name="name" class="form-control"
                                    value="{{ isset($circle) ? $circle->name : "" }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="supercircle">Supercircle:</label>
                                <select id="supercircle" name="supercircle" class="form-control">
                                    @foreach ($supercircles as $supercircle)
                                    <option value="{{ $supercircle->id }}" 
                                        @if (isset($circle))
                                            @if ($circle->supercircle_id == $supercircle->id)
                                                selected
                                            @endif
                                        @endif
                                        >{{ $supercircle->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div>
                        <button class="btn btn-success">{{ isset($circle) ? "Update Circle" : "Add Circle" }}</button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

@endsection