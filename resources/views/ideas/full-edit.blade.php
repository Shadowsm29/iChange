@extends('layouts.app')

@section('content')
<div class="card" style="border: none">
    <div class="card-header bg-success">Edit idea</div>
    <div class="card-body" style="border: 1px solid rgb(255, 98, 0)">
        <form action="" method="POST" enctype="multipart/form-data">
            @csrf
            @method("PUT")
            <div class="form-group">
                <div class="row">
                    <div class="col-md-2">
                        <label for="id">ID:</label>
                        <input type="text" name="id" id="id" class="form-control"
                            value="{{ $idea->getReqIdAsString() }}" disabled>
                    </div>
                    <div class="col-md-10">
                        <label for="title">Title:</label>
                        <input type="text" name="title" id="title" class="form-control" value="{{ $idea->title }}">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label for="change-type">Change-type:</label>
                        <select name="change-type" id="change-type" class="form-control" disabled>
                            <option value="{{ $idea->changeType->id }}">{{ $idea->changeType->name }}</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="justification">Justification:</label>
                        <select name="justification" id="justification" class="form-control">
                            @foreach ($justifications as $justification)
                            <option value="{{ $justification->id }}">{{ $justification->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label for="impacted-supercircle">Impacted supercircle:</label>
                        <select name="impacted-supercircle" id="impacted-supercircle" class="form-control">
                            <option value=""></option>
                            @foreach ($superCricles as $superCricle)
                            <option value="{{ $superCricle->id }}"
                                {{ $idea->superCircle->id == $superCricle->id ? "selected" : "" }}>
                                {{ $superCricle->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="impacted-circle">Impacted circle:</label>
                        <select name="impacted-circle" id="impacted-circle" class="form-control">
                            @foreach ($circles as $circle)
                            <option value="{{ $circle->id }}" {{ $idea->circle->id == $circle->id ? "selected" : "" }}>
                                {{ $circle->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-3">
                        <label for="expected-benefit">Expected benefit:</label>
                        <input type="text" name="expected-benefit" id="expected-benefit" class="form-control" value="5">
                    </div>
                    <div class="col-md-3 d-flex flex-column justify-content-center mt-4">
                        <div class="form-check">
                            <input type="radio" name="expected-benefit-type" id="expected-benefit-hours"
                                class="form-check-input" value="hours/week" checked>
                            <label for="expected-benefit-hours" class="form-check-label">Hours/week</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" name="expected-benefit-type" id="expected-benefit-euros"
                                class="form-check-input" value="euros">
                            <label for="expected-benefit-euros" class="form-check-label">Euros</label>
                        </div>
                    </div>
                    <div class="col-md-6" id="expected-effort-div">
                        <label for="expected-effort">Expected effort in hours:</label>
                        <input type="text" name="expected-effort" id="expected-effort" class="form-control"
                            value="{{ $idea->expected_effort }}" @if ($idea->expected_effort == null)
                        disabled
                        @endif
                        >
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label for="submitter">Submitted by:</label>
                        <select name="submitter" id="submitter" class="form-control">
                            @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ $idea->submitter->id == $user->id ? "selected" : "" }}>
                                {{ $user->email }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="sme">SME:</label>
                        <select name="sme" id="sme" class="form-control">
                            @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ $idea->smeUser->id == $user->id ? "selected" : "" }}>
                                {{ $user->email }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label for="status">Status:</label>
                        <input type="text" name="status" id="status" class="form-control"
                            value="{{ $idea->status->name }}" disabled>
                    </div>
                    <div class="col-md-6">
                        <label for="pending_at">Assigned to:</label>
                        <input type="text" name="pending_at" id="pending_at" class="form-control"
                            value="{{ $idea->pendingAt() }}" disabled>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label for="rag-status">RAG Status:</label>
                        <select name="rag-status" id="rag-status" class="form-control">
                            @foreach ($ragStatuses as $ragStatus)
                            <option value="{{ $ragStatus->id }}"
                                {{ $ragStatus->id == $idea->ragStatus->id ? "selected" : "" }}>
                                {{ $ragStatus->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="actual-effort">Actual effort (hours)</label>
                        <input type="number" name="actual-effort" id="actual-effort" class="form-control"
                            value="{{ $idea->actual_effort }}" {{ $idea->actual_effort == null ? "disabled" : "" }}>
                    </div>
                </div>
            </div>

            @include('ideas.partials.delete-attachments')

            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <label for="description">Description:</label>
                        <textarea name="description" id="description" class="form-control"
                            rows="5">Description of the idea</textarea>
                    </div>
                </div>
            </div>

            @include('ideas.partials.comment')

            <div class="text-center">
                <button class="btn btn-success" formaction="{{ route('ideas.full-edit', $idea) }}">Submit changes</button>
                @if (URL::previous() == URL::current())
                <a href="{{ route("ideas.personal-que-active") }}" class="btn btn-primary ml-2">Back to personal
                    queue</a>
                @else
                <a href="{{ URL::previous() }}" class="btn btn-primary ml-2">Back</a>
                @endif
            </div>

        </form>
    </div>
</div>

@include('ideas.partials.show-comments')

@endsection


@section('scripts')

<script type="text/javascript" src="{{ URL::asset('js/scripts/scripts.js') }}"></script>

<script>
    var circles = {!! json_encode($circles) !!};

    $(document).ready(function() {
        fillCirclesOptions(circles);
    });


</script>

@endsection