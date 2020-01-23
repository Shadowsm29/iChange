<?php

namespace App\Http\Requests\Ideas;

use App\ChangeType;
use Illuminate\Foundation\Http\FormRequest;

class StoreIdeaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (auth()->user()->isUser() && $this->request->get("change-type") == ChangeType::$JUST_DO_IT) {
            return true;
        } elseif (auth()->user()->isAdvancedUser()) {
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
            "change-type" => "required|exists:change_types,id",
            "justification" => "required|exists:justifications,id",
            "impacted-supercircle" => "required|exists:supercircles,id",
            "impacted-circle" => "required|exists:circles,id",
            "expected-benefit" => "required|numeric",
            "expected-benefit-type" => "required|in:hours/week,euros",
            "attachment" => "nullable|file",
            "description" => "required|string"
        ];

        if (
            $this->request->get("change-type") == ChangeType::$JUST_DO_IT ||
            $this->request->get("change-type") == ChangeType::$BUSINESS ||
            $this->request->get("change-type") == ChangeType::$ORGANIZATIONAL
        ) {
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
        }
    }
}
