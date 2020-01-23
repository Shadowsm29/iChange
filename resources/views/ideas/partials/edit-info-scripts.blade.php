<script type="text/javascript" src="{{ URL::asset('js/autocomplete/auto-complete.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('js/scripts/scripts.js') }}"></script>

{{-- {{dd($idea->expected_effort == null)}} --}}

<script>

var circles = {!! json_encode($circles) !!};
var users = {!! json_encode($users) !!};
var expectedEffort = {!! $idea->expected_effort == null ? '""' : $idea->expected_effort !!};
var sme = "{!! $idea->smeUser->email !!}";
var expectedEffortArr = {!! json_encode($enableExpectedEffort) !!};
var smeArr = {!! json_encode($enableSme) !!};

$(document).ready(function() {

    fillCirclesOptions(circles);
    enableExpectedEffort(expectedEffort, expectedEffortArr);
    enableSme(sme, smeArr);
    
    var usersArray =  jsonToArray(users, "email");
    fillSuggestions(usersArray, "sme");    
});


</script>