@extends('layouts.app')

@section('content')
<div class="page-container">
    <div class="unauth-container">
        <h1>Unauthorized access!</h1>
        <p>This action has been forbidden due to security reasons.</p>
    </div>
</div>
@endsection

<style>
    .page-container {
        width: 100%;
        height: 80vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .unauth-container {
        background-color: white;
        padding: 30px;
        border-radius: 5px;
        border: 1px solid red;
        box-shadow: 0 0 5px 1px red;
    }
</style>