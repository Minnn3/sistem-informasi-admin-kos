<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Billing;
use App\Models\Expense;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);

        // Calculate Data
        $billings = Billing::whereMonth('paid_at', $month)
                           ->whereYear('paid_at', $year)
                           ->where('status', 'paid')
                           ->get();
        $expenses = Expense::whereMonth('expense_date', $month)
                           ->whereYear('expense_date', $year)
                           ->get();

        $totalIncome = $billings->sum('amount');
        $totalExpense = $expenses->sum('amount');
        $netIncome = $totalIncome - $totalExpense;

        return view('reports.index', compact('month', 'year', 'billings', 'expenses', 'totalIncome', 'totalExpense', 'netIncome'));
    }

    public function export(Request $request)
    {
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);
        $monthName = Carbon::createFromDate($year, $month, 1)->translatedFormat('F');

        $billings = Billing::with('room')->whereMonth('paid_at', $month)
                           ->whereYear('paid_at', $year)
                           ->where('status', 'paid')
                           ->get();
        $expenses = Expense::whereMonth('expense_date', $month)
                           ->whereYear('expense_date', $year)
                           ->get();

        $fileName = "Laporan_Kos_{$monthName}_{$year}.csv";

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($billings, $expenses, $monthName, $year) {
            $file = fopen('php://output', 'w');
            
            // Format Excel CSV Header (untuk mengatasi isu delimiter koma/titik koma)
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM UTF-8
            
            fputcsv($file, ["LAPORAN KEUANGAN KOS - $monthName $year"]);
            fputcsv($file, [""]);
            
            // === BAGIAN PEMASUKAN ===
            fputcsv($file, ["PEMASUKAN"]);
            fputcsv($file, ["Tanggal", "Kamar", "Penyewa", "Nominal"]);
            $totalIncome = 0;
            foreach ($billings as $bill) {
                fputcsv($file, [
                    Carbon::parse($bill->paid_at)->format('d-m-Y'),
                    $bill->room->room_number ?? '-',
                    $bill->tenant->name ?? '-',
                    $bill->amount
                ]);
                $totalIncome += $bill->amount;
            }
            fputcsv($file, ["", "", "TOTAL PEMASUKAN", $totalIncome]);
            fputcsv($file, [""]);
            
            // === BAGIAN PENGELUARAN ===
            fputcsv($file, ["PENGELUARAN"]);
            fputcsv($file, ["Tanggal", "Deskripsi", "", "Nominal"]);
            $totalExpense = 0;
            foreach ($expenses as $exp) {
                fputcsv($file, [
                    Carbon::parse($exp->expense_date)->format('d-m-Y'),
                    $exp->description,
                    "",
                    $exp->amount
                ]);
                $totalExpense += $exp->amount;
            }
            fputcsv($file, ["", "", "TOTAL PENGELUARAN", $totalExpense]);
            fputcsv($file, [""]);
            
            // === RINGKASAN ===
            fputcsv($file, ["RINGKASAN"]);
            fputcsv($file, ["Laba Bersih", "", "", $totalIncome - $totalExpense]);

            fclose($file);
        };

        return response()->streamDownload($callback, $fileName, $headers);
    }
}
