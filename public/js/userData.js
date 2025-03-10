document.addEventListener("alpine:init", () => {
  Alpine.data("userData", ({ data, desas, kategories }) => ({
      data,
      desas,
      kategories,
      selectedDesa: '',
      selectedJenis: '',
      filteredData: [],
      expanded: false,

      filterData() {
        const desaId = this.selectedDesa;
        const jenisId = this.selectedJenis;
    
        // Update query string di URL
        const url = new URL(window.location);
        desaId ? url.searchParams.set('desa', desaId) : url.searchParams.delete('desa');
        jenisId ? url.searchParams.set('jenis', jenisId) : url.searchParams.delete('jenis');
        window.history.pushState({}, '', url);
    
        // Filter data sesuai pilihan
        this.filteredData = this.data.filter(item =>
            (!desaId || item.desa.id == desaId) &&
            (!jenisId || item.kategory.id == jenisId)
        );
      },

      init() {
        // Ambil parameter dari URL
        const urlParams = new URLSearchParams(window.location.search);
        const desaParam = urlParams.get('desa');
        const jenisParam = urlParams.get('jenis');
        
        // Set nilai filter dari URL jika ada
        this.selectedDesa = desaParam || '';
        this.selectedJenis = jenisParam || '';
    
        // Simpan data awal ke dalam filteredData
        this.filteredData = [...this.data];
    
        // Jalankan filter jika ada parameter
        if (this.selectedDesa || this.selectedJenis) {
            this.filterData();
        }
    }
  }));
});
