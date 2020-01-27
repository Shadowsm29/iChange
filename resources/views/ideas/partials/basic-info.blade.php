<div class="form-group">
    <div class="row">
        <div class="col-md-2">
            <label for="id">ID:</label>
            <input type="text" name="id" id="id" class="form-control" value="{{ $idea->getReqIdAsString() }}" disabled>
        </div>
        <div class="col-md-10">
            <label for="title">Title:</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ $idea->title }}" disabled>
        </div>
    </div>
</div>

<div class="form-group">
    <div class="row">
        <div class="col-md-3">
            <label for="change-type">Change type:</label>
            <input name="change-type" id="change-type" class="form-control" value="{{ $idea->changeType->name }}"
                disabled>
        </div>
        <div class="col-md-3">
            <label for="justification">Justification:</label>
            <input name="justification" id="justification" class="form-control" value="{{ $idea->justification->name }}"
                disabled>
        </div>
        <div class="col-md-3">
            <label for="expected-benefit">Expected benefit({{ $idea->expected_benefit_type }}):</label>
            <input type="text" name="expected-benefit" id="expected-benefit" class="form-control"
                value="{{ $idea->expected_benefit }}" disabled>
        </div>
        <div class="col-md-3">
            <label for="expected-effort">Expected effort in hours:</label>
            @if (isset($centralResources))
            <input type="text" name="expected-effort" id="expected-effort" class="form-control"
                value="{{ $idea->expected_effort }}">
            @else
            <input type="text" name="expected-effort" id="expected-effort" class="form-control"
                value="{{ $idea->expected_effort }}" disabled>
            @endif
        </div>
    </div>
</div>

<div class="form-group">
    <div class="row">
        <div class="col-md-6">
            <label for="impacted-supercircle">Impacted supercircle:</label>
            <input name="impacted-supercircle" id="impacted-supercircle" class="form-control"
                value="{{ $idea->supercircle->name }}" disabled>
        </div>
        <div class="col-md-6">
            <label for="impacted-circle">Impacted circle:</label>
            <input name="impacted-circle" id="impacted-circle" class="form-control" value="{{ $idea->circle->name }}"
                disabled>
        </div>
    </div>
</div>

<div class="form-group">
    <div class="row">
        <div class="col-md-6">
            <label for="submitter">Submitted by:</label>
            <input type="text" name="submitter" id="submitter" class="form-control" value="{{ $idea->submitter->name }}"
                disabled>
        </div>
        <div class="col-md-6">
            <label for="sme">SME:</label>
            @if (isset($assignSme))
            <input type="text" name="sme" id="sme" class="form-control" value="{{ $idea->smeUser->email }}">
            @else
            <input type="text" name="sme" id="sme" class="form-control" value="{{ $idea->smeUser->name }}" disabled>
            @endif
        </div>
    </div>
</div>

<div class="form-group">
    <div class="row">
        <div class="col-md-6">
            <label for="status">Status:</label>
            <input type="text" name="status" id="status" class="form-control" value="{{ $idea->status->name }}"
                disabled>
        </div>
        <div class="col-md-6">
            <label for="pending_at">Assigned to:</label>
            <input type="text" name="pending_at" id="pending_at" class="form-control" value="{{ $idea->pendingAt() }}"
                disabled>
        </div>
    </div>
</div>
@if (isset($ragStatuses))
<div class="form-group">
    <div class="row">
        <div class="col-md-6">
            <label for="rag-status">RAG Status:</label>
            <select name="rag-status" id="rag-status" class="form-control" {{ $idea->isWip() ? "" : "disabled" }}>
                @foreach ($ragStatuses as $ragStatus)
                <option value="{{ $ragStatus->id }}" {{ $ragStatus->id == $idea->ragStatus->id ? "selected" : "" }}>
                    {{ $ragStatus->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <label for="actual-effort">Actual effort (hours)</label>
            <input type="number" name="actual-effort" id="actual-effort" class="form-control"
                value="{{ $idea->actual_effort }}" {{ $idea->isWip() ? "" : "disabled" }}>
        </div>
    </div>
</div>
@endif

<div class="form-group">
    <div class="row">
        <div class="col-md-6">
            <label for="attachment">Attachments:</label> <br>
            @if ($idea->attachments->count() > 0)
            <ul class="list-group">
                @foreach ($idea->attachments as $attachment)
                <li class="list-group-item">
                    <a href="{{ route("attachments.download", [$idea, $attachment]) }}">{{ $attachment->name }}</a>
                </li>
                @endforeach
            </ul>
            @else
            No attachments available
            @endif
        </div>
    </div>
</div>

<div class="form-group">
    <div class="row">
        <div class="col-md-12">
            <label for="descriptioin">Description:</label>
            <textarea name="descriptioin" id="descriptioin" class="form-control" rows="5"
                disabled>{{ $idea->description }}</textarea>
        </div>
    </div>
</div>