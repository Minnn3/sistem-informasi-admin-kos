<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function index()
    {
        $todos = Todo::latest()->get();
        return view('todos.index', compact('todos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'note' => 'required|string|max:255'
        ]);

        Todo::create(['note' => $validated['note']]);
        return redirect()->back()->with('success', 'Catatan berhasil ditambahkan.');
    }

    public function toggleStatus(Todo $todo)
    {
        $todo->update([
            'status' => $todo->status === 'pending' ? 'done' : 'pending'
        ]);

        return redirect()->back()->with('success', 'Status catatan diperbarui.');
    }

    public function destroy(Todo $todo)
    {
        $todo->delete();
        return redirect()->back()->with('success', 'Catatan dihapus.');
    }
}
