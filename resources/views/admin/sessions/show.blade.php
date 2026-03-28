@extends('layouts.app')

@section('title', 'Session Details')

@section('content')
<div class="card">
    <h1>Stock Taking Session Details</h1>
    
    <table style="margin-top: 1rem;">
        <tr>
            <th style="width: 200px;">Session Code</th>
            <td>{{ $session->session_code }}</td>
        </tr>
        <tr>
            <th>Assigned User</th>
            <td>{{ $session->user->name }}</td>
        </tr>
        <tr>
            <th>Category</th>
            <td>{{ $session->category_label }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td><span class="badge badge-{{ $session->status }}">{{ $session->status_label }}</span></td>
        </tr>
        <tr>
            <th>Scheduled Date</th>
            <td>{{ $session->scheduled_date->format('d M Y') }}</td>
        </tr>
        @if($session->started_at)
        <tr>
            <th>Started At</th>
            <td>{{ $session->started_at->format('d M Y H:i') }}</td>
        </tr>
        @endif
        @if($session->completed_at)
        <tr>
            <th>Completed At</th>
            <td>{{ $session->completed_at->format('d M Y H:i') }}</td>
        </tr>
        @endif
        @if($session->notes)
        <tr>
            <th>Notes</th>
            <td>{{ $session->notes }}</td>
        </tr>
        @endif
    </table>
</div>

@if($session->details->count() > 0)
<div class="card">
    <h2>Stock Taking Details</h2>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Entry ID</th>
                    <th>Item Code</th>
                    <th>Item Name</th>
                    <th>Actual Qty</th>
                    <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @foreach($session->details as $detail)
            <tr>
                <td><strong>{{ $detail->entry_code ?? '#' . $detail->id }}</strong></td>
                <td>{{ $detail->item->code }}</td>
                <td>{{ $detail->item->name }}</td>
                <td>{{ number_format($detail->actual_quantity, 2) }}</td>
                <td>{{ $detail->remarks ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
</div>
@endif

<div style="margin-top: 1rem; display: flex; gap: 0.5rem; flex-wrap: wrap;">
    <a href="{{ route('admin.sessions.index') }}" class="btn btn-secondary">← Back to List</a>
    @if($session->status === 'completed')
        <a href="{{ route('admin.sessions.export', $session) }}" class="btn btn-success">📥 Download Excel</a>
    @endif
    <a href="{{ route('admin.sessions.edit', $session) }}" class="btn btn-warning">✏️ Edit Session</a>
    <form method="POST" action="{{ route('admin.sessions.destroy', $session) }}" style="display: inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this session?')">🗑️ Delete Session</button>
    </form>
</div>
@endsection
