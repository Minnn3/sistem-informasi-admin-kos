<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\Room;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function index(Request $request)
    {
        $query = Tenant::with('room')->latest();

        if ($request->has('search') && $request->search != '') {
            $search = strtolower($request->search);
            
            $statusSearch = $search;
            if (str_contains('aktif', $search)) $statusSearch = 'active';
            elseif (str_contains('nonaktif', $search) || str_contains('keluar', $search)) $statusSearch = 'inactive';

            $query->where(function($q) use ($search, $statusSearch) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('id_card_number', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$statusSearch}%")
                  ->orWhereHas('room', function($r) use ($search) {
                      $r->where('room_number', 'like', "%{$search}%");
                  });
            });
        }

        $tenants = $query->paginate(10)->withQueryString();
        return view('tenants.index', compact('tenants'));
    }

    public function create()
    {
        $rooms = Room::where('status', 'available')->get();
        return view('tenants.create', compact('rooms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'id_card_number' => 'required|string|unique:tenants,id_card_number',
            'phone' => 'required|string',
            'emergency_contact' => 'nullable|string',
            'room_id' => 'required|exists:rooms,id',
            'entry_date' => 'required|date',
            'status' => 'required|in:active,inactive',
        ]);

        $tenant = Tenant::create($validated);

        // Update room status
        if ($tenant->status === 'active') {
            $tenant->room->update(['status' => 'occupied']);
        }

        return redirect()->route('tenants.index')->with('success', 'Penyewa berhasil ditambahkan.');
    }

    public function show(Tenant $tenant)
    {
        return view('tenants.show', compact('tenant'));
    }

    public function edit(Tenant $tenant)
    {
        $rooms = Room::where('status', 'available')->orWhere('id', $tenant->room_id)->get();
        return view('tenants.edit', compact('tenant', 'rooms'));
    }

    public function update(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'id_card_number' => 'required|string|unique:tenants,id_card_number,' . $tenant->id,
            'phone' => 'required|string',
            'emergency_contact' => 'nullable|string',
            'room_id' => 'required|exists:rooms,id',
            'entry_date' => 'required|date',
            'status' => 'required|in:active,inactive',
        ]);

        $oldRoomId = $tenant->room_id;
        $tenant->update($validated);

        // Update room statuses if room changed or status changed
        if ($oldRoomId != $tenant->room_id || $tenant->wasChanged('status')) {
            if ($oldRoomId != $tenant->room_id) {
                Room::find($oldRoomId)->update(['status' => 'available']);
            }
            if ($tenant->status === 'active') {
                $tenant->room->update(['status' => 'occupied']);
            } else {
                $tenant->room->update(['status' => 'available']);
            }
        }

        return redirect()->route('tenants.index')->with('success', 'Data penyewa berhasil diperbarui.');
    }

    public function destroy(Tenant $tenant)
    {
        $room = $tenant->room;
        $tenant->delete();
        
        if ($room) {
            $room->update(['status' => 'available']);
        }

        return redirect()->route('tenants.index')->with('success', 'Penyewa berhasil dihapus.');
    }
}
