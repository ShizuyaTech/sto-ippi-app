@extends('layouts.app')

@section('title', 'Stock Taking Sessions')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; flex-wrap: wrap; gap: 0.75rem;">
        <h1 class="card-header" style="margin: 0;">📋 Stock Taking Sessions</h1>
        <a href="{{ route('admin.sessions.create') }}" class="btn btn-primary">+ Create Session</a>
    </div>

    <form method="GET" style="margin-bottom: 1rem;">
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <select name="status" class="form-control" style="flex: 1; min-width: 150px; max-width: 200px;">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
            <select name="category" class="form-control" style="flex: 1; min-width: 150px; max-width: 200px;">
                <option value="">All Categories</option>
                <option value="raw_material" {{ request('category') == 'raw_material' ? 'selected' : '' }}>Raw Material</option>
                <option value="wip" {{ request('category') == 'wip' ? 'selected' : '' }}>WIP</option>
                <option value="finish_part" {{ request('category') == 'finish_part' ? 'selected' : '' }}>Finish Part</option>
            </select>
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('admin.sessions.index') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    <!-- Desktop Table View -->
    <div class="table-responsive desktop-table">
        <table>
        <thead>
            <tr>
                <th>Session Code</th>
                <th>User</th>
                <th>Category</th>
                <th>Status</th>
                <th>Scheduled Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sessions as $session)
            <tr>
                <td>{{ $session->session_code }}</td>
                <td>{{ $session->user->name }}</td>
                <td><span class="badge">{{ $session->category_label }}</span></td>
                <td><span class="badge badge-{{ $session->status }}">{{ $session->status_label }}</span></td>
                <td>{{ $session->scheduled_date->format('d M Y') }}</td>
                <td>
                    <a href="{{ route('admin.sessions.show', $session) }}" class="btn btn-sm btn-primary">View</a>
                    @if($session->status === 'completed')
                        <a href="{{ route('admin.sessions.export', $session) }}" class="btn btn-sm btn-success">📥 Download</a>
                    @endif
                    <a href="{{ route('admin.sessions.edit', $session) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form method="POST" action="{{ route('admin.sessions.destroy', $session) }}" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center;">No sessions found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
    
    <!-- Mobile Card View -->
    <div class="mobile-cards">
        @forelse($sessions as $session)
        <div class="mobile-card">
            <div class="mobile-card-header">
                <strong>{{ $session->session_code }}</strong>
                <span class="badge badge-{{ $session->status }}">{{ $session->status_label }}</span>
            </div>
            <div class="mobile-card-body">
                <div class="mobile-card-row">
                    <span class="mobile-label">User:</span>
                    <span>{{ $session->user->name }}</span>
                </div>
                <div class="mobile-card-row">
                    <span class="mobile-label">Category:</span>
                    <span class="badge">{{ $session->category_label }}</span>
                </div>
                <div class="mobile-card-row">
                    <span class="mobile-label">Scheduled:</span>
                    <span>{{ $session->scheduled_date->format('d M Y') }}</span>
                </div>
            </div>
            <div class="mobile-card-actions">
                <a href="{{ route('admin.sessions.show', $session) }}" class="btn btn-sm btn-primary">View</a>
                @if($session->status === 'completed')
                    <a href="{{ route('admin.sessions.export', $session) }}" class="btn btn-sm btn-success">📥 Download</a>
                @endif
                <a href="{{ route('admin.sessions.edit', $session) }}" class="btn btn-sm btn-warning">Edit</a>
                <form method="POST" action="{{ route('admin.sessions.destroy', $session) }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </div>
        </div>
        @empty
        <div style="text-align: center; padding: 2rem; color: #999;">No sessions found</div>
        @endforelse
    </div>

    <div style="margin-top: 1rem;">
        {{ $sessions->links() }}
    </div>
</div>
@endsection
