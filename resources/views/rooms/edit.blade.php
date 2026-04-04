<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Kamar') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('rooms.update', $room) }}">
                        @csrf
                        @method('PUT')

                        <!-- No Kamar -->
                        <div>
                            <x-input-label for="room_number" :value="__('Nomor Kamar')" />
                            <x-text-input id="room_number" class="block mt-1 w-full" type="text" name="room_number" :value="old('room_number', $room->room_number)" required autofocus />
                            <x-input-error :messages="$errors->get('room_number')" class="mt-2" />
                        </div>

                        <!-- Type -->
                        <div class="mt-4">
                            <x-input-label for="type" :value="__('Tipe Kamar')" />
                            <x-text-input id="type" class="block mt-1 w-full" type="text" name="type" :value="old('type', $room->type)" required />
                            <x-input-error :messages="$errors->get('type')" class="mt-2" />
                        </div>

                        <!-- Price -->
                        <div class="mt-4">
                            <x-input-label for="price" :value="__('Harga per Bulan (Rp)')" />
                            <x-text-input id="price" class="block mt-1 w-full" type="number" name="price" :value="old('price', $room->price)" required />
                            <x-input-error :messages="$errors->get('price')" class="mt-2" />
                        </div>

                        <!-- Status -->
                        <div class="mt-4">
                            <x-input-label for="status" :value="__('Status')" />
                            <select id="status" name="status" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="available" {{ old('status', $room->status) == 'available' ? 'selected' : '' }}>Kosong</option>
                                <option value="occupied" {{ old('status', $room->status) == 'occupied' ? 'selected' : '' }}>Terisi</option>
                                <option value="maintenance" {{ old('status', $room->status) == 'maintenance' ? 'selected' : '' }}>Perbaikan</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>

                        <!-- Description -->
                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Deskripsi / Fasilitas')" />
                            <textarea id="description" name="description" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" rows="3">{{ old('description', $room->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('rooms.index') }}" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 me-3">
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
