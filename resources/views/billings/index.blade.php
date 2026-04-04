<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Daftar Tagihan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    @if(session('success'))
                    <div class="mb-4 bg-emerald-100 border border-emerald-400 text-emerald-800 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                    @endif

                    <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
                        <h3 class="text-lg font-bold mb-4 sm:mb-0 text-gray-800 dark:text-gray-200">Semua Tagihan</h3>
                        <form action="{{ route('billings.generate') }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow transition text-sm">
                                Generate Tagihan Bulan Ini
                            </button>
                        </form>
                    </div>

                    <div class="overflow-x-auto shadow-sm ring-1 ring-black ring-opacity-5 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kamar</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Penghuni</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jatuh Tempo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nominal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($billings as $bill)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-900 dark:text-gray-100">{{ $bill->room->room_number ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $bill->tenant->name ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">{{ \Carbon\Carbon::parse($bill->due_date)->format('d M Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">Rp {{ number_format($bill->amount, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($bill->status === 'paid')
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200">Lunas</span>
                                            <div class="text-[10px] text-gray-500 dark:text-gray-400 mt-1">{{ \Carbon\Carbon::parse($bill->paid_at)->format('dMy H:i') }}</div>
                                        @else
                                            @php
                                                $isOverdue = \Carbon\Carbon::parse($bill->due_date)->isPast();
                                            @endphp
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $isOverdue ? 'bg-red-100 text-red-800 border border-red-200' : 'bg-yellow-100 text-yellow-800 border border-yellow-200' }}">
                                                {{ $isOverdue ? 'Menunggak' : 'Pending' }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        @if($bill->status === 'pending')
                                            @php
                                                $waText = urlencode("Halo {$bill->tenant->name}, tagihan kos untuk kamar {$bill->room->room_number} sebesar Rp" . number_format($bill->amount, 0, ',', '.') . " sudah jatuh tempo pada " . \Carbon\Carbon::parse($bill->due_date)->format('d M Y') . ". Mohon segera diselesaikan ya. Terima kasih!");
                                                $waPhone = preg_replace('/^0/', '62', $bill->tenant->phone);
                                            @endphp
                                            <a href="https://wa.me/{{ $waPhone }}?text={{ $waText }}" target="_blank" class="text-emerald-600 hover:text-emerald-900 mr-4 font-bold">WA</a>
                                            
                                            <div class="flex flex-col items-end gap-2 mt-2">
                                                <form action="{{ route('billings.pay', $bill->id) }}" method="POST" enctype="multipart/form-data" class="flex flex-col items-end gap-1">
                                                    @csrf
                                                    <input type="file" name="proof_of_payment" accept="image/*" class="text-[10px] w-48 border border-gray-300 rounded p-1 dark:bg-gray-700 dark:border-gray-600" title="Upload Bukti TF (Opsional)">
                                                    <button type="submit" class="text-blue-600 hover:text-blue-900 font-bold text-xs bg-blue-50 px-3 py-1 border border-blue-200 rounded" onclick="return confirm('Tandai lunas?')">Lunas</button>
                                                </form>
                                            </div>
                                        @else
                                            <div class="flex flex-col items-end gap-1">
                                                <a href="{{ route('billings.invoice', $bill->id) }}" target="_blank" class="text-blue-600 hover:text-blue-900 font-bold text-[10px] uppercase bg-blue-50 px-2 py-1 rounded border border-blue-200">Struk</a>
                                                @if($bill->proof_of_payment)
                                                    <a href="{{ asset('storage/' . $bill->proof_of_payment) }}" target="_blank" class="text-purple-600 hover:text-purple-900 font-bold text-[10px] uppercase bg-purple-50 px-2 py-1 rounded border border-purple-200">Lihat Bukti</a>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400 italic">Belum ada data tagihan. Generate tagihan bulan ini terlebih dahulu.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
