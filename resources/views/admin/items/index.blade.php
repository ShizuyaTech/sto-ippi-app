@extends('layouts.app')

@section('title', 'Manage Items')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; flex-wrap: wrap; gap: 0.75rem;">
        <h1 class="card-header" style="margin: 0;">📦 Manage Items</h1>
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <a href="{{ route('admin.items.import') }}" class="btn btn-success">📥 Import</a>
            <a href="{{ route('admin.items.create') }}" class="btn btn-primary">+ Add Item</a>
        </div>
    </div>

    <form method="GET" style="margin-bottom: 1rem;">
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <select name="category" class="form-control" style="flex: 1; min-width: 150px; max-width: 200px;">
                <option value="">All Categories</option>
                <option value="raw_material" {{ request('category') == 'raw_material' ? 'selected' : '' }}>Raw Material</option>
                <option value="wip" {{ request('category') == 'wip' ? 'selected' : '' }}>WIP</option>
                <option value="finish_part" {{ request('category') == 'finish_part' ? 'selected' : '' }}>Finish Part</option>
            </select>
            <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}" style="flex: 2; min-width: 200px;">
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('admin.items.index') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    <!-- Desktop Table View -->
    <div class="table-responsive desktop-table">
        <table>
        <thead>
            <tr>
                <th>Code</th>
                <th>Name</th>
                <th>Category</th>
                <th>Unit</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $item)
            <tr>
                <td>{{ $item->code }}</td>
                <td>{{ $item->name }}</td>
                <td><span class="badge">{{ $item->category_label }}</span></td>
                <td>{{ $item->unit }}</td>
                <td>
                    <a href="{{ route('admin.items.edit', $item) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form method="POST" action="{{ route('admin.items.destroy', $item) }}" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center;">No items found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
    
    <!-- Mobile Card View -->
    <div class="mobile-cards">
        @forelse($items as $item)
        <div class="mobile-card">
            <div class="mobile-card-header">
                <strong>{{ $item->code }}</strong>
                <span class="badge">{{ $item->category_label }}</span>
            </div>
            <div class="mobile-card-body">
                <div class="mobile-card-row">
                    <span class="mobile-label">Name:</span>
                    <span>{{ $item->name }}</span>
                </div>
                <div class="mobile-card-row">
                    <span class="mobile-label">Unit:</span>
                    <span>{{ $item->unit }}</span>
                </div>
            </div>
            <div class="mobile-card-actions">
                <a href="{{ route('admin.items.edit', $item) }}" class="btn btn-sm btn-warning">Edit</a>
                <form method="POST" action="{{ route('admin.items.destroy', $item) }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </div>
        </div>
        @empty
        <div style="text-align: center; padding: 2rem; color: #999;">No items found</div>
        @endforelse
    </div>

    <div style="margin-top: 1rem;">
        {{ $items->links() }}
    </div>
</div>
@endsection
