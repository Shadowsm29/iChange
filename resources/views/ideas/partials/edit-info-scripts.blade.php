<script type="text/javascript" src="{{ URL::asset('js/autocomplete/auto-complete.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('js/scripts/scripts.js') }}"></script>

{{-- {{dd($idea->expected_effort == null)}} --}}

<script>

var circles = {!! json_encode($circles) !!};
var users = {!! json_encode($users) !!};
var expectedEffort = {!! $idea->expected_effort == null ? '""' : $idea->expected_effort !!};

$(document).ready(function() {

    fillCirclesOptions(circles);
    enableExpectedEffort(expectedEffort);
    
    var usersArray =  jsonToArray(users, "email");
    fillSuggestions(usersArray, "sme");    
});


</script>
