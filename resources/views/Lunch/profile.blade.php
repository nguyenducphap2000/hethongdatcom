@extends('layouts.app')
@section('content')
<div class="container-profile" style="width: 30%; border: 1px solid #D2CB95;border-radius: 3px;">
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    @if(Session('profileSuccess'))
        <div class="alert alert-success">
            {{ Session()->get('profileSuccess') }}
        </div>
    @elseif(Session('profileFail'))  
        <div class="alert alert-danger">
            {{ Session()->get('profileFail') }}
        </div>
    @endif

    <form action="{{ route('profileUpdate') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('put')
        <div class="card-header" style="background-color: #FCB71E;">Thông tin cá nhân</div>
        <div class="card-body" style="background-color: #FFFAD5;">
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">Name</span>
                <input name="name" type="text" class="form-control" value="{{ (isset(Auth::user()->name)) ? Auth::user()->name : '' }}" aria-label="Username" aria-describedby="basic-addon1">
            </div>
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">New Password</span>
                <input name="password" type="password" class="form-control" placeholder="New password" aria-label="Username" aria-describedby="basic-addon1">
            </div>
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">Repeat Password</span>
                <input name="RePassword" type="password" class="form-control" placeholder="Repeat password" aria-label="Username" aria-describedby="basic-addon1">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>    
    </form>
</div>
@endsection