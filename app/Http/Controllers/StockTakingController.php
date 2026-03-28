<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStockTakingDetailRequest;
use App\Models\Item;
use App\Models\StockTakingSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockTakingController extends Controller
{
    /**
     * Display user's stock taking sessions.
     */
    public function index()
    {
        $sessions = Auth::user()
            ->stockTakingSessions()
            ->latest()
            ->paginate(15);

        return view('stock-taking.index', compact('sessions'));
    }

    /**
     * Show stock taking form.
     */
    public function show(StockTakingSession $session)
    {
        if ($session->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $session->load('details.item');

        $items = Item::where('category', $session->category)->get();

        return view('stock-taking.show', compact('session', 'items'));
    }

    /**
     * Start stock taking session.
     */
    public function start(StockTakingSession $session)
    {
        if ($session->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($session->status !== 'pending') {
            return redirect()->route('stock-taking.show', $session)
                ->with('error', 'Sesi ini sudah dimulai atau completed.');
        }

        $session->update([
            'status' => 'in_progress',
            'started_at' => now(),
        ]);

        return redirect()->route('stock-taking.show', $session)
            ->with('success', 'Sesi stock taking dimulai.');
    }

    /**
     * Get items by category (AJAX).
     */
    public function getItems(Request $request)
    {
        $items = Item::where('category', $request->category)->get();

        return response()->json($items);
    }

    /**
     * Save single stock taking detail.
     */
    public function saveDetail(Request $request, StockTakingSession $session)
    {
        if ($session->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($session->status !== 'in_progress') {
            return response()->json([
                'success' => false,
                'message' => 'Sesi harus dalam status In Progress.'
            ], 400);
        }

        $request->validate([
            'tag_number' => ['required', 'string', 'max:50'],
            'item_id' => ['required', 'exists:items,id'],
            'actual_quantity' => ['required', 'numeric', 'min:0'],
            'remarks' => ['nullable', 'string'],
        ]);

        $detail = $session->details()->create([
            'tag_number' => $request->tag_number,
            'item_id' => $request->item_id,
            'actual_quantity' => $request->actual_quantity,
            'remarks' => $request->remarks,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Item berhasil disimpan.',
            'detail' => $detail->load('item')
        ]);
    }

    /**
     * Complete stock taking session.
     */
    public function complete(StockTakingSession $session)
    {
        if ($session->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($session->status !== 'in_progress') {
            return redirect()->route('stock-taking.show', $session)
                ->with('error', 'Sesi harus dalam status In Progress.');
        }

        if ($session->details()->count() === 0) {
            return redirect()->route('stock-taking.show', $session)
                ->with('error', 'Minimal harus ada 1 item yang diinput sebelum complete.');
        }

        $session->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return redirect()->route('stock-taking.index')
            ->with('success', 'Stock taking berhasil diselesaikan.');
    }
}
