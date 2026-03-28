@extends('layouts.app')

@section('title', 'Import Items from Excel')

@section('content')
<div class="card">
    <h1>Import Items from Excel</h1>
    <p style="color: #666; margin-top: 0.5rem;">Upload file Excel untuk import multiple items sekaligus.</p>

    @if(session('failures'))
        <div class="alert alert-error" style="margin-top: 1rem;">
            <strong>Terdapat kesalahan pada file Excel:</strong>
            <ul style="margin-top: 0.5rem;">
                @foreach(session('failures') as $failure)
                    <li>
                        Row {{ $failure->row() }}: {{ $failure->errors()[0] }}
                        @if($failure->values())
                            ({{ implode(', ', $failure->values()) }})
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <div style="background: #e3f2fd; padding: 1rem; border-radius: 4px; margin: 1.5rem 0;">
        <h3 style="margin-bottom: 0.5rem;">📋 Format Excel yang Dibutuhkan:</h3>
        <p style="margin: 0.5rem 0;">File Excel harus memiliki kolom berikut (header di baris pertama):</p>
        <table style="margin-top: 1rem; background: white;">
            <thead>
                <tr>
                    <th>Kolom</th>
                    <th>Keterangan</th>
                    <th>Contoh</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>code</strong></td>
                    <td>Kode item (wajib, unique)</td>
                    <td>ITEM-001</td>
                </tr>
                <tr>
                    <td><strong>name</strong></td>
                    <td>Nama item (wajib)</td>
                    <td>Steel Plate 10mm</td>
                </tr>
                <tr>
                    <td><strong>category</strong></td>
                    <td>Kategori: raw_material, wip, atau finish_part (wajib)</td>
                    <td>raw_material</td>
                </tr>
                <tr>
                    <td><strong>unit</strong></td>
                    <td>Satuan (wajib)</td>
                    <td>Sheet, Pcs, Kg, dll</td>
                </tr>
                <tr>
                    <td><strong>description</strong></td>
                    <td>Deskripsi (opsional)</td>
                    <td>Steel plate 10mm thickness</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div style="background: #fff3cd; padding: 1rem; border-radius: 4px; margin-bottom: 1.5rem;">
        <strong>💡 Tips:</strong>
        <ul style="margin: 0.5rem 0 0 1.5rem;">
            <li>Download template Excel terlebih dahulu untuk memastikan format yang benar</li>
            <li>Template sudah berisi contoh data yang bisa dihapus dan diganti dengan data Anda</li>
            <li>Pastikan tidak ada code yang duplicate dengan data yang sudah ada</li>
            <li>File maksimal 5 MB</li>
            <li>Format yang didukung: .xlsx, .xls, .csv</li>
        </ul>
    </div>

    <div style="margin-bottom: 2rem;">
        <a href="{{ route('admin.items.template') }}" class="btn btn-success">
            📥 Download Template Excel
        </a>
    </div>

    <form method="POST" action="{{ route('admin.items.import.process') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label>Upload File Excel *</label>
            <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
            @error('file')
                <span style="color: #e74c3c; font-size: 0.875rem;">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-top: 1.5rem;">
            <button type="submit" class="btn btn-primary">Upload & Import</button>
            <a href="{{ route('admin.items.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

@push('styles')
<style>
    .alert ul {
        margin: 0;
        padding-left: 1.5rem;
    }
    .alert li {
        margin: 0.25rem 0;
    }
</style>
@endpush
@endsection
