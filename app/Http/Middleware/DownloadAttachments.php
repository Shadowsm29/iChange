<?php

namespace App\Http\Middleware;

use Closure;

class DownloadAttachments
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $attachment = $request->route()->parameter("attachment");
        $idea = $request->route()->parameter("idea");

        if (
            $idea->isAssignedToUser() ||
            auth()->user()->canSeeAllIdeas() ||
            auth()->user()->id == $idea->sme_id ||
            auth()->user()->id == $idea->submitter_id
        ) {
            return $next($request);
        }

        abort(403, 'Unauthorized action.');
    }
}
