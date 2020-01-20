<?php

namespace App\Http\Requests\Ideas;

use App\Status;
use Illuminate\Foundation\Http\FormRequest;

class CancelIdeaRequest extends FormRequest
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
            "comment" => "required|string"
        ];
    }
}
