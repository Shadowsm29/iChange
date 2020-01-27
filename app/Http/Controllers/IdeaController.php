<?php

namespace App\Http\Controllers;

use App\Attachment;
use App\ChangeType;
use App\Circle;
use App\Comment;
use App\Http\Requests\Ideas\ApproveIdeaRequest;
use App\Http\Requests\Ideas\AssignSmeIdeaRequest;
use App\Http\Requests\Ideas\CancelIdeaRequest;
use App\Justification;
use Illuminate\Http\Request;
use App\Http\Requests\Ideas\StoreIdeaRequest;
use App\Http\Requests\Ideas\DeclineIdeaRequest;
use App\Http\Requests\Ideas\UpdateResubmitIdeaRequest;
use App\Http\Requests\Ideas\BackToSubmitterIdeaRequest;
use App\Http\Requests\Ideas\FullEditIdeaRequest;
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
    public function listAllideas()
    {
        return view("ideas.all-ideas")
            ->with("ideas", Idea::orderBy("created_at", "desc")->paginate(10))
            ->with("title", "All ideas");
    }

    public function listAllActiveIdeas()
    {
        return view("ideas.all-ideas")
            ->with("title", "All active ideas")
            ->with("ideas", Idea::orderBy("created_at", "desc")->isOpen()->paginate(10));
    }

    public function listMyActiveIdeas()
    {
        return view("ideas.all-ideas")
            ->with("title", "My active ideas")
            ->with("ideas", auth()->user()->ideas()->isOpen()->paginate(10));
    }

    public function listMyAllIdeas()
    {
        return view("ideas.all-ideas")
            ->with("ideas", auth()->user()->ideas()->paginate(10))
            ->with("title", "My all ideas");
    }

    public function showPersonalQueActive()
    {
        $authorizedArr = auth()->user()->getAuthorizationIds();

        $ideas = Idea::orderBy("created_at", "desc")->assignedToOrIsSme($authorizedArr, auth()->user()->id)->isOpen()->paginate(10);

        return view("ideas.all-ideas")
            ->with("ideas", $ideas)
            ->with("title", "Ideas assigned to me or my group");
    }

    public function showPersonalQueAll()
    {
        $authorizedArr = auth()->user()->getAuthorizationIds();

        $ideas = Idea::orderBy("created_at", "desc")->assignedToOrIsSme($authorizedArr, auth()->user()->id)->paginate(10);

        return view("ideas.all-ideas")
            ->with("ideas", $ideas)
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
            ->with("showExpectedEffort", [
                ChangeType::$JUST_DO_IT, ChangeType::$BUSINESS, ChangeType::$ORGANIZATIONAL
            ])
            ->with("showSme", [
                ChangeType::$LSS, ChangeType::$RPA, ChangeType::$COSMOS, ChangeType::$IT
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreIdeaRequest $request)
    {

        //Deciding next step based on change type
        if ($request["change-type"] == ChangeType::$JUST_DO_IT) {
            $status = Status::$INIT_LINE_MAN_APPR;
            $pendingAt = auth()->user()->manager_id;
        } else {
            $status = Status::$INIT_MT_APPR;
            $pendingAt = $this->getRoleIdByName(Role::$MT);
        }

        $idea = Idea::create([
            "submitter_id" => auth()->user()->id,
            "title" => $request->title,
            "change_type_id" => $request["change-type"],
            "justification_id" => $request["justification"],
            "impacted_supercircle_id" => $request["impacted-supercircle"],
            "impacted_circle_id" => $request["impacted-circle"],
            "expected_benefit" => $request["expected-benefit"],
            "expected_benefit_type" => $request["expected-benefit-type"],
            "expected_effort" => $request["expected-effort"],
            "sme_id" => auth()->user()->id,
            "pending_at_id" => $pendingAt,
            "status_id" => $status,
            "description" => $request["description"],
        ]);

        $this->uploadAttachment($request, $idea);

        $idea->notifyNewIdea(auth()->user());

        if ($request["change-type"] == ChangeType::$JUST_DO_IT) {
            $idea->notifyActionNeeded(auth()->user()->manager);
        } else {
            $idea->notifyActionNeeded(User::mtUsers());
        }

        session()->flash("success", "Idea registered successfully.");

        return redirect(route("ideas.my-active-ideas"));
    }

    public function display(Idea $idea)
    {
        if ($idea->isAssignedToUser()) {
            if ($idea->isApprovalAction()) {
                if ($idea->status_id == Status::$INIT_CENT_RES_APPR) {
                    return view("ideas.display-view")
                        ->with("idea", $idea)
                        ->with("approve", true)
                        ->with("update", true)
                        ->with("centralResources", true);
                } elseif ($idea->status_id == Status::$FIN_MT_APPR) {
                    return view("ideas.display-view")
                        ->with("idea", $idea)
                        ->with("ragStatuses", RagStatus::all())
                        ->with("update", true)
                        ->with("mtApprove", true)
                        ->with("approve", true);
                } else {
                    return view("ideas.display-view")
                        ->with("idea", $idea)
                        ->with("approve", true)
                        ->with("update", true);
                }
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
                    ->with("enableExpectedEffort", [
                        ChangeType::$JUST_DO_IT, ChangeType::$BUSINESS, ChangeType::$ORGANIZATIONAL
                    ])
                    ->with("enableSme", [
                        ChangeType::$LSS, ChangeType::$RPA, ChangeType::$COSMOS, ChangeType::$IT
                    ]);
            }
            if ($idea->status_id == Status::$APPR_SME_ASSGN) {
                return view("ideas.display-view")
                    ->with("idea", $idea)
                    ->with("users", User::all("email"))
                    ->with("update", true)
                    ->with("assignSme", true);
            }
            if ($idea->isWip()) {
                return view("ideas.display-view")
                    ->with("idea", $idea)
                    ->with("ragStatuses", RagStatus::all())
                    ->with("update", true)
                    ->with("complete", true);
            }
            if (!$idea->isOpen()) {
                return view("ideas.display-view")
                    ->with("idea", $idea);
            }

            return view("unauthorized.index");
        } elseif (
            auth()->user()->canSeeAllIdeas() ||
            auth()->user()->id == $idea->submitter_id ||
            auth()->user()->id == $idea->sme_id ||
            auth()->user()->id == $idea->submitter->manager->id
        ) {
            return view("ideas.display-view")->with("idea", $idea);
        } else {
            abort(403, "Unauthorized access");
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
            $this->updateBasicInfo($request, $idea);
            $this->uploadAttachment($request, $idea);

            if ($request["change-type"] == ChangeType::$JUST_DO_IT) {
                $this->createCommentAndUpdateStatus($idea, $request, Status::$INIT_LINE_MAN_APPR);
                $this->setPendingAt($idea, $idea->submitter->manager_id);
                $idea->notifyActionNeeded(auth()->user()->manager);
            } else {
                $this->createCommentAndUpdateStatus($idea, $request, Status::$INIT_MT_APPR);
                $this->setPendingAt($idea, $this->getRoleIdByName(Role::$MT));
                $idea->notifyActionNeeded(User::mtUsers());
            }
        } else {
            return view("unauthorized.index");
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
            return view("unauthorized.index");
        }

        $idea->notifyActionNeeded(User::mtUsers());

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
            $this->updateBasicInfo($request, $idea);
            $this->uploadAttachment($request, $idea);
        } elseif ($idea->status_id == Status::$INIT_MT_APPR || $idea->status_id == Status::$INIT_CHG_BOARD_APPR || $idea->status_id == Status::$FIN_MT_APPR) {
            //update only comments - after if section
        } elseif ($idea->status_id == Status::$APPR_SME_ASSGN) {
            $idea->updateSme($request, $this->getUserIdByEmail($request["sme"]));
        } elseif ($idea->status_id == Status::$INIT_CENT_RES_APPR) {
            $idea->update([
                "expected_effort" => $request["expected-effort"]
            ]);
        } elseif ($idea->isWip()) {
            $idea->updateRagStatAndActualEffort($request);
        } else {
            return view("unauthorized.index");
        }

        $this->createCommentAndUpdateStatus($idea, $request, $idea->status_id);

        $idea->notifyRequestUpdated($idea->submitter);

        session()->flash("success", "Request successfully updated.");

        return redirect()->back();
    }

    public function decline(DeclineIdeaRequest $request, Idea $idea)
    {
        $this->createCommentAndUpdateStatus($idea, $request, Status::$DECLINED);

        $idea->notifyRequestDeclined($idea->submitter);

        session()->flash("success", "Request successfully declined.");

        return redirect(route("ideas.personal-que-active"));
    }

    public function backToSubmitter(BackToSubmitterIdeaRequest $request, Idea $idea)
    {
        if ($idea->status_id == Status::$FIN_MT_APPR) {
            if ($idea->change_type_id == ChangeType::$JUST_DO_IT) {
                $this->createCommentAndUpdateStatus($idea, $request, Status::$WIP_JUST_DO_IT);
                $this->setPendingAt($idea, $idea->submitter_id);
                $idea->notifyActionNeeded($idea->submitter);
            } elseif ($idea->change_type_id == ChangeType::$BUSINESS) {
                $this->createCommentAndUpdateStatus($idea, $request, Status::$WIP_BUSINESS);
                $this->setPendingAt($idea, $idea->submitter_id);
                $idea->notifyActionNeeded($idea->submitter);
            } elseif ($idea->change_type_id == ChangeType::$ORGANIZATIONAL) {
                $this->createCommentAndUpdateStatus($idea, $request, Status::$WIP_ORGANIZATIONAL);
                $this->setPendingAt($idea, $idea->submitter_id);
                $idea->notifyActionNeeded($idea->submitter);
            } elseif ($idea->change_type_id == ChangeType::$RPA) {
                $this->createCommentAndUpdateStatus($idea, $request, Status::$WIP_RPA);
                $this->setPendingAt($idea, $this->getRoleIdByName(Role::$RPA));
                $idea->notifyActionNeeded(User::rpaUsers());
            } elseif ($idea->change_type_id == ChangeType::$LSS) {
                $this->createCommentAndUpdateStatus($idea, $request, Status::$WIP_LSS);
                $this->setPendingAt($idea, $this->getRoleIdByName(Role::$LSS));
                $idea->notifyActionNeeded(User::lssUsers());
            } elseif ($idea->change_type_id == ChangeType::$COSMOS) {
                $this->createCommentAndUpdateStatus($idea, $request, Status::$WIP_COSMOS);
                $this->setPendingAt($idea, $this->getRoleIdByName(Role::$COSMOS));
                $idea->notifyActionNeeded(User::cosmosUsers());
            } elseif ($idea->change_type_id == ChangeType::$IT) {
                $this->createCommentAndUpdateStatus($idea, $request, Status::$WIP_IT);
                $this->setPendingAt($idea, $this->getRoleIdByName(Role::$IT));
                $idea->notifyActionNeeded(User::itUsers());
            } else {
                return view("unauthorized.index");
            }
        } else {
            $this->createCommentAndUpdateStatus($idea, $request, Status::$CORR_NEEDED);
            $this->setPendingAt($idea, $idea->submitter_id);
            $idea->notifyActionNeeded($idea->submitter);
        }

        session()->flash("success", "Request successfully routed back to submitter.");

        return redirect(route("ideas.personal-que-active"));
    }

    public function cancel(CancelIdeaRequest $request, Idea $idea)
    {
        $this->createCommentAndUpdateStatus($idea, $request, Status::$CANCELLED);

        $idea->notifyRequestCancelled($idea->submitter);

        session()->flash("success", "Request successfully cancelled.");

        return redirect()->back();
    }

    public function approve(ApproveIdeaRequest $request, Idea $idea)
    {
        if ($idea->status_id == Status::$INIT_LINE_MAN_APPR) {
            $this->createCommentAndUpdateStatus($idea, $request, Status::$WIP_JUST_DO_IT);
            $this->setPendingAt($idea, $idea->submitter_id);
            $idea->notifyActionNeeded($idea->submitter);
        } elseif ($idea->status_id == Status::$INIT_MT_APPR) {
            if ($idea->change_type_id == ChangeType::$IT) {
                $this->createCommentAndUpdateStatus($idea, $request, Status::$INIT_CHG_BOARD_APPR);
                $this->setPendingAt($idea, $this->getRoleIdByName(Role::$CHG_BOARD));
                $idea->notifyActionNeeded(User::changeBoardUsers());
            } else {
                if ($idea->change_type_id == ChangeType::$RPA) {
                    $this->setPendingAt($idea, $this->getRoleIdByName(Role::$RPA));
                    $this->createCommentAndUpdateStatus($idea, $request, Status::$INIT_CENT_RES_APPR);
                    $idea->notifyActionNeeded(User::rpaUsers());
                } elseif ($idea->change_type_id == ChangeType::$LSS) {
                    $this->setPendingAt($idea, $this->getRoleIdByName(Role::$LSS));
                    $this->createCommentAndUpdateStatus($idea, $request, Status::$INIT_CENT_RES_APPR);
                    $idea->notifyActionNeeded(User::lssUsers());
                } elseif ($idea->change_type_id == ChangeType::$COSMOS) {
                    $this->setPendingAt($idea, $this->getRoleIdByName(Role::$COSMOS));
                    $this->createCommentAndUpdateStatus($idea, $request, Status::$INIT_CENT_RES_APPR);
                    $idea->notifyActionNeeded(User::cosmosUsers());
                } elseif ($idea->change_type_id == ChangeType::$ORGANIZATIONAL || $idea->change_type_id == ChangeType::$BUSINESS) {
                    $this->setPendingAt($idea, $this->getRoleIdByName(Role::$CHG_BOARD));
                    $this->createCommentAndUpdateStatus($idea, $request, Status::$INIT_CHG_BOARD_APPR);
                    $idea->notifyActionNeeded(User::changeBoardUsers());
                } else {
                    return view("unauthorized.index");
                }
            }
        } elseif ($idea->status_id == Status::$INIT_CENT_RES_APPR) {
            $this->createCommentAndUpdateStatus($idea, $request, Status::$INIT_CHG_BOARD_APPR);
            $this->setPendingAt($idea, $this->getRoleIdByName(Role::$CHG_BOARD));
            $idea->update([
                "expected_effort" => $request["expected-effort"]
            ]);
            $idea->notifyActionNeeded(User::changeBoardUsers());
        } elseif ($idea->status_id == Status::$INIT_CHG_BOARD_APPR) {
            if ($idea->change_type_id == ChangeType::$IT) {
                $this->createCommentAndUpdateStatus($idea, $request, Status::$WIP_IT);
                $this->setPendingAt($idea, $this->getRoleIdByName(Role::$IT));
                $idea->notifyActionNeeded(User::itUsers());
            } elseif ($idea->change_type_id == ChangeType::$ORGANIZATIONAL) {
                $this->createCommentAndUpdateStatus($idea, $request, Status::$WIP_ORGANIZATIONAL);
                $this->setPendingAt($idea, $idea->submitter_id);
                $idea->notifyActionNeeded($idea->submitter);
            } elseif ($idea->change_type_id == ChangeType::$BUSINESS) {
                $this->createCommentAndUpdateStatus($idea, $request, Status::$WIP_BUSINESS);
                $this->setPendingAt($idea, $idea->submitter_id);
                $idea->notifyActionNeeded($idea->submitter);
            } else {
                $this->createCommentAndUpdateStatus($idea, $request, Status::$APPR_SME_ASSGN);
                $this->setPendingAt($idea, $idea->submitter_id);
                $idea->notifyActionNeeded($idea->submitter);
            }
        } elseif ($idea->status_id == Status::$FIN_MT_APPR) {
            $this->createCommentAndUpdateStatus($idea, $request, Status::$IMPLEMENTED);
            $this->setPendingAt($idea, $idea->pending_at_id);
            $idea->notifyRequestCompleted($idea->submitter);
        } else {
            return view("unauthorized.index");
        }

        session()->flash("success", "Request successfully approved.");

        return redirect(route("ideas.personal-que-active"));
    }

    public function assignSme(AssignSmeIdeaRequest $request, Idea $idea)
    {
        if ($idea->change_type_id == ChangeType::$LSS) {
            $this->createCommentAndUpdateStatus($idea, $request, Status::$WIP_LSS);
            $this->setPendingAt($idea, $this->getRoleIdByName(Role::$LSS));
            $idea->notifyActionNeeded(User::lssUsers());
        } elseif ($idea->change_type_id == ChangeType::$RPA) {
            $this->createCommentAndUpdateStatus($idea, $request, Status::$WIP_RPA);
            $this->setPendingAt($idea, $this->getRoleIdByName(Role::$RPA));
            $idea->notifyActionNeeded(User::rpaUsers());
        } elseif ($idea->change_type_id == ChangeType::$COSMOS) {
            $this->createCommentAndUpdateStatus($idea, $request, Status::$WIP_COSMOS);
            $this->setPendingAt($idea, $this->getRoleIdByName(Role::$COSMOS));
            $idea->notifyActionNeeded(User::cosmosUsers());
        } else {
            return view("unauthorized.index");
        }

        $idea->updateSme($request, $this->getUserIdByEmail($request["sme"]));

        $idea->notifyYouAreAssignedSme($idea->smeUser);

        session()->flash("success", "SME successfully assigned and idea forwarded to processor team.");

        return redirect()->back();
    }

    public function showFullEdit(Idea $idea)
    {
        return view("ideas.full-edit")
            ->with("idea", $idea)
            ->with("circles", Circle::orderBy('name', 'asc')->get())
            ->with("superCricles", Supercircle::orderBy('name', 'asc')->get())
            ->with("justifications", Justification::orderBy('name', 'asc')->get())
            ->with("users", User::all("id", "email"))
            ->with("ragStatuses", RagStatus::all());
    }

    public function fullEdit(FullEditIdeaRequest $request, Idea $idea)
    {
        if ($idea->expected_effort != null) {
            $expectedEffort = $request["expected-effort"];
        } else {
            $expectedEffort = null;
        }

        if ($idea->actual_effort != null) {
            $actualEffort = $request["actual-effort"];
        } else {
            $actualEffort = null;
        }

        $idea->update([
            "title" => $request->title,
            "justification_id" => $request["justification"],
            "impacted_supercircle_id" => $request["impacted-supercircle"],
            "impacted_circle_id" => $request["impacted-circle"],
            "expected_benefit" => $request["expected-benefit"],
            "expected_benefit_type" => $request["expected-benefit-type"],
            "expected_effort" => $expectedEffort,
            "actual_effort" => $actualEffort,
            "sme_id" => $request["sme"],
            "description" => $request["description"],
            "rag_status_id" => $request["rag-status"],
            "submitter_id" => $request["submitter"]
        ]);

        $this->createCommentAndUpdateStatus($idea, $request, $idea->status_id);

        $idea->notifyRequestUpdated($idea->submitter);

        session()->flash("success", "Idea successfully edited.");

        return redirect()->back();
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

    public function updateBasicInfo($request, $idea)
    {
        //Expected effort validation
        if ($request["change-type"] == ChangeType::$JUST_DO_IT) {
            $expectedEffort = $request["expected-effort"];
        } else {
            $expectedEffort = $idea->expected_effort;
        }

        //Handling attachment
        if ($request->hasFile("attachment")) {
            $attachment = $request->file("attachment")->storeAs("attachments", $request->file("attachment")->getClientOriginalName());
        } else {
            $attachment = "";
        }

        $idea->update([
            "change_type_id" => $request["change-type"],
            "justification_id" => $request["justification"],
            "impacted_supercircle_id" => $request["impacted-supercircle"],
            "impacted_circle_id" => $request["impacted-circle"],
            "expected_benefit" => $request["expected-benefit"],
            "expected_benefit_type" => $request["expected-benefit-type"],
            "expected_effort" => $expectedEffort,
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

    public function uploadAttachment($request, $idea)
    {
        if ($request->hasFile("attachments")) {
            $attachments = $request->file("attachments");
            foreach ($attachments as $attachment) {
                $fileName = $attachment->getClientOriginalName();
                $path = $attachment->storeAs("attachments", $fileName);
                Attachment::create([
                    "idea_id" => $idea->id,
                    "name" => $fileName,
                    "path" => $path
                ]);
            }
        }
    }
}
