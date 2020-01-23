@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-success">My Notifications</div>
    <div class="card-body">
        @if ($notifications->count() > 0)
        <ul class="list-group">
            @foreach ($notifications as $notification)
            <li class="list-group-item d-flex" style="justify-content: space-between;">
                <div>
                    <p><i>{{ $notification->created_at }} </i></p>
                    <p style="margin-top: 1rem">{{ $notification->data["requestNumber"]}} - Status: {{ $notification->data["idea"]["status_id"] }} {{$notification->data["message"] }}</p>
                </div>
                <div style="display: flex; align-items: center">
                    <a href="{{route("ideas.display", $notification->data["idea"]["id"])}}" class="btn btn-success">Open</a>
                </div>
            </li>
            @endforeach
        </ul>
        @else
        <h5 class="d-flex" style="justify-content: center">You have no notifications</h5>
        @endif
        <div class="d-flex mt-3" style="justify-content: center;">
            {{ $notifications->links() }}
        </div>
    </div>
</div>
@endsection