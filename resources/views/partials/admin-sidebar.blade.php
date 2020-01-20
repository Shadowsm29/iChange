<ul class="list-group">
    @auth
    @if (auth()->user()->isIam() || auth()->user()->isSuperadmin())
    <li class="list-group-item"><a href="{{ route('users.index') }}">Users</a></li>
    <li class="list-group-item"><a href="{{ route('users.trashed') }}">Trashed users</a></li>
    @endif
    
    @if (auth()->user()->isRpa() || auth()->user()->isSuperadmin())
    <li class="list-group-item"><a href="{{ route('change-types.index') }}">Change types</a></li>
    <li class="list-group-item"><a href="{{ route('justifications.index') }}">Justification</a></li>
    <li class="list-group-item"><a href="{{ route('supercircles.index') }}">Supercircles</a></li>
    <li class="list-group-item"><a href="{{ route('circles.index') }}">Circles</a></li>
    @endif
    @endauth
</ul>