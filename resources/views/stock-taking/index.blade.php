@extends('layouts.app')

@section('title', 'My Stock Taking Sessions')

@section('content')
<div class="card">
    <h1 class="card-header">📋 My Stock Taking Sessions</h1>

    <!-- Desktop Table View -->
    <div class="table-responsive desktop-table">
        <table style="margin-top: 1rem;">
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
            @forelse($sessions as $session)
            <tr>
                <td>{{ $session->session_code }}</td>
                <td><span class="badge">{{ $session->category_label }}</span></td>
                <td><span class="badge badge-{{ $session->status }}">{{ $session->status_label }}</span></td>
                <td>{{ $session->scheduled_date->format('d M Y') }}</td>
                <td>
                    <a href="{{ route('stock-taking.show', $session) }}" class="btn btn-sm btn-primary">
                        @if($session->status === 'completed')
                            View
                        @else
                            Open
                        @endif
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center;">No sessions assigned to you</td>
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
                    <span class="mobile-label">Category:</span>
                    <span class="badge">{{ $session->category_label }}</span>
                </div>
                <div class="mobile-card-row">
                    <span class="mobile-label">Scheduled:</span>
                    <span>{{ $session->scheduled_date->format('d M Y') }}</span>
                </div>
            </div>
            <div class="mobile-card-actions">
                <a href="{{ route('stock-taking.show', $session) }}" class="btn btn-sm btn-primary" style="width: 100%;">
                    @if($session->status === 'completed')
                        📄 View Session
                    @else
                        ▶️ Open Session
                    @endif
                </a>
            </div>
        </div>
        @empty
        <div style="text-align: center; padding: 2rem; color: #999;">No sessions assigned to you</div>
        @endforelse
    </div>

    <div style="margin-top: 1rem;">
        {{ $sessions->links() }}
    </div>
</div>
@endsection
