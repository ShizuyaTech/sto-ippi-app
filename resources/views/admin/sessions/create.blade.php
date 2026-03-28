@extends('layouts.app')

@section('title', 'Create Stock Taking Session')

@section('content')
<div class="card">
    <h1>Create New Stock Taking Session</h1>

    <form method="POST" action="{{ route('admin.sessions.store') }}">
        @csrf

        <div class="form-group">
            <label>Assign to User</label>
            <select name="user_id" class="form-control" required>
                <option value="">Select User</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
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
                <option value="">Select Category</option>
                <option value="raw_material" {{ old('category') == 'raw_material' ? 'selected' : '' }}>Raw Material</option>
                <option value="wip" {{ old('category') == 'wip' ? 'selected' : '' }}>WIP (Work In Progress)</option>
                <option value="finish_part" {{ old('category') == 'finish_part' ? 'selected' : '' }}>Finish Part</option>
            </select>
            @error('category')
                <small style="color: red;">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label>Scheduled Date</label>
            <input type="date" name="scheduled_date" class="form-control" value="{{ old('scheduled_date', today()->format('Y-m-d')) }}" required>
            @error('scheduled_date')
                <small style="color: red;">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label>Notes</label>
            <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
            @error('notes')
                <small style="color: red;">{{ $message }}</small>
            @enderror
        </div>

        <div style="margin-top: 1rem;">
            <button type="submit" class="btn btn-success">Create Session</button>
            <a href="{{ route('admin.sessions.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
