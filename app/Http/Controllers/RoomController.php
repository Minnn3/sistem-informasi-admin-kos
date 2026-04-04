<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $query = Room::latest();
        
        if ($request->has('search') && $request->search != '') {
            $search = strtolower($request->search);
            
            // Mengubah input indonesia ke enum database
            $statusSearch = $search;
            if (str_contains('kosong', $search)) $statusSearch = 'available';
            elseif (str_contains('terisi', $search)) $statusSearch = 'occupied';
            elseif (str_contains('perbaikan', $search)) $statusSearch = 'maintenance';

            $query->where(function($q) use ($search, $statusSearch) {
                $q->where('room_number', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$statusSearch}%");
            });
        }

        $rooms = $query->paginate(10)->withQueryString();
        return view('rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('rooms.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_number' => 'required|string|unique:rooms,room_number',
            'type' => 'required|string',
            'price' => 'required|integer|min:0',
            'status' => 'required|in:available,occupied,maintenance',
            'description' => 'nullable|string',
        ]);

        Room::create($validated);

        return redirect()->route('rooms.index')->with('success', 'Kamar berhasil ditambahkan.');
    }

    public function show(Room $room)
    {
        return view('rooms.show', compact('room'));
    }

    public function edit(Room $room)
    {
        return view('rooms.edit', compact('room'));
    }

    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'room_number' => 'required|string|unique:rooms,room_number,' . $room->id,
            'type' => 'required|string',
            'price' => 'required|integer|min:0',
            'status' => 'required|in:available,occupied,maintenance',
            'description' => 'nullable|string',
        ]);

        $room->update($validated);

        return redirect()->route('rooms.index')->with('success', 'Data kamar berhasil diperbarui.');
    }

    public function destroy(Room $room)
    {
        $room->delete();

        return redirect()->route('rooms.index')->with('success', 'Kamar berhasil dihapus.');
    }
}
