@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-3">
        @include('partials.admin-sidebar')
    </div>
    <div class="col-md-9">
        <div class="card">
            <div class="card-header bg-success">
                {{ isset($supercircle) ? "Edit Supercircle" : "Create New Supercircle" }}
            </div>
            <div class="card-body">

                <form action="{{ isset($supercircle) ? route('supercircles.update', $supercircle->id) : route('supercircles.store') }}" method="POST">

                    @csrf
                    @if (isset($supercircle))
                    @method("PUT")
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Name:</label>
                                <input type="text" id="name" name="name" class="form-control" value="{{ isset($supercircle) ? $supercircle->name : "" }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6>Associated circles:</h6>
                            <ul class="list-unstyled">
                                @foreach ($circles as $circle)
                                    @if ($supercircle->id == $circle->supercircle_id)
                                    <li>{{ $circle->name }}</li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <div>
                        <button class="btn btn-success">{{ isset($supercircle) ? "Update Supercircle" : "Add Supercircle" }}</button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

@endsection