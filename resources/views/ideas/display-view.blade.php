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
                    <button formaction="{{ route("ideas.back-to-submitter", $idea->id) }}" class="btn btn-danger">
                        @if (isset($mtApprove))
                            Back to Processor
                        @else
                            Back to Submitter
                        @endif
                    </button>
                    @endif
                    @if (isset($forward))
                    <button formaction="{{ route("ideas.update-resubmit", $idea->id) }}" class="btn btn-success">Re-submit</button>
                    @endif
                    @if (isset($assignSme))
                    <button formaction="{{ route("ideas.assign-sme", $idea->id) }}" class="btn btn-success">Assign SME</button>
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
                @if (URL::previous() == URL::current())
                <a href="{{ route("ideas.personal-que-active") }}" class="btn btn-primary ml-2">Back to personal queue</a>
                @else
                <a href="{{ URL::previous() }}" class="btn btn-primary ml-2">Back to list</a>
                @endif
            </div>

            <div class="alert alert-danger mt-2">
                <button formaction="{{ route("ideas.approve", $idea->id) }}" class="btn btn-success">Approve</button>
                <button formaction="{{ route("ideas.decline", $idea->id) }}" class="btn btn-danger">Decline</button>
                <button formaction="{{ route("ideas.back-to-submitter", $idea->id) }}" class="btn btn-danger">
                    @if (isset($mtApprove))
                        Back to Processor
                    @else
                        Back to Submitter
                    @endif
                </button>
                <button formaction="{{ route("ideas.update-resubmit", $idea->id) }}" class="btn btn-success">Re-submit</button>
                <button formaction="{{ route("ideas.update-complete", $idea->id) }}" class="btn btn-success">Complete</button>
                <button formaction="{{ route("ideas.update", $idea->id) }}" class="btn btn-primary">Update</button>
                <button formaction="{{ route("ideas.cancel", $idea->id) }}" class="btn btn-danger">Cancel idea</button>
                <button formaction="{{ route("ideas.assign-sme", $idea->id) }}" class="btn btn-success">Assign SME</button>
            </div>
        </form>
    </div>
</div>

@include('ideas.partials.show-comments')

@endsection

@if (isset($edit))
@section('scripts')
@include('ideas.partials.edit-info-scripts')
@endsection
@endif