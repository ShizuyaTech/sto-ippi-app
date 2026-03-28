@extends('layouts.app')

@section('title', 'Stock Taking Form')

@section('content')
<div class="card">
    <h1 class="card-header">📝 Stock Taking Form</h1>
    
    <div style="background: white; border-radius: 8px; padding: 1rem; margin: 1rem 0; border-left: 4px solid #dc3545;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
            <div>
                <strong style="color: #6c757d; display: block; margin-bottom: 0.25rem;">Session Code</strong>
                <span>{{ $session->session_code }}</span>
            </div>
            <div>
                <strong style="color: #6c757d; display: block; margin-bottom: 0.25rem;">Category</strong>
                <span class="badge">{{ $session->category_label }}</span>
            </div>
            <div>
                <strong style="color: #6c757d; display: block; margin-bottom: 0.25rem;">Status</strong>
                <span class="badge badge-{{ $session->status }}">{{ $session->status_label }}</span>
            </div>
            <div>
                <strong style="color: #6c757d; display: block; margin-bottom: 0.25rem;">Scheduled Date</strong>
                <span>{{ $session->scheduled_date->format('d M Y') }}</span>
            </div>
        </div>
    </div>

    @if($session->status === 'pending')
        <form method="POST" action="{{ route('stock-taking.start', $session) }}" style="margin-top: 1rem;">
            @csrf
            <button type="submit" class="btn btn-success" style="width: 100%; max-width: 300px;">▶️ Start Stock Taking</button>
        </form>
    @elseif($session->status === 'in_progress')
        <h3 style="margin-top: 1.5rem; margin-bottom: 1rem;">📥 Input Stock Taking</h3>
        
        <form id="stockTakingForm" style="margin-bottom: 2rem; background: rgba(220, 53, 69, 0.05); padding: 1.5rem; border-radius: 12px; border: 2px solid #dc3545;">
            @csrf
            
            <div class="form-group">
                <label>
                    🏷️ Tag Number * 
                    <span id="scanStatus" style="color: #28a745; font-size: 0.875rem; font-weight: bold;"></span>
                </label>
                <div style="display: flex; gap: 0.5rem; align-items: stretch; flex-wrap: wrap;">
                    <input 
                        type="text" 
                        name="tag_number" 
                        id="tag_number" 
                        class="form-control"
                        placeholder="Klik tombol Scan atau ketik manual" 
                        style="flex: 1; min-width: 200px; font-family: 'Courier New', monospace; font-size: 1.1rem; font-weight: bold; letter-spacing: 1px; text-transform: uppercase; border: 2px solid #dc3545; background: #fff5f5;"
                        autocomplete="off"
                        required>
                    <button 
                        type="button" 
                        id="openCameraBtn" 
                        style="background: #dc3545; color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 6px; cursor: pointer; font-weight: bold; white-space: nowrap; display: flex; align-items: center; gap: 0.5rem; box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);">
                        📱 Scan
                    </button>
                </div>
            </div>
            
            <!-- Camera Scanner Modal -->
            <div id="cameraModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.95); z-index: 9999; justify-content: center; align-items: center; padding: 1rem;">
                <div style="background: white; border-radius: 16px; padding: 1.5rem; max-width: 600px; width: 100%; max-height: 90vh; overflow-y: auto;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; flex-wrap: wrap; gap: 0.5rem;">
                        <h3 style="margin: 0; color: #333;">📱 Scan Barcode / QR Code</h3>
                        <button id="closeCameraBtn" type="button" style="background: #dc3545; color: white; border: none; padding: 0.5rem 1rem; border-radius: 6px; cursor: pointer; font-weight: bold; font-size: 1rem;">✕ Tutup</button>
                    </div>
                    
                    <div style="background: rgba(220, 53, 69, 0.1); padding: 1rem; border-radius: 8px; margin-bottom: 1rem; border-left: 4px solid #dc3545;">
                        <div style="margin: 0; font-size: 0.9rem; color: #555; line-height: 1.6;">
                            <strong style="color: #dc3545;">📌 Panduan:</strong><br>
                            1️⃣ Izinkan akses kamera<br>
                            2️⃣ Arahkan kamera ke barcode atau QR code<br>
                            3️⃣ Jaga jarak 10-20cm<br>
                            4️⃣ Tunggu deteksi otomatis (1-2 detik)
                        </div>
                    </div>
                    
                    <div id="cameraReader" style="width: 100%; min-height: 250px; border-radius: 8px; overflow: hidden; background: #000; border: 3px solid #dc3545;"></div>
                    
                    <div id="cameraStatus" style="margin-top: 1rem; padding: 0.75rem; background: #e9ecef; border-radius: 6px; text-align: center; font-weight: bold; font-size: 0.9rem;">
                        ⏳ Memuat kamera...
                    </div>
                    
                    <div style="margin-top: 1rem; font-size: 0.8rem; color: #6c757d; text-align: center;">
                        💡 Tip: Pastikan barcode/QR tidak tertutup dan pencahayaan cukup
                    </div>
                </div>
            </div>
            <div class="form-group"
                 style="position: relative;"
                 id="itemSearchWrapper"
                 x-data="{
                    query: '',
                    selectedId: '',
                    open: false,
                    items: @js($items->map(fn($i) => ['id' => $i->id, 'code' => $i->code, 'name' => $i->name])->values()),
                    get filtered() {
                        if (!this.query) return this.items.slice(0, 60);
                        const q = this.query.toLowerCase();
                        return this.items.filter(i =>
                            i.code.toLowerCase().includes(q) || i.name.toLowerCase().includes(q)
                        ).slice(0, 60);
                    },
                    select(item) {
                        this.selectedId = item.id;
                        this.query = item.code + ' - ' + item.name;
                        this.open = false;
                    },
                    reset() {
                        this.query = '';
                        this.selectedId = '';
                        this.open = false;
                    }
                 }"
                 x-on:click.outside="open = false"
                 x-on:item-search-reset.window="reset()"
            >
                <label>Item *</label>
                <input type="hidden" name="item_id" :value="selectedId" id="item_id">
                <input
                    type="text"
                    id="item_search_input"
                    x-model="query"
                    x-on:input="open = true; selectedId = ''"
                    x-on:focus="open = true"
                    class="form-control"
                    placeholder="Ketik kode atau nama item untuk mencari..."
                    autocomplete="off"
                >
                <div
                    x-show="open && filtered.length > 0"
                    style="position: absolute; z-index: 1000; background: white; border: 2px solid #dc3545; border-radius: 8px; max-height: 260px; overflow-y: auto; width: 100%; box-shadow: 0 8px 24px rgba(0,0,0,0.12); margin-top: 2px;"
                >
                    <template x-for="item in filtered" :key="item.id">
                        <div
                            x-on:mousedown.prevent="select(item)"
                            style="padding: 0.625rem 1rem; cursor: pointer; border-bottom: 1px solid #f0f0f0; display: flex; gap: 0.5rem;"
                            x-on:mouseenter="$el.style.background = '#fff5f5'"
                            x-on:mouseleave="$el.style.background = 'white'"
                        >
                            <span style="font-weight: 700; color: #dc3545; white-space: nowrap;" x-text="item.code"></span>
                            <span style="color: #4a5568;" x-text="item.name"></span>
                        </div>
                    </template>
                </div>
                <p style="font-size: 0.8rem; color: #6c757d; margin-top: 0.25rem;">Ketik kode atau nama item untuk mencari</p>
                @error('item_id')
                    <span style="color: #e74c3c; font-size: 0.875rem; display: block; margin-top: 0.25rem;">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label>Actual Quantity *</label>
                <input type="number" step="0.01" name="actual_quantity" id="actual_quantity" class="form-control" placeholder="0.00" required>
            </div>
            <div class="form-group">
                <label>Remarks</label>
                <input type="text" name="remarks" id="remarks" class="form-control" placeholder="Catatan tambahan (opsional)">
            </div>

            <div style="margin-top: 1.5rem; display: flex; gap: 0.5rem; flex-wrap: wrap;">
                <button type="submit" class="btn btn-success" id="saveBtn" style="flex: 1; min-width: 150px;">💾 Save Item</button>
                <a href="{{ route('stock-taking.index') }}" class="btn btn-secondary" style="flex: 1; min-width: 150px;">← Back</a>
            </div>
        </form>

        <div id="alertContainer"></div>

        <!-- List item yang sudah diinput -->
        <h3 style="margin-bottom: 1rem;">📋 Items yang Sudah Diinput</h3>
        
        <!-- Desktop Table View -->
        <div class="table-responsive desktop-table">
            <table id="itemsTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tag Number</th>
                        <th>Item Code</th>
                        <th>Item Name</th>
                        <th>Actual Qty</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody id="itemsTableBody">
                    @forelse($session->details as $index => $detail)
                    <tr>
                        <td><strong>{{ $index + 1 }}</strong></td>
                        <td><code>{{ $detail->tag_number }}</code></td>
                        <td>{{ $detail->item->code }}</td>
                        <td>{{ $detail->item->name }}</td>
                        <td>{{ number_format($detail->actual_quantity, 2) }}</td>
                        <td>{{ $detail->remarks ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr id="emptyRow">
                        <td colspan="6" style="text-align: center; color: #999;">Belum ada item yang diinput</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Mobile Card View -->
        <div class="mobile-cards" id="mobileCardsContainer">
            @forelse($session->details as $index => $detail)
            <div class="mobile-card">
                <div class="mobile-card-header">
                    <strong>#{{ $index + 1 }} - {{ $detail->item->code }}</strong>
                    <span class="badge">{{ $detail->item->category_label }}</span>
                </div>
                <div class="mobile-card-body">
                    <div class="mobile-card-row">
                        <span class="mobile-label">Tag Number:</span>
                        <code>{{ $detail->tag_number }}</code>
                    </div>
                    <div class="mobile-card-row">
                        <span class="mobile-label">Item:</span>
                        <span>{{ $detail->item->name }}</span>
                    </div>
                    <div class="mobile-card-row">
                        <span class="mobile-label">Quantity:</span>
                        <span style="font-weight: bold; color: #667eea;">{{ number_format($detail->actual_quantity, 2) }}</span>
                    </div>
                    <div class="mobile-card-row">
                        <span class="mobile-label">Remarks:</span>
                        <span>{{ $detail->remarks ?? '-' }}</span>
                    </div>
                </div>
            </div>
            @empty
            <div id="emptyMobileCard" style="text-align: center; padding: 2rem; color: #999;">
                📭 Belum ada item yang diinput
            </div>
            @endforelse
        </div>

        <!-- Button Complete Session -->
        <form method="POST" action="{{ route('stock-taking.complete', $session) }}" style="margin-top: 1.5rem;" id="completeForm">
            @csrf
            <button type="submit" class="btn btn-primary" id="completeBtn" style="width: 100%; max-width: 400px;">✅ Complete Stock Taking</button>
        </form>
    @else
        <h3 style="margin-bottom: 1rem;">📊 Stock Taking Details</h3>
        
        <!-- Desktop Table View -->
        <div class="table-responsive desktop-table">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tag Number</th>
                        <th>Item Code</th>
                        <th>Item Name</th>
                        <th>Actual Qty</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($session->details as $index => $detail)
                    <tr>
                        <td><strong>{{ $index + 1 }}</strong></td>
                        <td><code>{{ $detail->tag_number }}</code></td>
                        <td>{{ $detail->item->code }}</td>
                        <td>{{ $detail->item->name }}</td>
                        <td>{{ number_format($detail->actual_quantity, 2) }}</td>
                        <td>{{ $detail->remarks ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Mobile Card View -->
        <div class="mobile-cards">
            @foreach($session->details as $index => $detail)
            <div class="mobile-card">
                <div class="mobile-card-header">
                    <strong>#{{ $index + 1 }} - {{ $detail->item->code }}</strong>
                    <span class="badge">{{ $detail->item->category_label }}</span>
                </div>
                <div class="mobile-card-body">
                    <div class="mobile-card-row">
                        <span class="mobile-label">Tag Number:</span>
                        <code>{{ $detail->tag_number }}</code>
                    </div>
                    <div class="mobile-card-row">
                        <span class="mobile-label">Item:</span>
                        <span>{{ $detail->item->name }}</span>
                    </div>
                    <div class="mobile-card-row">
                        <span class="mobile-label">Quantity:</span>
                        <span style="font-weight: bold; color: #dc3545;">{{ number_format($detail->actual_quantity, 2) }}</span>
                    </div>
                    <div class="mobile-card-row">
                        <span class="mobile-label">Remarks:</span>
                        <span>{{ $detail->remarks ?? '-' }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div style="margin-top: 1rem;">
            <a href="{{ route('stock-taking.index') }}" class="btn btn-secondary">← Back</a>
        </div>
    @endif
</div>

@push('scripts')
<!-- html5-qrcode Library for Camera Barcode & QR Code Scanning -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('stockTakingForm');
    const saveBtn = document.getElementById('saveBtn');
    const completeBtn = document.getElementById('completeBtn');
    const alertContainer = document.getElementById('alertContainer');
    const tableBody = document.getElementById('itemsTableBody');
    const emptyRow = document.getElementById('emptyRow');
    const mobileCardsContainer = document.getElementById('mobileCardsContainer');
    const emptyMobileCard = document.getElementById('emptyMobileCard');
    const tagNumberInput = document.getElementById('tag_number');
    const scanStatus = document.getElementById('scanStatus');
    
    // Camera Scanner Elements
    const openCameraBtn = document.getElementById('openCameraBtn');
    const cameraModal = document.getElementById('cameraModal');
    const closeCameraBtn = document.getElementById('closeCameraBtn');
    const cameraStatus = document.getElementById('cameraStatus');
    let html5QrCode = null;
    let cameraStarted = false;
    
    // Auto uppercase for manual input
    if (tagNumberInput) {
        tagNumberInput.addEventListener('input', function(e) {
            this.value = this.value.toUpperCase();
        });
    }
    
    // Open Camera Modal
    if (openCameraBtn) {
        openCameraBtn.addEventListener('click', function() {
            cameraModal.style.display = 'flex';
            startCamera();
        });
    }
    
    // Close Camera Modal
    if (closeCameraBtn) {
        closeCameraBtn.addEventListener('click', function() {
            stopCamera();
            cameraModal.style.display = 'none';
        });
    }
    
    // Start Camera Scanner (Support Barcode AND QR Code)
    function startCamera() {
        if (cameraStarted) return;
        
        // Check if site is secure (HTTPS or localhost)
        const isSecure = window.location.protocol === 'https:' || 
                        window.location.hostname === 'localhost' || 
                        window.location.hostname === '127.0.0.1';
        
        if (!isSecure) {
            cameraStatus.textContent = '⚠️ Perlu HTTPS! Akses kamera hanya tersedia melalui HTTPS atau localhost';
            cameraStatus.style.background = '#fff3cd';
            cameraStatus.style.color = '#856404';
            
            // Show detailed instructions
            setTimeout(() => {
                cameraStatus.innerHTML = `
                    ⚠️ <strong>Error: Butuh Koneksi Aman</strong><br>
                    <small>Chrome memerlukan HTTPS untuk akses kamera.<br>
                    Solusi: Gunakan ngrok atau akses via localhost.<br>
                    Atau gunakan input manual sementara.</small>
                `;
            }, 1000);
            
            showAlert('error', 'Akses kamera memerlukan HTTPS. Gunakan input manual atau setup ngrok.');
            return;
        }
        
        cameraStatus.textContent = 'Meminta izin akses kamera...';
        cameraStatus.style.background = '#fff3cd';
        cameraStatus.style.color = '#856404';
        
        // Check if browser supports getUserMedia
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            cameraStatus.textContent = '❌ Browser tidak support akses kamera';
            cameraStatus.style.background = '#f8d7da';
            cameraStatus.style.color = '#721c24';
            showAlert('error', 'Browser Anda tidak mendukung akses kamera. Gunakan Chrome/Safari versi terbaru.');
            return;
        }
        
        html5QrCode = new Html5Qrcode("cameraReader");
        
        const config = {
            fps: 10,
            qrbox: { width: 250, height: 150 },
            aspectRatio: 1.777778,
            formatsToSupport: [
                Html5QrcodeSupportedFormats.QR_CODE,
                Html5QrcodeSupportedFormats.CODE_128,
                Html5QrcodeSupportedFormats.CODE_39,
                Html5QrcodeSupportedFormats.EAN_13,
                Html5QrcodeSupportedFormats.EAN_8,
                Html5QrcodeSupportedFormats.UPC_A,
                Html5QrcodeSupportedFormats.UPC_E
            ]
        };
        
        html5QrCode.start(
            { facingMode: "environment" }, // Use back camera
            config,
            (decodedText, decodedResult) => {
                // Success callback - barcode/QR detected!
                console.log('Barcode/QR detected:', decodedText);
                
                // Fill the tag number input
                tagNumberInput.value = decodedText.toUpperCase();
                
                // Show success feedback
                tagNumberInput.style.borderColor = '#dc3545';
                tagNumberInput.style.background = '#f8d7da';
                if (scanStatus) {
                    scanStatus.textContent = '✅ Scan Berhasil!';
                }
                
                // Vibrate if supported
                if (navigator.vibrate) {
                    navigator.vibrate([200, 100, 200]);
                }
                
                // Show success alert
                const codeType = decodedResult.result.format ? decodedResult.result.format.formatName : 'Code';
                showAlert('success', `✅ ${codeType} berhasil di-scan: ${decodedText}`);
                
                // Stop camera and close modal
                stopCamera();
                cameraModal.style.display = 'none';
                
                // Move focus to item search input
                const itemSearchInput = document.getElementById('item_search_input');
                if (itemSearchInput) {
                    setTimeout(() => {
                        itemSearchInput.focus();
                    }, 300);
                }
                
                // Reset visual feedback after 2 seconds
                setTimeout(() => {
                    tagNumberInput.style.borderColor = '#dc3545';
                    tagNumberInput.style.background = '#fff5f5';
                    if (scanStatus) {
                        scanStatus.textContent = '';
                    }
                }, 2000);
            },
            (errorMessage) => {
                // Error callback - no barcode detected (this is normal, just keep scanning)
            }
        ).then(() => {
            cameraStarted = true;
            cameraStatus.textContent = '📷 Kamera aktif - Arahkan ke barcode atau QR code';
            cameraStatus.style.background = '#d4edda';
            cameraStatus.style.color = '#155724';
        }).catch((err) => {
            cameraStarted = false;
            console.error('Camera error:', err);
            
            let errorMsg = '❌ ';
            let solution = '';
            
            if (err.name === 'NotAllowedError' || err.toString().includes('Permission denied')) {
                errorMsg += 'Izin akses kamera ditolak';
                solution = 'Klik icon 🔒 di address bar → Site Settings → Camera → Allow';
            } else if (err.name === 'NotFoundError') {
                errorMsg += 'Kamera tidak ditemukan';
                solution = 'Pastikan HP memiliki kamera dan tidak digunakan aplikasi lain';
            } else if (err.name === 'NotReadableError') {
                errorMsg += 'Kamera sedang digunakan aplikasi lain';
                solution = 'Tutup aplikasi lain yang menggunakan kamera';
            } else if (err.name === 'NotSupportedError' || err.toString().includes('Only secure')) {
                errorMsg += 'Butuh HTTPS untuk akses kamera';
                solution = 'Gunakan ngrok atau akses via localhost. Atau ketik manual.';
            } else {
                errorMsg += err.toString();
                solution = 'Cek setting browser → Camera permission';
            }
            
            cameraStatus.innerHTML = `${errorMsg}<br><small style="font-weight: normal;">${solution}</small>`;
            cameraStatus.style.background = '#f8d7da';
            cameraStatus.style.color = '#721c24';
            
            showAlert('error', errorMsg + '. ' + solution);
        });
    }
    
    // Stop Camera Scanner
    function stopCamera() {
        if (html5QrCode && cameraStarted) {
            html5QrCode.stop().then(() => {
                cameraStarted = false;
                console.log('Camera stopped');
            }).catch((err) => {
                console.error('Error stopping camera:', err);
            });
        }
    }

    if (form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Disable button to prevent double submit
            saveBtn.disabled = true;
            saveBtn.textContent = 'Saving...';

            const formData = new FormData(form);
            
            try {
                const response = await fetch('{{ route("stock-taking.save-detail", $session) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    // Show success message
                    showAlert('success', data.message);

                    // Remove empty state if exists
                    if (emptyRow) {
                        emptyRow.remove();
                    }
                    if (emptyMobileCard) {
                        emptyMobileCard.remove();
                    }

                    // Calculate new row number
                    const rowCount = tableBody.getElementsByTagName('tr').length + 1;

                    // Add new row to DESKTOP table
                    const newRow = document.createElement('tr');
                    newRow.innerHTML = `
                        <td><strong>${rowCount}</strong></td>
                        <td><code>${data.detail.tag_number}</code></td>
                        <td>${data.detail.item.code}</td>
                        <td>${data.detail.item.name}</td>
                        <td>${parseFloat(data.detail.actual_quantity).toFixed(2)}</td>
                        <td>${data.detail.remarks || '-'}</td>
                    `;
                    tableBody.appendChild(newRow);

                    // Add new card to MOBILE view
                    const newCard = document.createElement('div');
                    newCard.className = 'mobile-card';
                    newCard.innerHTML = `
                        <div class="mobile-card-header">
                            <strong>#${rowCount} - ${data.detail.item.code}</strong>
                            <span class="badge">${data.detail.item.category_label}</span>
                        </div>
                        <div class="mobile-card-body">
                            <div class="mobile-card-row">
                                <span class="mobile-label">Tag Number:</span>
                                <code>${data.detail.tag_number}</code>
                            </div>
                            <div class="mobile-card-row">
                                <span class="mobile-label">Item:</span>
                                <span>${data.detail.item.name}</span>
                            </div>
                            <div class="mobile-card-row">
                                <span class="mobile-label">Quantity:</span>
                                <span style="font-weight: bold; color: #667eea;">${parseFloat(data.detail.actual_quantity).toFixed(2)}</span>
                            </div>
                            <div class="mobile-card-row">
                                <span class="mobile-label">Remarks:</span>
                                <span>${data.detail.remarks || '-'}</span>
                            </div>
                        </div>
                    `;
                    if (mobileCardsContainer) {
                        mobileCardsContainer.appendChild(newCard);
                    }

                    // Reset form
                    form.reset();
                    window.dispatchEvent(new CustomEvent('item-search-reset'));
                    
                    // Re-focus on tag number for next scan
                    if (tagNumberInput) {
                        setTimeout(() => {
                            tagNumberInput.focus();
                        }, 100);
                    }

                    // Enable complete button if there are items
                    if (completeBtn) {
                        completeBtn.disabled = false;
                    }
                } else {
                    showAlert('error', data.message);
                }
            } catch (error) {
                showAlert('error', 'Terjadi kesalahan saat menyimpan data.');
                console.error('Error:', error);
            } finally {
                // Re-enable button
                saveBtn.disabled = false;
                saveBtn.textContent = 'Save Item';
            }
        });
    }

    function showAlert(type, message) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-error';
        const alertHtml = `<div class="alert ${alertClass}">${message}</div>`;
        alertContainer.innerHTML = alertHtml;

        // Auto hide after 3 seconds
        setTimeout(() => {
            alertContainer.innerHTML = '';
        }, 3000);
    }
});
</script>
@endpush
@endsection
