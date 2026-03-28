<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStockTakingSessionRequest;
use App\Models\StockTakingSession;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StockTakingSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = StockTakingSession::with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $sessions = $query->latest()->paginate(15);

        return view('admin.sessions.index', compact('sessions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::query()->where('role', 'user')->get();
        
        return view('admin.sessions.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStockTakingSessionRequest $request)
    {
        $sessionCode = 'STO-' . now()->format('Ymd') . '-' . str_pad(StockTakingSession::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);

        StockTakingSession::create([
            'session_code' => $sessionCode,
            'user_id' => $request->user_id,
            'category' => $request->category,
            'scheduled_date' => $request->scheduled_date,
            'notes' => $request->notes,
            'status' => 'pending',
        ]);

        return redirect()->route('admin.sessions.index')
            ->with('success', 'Sesi stock taking berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(StockTakingSession $session)
    {
        $session->load(['user', 'details.item']);

        return view('admin.sessions.show', compact('session'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StockTakingSession $session)
    {
        $users = User::query()->where('role', 'user')->get();

        return view('admin.sessions.edit', compact('session', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreStockTakingSessionRequest $request, StockTakingSession $session)
    {
        $session->update($request->validated());

        return redirect()->route('admin.sessions.index')
            ->with('success', 'Sesi stock taking berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StockTakingSession $session)
    {
        $session->delete();

        return redirect()->route('admin.sessions.index')
            ->with('success', 'Sesi stock taking berhasil dihapus.');
    }

    /**
     * Export the specified session to Excel.
     */
    public function export(StockTakingSession $session)
    {
        $session->load(['user', 'details.item']);

        $fileName = 'STO_' . $session->session_code . '_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new SessionExport($session), $fileName);
    }
}

class SessionExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $session;
    protected $rowNumber = 0;

    public function __construct(StockTakingSession $session)
    {
        $this->session = $session;
    }

    public function collection()
    {
        return $this->session->details;
    }

    public function headings(): array
    {
        return [
            ['STOCK TAKING OPNAME REPORT'],
            ['Session Code: ' . $this->session->session_code],
            ['User: ' . $this->session->user->name],
            ['Category: ' . $this->session->category_label],
            ['Status: ' . $this->session->status_label],
            ['Scheduled Date: ' . $this->session->scheduled_date->format('d M Y')],
            ['Export Date: ' . now()->format('d M Y H:i:s')],
            [''],
            ['No', 'Tag Number', 'Item Code', 'Item Name', 'Category', 'Actual Quantity', 'Remarks'],
        ];
    }

    public function map($detail): array
    {
        $this->rowNumber++;
        
        return [
            $this->rowNumber,
            $detail->tag_number,
            $detail->item->code,
            $detail->item->name,
            $detail->item->category_label,
            $detail->actual_quantity,
            $detail->remarks ?? '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Title row
        $sheet->mergeCells('A1:G1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
        
        // Info rows
        $sheet->getStyle('A2:A7')->getFont()->setBold(true);
        
        // Header row
        $sheet->getStyle('A9:G9')->getFont()->setBold(true);
        $sheet->getStyle('A9:G9')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFDC3545');
        $sheet->getStyle('A9:G9')->getFont()->getColor()->setARGB('FFFFFFFF');
        
        // Auto-size columns
        foreach (range('A', 'G') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
        
        // Borders for data rows
        $lastRow = $this->rowNumber + 9;
        $sheet->getStyle("A9:G{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ]);
        
        return [];
    }
}
