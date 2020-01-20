<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Idea extends Model
{
    protected $fillable = [
        "title", "change_type_id", "justification_id", "impacted_supercircle_id",
        "impacted_circle_id", "expected_benefit", "expected_benefit_type", "expected_effort", "sme_id", "attachment", "pending_at_id", "status_id", "description", "submitter_id", "actual_effort", "rag_status_id"
    ];

    public function changeType()
    {
        return $this->belongsTo(ChangeType::class);
    }

    public function justification()
    {
        return $this->belongsTo(Justification::class);
    }

    public function supercircle()
    {
        return $this->belongsTo(Supercircle::class, "impacted_supercircle_id");
    }

    public function circle()
    {
        return $this->belongsTo(Circle::class, "impacted_circle_id");
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->orderBy("id", "desc");
    }

    public function submitter()
    {
        return $this->belongsTo(User::class, "submitter_id");
    }

    public function smeUser()
    {
        return $this->belongsTo(User::class, "sme_id");
    }

    public function ragStatus()
    {
        return $this->belongsTo(RagStatus::class, "rag_status_id");
    }

    public function pendingAt()
    {
        $pendingAt = Role::all()->where("id", $this->pending_at_id);
        if ($pendingAt->count() == 1) {
            return $pendingAt->first()["short_description"];
        } else {
            return User::all()->where("id", $this->pending_at_id)->first()["name"];
        }

    }

    public function isAssignedToUser()
    {
        if($this->pending_at_id == auth()->user()->id || auth()->user()->name == "superadmin") {
            return true;
        }
        else {
            foreach (auth()->user()->roles as $role) {
                if($role->id == $this->pending_at_id) {
                    return true;
                }
            }
            return false;
        }
    }

    public function isApprovalAction()
    {
        return  $this->status_id == Status::$INIT_LINE_MAN_APPR ||
                $this->status_id == Status::$INIT_MT_APPR ||
                $this->status_id == Status::$INIT_CENT_RES_APPR ||
                $this->status_id == Status::$INIT_CHG_BOARD_APPR ||
                $this->status_id == Status::$FIN_MT_APPR;
    }

    public function isOpen()
    {
        return  $this->status_id != Status::$CANCELLED &&
                $this->status_id != Status::$DECLINED &&
                $this->status_id != Status::$IMPLEMENTED;
    }

    public function isWip()
    {
        return  $this->status_id == Status::$WIP_COSMOS ||
                $this->status_id == Status::$WIP_JUST_DO_IT ||
                $this->status_id == Status::$WIP_RPA ||
                $this->status_id == Status::$WIP_LSS;
    }



    public function updateRagStatAndActualEffort($request)
    {
        // dd($request);
        $this->update([
            "rag_status_id" => $request["rag-status"],
            "actual_effort" => $request["actual-effort"]
        ]);
    }
}
