@extends('layouts.app')

@section('title', 'Edit Session')

@section('content')
<div class="card">
    <h1>Edit Stock Taking Session</h1>

    <form method="POST" action="{{ route('admin.sessions.update', $session) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Assign to User</label>
            <select name="user_id" class="form-control" required>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ old('user_id', $session->user_id) == $user->id ? 'selected' : '' }}>
                        {{ $user->name }} ({{ $user->email }})
                    </option>
                @endforeach
            </select>
            @error('user_id')
                <small style="color: red;">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label>Category</label>
            <select name="category" class="form-control" required>
                <option value="raw_material" {{ old('category', $session->category) == 'raw_material' ? 'selected' : '' }}>Raw Material</option>
                <option value="wip" {{ old('category', $session->category) == 'wip' ? 'selected' : '' }}>WIP (Work In Progress)</option>
                <option value="finish_part" {{ old('category', $session->category) == 'finish_part' ? 'selected' : '' }}>Finish Part</option>
            </select>
            @error('category')
                <small style="color: red;">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label>Scheduled Date</label>
            <input type="date" name="scheduled_date" class="form-control" value="{{ old('scheduled_date', $session->scheduled_date->format('Y-m-d')) }}" required>
            @error('scheduled_date')
                <small style="color: red;">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label>Notes</label>
            <textarea name="notes" class="form-control" rows="3">{{ old('notes', $session->notes) }}</textarea>
            @error('notes')
                <small style="color: red;">{{ $message }}</small>
            @enderror
        </div>

        <div style="margin-top: 1rem;">
            <button type="submit" class="btn btn-success">Update Session</button>
            <a href="{{ route('admin.sessions.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
