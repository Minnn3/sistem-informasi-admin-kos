<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::orderBy('expense_date', 'desc')->get();
        return view('expenses.index', compact('expenses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date'
        ]);

        Expense::create($validated);
        return redirect()->back()->with('success', 'Pengeluaran berhasil dicatat.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->back()->with('success', 'Catatan pengeluaran dihapus.');
    }
}
