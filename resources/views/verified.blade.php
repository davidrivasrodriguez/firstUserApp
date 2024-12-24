@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Email Verification Status') }}</div>

                <div class="card-body">
                    @if (Auth::check())
                        @if (Auth::user()->hasVerifiedEmail())
                            <h1>Verified</h1>
                        @else
                            <h1>Not Verified</h1>
                        @endif
                    @else
                        <h1>Please log in to check your verification status.</h1>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection