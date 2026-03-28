@extends('layouts.app')

@section('title', 'Item Details')

@section('content')
<div class="card">
    <h1>Item Details</h1>
    
    <table style="margin-top: 1rem;">
        <tr>
            <th style="width: 200px;">Code</th>
            <td>{{ $item->code }}</td>
        </tr>
        <tr>
            <th>Name</th>
            <td>{{ $item->name }}</td>
        </tr>
        <tr>
            <th>Category</th>
            <td><span class="badge">{{ $item->category_label }}</span></td>
        </tr>
        <tr>
            <th>Description</th>
            <td>{{ $item->description ?? '-' }}</td>
        </tr>
        <tr>
            <th>Unit</th>
            <td>{{ $item->unit }}</td>
        </tr>
        <tr>
            <th>Created At</th>
            <td>{{ $item->created_at->format('d M Y H:i') }}</td>
        </tr>
        <tr>
            <th>Last Updated</th>
            <td>{{ $item->updated_at->format('d M Y H:i') }}</td>
        </tr>
    </table>
</div>

<div style="margin-top: 1rem;">
    <a href="{{ route('admin.items.index') }}" class="btn btn-secondary">Back to List</a>
    <a href="{{ route('admin.items.edit', $item) }}" class="btn btn-warning">Edit Item</a>
</div>
@endsection
