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