<?php

namespace App\Http\Requests\Ideas;

use App\Status;
use Illuminate\Foundation\Http\FormRequest;

class UpdateIdeaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->idea->isAssignedToUser() && $this->idea->isOpen()) {
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
        if ($this->idea->status_id == Status::$CORR_NEEDED) {
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
        } elseif ($this->idea->isWip()) {
            return [
                "comment" => "required|string",
                "rag-status" => "required|exists:rag_statuses,id",
                "actual-effort" => "required|numeric"
            ];
        } else {
            return [
                "comment" => "required|string"
            ];
        }
    }
}
