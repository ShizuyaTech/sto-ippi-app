@extends('layouts.app')

@section('title', 'User Details')

@section('content')
<div class="card">
    <h1>User Details</h1>
    
    <table style="margin-top: 1rem;">
        <tr>
            <th style="width: 200px;">Name</th>
            <td>{{ $user->name }}</td>
        </tr>
        <tr>
            <th>Email</th>
            <td>{{ $user->email }}</td>
        </tr>
        <tr>
            <th>Role</th>
            <td><span class="badge">{{ ucfirst($user->role) }}</span></td>
        </tr>
        <tr>
            <th>Created At</th>
            <td>{{ $user->created_at->format('d M Y H:i') }}</td>
        </tr>
    </table>
</div>

<div class="card">
    <h2>Recent Stock Taking Sessions</h2>
    <table>
        <thead>
            <tr>
                <th>Session Code</th>
                <th>Category</th>
                <th>Status</th>
                <th>Scheduled Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($user->stockTakingSessions as $session)
            <tr>
                <td>{{ $session->session_code }}</td>
                <td><span class="badge">{{ $session->category_label }}</span></td>
                <td><span class="badge badge-{{ $session->status }}">{{ $session->status_label }}</span></td>
                <td>{{ $session->scheduled_date->format('d M Y') }}</td>
                <td>
                    <a href="{{ route('admin.sessions.show', $session) }}" class="btn btn-primary">View</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center;">No sessions assigned</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top: 1rem;">
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Back to List</a>
    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">Edit User</a>
</div>
@endsection
