<?php

namespace App\Http\Requests\Ideas;

use Illuminate\Foundation\Http\FormRequest;

class FullEditIdeaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(auth()->user()->canFullyEditIdea()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $basicValidationRules = [
            "title" => "required|string",
            "justification" => "required|exists:justifications,id",
            "impacted-supercircle" => "required|exists:supercircles,id",
            "impacted-circle" => "required|exists:circles,id",
            "expected-benefit" => "required|numeric",
            "expected-benefit-type" => "required|in:hours/week,euros",
            "attachment" => "nullable|file",
            "description" => "required|string",
            "rag-status" => "required|exists:rag_statuses,id",
            "comment" => "required|string",
            "sme" => "required|exists:users,id",
            "submitter" => "required|exists:users,id"
        ];

        $expectedEffortValidationRule = [
            "expected-effort" => "required|numeric"
        ];

        $actualEffortValidationRule = [
            "actual-effort" => "required|numeric"
        ];

        if($this->idea->expected_effort != null) {
            $basicValidationRules = array_merge($basicValidationRules, $expectedEffortValidationRule);
        }

        if($this->idea->actual_effort != null) {
            $basicValidationRules = array_merge($basicValidationRules, $actualEffortValidationRule);
        }

        return $basicValidationRules;
    }
}
