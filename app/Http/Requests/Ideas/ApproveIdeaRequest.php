<?php

namespace App\Http\Requests\Ideas;

use App\Status;
use Illuminate\Foundation\Http\FormRequest;

class ApproveIdeaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->idea->isAssignedToUser() && $this->idea->isApprovalAction()) {
            return true;
         } 
         else {
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

        $expectedEffortValidationRule = [
            "expected-effort" => "required|numeric"
        ];

        if($this->idea->status_id == Status::$INIT_CENT_RES_APPR) {
            return array_merge($expectedEffortValidationRule, $commentValidationRule);
        } else {
            return $commentValidationRule;
        }
    }
}
