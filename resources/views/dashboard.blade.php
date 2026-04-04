<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard Admin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- Notifikasi --}}
            @if(session('success'))
            <div class="bg-emerald-100 border border-emerald-400 text-emerald-800 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
            @endif

            {{-- Metric Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- Total Kamar --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-500 uppercase">Total Kamar</h3>
                    <p class="text-3xl font-bold text-gray-800 dark:text-gray-200 mt-2">{{ $totalRooms }}</p>
                </div>
                {{-- Kamar Kosong --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-b-4 border-green-500">
                    <h3 class="text-sm font-medium text-gray-500 uppercase">Kamar Kosong</h3>
                    <p class="text-3xl font-bold text-green-500 mt-2">{{ $availableRooms }}</p>
                </div>
                {{-- Penghuni Aktif --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-b-4 border-blue-500">
                    <h3 class="text-sm font-medium text-gray-500 uppercase">Penghuni Aktif</h3>
                    <p class="text-3xl font-bold text-blue-500 mt-2">{{ $occupiedRooms }}</p>
                </div>
                {{-- Laba Bulan Ini --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-b-4 {{ $netProfit >= 0 ? 'border-emerald-500' : 'border-red-500' }}">
                    <h3 class="text-sm font-medium text-gray-500 uppercase">Laba Bulan Ini</h3>
                    <p class="text-3xl font-bold {{ $netProfit >= 0 ? 'text-emerald-500' : 'text-red-500' }} mt-2">
                        Rp {{ number_format($netProfit, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Kolom Kiri: Tunggakan & To-do --}}
                <div class="lg:col-span-2 space-y-6">
                    
                    {{-- Alert Tunggakan / Jatuh Tempo --}}
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-red-200 dark:border-red-900">
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Peringatan Jatuh Tempo & Menunggak
                            </h3>
                            @if($pendingBillings->isEmpty())
                                <p class="text-gray-500 dark:text-gray-400 italic">Mantap, semua tagihan terbayar lunas atau belum ada tagihan bulan ini.</p>
                            @else
                                <div class="overflow-x-auto rounded-lg">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead class="bg-gray-50 dark:bg-gray-700">
                                            <tr>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kamar</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Penghuni</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jatuh Tempo</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nominal</th>
                                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach($pendingBillings as $bill)
                                            <tr>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 font-bold">{{ $bill->room->room_number }}</td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $bill->tenant->name }}</td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm">
                                                    @php
                                                        $isOverdue = \Carbon\Carbon::parse($bill->due_date)->isPast();
                                                    @endphp
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $isOverdue ? 'bg-red-100 text-red-800 border.border-red-200' : 'bg-yellow-100 text-yellow-800 border.border-yellow-200' }}">
                                                        {{ \Carbon\Carbon::parse($bill->due_date)->format('d M Y') }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 font-medium">Rp {{ number_format($bill->amount, 0, ',', '.') }}</td>
                                                <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                                                    {{-- WA Redirect --}}
                                                    @php
                                                        $waText = urlencode("Halo {$bill->tenant->name}, tagihan kos untuk kamar {$bill->room->room_number} sebesar Rp" . number_format($bill->amount, 0, ',', '.') . " sudah jatuh tempo pada tanggal " . \Carbon\Carbon::parse($bill->due_date)->format('d M Y') . ". Mohon segera diselesaikan ya. Terima kasih!");
                                                        $waPhone = preg_replace('/^0/', '62', $bill->tenant->phone);
                                                    @endphp
                                                    <a href="https://wa.me/{{ $waPhone }}?text={{ $waText }}" target="_blank" class="text-emerald-600 hover:text-emerald-900 mr-4 font-bold">WA</a>
                                                    
                                                    {{-- Button Lunas --}}
                                                    <form action="{{ route('billings.pay', $bill->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="text-blue-600 hover:text-blue-900 font-bold" onclick="return confirm('Tandai tagihan ini menjadi Lunas?')">Lunas</button>
                                                    </form>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                            
                            {{-- Generate Auto --}}
                            <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700 flex justify-between items-center">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Pastikan untuk men-generate tagihan setiap bulannya agar data terekam.</span>
                                <form action="{{ route('billings.generate') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-sm bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-4 py-2 rounded shadow-sm focus:ring focus:ring-indigo-300">
                                        Generate Tagihan Bulan Ini
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- Visualisasi Status Kamar (Grid) --}}
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                Denah/Grid Status Kamar
                            </h3>
                            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-4">
                                @foreach($roomsGrid as $room)
                                    @php
                                        $bgColor = 'bg-white border-green-500 text-green-700'; // available
                                        $statusMap = ['available' => 'Kosong', 'occupied' => 'Terisi', 'maintenance' => 'Perbaikan'];
                                        if($room->status == 'occupied') $bgColor = 'bg-red-50 border-red-500 text-red-700';
                                        elseif($room->status == 'maintenance') $bgColor = 'bg-yellow-50 border-yellow-500 text-yellow-700';
                                    @endphp
                                    <div class="border-2 {{ $bgColor }} p-3 text-center rounded-lg shadow-sm flex flex-col justify-center items-center h-24 hover:shadow-md transition">
                                        <span class="font-extrabold text-2xl">{{ $room->room_number }}</span>
                                        <span class="text-xs uppercase font-semibold mt-1">{{ $statusMap[$room->status] ?? $room->status }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Kolom Kanan: To-Do List --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg h-fit">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                            To-Do List Penjaga
                        </h3>
                        
                        {{-- Form Tambah Todo --}}
                        <form action="{{ route('todos.store') }}" method="POST" class="mb-5">
                            @csrf
                            <div class="flex shadow-sm rounded-md">
                                <input type="text" name="note" required placeholder="Tambah catatan..." class="flex-1 rounded-l-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-r-md px-4 py-2 font-bold text-lg">+</button>
                            </div>
                        </form>

                        {{-- Daftar Todo --}}
                        <div class="space-y-3 max-h-[600px] overflow-y-auto pr-2">
                            @foreach($todos as $todo)
                                <div class="flex items-start justify-between p-3 rounded-md {{ $todo->status === 'done' ? 'bg-gray-100 dark:bg-gray-700 opacity-60' : 'bg-gray-50 dark:bg-gray-700 shadow-sm border border-gray-200 dark:border-gray-600' }}">
                                    <div class="flex items-start">
                                        <form action="{{ route('todos.toggle', $todo->id) }}" method="POST" class="mt-1">
                                            @csrf
                                            <input type="checkbox" onchange="this.form.submit()" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 cursor-pointer" {{ $todo->status === 'done' ? 'checked' : '' }}>
                                        </form>
                                        <span class="ml-3 text-sm {{ $todo->status === 'done' ? 'line-through text-gray-500 dark:text-gray-400' : 'text-gray-700 dark:text-gray-200 font-medium' }}">
                                            {{ $todo->note }}
                                            <br>
                                            <span class="text-[10px] text-gray-400">{{ $todo->created_at->diffForHumans() }}</span>
                                        </span>
                                    </div>
                                    <form action="{{ route('todos.destroy', $todo->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-600 focus:outline-none" onclick="return confirm('Hpus pesan ini?')">
                                            <svg class="w-4 h-4 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                            @if($todos->isEmpty())
                                <div class="text-center py-6 text-gray-500 dark:text-gray-400 border border-dashed rounded-md border-gray-300 dark:border-gray-600">
                                    <p class="text-sm">Yey, tidak ada tugas hari ini!</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
