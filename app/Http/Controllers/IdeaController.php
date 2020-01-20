<?php

namespace App\Http\Controllers;

use App\ChangeType;
use App\Circle;
use App\Comment;
use App\Http\Requests\Ideas\ApproveIdeaRequest;
use App\Http\Requests\Ideas\CancelIdeaRequest;
use App\Justification;
use Illuminate\Http\Request;
use App\Http\Requests\Ideas\StoreIdeaRequest;
use App\Http\Requests\Ideas\DeclineIdeaRequest;
use App\Http\Requests\Ideas\UpdateResubmitIdeaRequest;
use App\Http\Requests\Ideas\BackToSubmitterIdeaRequest;
use App\Http\Requests\Ideas\UpdateCompleteIdeaRequest;
use App\Http\Requests\Ideas\UpdateIdeaRequest;
use App\Idea;
use App\RagStatus;
use App\Role;
use App\Status;
use App\Supercircle;
use App\User;

class IdeaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    public function listAllideas()
    {
        return view("ideas.all-ideas")
            ->with("ideas", Idea::orderBy("created_at", "desc")->get())
            ->with("title", "All ideas");
    }

    public function listAllActiveIdeas()
    {
        return view("ideas.all-ideas")
            ->with("title", "All active ideas")
            ->with("ideas", Idea::orderBy("created_at", "desc")->get()
                ->where("status_id", "<>", Status::$CANCELLED)
                ->where("status_id", "<>", Status::$IMPLEMENTED)
                ->where("status_id", "<>", Status::$DECLINED));
    }

    public function listMyActiveIdeas()
    {
        return view("ideas.all-ideas")
            ->with("title", "My active ideas")
            ->with("ideas", auth()->user()->ideas()
                ->where("status_id", "<>", Status::$CANCELLED)
                ->where("status_id", "<>", Status::$IMPLEMENTED)
                ->where("status_id", "<>", Status::$DECLINED)->get());
    }

    public function listMyAllIdeas()
    {
        return view("ideas.all-ideas")
            ->with("ideas", auth()->user()->ideas)
            ->with("title", "My all ideas");
    }

    public function showPersonalQue()
    {
        $ideas = Idea::orderBy("created_at", "desc")->get();
        $ideasAssignedToUser = [];
        foreach ($ideas as $idea) {
            if ($idea->isAssignedToUser() && $idea->isOpen()) {
                array_push($ideasAssignedToUser, $idea);
            }
        }

        return view("ideas.all-ideas")
            ->with("ideas", $ideasAssignedToUser)
            ->with("title", "Ideas assigned to me or my group");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("ideas.create")
            ->with("changeTypes", ChangeType::orderBy('name', 'asc')->get())
            ->with("circles", Circle::orderBy('name', 'asc')->get())
            ->with("superCricles", Supercircle::orderBy('name', 'asc')->get())
            ->with("justifications", Justification::orderBy('name', 'asc')->get())
            ->with("users", User::all("email"))
            ->with("justDoItId", ChangeType::$JUST_DO_IT)
            ->with("lss", ChangeType::$LSS)
            ->with("rpa", ChangeType::$RPA)
            ->with("cosmos", ChangeType::$COSMOS)
            ->with("it", ChangeType::$IT);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreIdeaRequest $request)
    {
        //Handling attachment
        if ($request->hasFile("attachment")) {
            $attachment = $request->file("attachment")->storeAs("attachments", $request->file("attachment")->getClientOriginalName());
        } else {
            $attachment = "";
        }

        //Deciding next step based on change type
        if ($request["change-type"] == ChangeType::$JUST_DO_IT) {
            $status = Status::$INIT_LINE_MAN_APPR;
            $pendingAt = auth()->user()->manager_id;
        } else {
            $status = Status::$INIT_MT_APPR;
            $pendingAt = $this->getRoleIdByName(Role::$MT);
        }

        if (
            $request["change-type"] == ChangeType::$JUST_DO_IT ||
            $request["change-type"] == ChangeType::$BUSINESS ||
            $request["change-type"] == ChangeType::$ORGANIZATIONAL
        ) {
            $sme = auth()->user()->id;
        } else {
            $sme = $this->getUserIdByEmail($request["sme"]);
        }


        Idea::create([
            "submitter_id" => auth()->user()->id,
            "title" => $request->title,
            "change_type_id" => $request["change-type"],
            "justification_id" => $request["justification"],
            "impacted_supercircle_id" => $request["impacted-supercircle"],
            "impacted_circle_id" => $request["impacted-circle"],
            "expected_benefit" => $request["expected-benefit"],
            "expected_benefit_type" => $request["expected-benefit-type"],
            "expected_effort" => $request["expected-effort"],
            "sme_id" => $sme,
            "pending_at_id" => $pendingAt,
            "status_id" => $status,
            "attachment" => $attachment,
            "description" => $request["description"],
        ]);

        session()->flash("success", "Idea registered successfully.");

        return redirect(route("ideas.my-active-ideas"));
    }

    public function display(Idea $idea)
    {
        if ($idea->isAssignedToUser()) {
            if ($idea->isApprovalAction()) {
                return view("ideas.display-view")
                    ->with("idea", $idea)
                    ->with("approve", true)
                    ->with("update", true);
            }
            if ($idea->status_id == Status::$CORR_NEEDED) {
                return view("ideas.display-view")
                    ->with("idea", $idea)
                    ->with("changeTypes", ChangeType::orderBy('name', 'asc')->get())
                    ->with("circles", Circle::orderBy('name', 'asc')->get())
                    ->with("superCircles", Supercircle::orderBy('name', 'asc')->get())
                    ->with("justifications", Justification::orderBy('name', 'asc')->get())
                    ->with("users", User::all("email"))
                    ->with("edit", true)
                    ->with("update", true)
                    ->with("cancel", true)
                    ->with("forward", true)
                    ->with("justDoItId", ChangeType::$JUST_DO_IT);;
            }
            if ($idea->isWip()) {
                // dd(RagStatus::all()->first()->id);
                return view("ideas.display-view")
                    ->with("idea", $idea)
                    ->with("ragStatuses", RagStatus::all())
                    ->with("update", true)
                    ->with("complete", true);
            }
            if ($idea->status_id == Status::$FIN_MT_APPR) {
                return view("ideas.display-view")
                    ->with("idea", $idea)
                    ->with("ragStatuses", RagStatus::all())
                    ->with("update", true)
                    ->with("approve", true);
            }
        } elseif (
            auth()->user()->isMt() ||
            auth()->user()->isCentralResources() ||
            auth()->user()->id == $idea->submitter_id
        ) {
            return view("ideas.display-view")->with("idea", $idea);
        } else {
            return redirect(route("home"));
        }
    }

    /**
     * Edit and forward the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateResubmit(UpdateResubmitIdeaRequest $request, Idea $idea)
    {
        if ($idea->status_id == Status::$CORR_NEEDED) {

            $this->updateIdea($request, $idea);

            if ($request["change-type"] == ChangeType::$JUST_DO_IT) {
                $this->createCommentAndUpdateStatus($idea, $request, Status::$INIT_LINE_MAN_APPR);
                $this->setPendingAt($idea, $idea->submitter->manager_id);
            } else {
                $this->createCommentAndUpdateStatus($idea, $request, Status::$INIT_MT_APPR);
                $this->setPendingAt($idea, $this->getRoleIdByName(Role::$MT));
            }
        } else {
            return redirect(route("home"));
        }

        session()->flash("success", "Request successfully updated and forwarded.");

        return redirect()->back();
    }

    public function updateComplete(UpdateCompleteIdeaRequest $request, Idea $idea)
    {
        if ($idea->isWip()) {
            $idea->updateRagStatAndActualEffort($request);
            $this->createCommentAndUpdateStatus($idea, $request, Status::$FIN_MT_APPR);
            $this->setPendingAt($idea, $this->getRoleIdByName(Role::$MT));
        } else {
            return redirect(route("home"));
        }

        session()->flash("success", "Request successfully updated and forwarded for final approval.");

        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateIdeaRequest $request, Idea $idea)
    {
        if ($idea->status_id == Status::$CORR_NEEDED) {
            $this->updateIdea($request, $idea);
        } elseif ($idea->isWip()) {
            $idea->updateRagStatAndActualEffort($request);
            $this->createCommentAndUpdateStatus($idea, $request, $idea->status_id);
        }

        $this->createCommentAndUpdateStatus($idea, $request, $idea->status_id);

        session()->flash("success", "Request successfully updated.");

        return redirect()->back();
    }

    public function decline(DeclineIdeaRequest $request, Idea $idea)
    {
        $this->createCommentAndUpdateStatus($idea, $request, Status::$DECLINED);

        session()->flash("success", "Request successfully declined.");

        return redirect(route("ideas.all-ideas"));
    }

    public function backToSubmitter(BackToSubmitterIdeaRequest $request, Idea $idea)
    {
        if ($idea->status_id == Status::$FIN_MT_APPR) {
            if ($idea->change_type_id == ChangeType::$JUST_DO_IT) {
                $this->createCommentAndUpdateStatus($idea, $request, Status::$WIP_JUST_DO_IT);
            } else {
                dd("backToSubmitter");
            }
        } else {
            $this->createCommentAndUpdateStatus($idea, $request, Status::$CORR_NEEDED);
        }
        $this->setPendingAt($idea, $idea->submitter_id);

        session()->flash("success", "Request successfully routed back to submitter.");

        return redirect(route("ideas.all-active"));
    }

    public function cancel(CancelIdeaRequest $request, Idea $idea)
    {
        $this->createCommentAndUpdateStatus($idea, $request, Status::$CANCELLED);

        session()->flash("success", "Request successfully cancelled.");

        return redirect(route("ideas.all-ideas"));
    }

    public function approve(ApproveIdeaRequest $request, Idea $idea)
    {
        if ($idea->status_id == Status::$INIT_LINE_MAN_APPR) {
            $this->createCommentAndUpdateStatus($idea, $request, Status::$WIP_JUST_DO_IT);
            $this->setPendingAt($idea, $idea->submitter_id);
        } elseif ($idea->status_id == Status::$INIT_MT_APPR) {
            if ($idea->change_type_id == ChangeType::$IT) {
                $this->createCommentAndUpdateStatus($idea, $request, Status::$INIT_CHG_BOARD_APPR);
                $this->setPendingAt($idea, $this->getRoleIdByName(Role::$CHG_BOARD));
            } else {
                $this->createCommentAndUpdateStatus($idea, $request, Status::$INIT_CENT_RES_APPR);
                if ($idea->change_type_id == ChangeType::$RPA) {
                    $this->setPendingAt($idea, $this->getRoleIdByName(Role::$RPA));
                } elseif ($idea->change_type_id == ChangeType::$LSS) {
                    $this->setPendingAt($idea, $this->getRoleIdByName(Role::$LSS));
                } elseif ($idea->change_type_id == ChangeType::$COSMOS) {
                    $this->setPendingAt($idea, $this->getRoleIdByName(Role::$COSMOS));
                } else {
                    return view("unauthorized.index");
                }
            }
        } elseif ($idea->status_id == Status::$INIT_CENT_RES_APPR) {
            $this->createCommentAndUpdateStatus($idea, $request, Status::$INIT_CHG_BOARD_APPR);
            $this->setPendingAt($idea, $this->getRoleIdByName(Role::$CHG_BOARD));
        } elseif ($idea->status_id == Status::$FIN_MT_APPR) {
            $this->createCommentAndUpdateStatus($idea, $request, Status::$IMPLEMENTED);
            $this->setPendingAt($idea, $idea->pending_at_id);
        } else {
            return view("unauthorized.index");
        }

        session()->flash("success", "Request successfully approved.");

        return redirect(route("ideas.all-active"));
    }

    public function createCommentAndUpdateStatus($idea, $request, $newStatusId)
    {
        Comment::create([
            "comment" => $request["comment"],
            "idea_id" => $idea["id"],
            "owner_id" => auth()->user()->id,
            "old_status_id" => $idea["status_id"],
            "new_status_id" => $newStatusId
        ]);

        $idea->update([
            "status_id" => $newStatusId
        ]);
    }

    public function getUserIdByEmail($email)
    {
        return User::all()->where("email", $email)->first()->id;
    }

    public function isChangeTypeInvalid($request)
    {
        if ($request["change-type"] == ChangeType::$JUST_DO_IT) {
            if (!$request["expected-effort"]) {
                session()->flash("error", "Please fill in expected effort.");
                return true;
            } else {
                return false;
            }
        }
    }

    public function updateIdea($request, $idea)
    {
        //Expected effort validation
        if ($request["change-type"] == ChangeType::$JUST_DO_IT) {
            if ($request["expected-effort"]) {
                $expectedEffort = $request["expected-effort"];
            } else {
                session()->flash("error", "Please fill in expected effort.");
                return redirect()->back();
            }
        } else {
            $expectedEffort = $idea->expected_effort;
        }

        //Handling attachment
        if ($request->hasFile("attachment")) {
            $attachment = $request->file("attachment")->storeAs("attachments", $request->file("attachment")->getClientOriginalName());
        } else {
            $attachment = "";
        }

        $smeId = $this->getUserIdByEmail($request["sme"]);

        $idea->update([
            "change_type_id" => $request["change-type"],
            "justification_id" => $request["justification"],
            "impacted_supercircle_id" => $request["impacted-supercircle"],
            "impacted_circle_id" => $request["impacted-circle"],
            "expected_benefit" => $request["expected-benefit"],
            "expected_benefit_type" => $request["expected-benefit-type"],
            "expected_effort" => $expectedEffort,
            "sme_id" => $smeId,
            "attachment" => $attachment,
            "description" => $request["description"]
        ]);
    }

    public function setPendingAt($idea, $nextPendingAtId)
    {
        $idea->update([
            "pending_at_id" => $nextPendingAtId
        ]);
    }

    public function getRoleIdByName($roleName)
    {
        return Role::all()->where("name", $roleName)->first()->id;
    }
}
