<?php

namespace App\Http\Requests\Ideas;

use App\ChangeType;
use App\Status;
use Illuminate\Foundation\Http\FormRequest;

class UpdateResubmitIdeaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->idea->isAssignedToUser() && $this->idea->status_id == Status::$CORR_NEEDED) {
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
            "change-type" => "required|exists:change_types,id",
            "justification" => "required|exists:justifications,id",
            "impacted-supercircle" => "required|exists:supercircles,id",
            "impacted-circle" => "required|exists:circles,id",
            "expected-benefit" => "required|numeric",
            "expected-benefit-type" => "required|in:hours/week,euros",
            "attachments.*" => "nullable|file|max:10000",
            "description" => "required|string"
        ];

        if ($this->request->get("change-type") == ChangeType::$JUST_DO_IT) {
            $specificValidationRules = [
                "expected-effort" => "required|numeric"
            ];

            return array_merge($basicValidationRules, $specificValidationRules);
        } elseif (
            $this->request->get("change-type") == ChangeType::$LSS ||
            $this->request->get("change-type") == ChangeType::$RPA ||
            $this->request->get("change-type") == ChangeType::$COSMOS ||
            $this->request->get("change-type") == ChangeType::$IT
        ) {
            return $basicValidationRules;
        } elseif (
            $this->request->get("change-type") == ChangeType::$BUSINESS ||
            $this->request->get("change-type") == ChangeType::$ORGANIZATIONAL
        ) {
            return $basicValidationRules;
        }
    }
}
