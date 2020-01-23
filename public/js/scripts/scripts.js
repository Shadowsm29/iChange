function enableSme(sme, validValues) {
    var changeTypeEl = "#change-type";
    var smeEl = "#sme";

    for (var i = 0; i < validValues.length; i++) {
        if ($(changeTypeEl).val() == validValues[i]) {
            $(smeEl).removeAttr('disabled');
            break;
        }
        else {
            $(smeEl).attr('disabled', true);
        }
    }

    $(changeTypeEl).change(function () {
        for (var i = 0; i < validValues.length; i++) {
            if ($(changeTypeEl).val() == validValues[i]) {
                $(smeEl).removeAttr('disabled');
                break;
            }
            else {
                $(smeEl).attr('disabled', true);
                $(smeEl).val(sme);
            }
        }
    });
}

function displaySme(validValues) {
    var changeTypeEl = "#change-type";
    var smediv = "#sme-div";

    for (var i = 0; i < validValues.length; i++) {
        if ($(changeTypeEl).val() == validValues[i]) {
            $(smediv).show();
            break;
        }
        else {
            $(smediv).hide();
        }
    }

    $(changeTypeEl).change(function () {
        for (var i = 0; i < validValues.length; i++) {
            if ($(changeTypeEl).val() == validValues[i]) {
                $(smediv).show();
                break;
            }
            else {
                $(smediv).hide();
            }
        }
    });
}

function displayExpectedEffort(validValues) {
    var expEffortDiv = "#expected-effort-div";
    var changeTypeEl = "#change-type";

    for (var i = 0; i < validValues.length; i++) {
        if ($(changeTypeEl).val() == validValues[i]) {
            $(expEffortDiv).show();
            break;
        }
        else {
            $(expEffortDiv).hide();
        }
    }

    $(changeTypeEl).change(function () {
        for (var i = 0; i < validValues.length; i++) {
            if ($(changeTypeEl).val() == validValues[i]) {
                $(expEffortDiv).show();
                break;
            }
            else {
                $(expEffortDiv).hide();
            }
        }
    });
}

function enableExpectedEffort(expectedEffort, validValues) {

    var changeTypeEl = "#change-type";
    var expEffortEl = "#expected-effort";

    for (var i = 0; i < validValues.length; i++) {
        if ($(changeTypeEl).val() == validValues[i]) {
            $(expEffortEl).removeAttr('disabled');
            break;
        }
        else {
            $(expEffortEl).attr('disabled', true);
        }
    }

    $(changeTypeEl).change(function () {
        for (var i = 0; i < validValues.length; i++) {
            if ($(changeTypeEl).val() == validValues[i]) {
                $(expEffortEl).removeAttr('disabled');
                break;
            }
            else {
                $(expEffortEl).attr('disabled', true);
                $(expEffortEl).val(expectedEffort);
            }
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