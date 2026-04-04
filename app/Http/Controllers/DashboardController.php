<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Billing;
use App\Models\Expense;
use App\Models\Todo;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalRooms = Room::count();
        $occupiedRooms = Room::where('status', 'occupied')->count();
        $availableRooms = Room::where('status', 'available')->count();
        $maintenanceRooms = Room::where('status', 'maintenance')->count();

        // Kalkulasi Keuangan Bulan Ini
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $totalIncome = Billing::where('status', 'paid')
            ->whereBetween('paid_at', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        $totalExpenses = Expense::whereBetween('expense_date', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        $netProfit = $totalIncome - $totalExpenses;

        // Pending Billings (Penunggak dan Jatuh Tempo)
        $pendingBillings = Billing::with(['tenant', 'room'])
            ->where('status', 'pending')
            ->orderBy('due_date', 'asc')
            ->get();

        // Todos List
        $todos = Todo::orderBy('status', 'asc')
                     ->orderBy('created_at', 'desc')
                     ->get();

        $roomsGrid = Room::all();

        return view('dashboard', compact(
            'totalRooms', 'occupiedRooms', 'availableRooms', 'maintenanceRooms',
            'totalIncome', 'totalExpenses', 'netProfit',
            'pendingBillings', 'todos', 'roomsGrid'
        ));
    }
}
