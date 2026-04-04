<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Laporan Keuangan Bulanan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Filter Laporan -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6 border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <form method="GET" action="{{ route('reports.index') }}" class="flex flex-col sm:flex-row gap-4 items-end">
                        <div>
                            <x-input-label for="month" :value="__('Bulan')" />
                            <select name="month" id="month" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm xl:w-48">
                                @for($i=1; $i<=12; $i++)
                                    <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>{{ \Carbon\Carbon::create(null, $i)->translatedFormat('F') }}</option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <x-input-label for="year" :value="__('Tahun')" />
                            <select name="year" id="year" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm xl:w-48">
                                @for($y = \Carbon\Carbon::now()->year - 2; $y <= \Carbon\Carbon::now()->year + 1; $y++)
                                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow">
                                Tampilkan
                            </button>
                            <a href="{{ route('reports.export', ['month' => $month, 'year' => $year]) }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded shadow flex items-center gap-2">
                                Download Excel (CSV)
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Ringkasan Laporan -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border-l-4 border-green-500">
                    <p class="text-sm font-semibold text-gray-500 uppercase">Pemasukan (Tagihan Lunas)</p>
                    <h3 class="mt-2 text-2xl font-bold text-gray-900 dark:text-gray-100">Rp {{ number_format($totalIncome, 0, ',', '.') }}</h3>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border-l-4 border-red-500">
                    <p class="text-sm font-semibold text-gray-500 uppercase">Pengeluaran (Buku Kas)</p>
                    <h3 class="mt-2 text-2xl font-bold text-gray-900 dark:text-gray-100">Rp {{ number_format($totalExpense, 0, ',', '.') }}</h3>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border-l-4 border-blue-500">
                    <p class="text-sm font-semibold text-gray-500 uppercase">Laba Bersih Bulan Ini</p>
                    <h3 class="mt-2 text-2xl font-bold {{ $netIncome >= 0 ? 'text-green-600' : 'text-red-500' }}">Rp {{ number_format($netIncome, 0, ',', '.') }}</h3>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Tabel Transaksi Cepat -->
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <h3 class="text-lg font-bold mb-4 dark:text-gray-200">Rincian Transaksi Pemasukan</h3>
                        <table class="w-full text-left text-sm text-gray-700 dark:text-gray-300">
                            <thead>
                                <tr class="border-b dark:border-gray-600">
                                    <th class="py-2">Tanggal Lunas</th>
                                    <th class="py-2">Penyewa/Kamar</th>
                                    <th class="py-2 text-right">Nominal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y dark:divide-gray-700">
                                @forelse($billings as $bill)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="py-2">{{ \Carbon\Carbon::parse($bill->paid_at)->format('d M') }}</td>
                                    <td class="py-2">{{ $bill->tenant->name ?? '-' }} ({{ $bill->room->room_number ?? '-' }})</td>
                                    <td class="py-2 text-right">Rp {{ number_format($bill->amount, 0, ',', '.') }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="py-4 text-center text-gray-500">Tidak ada pemasukan bulan ini</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <h3 class="text-lg font-bold mb-4 dark:text-gray-200">Rincian Transaksi Pengeluaran</h3>
                        <table class="w-full text-left text-sm text-gray-700 dark:text-gray-300">
                            <thead>
                                <tr class="border-b dark:border-gray-600">
                                    <th class="py-2">Tanggal</th>
                                    <th class="py-2">Deskripsi Pengeluaran</th>
                                    <th class="py-2 text-right">Nominal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y dark:divide-gray-700">
                                @forelse($expenses as $exp)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="py-2">{{ \Carbon\Carbon::parse($exp->expense_date)->format('d M') }}</td>
                                    <td class="py-2">{{ $exp->description }}</td>
                                    <td class="py-2 text-right">Rp {{ number_format($exp->amount, 0, ',', '.') }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="py-4 text-center text-gray-500">Tidak ada pengeluaran bulan ini</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
