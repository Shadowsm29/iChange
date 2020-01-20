@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-md-3">
        @include('partials.admin-sidebar')
    </div>
    <div class="col-md-9">
        <div class="card">
            <div class="card-header bg-success">
                {{ isset($justification) ? "Edit Justification" : "Create New Justification" }}
            </div>
            <div class="card-body">

                <form action="{{ isset($justification) ? route("justifications.update", $justification->id) : route('justifications.store') }}" method="POST">

                    @csrf
                    @if (isset($justification))
                    @method("PUT")
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Name:</label>
                                <input type="text" id="name" name="name" class="form-control" value="{{ isset($justification) ? $justification->name : "" }}">
                            </div>
                        </div>
                    </div>

                    <div>
                        <button class="btn btn-success">{{ isset($justification) ? "Update Justification" : "Add Justification" }}</button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

@endsection