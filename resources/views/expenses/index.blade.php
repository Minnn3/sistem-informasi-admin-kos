<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Buku Kas / Pengeluaran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
            <div class="mb-4 bg-emerald-100 border border-emerald-400 text-emerald-800 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                {{-- Form Tambah Pengeluaran - Hanya Admin --}}
                <div class="md:col-span-1">
                    @role('admin')
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-bold mb-4 text-gray-900 dark:text-gray-100 border-b pb-2">Catat Pengeluaran Baru</h3>
                            <form action="{{ route('expenses.store') }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deskripsi/Keterangan</label>
                                    <input type="text" name="description" required placeholder="Cth: Beli Token Listrik" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nominal (Rp)</label>
                                    <input type="number" name="amount" required min="1000" placeholder="50000" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>
                                <div class="mb-5">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal</label>
                                    <input type="date" name="expense_date" required value="{{ date('Y-m-d') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>
                                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow transition-colors">
                                    Simpan Pengeluaran
                                </button>
                            </form>
                        </div>
                    </div>
                    @endrole
                </div>

                {{-- Tabel Riwayat --}}
                <div class="md:col-span-2">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg h-full">
                        <div class="p-6 h-full flex flex-col">
                            <h3 class="text-lg font-bold mb-4 text-gray-900 dark:text-gray-100 border-b pb-2">Riwayat Pengeluaran Keseluruhan</h3>
                            
                            <div class="overflow-x-auto rounded-lg ring-1 ring-black ring-opacity-5 flex-1">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Keterangan</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nominal</th>
                                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @php $total = 0; @endphp
                                        @forelse($expenses as $expense)
                                        @php $total += $expense->amount; @endphp
                                        <tr>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">{{ \Carbon\Carbon::parse($expense->expense_date)->format('d M Y') }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100 font-medium">{{ $expense->description }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-red-500 font-bold">Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-right text-sm">
                                                @role('admin')
                                                <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirm('Yakin hapus pengeluaran ini?')">Hapus</button>
                                                </form>
                                                @else
                                                <span class="text-gray-400 text-xs italic">-</span>
                                                @endrole
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="px-4 py-8 text-center text-gray-500 italic">Belum ada riwayat pengeluaran yang dicatat.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot class="bg-gray-50 dark:bg-gray-900 border-t-2 border-gray-200 dark:border-gray-700">
                                        <tr>
                                            <td colspan="2" class="px-4 py-3 text-right font-bold text-gray-700 dark:text-gray-200 text-sm">TOTAL PENGELUARAN</td>
                                            <td colspan="2" class="px-4 py-3 text-red-600 font-extrabold text-base">Rp {{ number_format($total, 0, ',', '.') }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
