@extends('layouts.app')

@section('title', 'Manage Users')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; flex-wrap: wrap; gap: 0.75rem;">
        <h1 class="card-header" style="margin: 0;">👥 Manage Users</h1>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">+ Add User</a>
    </div>

    <!-- Desktop Table View -->
    <div class="table-responsive desktop-table">
        <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td><span class="badge">{{ ucfirst($user->role) }}</span></td>
                <td>{{ $user->created_at->format('d M Y') }}</td>
                <td>
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center;">No users found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
    
    <!-- Mobile Card View -->
    <div class="mobile-cards">
        @forelse($users as $user)
        <div class="mobile-card">
            <div class="mobile-card-header">
                <strong>{{ $user->name }}</strong>
                <span class="badge">{{ ucfirst($user->role) }}</span>
            </div>
            <div class="mobile-card-body">
                <div class="mobile-card-row">
                    <span class="mobile-label">Email:</span>
                    <span>{{ $user->email }}</span>
                </div>
                <div class="mobile-card-row">
                    <span class="mobile-label">Created:</span>
                    <span>{{ $user->created_at->format('d M Y') }}</span>
                </div>
            </div>
            <div class="mobile-card-actions">
                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-warning">Edit</a>
                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </div>
        </div>
        @empty
        <div style="text-align: center; padding: 2rem; color: #999;">No users found</div>
        @endforelse
    </div>

    <div style="margin-top: 1rem;">
        {{ $users->links() }}
    </div>
</div>
@endsection
