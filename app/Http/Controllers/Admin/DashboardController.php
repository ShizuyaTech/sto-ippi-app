<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\StockTakingSession;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display admin dashboard.
     */
    public function index()
    {
        $totalUsers = User::query()->where('role', 'user')->count();
        $totalItems = Item::count();
        $pendingSessions = StockTakingSession::where('status', 'pending')->count();
        $inProgressSessions = StockTakingSession::where('status', 'in_progress')->count();
        $completedSessions = StockTakingSession::where('status', 'completed')->count();
        
        $recentSessions = StockTakingSession::with('user')
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalItems',
            'pendingSessions',
            'inProgressSessions',
            'completedSessions',
            'recentSessions'
        ));
    }
}
