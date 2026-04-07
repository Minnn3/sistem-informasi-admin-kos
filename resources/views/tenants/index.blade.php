<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manajemen Penyewa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="flex justify-between items-center mb-4">
                <form method="GET" action="{{ route('tenants.index') }}" class="flex w-full max-w-sm">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari penyewa, no kamar..." class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-l-md dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-r-md font-bold">Cari</button>
                    @if(request('search'))
                        <a href="{{ route('tenants.index') }}" class="ml-3 text-sm text-red-500 hover:text-red-700 self-center font-semibold">Reset</a>
                    @endif
                </form>
                @role('admin')
                <a href="{{ route('tenants.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded whitespace-nowrap ml-4">
                    + Tambah Penyewa
                </a>
                @endrole
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr>
                                    <th class="py-4 px-6 bg-gray-50 border-b border-gray-200 font-bold uppercase text-sm text-gray-700 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">Nama</th>
                                    <th class="py-4 px-6 bg-gray-50 border-b border-gray-200 font-bold uppercase text-sm text-gray-700 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">Kamar</th>
                                    <th class="py-4 px-6 bg-gray-50 border-b border-gray-200 font-bold uppercase text-sm text-gray-700 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">Telepon</th>
                                    <th class="py-4 px-6 bg-gray-50 border-b border-gray-200 font-bold uppercase text-sm text-gray-700 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">Tgl Masuk</th>
                                    <th class="py-4 px-6 bg-gray-50 border-b border-gray-200 font-bold uppercase text-sm text-gray-700 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">Status</th>
                                    <th class="py-4 px-6 bg-gray-50 border-b border-gray-200 font-bold uppercase text-sm text-gray-700 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($tenants as $tenant)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="py-4 px-6 border-b border-gray-200 dark:border-gray-600">{{ $tenant->name }}</td>
                                    <td class="py-4 px-6 border-b border-gray-200 dark:border-gray-600">{{ $tenant->room->room_number ?? '-' }}</td>
                                    <td class="py-4 px-6 border-b border-gray-200 dark:border-gray-600">{{ $tenant->phone }}</td>
                                    <td class="py-4 px-6 border-b border-gray-200 dark:border-gray-600">{{ \Carbon\Carbon::parse($tenant->entry_date)->format('d M Y') }}</td>
                                    <td class="py-4 px-6 border-b border-gray-200 dark:border-gray-600">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $tenant->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $tenant->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 border-b border-gray-200 dark:border-gray-600 flex gap-2">
                                        @role('admin')
                                        <a href="{{ route('tenants.edit', $tenant) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400">Edit</a>
                                        <form action="{{ route('tenants.destroy', $tenant) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus penyewa ini? (Kamar akan menjadi Available kembali)');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400">Hapus</button>
                                        </form>
                                        @else
                                        <span class="text-gray-400 text-xs italic">Hanya lihat</span>
                                        @endrole
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="py-4 px-6 text-center text-gray-500 dark:text-gray-400">Belum ada data penyewa.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $tenants->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
