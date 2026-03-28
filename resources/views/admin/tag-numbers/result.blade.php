@extends('layouts.app')

@section('content')
<div class="min-h-screen py-8 px-4 no-print">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Tag Numbers Generated</h1>
            <p class="text-gray-600">{{ count($tagNumbers) }} tag numbers telah berhasil dibuat</p>
        </div>

        <!-- Success Alert -->
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-2xl p-6 mb-6 shadow-sm">
            <div class="flex items-start">
                <svg class="w-6 h-6 text-green-500 mt-0.5 mr-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <h3 class="text-lg font-semibold text-green-800 mb-1">Berhasil!</h3>
                    <p class="text-green-700">Tag numbers telah dibuat dan siap untuk digunakan dalam stock taking.</p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-wrap gap-4 mb-6">
            <button 
                onclick="copyAllTagNumbers()"
                class="flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-cyan-600 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-cyan-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
            >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                </svg>
                Copy All
            </button>
            <form action="{{ route('admin.tag-numbers.download-excel') }}" method="POST" style="display: inline-block;">
                @csrf
                <input type="hidden" name="tag_numbers" value="{{ json_encode($tagNumbers) }}">
                <input type="hidden" name="category" value="{{ $category }}">
                <button 
                    type="submit"
                    class="flex items-center px-6 py-3 bg-gradient-to-r from-emerald-600 to-green-600 text-white font-semibold rounded-xl hover:from-emerald-700 hover:to-green-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Download Excel
                </button>
            </form>
            <button 
                onclick="printTags()"
                class="flex items-center px-6 py-3 bg-gradient-to-r from-orange-600 to-red-600 text-white font-semibold rounded-xl hover:from-orange-700 hover:to-red-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
            >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Print Tags
            </button>
            <a 
                href="{{ route('admin.tag-numbers.index') }}"
                class="flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold rounded-xl hover:from-purple-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
            >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Generate Lagi
            </a>
            <a 
                href="{{ route('admin.dashboard') }}"
                class="px-6 py-3 border-2 border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-200"
            >
                Dashboard
            </a>
        </div>

        <!-- Tag Numbers Display -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
            <div class="p-8">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Daftar Tag Numbers</h3>
                
                <!-- Desktop Grid -->
                <div class="hidden md:grid md:grid-cols-3 lg:grid-cols-4 gap-3">
                    @foreach($tagNumbers as $tagNumber)
                        <div 
                            class="relative group"
                            onclick="copyTagNumber('{{ $tagNumber }}')"
                        >
                            <div class="bg-gradient-to-br from-gray-50 to-gray-100 border border-gray-200 rounded-lg p-4 text-center cursor-pointer hover:from-purple-50 hover:to-indigo-50 hover:border-purple-300 transition duration-200 transform hover:-translate-y-1 hover:shadow-md">
                                <code class="text-sm font-mono font-semibold text-gray-800 group-hover:text-purple-700">{{ $tagNumber }}</code>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Mobile List -->
                <div class="md:hidden space-y-2">
                    @foreach($tagNumbers as $tagNumber)
                        <div 
                            class="bg-gradient-to-br from-gray-50 to-gray-100 border border-gray-200 rounded-lg p-4 text-center cursor-pointer active:from-purple-50 active:to-indigo-50 active:border-purple-300 transition duration-200"
                            onclick="copyTagNumber('{{ $tagNumber }}')"
                        >
                            <code class="text-sm font-mono font-semibold text-gray-800">{{ $tagNumber }}</code>
                        </div>
                    @endforeach
                </div>

                <p class="mt-6 text-sm text-gray-500 text-center">
                    <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    Klik pada tag number untuk copy
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div 
    id="toast" 
    class="fixed bottom-8 right-8 bg-gray-900 text-white px-6 py-3 rounded-xl shadow-2xl transform translate-y-20 opacity-0 transition-all duration-300 z-50"
>
    <span id="toast-message">Copied!</span>
</div>

<script>
// Copy single tag number
function copyTagNumber(tagNumber) {
    navigator.clipboard.writeText(tagNumber).then(() => {
        showToast('Tag number copied: ' + tagNumber);
    }).catch(err => {
        console.error('Failed to copy:', err);
    });
}

// Copy all tag numbers
function copyAllTagNumbers() {
    const tagNumbers = @json($tagNumbers);
    const text = tagNumbers.join('\n');
    
    navigator.clipboard.writeText(text).then(() => {
        showToast('All tag numbers copied to clipboard!');
    }).catch(err => {
        console.error('Failed to copy:', err);
        alert('Failed to copy to clipboard');
    });
}

// Show toast notification
function showToast(message) {
    const toast = document.getElementById('toast');
    const toastMessage = document.getElementById('toast-message');
    
    toastMessage.textContent = message;
    toast.classList.remove('translate-y-20', 'opacity-0');
    toast.classList.add('translate-y-0', 'opacity-100');
    
    setTimeout(() => {
        toast.classList.remove('translate-y-0', 'opacity-100');
        toast.classList.add('translate-y-20', 'opacity-0');
    }, 3000);
}

// Print tags with QR code
function printTags() {
    window.print();
}
</script>

<!-- Print Styles and Layout -->
<style>
@media print {
    .no-print {
        display: none !important;
    }
    
    #printArea {
        display: block !important;
    }
    
    .print-page {
        page-break-after: always;
        display: grid;
        grid-template-columns: 1fr 1fr;
        grid-template-rows: 1fr 1fr;
        gap: 0.5cm;
        height: 100%;
        padding: 0.5cm;
    }
    
    .print-page:last-child {
        page-break-after: auto;
    }
    
    .print-tag-table {
        width: 100%;
        height: 100%;
        border-collapse: collapse;
        font-family: Arial, sans-serif;
        font-size: 9pt;
    }
    
    .print-tag-table td, .print-tag-table th {
        border: 2px solid #000;
        padding: 0.15cm;
    }
    
    .qr-cell {
        width: 2.8cm;
        text-align: center;
        vertical-align: middle;
        padding: 0.1cm !important;
    }
    
    .tag-number-vertical {
        writing-mode: vertical-lr;
        transform: rotate(180deg);
        text-align: center;
        font-family: 'Courier New', monospace;
        font-weight: bold;
        font-size: 10pt;
        padding: 0.1cm !important;
        width: 0.7cm;
        white-space: nowrap;
    }
    
    .field-cell {
        height: 0.9cm;
        vertical-align: top;
        position: relative;
    }
    
    .field-label {
        font-weight: bold;
        font-size: 7pt;
        display: block;
        margin-bottom: 0.05cm;
    }
    
    .field-value {
        font-size: 8pt;
        font-weight: bold;
    }
    
    .field-line {
        border-bottom: 1px solid #000;
        height: 0.4cm;
    }
    
    .signature-cell {
        font-size: 6pt;
        padding: 0.08cm !important;
        text-align: center;
        vertical-align: bottom;
    }
    
    .signature-line {
        border-top: 1px solid #000;
        margin-top: 1.2cm;
        padding-top: 0.03cm;
    }
    
    @page {
        size: A4 landscape;
        margin: 0.5cm;
    }
}

#printArea {
    display: none;
}
</style>

<!-- Print Area -->
<div id="printArea">
    @php
        $tagsPerPage = 4;
        $totalTags = count($tagNumbers);
        $pages = ceil($totalTags / $tagsPerPage);
    @endphp
    
    @for($page = 0; $page < $pages; $page++)
        <div class="print-page">
            @for($i = 0; $i < $tagsPerPage; $i++)
                @php
                    $index = ($page * $tagsPerPage) + $i;
                @endphp
                @if($index < $totalTags)
                    @php
                        $tagNumber = $tagNumbers[$index];
                        $no = $index + 1;
                        // Get category from tag prefix
                        $prefix = explode('-', $tagNumber)[0];
                        $satuan = match($prefix) {
                            'RM' => 'SHEET/KG',
                            'WIP' => 'PCS',
                            'FP' => 'PCS',
                            default => 'PCS'
                        };
                        $location = match($prefix) {
                            'RM' => 'WH-RM',
                            'WIP' => 'WH-WIP',
                            'FP' => 'WH-FG',
                            default => 'WH-01'
                        };
                    @endphp
                    
                    <table class="print-tag-table">
                        <tr>
                            <td rowspan="4" class="qr-cell">
                                {!! QrCode::size(90)->margin(0)->generate($tagNumber) !!}
                            </td>
                            <td colspan="5" style="text-align: center; font-weight: bold; background: #f0f0f0; font-size: 9pt;">
                                STOCK TAKING TAG
                            </td>
                            <td rowspan="4" class="tag-number-vertical">
                                {{ $tagNumber }}
                            </td>
                        </tr>
                        <tr>
                            <td class="field-cell" style="width: 12%;">
                                <span class="field-label">NO</span>
                                <div class="field-value">{{ $no }}</div>
                            </td>
                            <td class="field-cell" colspan="3" style="width: 50%;">
                                <span class="field-label">NAMA BARANG / ITEM NAME</span>
                                <div class="field-line"></div>
                            </td>
                            <td class="field-cell" style="width: 18%;">
                                <span class="field-label">LOCATION</span>
                                <div class="field-value">{{ $location }}</div>
                            </td>
                        </tr>
                        <tr>
                            <td class="field-cell">
                                <span class="field-label">SATUAN</span>
                                <div class="field-value">{{ $satuan }}</div>
                            </td>
                            <td class="field-cell">
                                <span class="field-label">QTY</span>
                                <div class="field-line"></div>
                            </td>
                            <td class="field-cell" colspan="3">
                                <span class="field-label">KETERANGAN / REMARKS</span>
                                <div class="field-line"></div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" style="padding: 0.08cm;">
                                <table style="width: 100%; border-collapse: collapse;">
                                    <tr>
                                        <td colspan="4" style="font-weight: bold; font-size: 6pt; padding: 0.03cm; border: none;">
                                            TANDA TANGAN STOCK OPNAME
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="signature-cell" style="width: 25%; border-right: 1px solid #ccc;">
                                            <div style="height: 1.2cm;"></div>
                                            <div class="signature-line">1. ______</div>
                                        </td>
                                        <td class="signature-cell" style="width: 25%; border-right: 1px solid #ccc;">
                                            <div style="height: 1.2cm;"></div>
                                            <div class="signature-line">2. ______</div>
                                        </td>
                                        <td class="signature-cell" style="width: 25%; border-right: 1px solid #ccc;">
                                            <div style="height: 1.2cm;"></div>
                                            <div class="signature-line">3. ______</div>
                                        </td>
                                        <td class="signature-cell" style="width: 25%;">
                                            <div style="height: 1.2cm;"></div>
                                            <div class="signature-line">4. ______</div>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                @endif
            @endfor
        </div>
    @endfor
</div>
@endsection
