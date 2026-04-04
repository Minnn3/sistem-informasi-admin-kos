<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Penyewa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('tenants.update', $tenant) }}">
                        @csrf
                        @method('PUT')

                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="__('Nama Lengkap')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $tenant->name)" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- ID Card Number -->
                        <div class="mt-4">
                            <x-input-label for="id_card_number" :value="__('No Identitas (KTP/SIM)')" />
                            <x-text-input id="id_card_number" class="block mt-1 w-full" type="text" name="id_card_number" :value="old('id_card_number', $tenant->id_card_number)" required />
                            <x-input-error :messages="$errors->get('id_card_number')" class="mt-2" />
                        </div>

                        <!-- Phone -->
                        <div class="mt-4">
                            <x-input-label for="phone" :value="__('Nomor Handphone/WA')" />
                            <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone', $tenant->phone)" required />
                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                        </div>

                        <!-- Emergency Contact -->
                        <div class="mt-4">
                            <x-input-label for="emergency_contact" :value="__('Kontak Darurat')" />
                            <x-text-input id="emergency_contact" class="block mt-1 w-full" type="text" name="emergency_contact" :value="old('emergency_contact', $tenant->emergency_contact)" />
                            <x-input-error :messages="$errors->get('emergency_contact')" class="mt-2" />
                        </div>

                        <!-- Room Selection -->
                        <div class="mt-4">
                            <x-input-label for="room_id" :value="__('Pilih Kamar')" />
                            <select id="room_id" name="room_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                @foreach($rooms as $room)
                                    <option value="{{ $room->id }}" {{ old('room_id', $tenant->room_id) == $room->id ? 'selected' : '' }}>
                                        Kamar {{ $room->room_number }} ({{ $room->type }}) - Rp {{ number_format($room->price, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('room_id')" class="mt-2" />
                            <p class="text-xs text-gray-500 mt-1">*Jika mengubah kamar, status kamar lama akan menjadi Available.</p>
                        </div>

                        <!-- Entry Date -->
                        <div class="mt-4">
                            <x-input-label for="entry_date" :value="__('Tanggal Masuk')" />
                            <x-text-input id="entry_date" class="block mt-1 w-full" type="date" name="entry_date" :value="old('entry_date', $tenant->entry_date)" required />
                            <x-input-error :messages="$errors->get('entry_date')" class="mt-2" />
                        </div>

                        <!-- Status -->
                        <div class="mt-4">
                            <x-input-label for="status" :value="__('Status Penyewa')" />
                            <select id="status" name="status" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="active" {{ old('status', $tenant->status) == 'active' ? 'selected' : '' }}>Aktif (Ngekos)</option>
                                <option value="inactive" {{ old('status', $tenant->status) == 'inactive' ? 'selected' : '' }}>Tidak Aktif / Keluar</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            <p class="text-xs text-gray-500 mt-1">*Jika diset Tidak Aktif, status kamar akan otomatis menjadi Available.</p>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('tenants.index') }}" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 me-3">
                                {{ __('Batal') }}
                            </a>

                            <x-primary-button>
                                {{ __('Simpan Perubahan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
