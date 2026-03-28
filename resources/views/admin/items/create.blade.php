@extends('layouts.app')

@section('title', 'Create Item')

@section('content')
<div class="card">
    <h1>Create New Item</h1>

    <form method="POST" action="{{ route('admin.items.store') }}">
        @csrf

        <div class="form-group">
            <label>Code</label>
            <input type="text" name="code" class="form-control" value="{{ old('code') }}" required>
            @error('code')
                <small style="color: red;">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            @error('name')
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
            <label>Description</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
            @error('description')
                <small style="color: red;">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label>Unit</label>
            <input type="text" name="unit" class="form-control" value="{{ old('unit', 'pcs') }}" required>
            @error('unit')
                <small style="color: red;">{{ $message }}</small>
            @enderror
        </div>

        <div style="margin-top: 1rem;">
            <button type="submit" class="btn btn-success">Create Item</button>
            <a href="{{ route('admin.items.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
