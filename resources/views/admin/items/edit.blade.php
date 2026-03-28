@extends('layouts.app')

@section('title', 'Edit Item')

@section('content')
<div class="card">
    <h1>Edit Item</h1>

    <form method="POST" action="{{ route('admin.items.update', $item) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Code</label>
            <input type="text" name="code" class="form-control" value="{{ old('code', $item->code) }}" required>
            @error('code')
                <small style="color: red;">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $item->name) }}" required>
            @error('name')
                <small style="color: red;">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label>Category</label>
            <select name="category" class="form-control" required>
                <option value="raw_material" {{ old('category', $item->category) == 'raw_material' ? 'selected' : '' }}>Raw Material</option>
                <option value="wip" {{ old('category', $item->category) == 'wip' ? 'selected' : '' }}>WIP (Work In Progress)</option>
                <option value="finish_part" {{ old('category', $item->category) == 'finish_part' ? 'selected' : '' }}>Finish Part</option>
            </select>
            @error('category')
                <small style="color: red;">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description', $item->description) }}</textarea>
            @error('description')
                <small style="color: red;">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label>Unit</label>
            <input type="text" name="unit" class="form-control" value="{{ old('unit', $item->unit) }}" required>
            @error('unit')
                <small style="color: red;">{{ $message }}</small>
            @enderror
        </div>

        <div style="margin-top: 1rem;">
            <button type="submit" class="btn btn-success">Update Item</button>
            <a href="{{ route('admin.items.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
