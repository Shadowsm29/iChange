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
            <select name="change-type" id="change-type" class="form-control">
                @foreach ($changeTypes as $changeType)
                @if ($changeType->id == $idea->change_type_id)
                <option value="{{ $changeType->id }}"S>
                    {{ $changeType->name }}</option>
                @endif
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label for="justification">Justification:</label>
            <select name="justification" id="justification" class="form-control" required>
                @foreach ($justifications as $justification)
                <option value="{{ $justification->id }}"
                    {{ $justification->id == $idea->justification->id ? "selected" : ""}}>{{ $justification->name }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label for="impacted-supercircle">Impacted supercircle:</label>
            <select name="impacted-supercircle" id="impacted-supercircle" class="form-control" required>
                @foreach ($superCircles as $superCircle)
                <option value="{{ $superCircle->id }}"
                    {{ $superCircle->id == $idea->supercircle->id ? "selected" : "" }}>{{ $superCircle->name }}</option>
                @endforeach
            </select>

        </div>
        <div class="col-md-3">
            <label for="impacted-circle">Impacted circle:</label>
            <select name="impacted-circle" id="impacted-circle" class="form-control" required>
                @foreach ($circles as $circle)
                @if ($idea->supercircle->id == $circle->supercircle->id)
                <option value="{{ $circle->id }}" {{ $circle->id == $idea->circle->id ? "selected" : "" }}>
                    {{ $circle->name }}</option>
                @endif
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="form-group">
    <div class="row">
        <div class="col-md-3">
            <label for="expected-benefit">Expected benefit:</label>
            <input type="text" name="expected-benefit" id="expected-benefit" class="form-control"
                value="{{ $idea->expected_benefit }}" required>
        </div>
        <div class="col-md-3 d-flex flex-column justify-content-center mt-4">
            <div class="form-check">
                <input type="radio" name="expected-benefit-type" id="expected-benefit-hours" class="form-check-input"
                    value="hours/week" {{ $idea->expected_benefit_type == "hours/week" ? "checked" : ""}} required>
                <label for="expected-benefit-hours" class="form-check-label">Hours/week</label>
            </div>
            <div class="form-check">
                <input type="radio" name="expected-benefit-type" id="expected-benefit-euros" class="form-check-input"
                    value="euros" {{ $idea->expected_benefit_type == "euros" ? "checked" : ""}} required>
                <label for="expected-benefit-euros" class="form-check-label">Euros</label>
            </div>
        </div>
        <div class="col-md-3" id="expected-effort-div">
            <label for="expected-effort">Expected effort in hours:</label>
            <input type="text" name="expected-effort" id="expected-effort" class="form-control"
                value="{{ $idea->expected_effort }}" disabled required>
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
    </div>
</div>

<div class="form-group">
    <div class="row">
        {{-- <div class="col-md-6">
            <label for="sme">SME:</label>
            <input type="text" name="sme" id="sme" class="form-control" value="{{ $idea->smeUser->email }}">
        </div> --}}
        <div class="col-md-12">
            <label for="attachment">Attachment:</label>
            {{-- <input type="file" class="form-control-file" id="attachment" name="attachment"> --}}
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

<div class="form-group">
    <div class="row">
        <div class="col-md-12">
            <label for="description">Description:</label>
            <textarea name="description" id="description" class="form-control" rows="5"
                required>{{ $idea->description }}</textarea>
        </div>
    </div>
</div>