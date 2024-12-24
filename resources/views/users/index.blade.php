@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Users</div>
                <div class="card-body">
                    @if (Auth::user()->role == 'admin' || Auth::user()->role == 'superadmin')
                        <div class="mb-3">
                            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Create User</a>                        
                        </div>
                    @endif
                    <table class="table mt-3">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->role }}</td>
                                    <td>
                                        @if (Auth::user()->role == 'admin' || Auth::user()->role == 'superadmin')
                                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">Edit</a>
                                            @if (Auth::user()->role == 'superadmin' && $user->id != 1)
                                                <form action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection