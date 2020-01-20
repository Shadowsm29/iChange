<?php

namespace App\Http\Requests\Ideas;

use Illuminate\Foundation\Http\FormRequest;

class BackToSubmitterIdeaRequest extends FormRequest
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
        return [
            "comment" => "required|string"
        ];
    }
}
