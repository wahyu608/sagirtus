<x-guest-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Halaman Data') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div x-data="userData({
                        data: {{ Js::from($data) }},
                        desas: {{ Js::from($desas) }},
                        kategories: {{ Js::from($kategories) }}
                    })">
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                            <div class="flex justify-between items-center mb-4">
                                <label class="font-bold">
                                    Total data Seni dan Ritus: <span x-text="filteredData.length"></span>
                                </label>
                            </div>
                            <div class="flex w-full justify-between gap-4 mb-4">
                                <!-- Filter Desa -->
                                <div class="md:flex gap-4">
                                    <select x-model="selectedDesa" @change="filterData()" class="px-2 py-1 w-7/12 border rounded-md text-sm text-black">
                                        <option value="">Semua Desa</option>
                                        @foreach ($desas as $desa)
                                            <option value="{{ $desa->id }}">{{ $desa->nama }}</option>
                                        @endforeach
                                    </select>

                                    <!-- Filter Jenis -->
                                    <select x-model="selectedJenis" @change="filterData()" class="px-2 py-1 pr-7 border rounded-md text-sm text-black">
                                        <option value="">Semua Jenis</option>
                                        @foreach ($kategories as $kategori)
                                            <option value="{{ $kategori->id }}">{{ $kategori->jenis }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="p-6 text-gray-900">
                                    <table class="min-w-full">
                                        <thead>
                                            <tr class="border-b-2">
                                                <th class="px-6 py-3 text-left text-lg font-medium text-black">No</th>
                                                <th class="px-6 py-3 text-left text-lg font-medium text-black">Nama</th>
                                                <th class="px-6 py-3 text-left text-lg font-medium text-black">Deskripsi</th>
                                                <th class="px-6 py-3 text-left text-lg font-medium text-black">Desa</th>
                                                <th class="px-6 py-3 text-left text-lg font-medium text-black">Jenis</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <template x-for="(item, index) in filteredData" :key="item.id">
                                                <tr class="border-b">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" x-text="index + 1"></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        <span x-text="item.nama_kegiatan"></span>
                                                    </td>
                                                    <td class="px-6 py-4 text-sm text-gray-500 max-w-xs">
                                                        <div x-data="{ expanded: false }" class="w-full">
                                                                <div>
                                                                    <div x-bind:class="expanded ? 'whitespace-normal text-left' : 'truncate w-52'"
                                                                        x-text="expanded ? item.deskripsi : (item.deskripsi.length > 50 ? item.deskripsi.substring(0, 50) + '...' : item.deskripsi)" style="text-align: justify;">
                                                                    </div>
                                                                    <span x-show="item.deskripsi.length > 50 && !expanded"
                                                                        @click="expanded = !expanded"
                                                                        class="text-blue-500 cursor-pointer block" x-cloak>
                                                                        <span x-text="'Baca Selengkapnya'"></span>
                                                                    </span>
                                                                    <span x-show="expanded && item.deskripsi.length > 50"
                                                                        @click="expanded = !expanded"
                                                                        class="text-blue-500 cursor-pointer block" x-cloak>
                                                                        <span x-text="'Lebih Sedikit'"></span>
                                                                    </span>
                                                                </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        <span x-text="item.desa.nama"></span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        <span x-text="item.kategory.jenis"></span>
                                                    </td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div> <!-- max-w-7xl -->
                    </div> <!-- py-12 -->
                </div> <!-- p-6 -->
            </div> <!-- bg-white -->
        </div> <!-- max-w-7xl -->
    </div> <!-- py-12 -->
    <script src="{{ asset('js/userData.js') }}">console.log(item)</script>
</x-guest-layout>
