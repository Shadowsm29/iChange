<?php

namespace App\Http\Requests;

use App\Status;
use Illuminate\Foundation\Http\FormRequest;

class DeleteAttachmentsRequest extends FormRequest
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
        }

        if($this->idea->status_id == Status::$CORR_NEEDED) {
            if(
                auth()->user()->id == $this->idea->submitter_id ||
                auth()->user()->id == $this->idea->submitter->manager_id
            ) {
                return true;
            }
        }

        abort(403, 'Unauthorized action.');
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
