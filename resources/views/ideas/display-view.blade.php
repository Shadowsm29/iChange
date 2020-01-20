@extends('layouts.app')

@section('content')
<div class="card" style="border: none">
    <div class="card-header bg-success">Idea details</div>
    <div class="card-body" style="border: 1px solid rgb(255, 98, 0)">
        <form action="" method="POST" enctype="multipart/form-data">
            @method("PUT")
            @csrf

            @if (isset($edit))
            @include('ideas.partials.edit-info')
            @else
            @include('ideas.partials.basic-info')
            @endif

            @if (isset($update))
            @include('ideas.partials.comment')
            @endif

            <div class="d-flex" style="justify-content: space-between">
                <div>
                    @if (isset($approve))
                    <button formaction="{{ route("ideas.approve", $idea->id) }}"
                        class="btn btn-success">Approve</button>
                    <button formaction="{{ route("ideas.decline", $idea->id) }}" class="btn btn-danger">Decline</button>
                    <button formaction="{{ route("ideas.back-to-submitter", $idea->id) }}" class="btn btn-danger">Back to
                        Submitter</button>
                    @endif
                    @if (isset($forward))
                    <button formaction="{{ route("ideas.update-resubmit", $idea->id) }}" class="btn btn-success">Re-submit</button>
                    @endif
                    @if (isset($complete))
                    <button formaction="{{ route("ideas.update-complete", $idea->id) }}" class="btn btn-success">Complete</button>
                    @endif
                    @if (isset($update))
                    <button formaction="{{ route("ideas.update", $idea->id) }}" class="btn btn-primary">Update</button>
                    @endif
                    @if (isset($cancel))
                    <button formaction="{{ route("ideas.cancel", $idea->id) }}" class="btn btn-danger">Cancel
                        idea</button>
                    @endif
                </div>
                <a href="{{ route("ideas.all-ideas") }}" class="btn btn-primary ml-2">Back to list</a>
            </div>

            <div class="alert alert-danger mt-2">
                <button formaction="{{ route("ideas.approve", $idea->id) }}" class="btn btn-success">Approve</button>
                <button formaction="{{ route("ideas.decline", $idea->id) }}" class="btn btn-danger">Decline</button>
                <button formaction="{{ route("ideas.back-to-submitter", $idea->id) }}" class="btn btn-danger">Back to
                    Submitter</button>
                <button formaction="{{ route("ideas.update-resubmit", $idea->id) }}" class="btn btn-success">Re-submit</button>
                <button formaction="{{ route("ideas.update-complete", $idea->id) }}" class="btn btn-success">Complete</button>
                <button formaction="{{ route("ideas.update", $idea->id) }}" class="btn btn-primary">Update</button>
                <button formaction="{{ route("ideas.cancel", $idea->id) }}" class="btn btn-danger">Cancel idea</button>
            </div>
        </form>
    </div>
</div>

@if ($idea->comments()->count() > 0)
<div class="card mt-3">
    <div class="card-header">Comments</div>
    <div class="card-body">
        @foreach ($idea->comments as $comment)
        <div class="card mb-2">
            <div class="card-header d-flex" style="justify-content: space-between">
                <div>
                    Updated by <strong>{{ $comment->owner->name }}</strong> (Status:
                    <strong>{{ $comment->oldStatus->name }}</strong> ->
                    <strong>{{ $comment->newStatus->name }}</strong>)
                </div>
                <div>
                    {{ $comment->created_at }}
                </div>
            </div>
            <div class="card-body">
                {{ $comment->comment }}
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif
@endsection

@if (isset($edit))
@section('scripts')
@include('ideas.partials.edit-info-scripts')
@endsection
@endif