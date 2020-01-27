@extends('layouts.app')

@section('table')

<div class="row mx-2">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-success">
                {{$title}}
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <th>ID</th>
                        <th>Project Name</th>
                        <th>Submitted by</th>
                        <th>Change Type</th>
                        {{-- <th>Justification</th> --}}
                        {{-- <th>Supercircle</th>
                        <th>Cricle</th> --}}
                        <th>Expected benefit</th>
                        <th>Benefit in</th>
                        <th>Expected effort</th>
                        <th>SME</th>
                        <th>Assigned to</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </thead>
                    <tbody>
                        @if (count($ideas) > 0)
                        @foreach ($ideas as $idea)
                        <tr>
                            <td>{{ $idea->getReqIdAsString() }}</td>
                            <td>{{ $idea->title }}</td>
                            <td>{{ $idea->submitter->name }}</td>
                            <td>{{ $idea->changeType->name }}</td>
                            {{-- <td>{{ $idea->justification->name }}</td> --}}
                            {{-- <td>{{ $idea->supercircle->name }}</td>
                            <td>{{ $idea->circle->name }}</td> --}}
                            <td>{{ $idea->expected_benefit }}</td>
                            <td>{{ $idea->expected_benefit_type }}</td>
                            <td>{{ $idea->expected_effort }}</td>
                            <td>{{ $idea->smeUser->name }}</td>
                            <td>{{ $idea->pendingAt() }}</td>
                            <td>{{ $idea->status->name }}</td>
                            <td>
                                <a href="{{ route("ideas.display", $idea) }}" class="btn btn-primary btn-sm">Open</a>
                                @if (auth()->user()->canFullyEditIdea())
                                <a href="{{ route("ideas.show-full-edit", $idea) }}" class="btn btn-success btn-sm" style="color: white">Edit</a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
                @if (count($ideas) == 0)
                <div class="text-center">
                    <h5>There are no items on this list</h5>
                </div>
                @endif
            </div>
            <div class="d-flex" style="justify-content: center">{{ $ideas->links() }}</div>
        </div>
    </div>
</div>

<!-- Modal -->
{{-- <div class="modal fade" id="delete-idea-modal" tabindex="-1" role="dialog" aria-labelledby="delete-idea-modal"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="delete-idea-modal-title">Delete idea</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Would you like to delete idea <span id="delete-idea-modal-name"></span>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>

                <form action="#" method="POST" class="d-inline-block" id="delete-button-form">

                    @csrf
                    @method("DELETE")

                    <button class="btn btn-danger">Delete</button>

                </form>

            </div>
        </div>
    </div>
</div> --}}

@endsection

@section('scripts')

<script src="https://code.jquery.com/jquery-3.4.1.min.js"
    integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous">
</script>

<script>
    $( document ).ready(function() {
    
    $(".delete-button").click(function () { 
    
        userID = $(this).attr("user-id");
        userName = $(this).attr("user-name");
    
        $("#delete-user-modal-name").text(userName);

        action = $("#delete-button-form").attr("action");
        action = action.replace("*", userID);
        $("#delete-button-form").attr("action", action);
    });

});

</script>

@endsection