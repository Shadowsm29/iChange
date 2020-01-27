<?php

namespace App\Http\Controllers;

use App\Attachment;
use App\Comment;
use App\Http\Requests\DeleteAttachmentsRequest;
use App\Idea;
use Illuminate\Http\Request;

class AttachmentsController extends Controller
{
    public function download(Idea $idea, Attachment $attachment)
    {
        $pathToFile = storage_path('app/attachments/' . $attachment->name);
        return response()->download($pathToFile);
    }

    public function delete(DeleteAttachmentsRequest $request, Idea $idea, Attachment $attachment)
    {
        Comment::create([
            "comment" => $request["comment"],
            "idea_id" => $idea["id"],
            "owner_id" => auth()->user()->id,
            "old_status_id" => $idea["status_id"],
            "new_status_id" => $idea["status_id"]
        ]);
        
        $attachment->delete();

        $idea->notifyRequestUpdated($idea->submitter);

        session()->flash("success", "Attachment successfully deleted.");

        return redirect()->back();
    }
}
