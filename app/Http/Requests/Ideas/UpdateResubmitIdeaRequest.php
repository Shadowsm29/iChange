<?php

namespace App\Http\Requests\Ideas;

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
        return [
            "change-type" => "required|exists:change_types,id",
            "justification" => "required|exists:justifications,id",
            "impacted-supercircle" => "required|exists:supercircles,id",
            "impacted-circle" => "required|exists:circles,id",
            "expected-benefit" => "required|numeric",
            "expected-benefit-type" => "required|in:hours/week,euros",
            "expected-effort" => "nullable|numeric",
            "sme" => "required|email|exists:users,email",
            "attachment" => "nullable|file",
            "description" => "required|string",
            "comment" => "required|string"
        ];
    }
}
