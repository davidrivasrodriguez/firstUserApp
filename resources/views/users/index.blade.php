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
                                <th>Profile Photo</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Verified</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>
                                        @if ($user->profile_photo)
                                            <img src="{{ asset('storage/profileImages/' . $user->profile_photo) }}" alt="Profile Photo" width="50" height="50">
                                        @else
                                            No Photo
                                        @endif
                                    </td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->role }}</td>
                                    <td>
                                        @if ($user->email_verified_at)
                                            <span class="badge bg-success">Verified</span>
                                        @else
                                            <span class="badge bg-danger">Not Verified</span>
                                        @endif
                                    </td>
                                <td>
                                @push('scripts')
                                <script>
                                function confirmDelete(event) {
                                    event.preventDefault();
                                    if (confirm('Are you sure you want to delete this user?')) {
                                        event.target.submit();
                                    }
                                }
                                </script>
                                @endpush
                                @if (Auth::user()->role == 'superadmin' || (Auth::user()->role == 'admin' && $user->role != 'superadmin'))
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">Edit</a>
                                    @if (Auth::user()->role == 'superadmin' && $user->id != 1)
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display:inline;" onsubmit="confirmDelete(event)">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    @elseif (Auth::user()->role == 'admin' && $user->role != 'superadmin')
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display:inline;" onsubmit="confirmDelete(event)">
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