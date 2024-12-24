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
                    <a href="{{ route('password.request') }}">Forgot Your Password?</a>
                    <br>
                    @if (Auth::check() && Auth::user()->role == 'admin')
                        <div class="mt-3">
                            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Create User</a>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Manage Users</a>
                        </div>
                    @elseif (Auth::check() && Auth::user()->role == 'superadmin')
                        <div class="mt-3">
                            <a href="{{ route('superadmin.users.create') }}" class="btn btn-primary">Create User</a>
                            <a href="{{ route('superadmin.users.index') }}" class="btn btn-secondary">Manage Users</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection