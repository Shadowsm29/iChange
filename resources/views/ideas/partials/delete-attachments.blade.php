<div class="form-group">
    <div class="row">
        <div class="col-md-12">
            <label for="attachments">Add New Attachments:</label>
            <input type="file" class="form-control-file" id="attachments" name="attachments[]" multiple>
        </div>
    </div>
</div>

<div class="form-group">
    <div class="row">
        <div class="col-md-12">
            <form action="">
                <label for="attachment">Current Attachments:</label> <br>
                @if ($idea->attachments->count() > 0)
                <ul class="list-group">
                    @foreach ($idea->attachments as $attachment)
                    <li class="list-group-item">
                        <a href="{{ route("attachments.download", [$idea, $attachment]) }}">{{ $attachment->name }}</a>
                        <button class="btn btn-danger btn-sm float-right"
                            formaction="{{ route("attachments.delete", [$idea, $attachment]) }}">Delete
                            attachment</button>
                    </li>
                    @endforeach
                </ul>
                @else
                No attachments available
                @endif
        </div>
    </div>
</div>