<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Halaman Data') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div x-data="crudDesa({
                        //property yang akan digunakan menyimpan nilai,
                        desas: {{ Js::from($desas) }},
                        storeUrl: '{{ route('store.desa') }}',
                        updateUrl: '{{ route('update.desa', ['id' => ':id']) }}',
                        deleteUrl: '{{ route('delete.desa', ['id' => ':id']) }}',
                        csrfToken: '{{ csrf_token() }}'
                    })">
                    <div x-show="showAlert" x-transition.duration.500ms
                            class="fixed right-52 top-80 shadow-2xl hidden bg-green-500 text-white p-3 rounded-md mb-4 text-center z-50"
                            :class="{ 'hidden' : !showAlert}"
                            x-text="textAlert"
                            @click="showAlert = false">
                        </div>
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                            <div class="flex justify-between items-center mb-4">
                                <label class="font-bold">
                                    Total desa: <span x-text="desas.length"></span>
                                </label>
                            </div>
                            <button @click="startAdding()" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                Tambah Data
                            </button>

                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="p-6 text-gray-900">
                                    <div class="overflow-x-auto">
                                    <table class="min-w-full">
                                        <thead>
                                            <tr class="border-b-2">
                                                <th class="px-6 py-3 text-left text-lg font-medium text-black">No</th>
                                                <th class="px-6 py-3 text-left text-lg font-medium text-black">Nama Desa</th>
                                                <th class="px-6 py-3 text-left text-lg font-medium text-black">longitude</th>
                                                <th class="px-6 py-3 text-left text-lg font-medium text-black">latitude</th>
                                                <th>&nbsp;</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr x-show="adding" class="hidden border-b bg-gray-100" :class="{'hidden': !adding}">
                                                <td class="px-6 py-4 text-sm text-gray-900 font-bold">New</td>
                                                <td class="px-6 py-4">
                                                    <input x-model="newData.nama" class="px-2 py-1 border rounded-md text-sm w-full" placeholder="Nama Desa" />
                                                </td>
                                                <td class="px-6 py-4">
                                                    <input x-model="newData.longitude" class="px-2 py-1 border rounded-md text-sm w-full" placeholder="xxx.xxxxx" />
                                                </td>
                                                <td class="px-6 py-4">
                                                    <input x-model="newData.latitude" class="px-2 py-1 border rounded-md text-sm w-full" placeholder="x.xxxxxx" />
                                                </td>
                                                <td class="text-center">
                                                    <div class="w-full flex justify-center gap-2 items-center">
                                                    <button @click="addNewItems()" :disables="isLoading" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                                                        <span x-show="!isLoading">
                                                            Save
                                                        </span>
                                                        <span x-show="isLoading" class="flex items-center">
                                                            <svg class="animate-spin h-4 w-4 mr-2 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                <circle cx="12" cy="12" r="10"></circle>
                                                                <path d="M12 2v4"></path>
                                                            </svg>   
                                                        </span>
                                                    </button>
                                                    <button @click="adding = false" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                                                        Cancel
                                                    </button>
                                                </div>
                                                </td>
                                            </tr>
                                            <template x-for="(item, index) in desas" :key="item.id">
                                                <tr class="border-b">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" x-text="index + 1"></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        <span x-show="editing !== item.id" x-text="item.nama"></span>
                                                        <input x-show="editing === item.id" x-model="originalData.nama" class="px-2 py-1 border rounded-md text-sm" />
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        <span x-show="editing !== item.id" x-text="item.longitude"></span>
                                                        <input x-show="editing === item.id" x-model="originalData.longitude" class="px-2 py-1 border rounded-md text-sm" />
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        <span x-show="editing !== item.id" x-text="item.latitude"></span>
                                                        <input x-show="editing === item.id" x-model="originalData.latitude" class="px-2 py-1 border rounded-md text-sm" />
                                                    </td>
                                                    <td>
                                                        <div class="w-full flex justify-center gap-2 items-center">
                                                            <button x-show="editing !== item.id"
                                                                    @click="startEditing(item.id, item.nama, item.longitude, item.latitude)"
                                                                    class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white font-semibold text-xs tracking-widest shadow-sm hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                                                                Edit
                                                            </button>
                                                            <button x-show="editing === item.id"
                                                                    @click="saveItem()"
                                                                    :disables="isLoading"
                                                                    class="inline-flex items-center px-4 py-2 bg-green-500 text-white font-semibold text-xs tracking-widest shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500">
                                                                <span x-show="!isLoading">
                                                                    Save
                                                                </span>
                                                                <span x-show="isLoading" class="flex items-center">
                                                                    <svg class="animate-spin h-4 w-4 mr-2 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                        <circle cx="12" cy="12" r="10"></circle>
                                                                        <path d="M12 2v4"></path>
                                                                    </svg>   
                                                                </span>
                                                            </button>
                                                            <button x-show="editing === item.id"
                                                                    @click="cancelEdit()"
                                                                    class="inline-flex items-center px-4 py-2 bg-gray-500 text-white font-semibold text-xs tracking-widest shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500">
                                                                Cancel
                                                            </button>
                                                            <button @click="deleteItem(item.id)" class="inline-flex items-center px-4 py-2 bg-red-500 text-white font-semibold text-xs tracking-widest shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                                                                Delete
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>
                                </div>
                            </div>
                        </div> <!-- max-w-7xl -->
                    </div> <!-- py-12 -->
                </div> <!-- p-6 -->
            </div> <!-- bg-white -->
        </div> <!-- max-w-7xl -->
    </div> <!-- py-12 -->
    <script src="{{ asset('js/crudDesa.js') }}"></script>

</x-app-layout>
