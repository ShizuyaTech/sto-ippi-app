@extends('layouts.app')

@section('content')
<div class="min-h-screen py-8 px-4">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Generate Tag Number</h1>
            <p class="text-gray-600">Generate tag numbers untuk stock taking session</p>
        </div>

        <!-- Generate Form -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
            <div class="p-8">
                <form action="{{ route('admin.tag-numbers.generate') }}" method="POST">
                    @csrf
                    
                    <div class="space-y-6">
                        <!-- Category Selection -->
                        <div>
                            <label for="category" class="block text-sm font-semibold text-gray-700 mb-2">
                                Category *
                            </label>
                            <select 
                                name="category" 
                                id="category" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200"
                                required
                            >
                                <option value="">-- Pilih Category --</option>
                                <option value="raw_material" {{ old('category') == 'raw_material' ? 'selected' : '' }}>Raw Material</option>
                                <option value="wip" {{ old('category') == 'wip' ? 'selected' : '' }}>WIP (Work in Progress)</option>
                                <option value="finish_part" {{ old('category') == 'finish_part' ? 'selected' : '' }}>Finish Part</option>
                            </select>
                            @error('category')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Quantity Input -->
                        <div>
                            <label for="quantity" class="block text-sm font-semibold text-gray-700 mb-2">
                                Jumlah Tag Number *
                            </label>
                            <input 
                                type="number" 
                                name="quantity" 
                                id="quantity" 
                                min="1" 
                                max="1000"
                                value="{{ old('quantity', 10) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200"
                                required
                            >
                            <p class="mt-1 text-sm text-gray-500">Maksimal 1000 tag numbers per generate</p>
                            @error('quantity')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Mode Generate -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                Mode Generate *
                            </label>
                            <div class="space-y-2">
                                <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition duration-150">
                                    <input 
                                        type="radio" 
                                        name="mode" 
                                        value="fresh" 
                                        id="mode_fresh"
                                        {{ old('mode', 'fresh') === 'fresh' ? 'checked' : '' }}
                                        class="w-4 h-4 text-purple-600"
                                        onchange="toggleStartFrom()"
                                    >
                                    <div>
                                        <span class="font-semibold text-gray-800">Mulai dari awal</span>
                                        <p class="text-xs text-gray-500">Nomor tag dimulai dari 0001</p>
                                    </div>
                                </label>
                                <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition duration-150">
                                    <input 
                                        type="radio" 
                                        name="mode" 
                                        value="continue" 
                                        id="mode_continue"
                                        {{ old('mode') === 'continue' ? 'checked' : '' }}
                                        class="w-4 h-4 text-purple-600"
                                        onchange="toggleStartFrom()"
                                    >
                                    <div>
                                        <span class="font-semibold text-gray-800">Lanjutkan dari nomor terakhir</span>
                                        <p class="text-xs text-gray-500">Nomor tag dilanjutkan dari nomor yang sudah ada</p>
                                    </div>
                                </label>
                            </div>
                            @error('mode')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Start From (only shown when mode = continue) -->
                        <div id="start_from_section" style="{{ old('mode') === 'continue' ? '' : 'display:none;' }}">
                            <label for="start_from" class="block text-sm font-semibold text-gray-700 mb-2">
                                Nomor Tag Terakhir *
                            </label>
                            <input 
                                type="number" 
                                name="start_from" 
                                id="start_from" 
                                min="1" 
                                max="9999"
                                value="{{ old('start_from', 1) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200"
                                placeholder="Contoh: 50 (berarti generate mulai dari 0051)"
                            >
                            <p class="mt-1 text-sm text-gray-500">Masukkan nomor urut tag terakhir yang sudah digenerate. Generate baru akan dimulai dari nomor berikutnya.</p>
                            @error('start_from')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <script>
                            function toggleStartFrom() {
                                const isContinue = document.getElementById('mode_continue').checked;
                                const section = document.getElementById('start_from_section');
                                const input = document.getElementById('start_from');
                                section.style.display = isContinue ? '' : 'none';
                                input.required = isContinue;
                            }
                            // Init on page load
                            document.addEventListener('DOMContentLoaded', toggleStartFrom);
                        </script>

                        <!-- Info Box -->
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-4">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                <div class="text-sm text-blue-800">
                                    <p class="font-semibold mb-1">Format Tag Number:</p>
                                    <ul class="list-disc list-inside space-y-1">
                                        <li>Raw Material: <span class="font-mono font-semibold">RM-YYYYMMDD-0001</span></li>
                                        <li>WIP: <span class="font-mono font-semibold">WIP-YYYYMMDD-0001</span></li>
                                        <li>Finish Part: <span class="font-mono font-semibold">FP-YYYYMMDD-0001</span></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-4 pt-4">
                            <button 
                                type="submit"
                                class="flex-1 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold px-6 py-3 rounded-xl hover:from-purple-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                            >
                                <span class="flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Generate Tag Numbers
                                </span>
                            </button>
                            <a 
                                href="{{ route('admin.dashboard') }}"
                                class="px-6 py-3 border-2 border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-200"
                            >
                                Kembali
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
