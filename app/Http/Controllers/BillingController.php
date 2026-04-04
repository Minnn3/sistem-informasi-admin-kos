<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use App\Models\Tenant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class BillingController extends Controller
{
    public function index()
    {
        $billings = Billing::with(['tenant', 'room'])->orderBy('due_date', 'desc')->get();
        return view('billings.index', compact('billings'));
    }

    public function generate(Request $request)
    {
        $activeTenants = Tenant::with('room')->where('status', 'active')->get();
        $targetMonth = Carbon::now()->month;
        $targetYear = Carbon::now()->year;
        $generatedCount = 0;

        foreach ($activeTenants as $tenant) {
            $entryDate = Carbon::parse($tenant->entry_date);
            
            // Tanggal jatuh tempo adalah tanggal yang sama dengan entry_date tapi untuk bulan ini
            // Gunakan min() untuk menghindari error jika bulan ini tidak memiliki tanggal yang sama (misal 31 Feb)
            $day = min($entryDate->day, Carbon::create($targetYear, $targetMonth)->daysInMonth);
            $dueDate = Carbon::create($targetYear, $targetMonth, $day);

            // Cek apakah tagihan untuk tenant ini dengan due_date yang sama sudah digenerate
            $exists = Billing::where('tenant_id', $tenant->id)
                ->whereDate('due_date', $dueDate)
                ->exists();

            if (!$exists) {
                Billing::create([
                    'tenant_id' => $tenant->id,
                    'room_id' => $tenant->room_id,
                    'amount' => $tenant->room->price,
                    'due_date' => $dueDate,
                    'status' => 'pending'
                ]);
                $generatedCount++;
            }
        }

        return redirect()->back()->with('success', "$generatedCount tagihan baru untuk bulan ini berhasil di-generate.");
    }

    public function markAsPaid(Request $request, Billing $billing)
    {
        $data = [
            'status' => 'paid',
            'paid_at' => Carbon::now()
        ];
        
        if ($request->hasFile('proof_of_payment')) {
            $path = $request->file('proof_of_payment')->store('proofs', 'public');
            $data['proof_of_payment'] = $path;
        }

        $billing->update($data);

        return redirect()->back()->with('success', 'Tagihan berhasil ditandai Lunas.');
    }

    public function invoice(Billing $billing)
    {
        $billing->load(['tenant', 'room']);
        $pdf = Pdf::loadView('billings.invoice', compact('billing'));
        return $pdf->stream('Invoice-Kos-' . str_pad($billing->id, 5, '0', STR_PAD_LEFT) . '.pdf');
    }
}
