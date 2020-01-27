<?php

namespace App;

use App\Notifications\AssignedAsSme;
use App\Notifications\NewIdeaAdded;
use App\Notifications\RequestCancelled;
use App\Notifications\RequestCompleted;
use App\Notifications\RequestDeclined;
use App\Notifications\RequestIsWaitingForYourAction;
use App\Notifications\RequestUpdated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification;

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

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    public function scopeAssignedTo($query, $authorizedArr)
    {
        return $query->whereIn("pending_at_id", $authorizedArr);
    }

    public function scopeOrIsSme($query, $sme)
    {
        return $query->orWhere("sme_id", $sme);
    }

    public function scopeAssignedToOrIsSme($query, $authorizedArr, $sme_id)
    {
        return $query
            ->whereIn("pending_at_id", $authorizedArr)
            ->orWhere(function ($query) use ($sme_id) {
                $query->where("sme_id", $sme_id)->where("submitter_id", "<>", $sme_id);
            });
    }

    public function scopeIsOpen($query)
    {
        return $query
            ->where("status_id", "<>", Status::$CANCELLED)
            ->where("status_id", "<>", Status::$IMPLEMENTED)
            ->where("status_id", "<>", Status::$DECLINED);
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
        $pendingAt = User::where("id", $this->pending_at_id)->first();

        if ($this->pending_at_id == auth()->user()->id || auth()->user()->name == "superadmin") {
            return true;
        } elseif ($pendingAt != null) {
            if ($pendingAt->manager_id == auth()->user()->id) {
                return true;
            }
        } else {
            foreach (auth()->user()->roles as $role) {
                if ($role->id == $this->pending_at_id) {
                    return true;
                }
            }
        }
        return false;
    }

    public function isAssignedToUserSme()
    {
        if ($this->sme_id == auth()->user()->id) {
            return true;
        } else {
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
            $this->status_id == Status::$WIP_LSS ||
            $this->status_id == Status::$WIP_ORGANIZATIONAL ||
            $this->status_id == Status::$WIP_BUSINESS;
    }

    public function isForCentralResources()
    {
        return  $this->change_type_id == ChangeType::$LSS ||
            $this->change_type_id == ChangeType::$RPA ||
            $this->change_type_id == ChangeType::$COSMOS ||
            $this->change_type_id == ChangeType::$IT;
    }

    public function updateRagStatAndActualEffort($request)
    {
        // dd($request);
        $this->update([
            "rag_status_id" => $request["rag-status"],
            "actual_effort" => $request["actual-effort"]
        ]);
    }

    public function updateSme($request, $smeId)
    {
        $this->update([
            "sme_id" => $smeId
        ]);
    }

    public function getReqIdAsString()
    {
        return "REQ" . sprintf('%05d', $this->id);
    }

    public function notifyNewIdea($users)
    {
        Notification::send($users, new NewIdeaAdded($this));
    }

    public function notifyActionNeeded($users)
    {
        Notification::send($users, new RequestIsWaitingForYourAction($this));
    }

    public function notifyRequestCompleted($users)
    {
        Notification::send($users, new RequestCompleted($this));
    }

    public function notifyRequestCancelled($users)
    {
        Notification::send($users, new RequestCancelled($this));
    }

    public function notifyRequestDeclined($users)
    {
        Notification::send($users, new RequestDeclined($this));
    }

    public function notifyRequestUpdated($users)
    {
        Notification::send($users, new RequestUpdated($this));
    }

    public function notifyYouAreAssignedSme($users)
    {
        Notification::send($users, new AssignedAsSme($this));
    }
}
