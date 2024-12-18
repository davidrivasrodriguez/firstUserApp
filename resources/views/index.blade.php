@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    Hola
                </div>
                <div class="card-body">
                    <a href="{{ url('login') }}">Login</a>
                    <br>
                    <a href="{{ route('verified') }}">Verified</a>
                    <br>
                     <a href="{{ route('profile.show') }}">Profile Settings</a>
                    <br>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-link" style="padding: 0; border: none; background: none;" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </button>
                    </form>
                    <br>
                    <a href="">Password forgot</a>
                    <br>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection