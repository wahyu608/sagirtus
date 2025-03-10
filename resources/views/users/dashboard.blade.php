<x-guest-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-5">
                        <div id="map" style="height: 500px;"></div>
                    </div>
                    <select id="filter-desa">
                        <option value="all" class="dark:text-black">Semua Desa</option>
                        @foreach ($desaData as $desa)
                            <option value="{{ $desa->id }}">{{ $desa->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var map = L.map('map').setView([-2.5489, 118.0149], 5); // Posisi awal map
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map); 

            let desaData = @json($desaData); // Data desa
            let seniRitusData = @json($data);

            console.log("Desa Data: ", desaData);
            console.log("Seni & Ritus Data: ", seniRitusData);

            let markers = {}; // Objek untuk menyimpan marker per desa

            // Fungsi untuk menampilkan marker
            function tampilkanMarker() {
                // Hapus semua marker sebelum menampilkan yang baru
                Object.values(markers).forEach(marker => map.removeLayer(marker));
                markers = {};

                let selectedDesa = document.getElementById("filter-desa").value; //mendapatkan desa yang dipilih

                desaData.forEach(desa => { //menampilkan
                    if (selectedDesa === "all" || desa.id == selectedDesa) { // jika semua desa atau desa yang dipilih  
                        let seniRitusCount = seniRitusData.filter(item => item.desa_id == desa.id).length; //menghitung jumlah seni & ritus
                        let setView = map.setView([desa.latitude, desa.longitude], 10);

                        let marker = L.marker([desa.latitude, desa.longitude]).addTo(map); //menampilkan marker

                        let popupContent = `
                            <b>${desa.nama}</b><br>
                            Seni & Ritus: ${seniRitusCount}<br>
                            <a href="/data?desa=${desa.id}&jenis=""">Lihat Data</a>
                        `; //konten popup

                        marker.bindPopup(popupContent); //binding 
                        markers[desa.id] = marker; // 
                    }
                });
            }

            tampilkanMarker();

            // Event listener untuk filter desa
            document.getElementById("filter-desa").addEventListener("change", tampilkanMarker);
        });
    </script>
</x-guest-layout>
