<?php

namespace App\Http\Requests\Ideas;

use App\Status;
use Illuminate\Foundation\Http\FormRequest;

class AssignSmeIdeaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->idea->isAssignedToUser() && $this->idea->isOpen() && $this->idea->status_id == Status::$APPR_SME_ASSGN) {
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
        $commentValidationRule = [
            "comment" => "required|string"
        ];

        $smeValidationRule = [
            "sme" => "required|email|exists:users,email"
        ];

        return array_merge($smeValidationRule, $commentValidationRule);
    }
}
