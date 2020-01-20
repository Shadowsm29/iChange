<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        "comment", "idea_id", "owner_id", "old_status_id", "new_status_id"
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, "owner_id");
    }

    public function oldStatus()
    {
        return $this->belongsTo(Status::class, "old_status_id");
    }

    public function newStatus()
    {
        return $this->belongsTo(Status::class, "new_status_id");
    }
}
