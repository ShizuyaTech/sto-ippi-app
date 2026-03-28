@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="card">
    <h1 class="card-header">📊 Admin Dashboard</h1>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.25rem; margin-bottom: 2rem;">
    <div class="card" style="text-align: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none;">
        <h3 style="font-size: 2.5rem; margin-bottom: 0.5rem; color: white;">{{ $totalUsers }}</h3>
        <p style="font-weight: 600; opacity: 0.95;">Total Users</p>
    </div>
    <div class="card" style="text-align: center; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; border: none;">
        <h3 style="font-size: 2.5rem; margin-bottom: 0.5rem; color: white;">{{ $totalItems }}</h3>
        <p style="font-weight: 600; opacity: 0.95;">Total Items</p>
    </div>
    <div class="card" style="text-align: center; background: linear-gradient(135deg, #f2994a 0%, #f2c94c 100%); color: white; border: none;">
        <h3 style="font-size: 2.5rem; margin-bottom: 0.5rem; color: white;">{{ $pendingSessions }}</h3>
        <p style="font-weight: 600; opacity: 0.95;">Pending Sessions</p>
    </div>
    <div class="card" style="text-align: center; background: linear-gradient(135deg, #00b4db 0%, #0083b0 100%); color: white; border: none;">
        <h3 style="font-size: 2.5rem; margin-bottom: 0.5rem; color: white;">{{ $inProgressSessions }}</h3>
        <p style="font-weight: 600; opacity: 0.95;">In Progress</p>
    </div>
    <div class="card" style="text-align: center; background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white; border: none;">
        <h3 style="font-size: 2.5rem; margin-bottom: 0.5rem; color: white;">{{ $completedSessions }}</h3>
        <p style="font-weight: 600; opacity: 0.95;">Completed</p>
    </div>
</div>

<div class="card">
    <h2 class="card-header">📋 Recent Sessions</h2>
    
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
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentSessions as $session)
            <tr>
                <td>{{ $session->session_code }}</td>
                <td>{{ $session->user->name }}</td>
                <td>{{ $session->category_label }}</td>
                <td>
                    <span class="badge badge-{{ $session->status }}">{{ $session->status_label }}</span>
                </td>
                <td>{{ $session->scheduled_date->format('d M Y') }}</td>
                <td>
                    <a href="{{ route('admin.sessions.show', $session) }}" class="btn btn-primary btn-sm">View</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center;">No sessions yet</td>
            </tr>
            @endforelse
        </tbody>
        </table>
    </div>
    
    <!-- Mobile Card View -->
    <div class="mobile-cards">
        @forelse($recentSessions as $session)
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
                    <span>{{ $session->category_label }}</span>
                </div>
                <div class="mobile-card-row">
                    <span class="mobile-label">Scheduled:</span>
                    <span>{{ $session->scheduled_date->format('d M Y') }}</span>
                </div>
                <div class="mobile-card-row">
                    <span class="mobile-label">Action:</span>
                    <a href="{{ route('admin.sessions.show', $session) }}" class="btn btn-primary btn-sm">View</a>
                </div>
            </div>
        </div>
        @empty
        <div style="text-align: center; padding: 2rem; color: #999;">
            📭 No sessions yet
        </div>
        @endforelse
    </div>
</div>
@endsection
