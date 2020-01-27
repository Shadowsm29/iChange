<?php

namespace App\Http\Requests\Ideas;

use App\ChangeType;
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
        $basicValidationRule = [
            "change-type" => "required|exists:change_types,id",
            "justification" => "required|exists:justifications,id",
            "impacted-supercircle" => "required|exists:supercircles,id",
            "impacted-circle" => "required|exists:circles,id",
            "expected-benefit" => "required|numeric",
            "expected-benefit-type" => "required|in:hours/week,euros",
            "attachments.*" => "nullable|file|max:10000",
            "description" => "required|string",
        ];

        $commentValidationRule = [
            "comment" => "required|string"
        ];

        $smeValidationRule = [
            "sme" => "required|email|exists:users,email"
        ];

        $expectedEffortValidationRule = [
            "expected-effort" => "required|numeric"
        ];

        if ($this->idea->status_id == Status::$CORR_NEEDED) {

            if (
                $this->request->get("change-type") == ChangeType::$LSS ||
                $this->request->get("change-type") == ChangeType::$COSMOS ||
                $this->request->get("change-type") == ChangeType::$RPA ||
                $this->request->get("change-type") == ChangeType::$IT
            ) {
                return array_merge($basicValidationRule, $commentValidationRule);
            } elseif ($this->request->get("change-type") == ChangeType::$JUST_DO_IT) {
                return array_merge($basicValidationRule, $commentValidationRule, $expectedEffortValidationRule);
            } elseif (
                $this->request->get("change-type") == ChangeType::$BUSINESS ||
                $this->request->get("change-type") == ChangeType::$ORGANIZATIONAL
            ) {
                return array_merge($basicValidationRule, $commentValidationRule, $expectedEffortValidationRule);
            } else {
                abort(403, 'Unauthorized action.');
            }
        } elseif ($this->idea->status_id == Status::$INIT_CENT_RES_APPR) {
            return $commentValidationRule;
        } elseif ($this->idea->status_id == Status::$APPR_SME_ASSGN) {
            return array_merge($smeValidationRule, $commentValidationRule);
        } elseif ($this->idea->isWip()) {
            $specificValidationRule = [
                "rag-status" => "required|exists:rag_statuses,id",
                "actual-effort" => "required|numeric"
            ];

            return array_merge($commentValidationRule, $specificValidationRule);
        } else {
            return $commentValidationRule;
        }
    }
}
