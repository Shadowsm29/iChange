function displaySme(lss, rpa, cosmos, it) {
    var changeTypeEl = "#change-type";
    var smediv = "#sme-div";

    if($(changeTypeEl).val() == lss || $(changeTypeEl).val() == rpa || $(changeTypeEl).val() == cosmos || $(changeTypeEl).val() == it) {
        $(smediv).show();
    }
    else {
        $(smediv).hide();
    }

    $(changeTypeEl).change(function () {
        if($(this).val() == lss || $(this).val() == rpa || $(this).val() == cosmos || $(this).val() == it) {
            $(smediv).show();
        }
        else {
            $(smediv).hide();
        }
    });
}

function displayExpectedEffort(justDoIt) {
    var expEffortDiv = "#expected-effort-div";
    var changeTypeEl = "#change-type";

    if($(changeTypeEl).val() == justDoIt) {
        $(expEffortDiv).show();
    }
    else {
        $(expEffortDiv).hide();
    }
    
    $(changeTypeEl).change(function () {
        if (this.value == justDoIt) {
            $(expEffortDiv).show();
        }
        else {
            $(expEffortDiv).hide();
        }
    });
}

function enableExpectedEffort(expectedEffort) {

    var changeTypeEl = "#change-type";
    var expEffortEl = "#expected-effort";

    if ($(changeTypeEl).val() == 5) {
        //JUST DO IT
        $(expEffortEl).removeAttr('disabled');
    }
    else {
        //Other types
        $(expEffortEl).attr('disabled',true);
    }

    $(changeTypeEl).change(function () {
        if (this.value == 5) {
            //JUST DO IT
            $(expEffortEl).removeAttr('disabled');
        }
        else {
            //Other types
            $(expEffortEl).attr('disabled',true);
            $(expEffortEl).val(expectedEffort);
        }
    });
}

function fillCirclesOptions(circles) {
    $("#impacted-supercircle").change(function () {

        $("#impacted-circle > *").remove();
        superCircleId = $(this).val();

        $("#impacted-circle").append('<option value=""></option>');

        for (var i = 0; i < circles.length; i++) {
            if (superCircleId == circles[i]["supercircle_id"]) {
                $("#impacted-circle").append('<option value="' + circles[i]["id"] + '">' + circles[i]["name"] + '</option>');
            }
        }

    });
}

function fillSuggestions(suggestionsArray, inputName) {
    var autoCompleteField = new autoComplete({
        selector: 'input[name="' + inputName + '"]',
        minChars: 2,
        source: function (term, suggest) {
            suggest(suggestionsArray);
        }
    });
}

function jsonToArray(json, key) {
    var array = [];
    json.forEach(function (element) {
        array.push(element[key]);
    });
    return array;
}