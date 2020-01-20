@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ URL::asset('js/autocomplete/auto-complete.css') }}">
@endsection

@section('content')
<div class="card" style="border: none">
    <div class="card-header bg-success">Register new idea</div>
    <div class="card-body" style="border: 1px solid rgb(255, 98, 0)">
        <form action="{{ route('ideas.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <label for="title">Title:</label>
                        <input type="text" name="title" id="title" class="form-control" value="Idea title">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label for="change-type">Change-type:</label>
                        @if (auth()->user()->isAdvancedUser())
                        <select name="change-type" id="change-type" class="form-control">
                            <option value=""></option>
                            @foreach ($changeTypes as $changeType)
                            <option value="{{ $changeType->id }}">{{ $changeType->name }}</option>
                            @endforeach
                        </select>
                        @else
                        <select name="change-type" id="change-type" class="form-control">
                            @foreach ($changeTypes as $changeType)
                            @if ($changeType->id == $justDoItId)
                            <option value="{{ $changeType->id }}">{{ $changeType->name }}</option>
                            @endif
                            @endforeach
                        </select>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label for="justification">Justification:</label>
                        <select name="justification" id="justification" class="form-control">
                            {{-- <option value=""></option> --}}
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
                            <option value="{{ $superCricle->id }}">{{ $superCricle->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="impacted-circle">Impacted circle:</label>
                        <select name="impacted-circle" id="impacted-circle" class="form-control">
                            {{-- Filled in dynamically via JS --}}
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
                        <input type="text" name="expected-effort" id="expected-effort" class="form-control">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <div id="sme-div">
                            <label for="sme">SME:</label>
                            <input type="text" name="sme" id="sme" class="form-control" value="superadmin@test.com">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="attachment">Attachment:</label>
                        <input type="file" class="form-control-file" id="attachment" name="attachment">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <label for="description">Description:</label>
                        <textarea name="description" id="description" class="form-control"
                            rows="5">Description of the idea</textarea>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <button class="btn btn-success">Register idea</button>
            </div>

        </form>
    </div>
</div>
@endsection


@section('scripts')

<script type="text/javascript" src="{{ URL::asset('js/autocomplete/auto-complete.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('js/scripts/scripts.js') }}"></script>

<script>
    var circles = {!! json_encode($circles) !!};
    var users = {!! json_encode($users) !!};
    var currentUser = "{!! auth()->user()->email !!}";
    var lss = {!! $lss !!};
    var rpa = {!! $rpa !!};
    var cosmos = {!! $cosmos !!};
    var it = {!! $it !!};
    var justDoIt = {!! $justDoItId !!}

    $(document).ready(function() {
        
        displaySme(lss, rpa, cosmos, it);
        displayExpectedEffort(justDoIt);
        fillCirclesOptions(circles);
    
    var usersArray =  jsonToArray(users, "email");
    fillSuggestions(usersArray, "sme");    
});


</script>

@endsection