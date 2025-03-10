document.addEventListener("alpine:init", () => {
    Alpine.data("crudData", ({ data, desas, kategories, storeUrl, updateUrl, deleteUrl, csrfToken, selectedDesa, selectedJenis }) => ({
        data,
        desas,
        kategories,
        fetchUrl: '../datajson',
        textAlert: '',
        showAlert: false,
        editing: null,
        selectedDesa,
        selectedJenis,
        isLoading: false,
        originalData: { id: null, nama_kegiatan: null, deskripsi: null, desa_id: null, kategory_id: null },
        filteredData: [...data],
        newData: { nama_kegiatan: '', deskripsi: '', desa_id: '', kategory_id: '' },
        adding: false,

        startAdding() {
            this.adding = true;
        },
        fetchData(retryCount = 3) {
            this.isLoading = true;
            
            fetch(this.fetchUrl)  // Gantilah dengan endpoint yang sesuai untuk ambil semua data
            .then(response => response.json())
            .then(res => {
                if (res.status === "success") {
                  this.data = res.data; // Ambil array dari dalam objek
                  this.filteredData = [...this.data];
                } else {
                  console.error("Gagal mengambil data:", res);
                }
              })
            .catch(error => {
                console.error("Error fetching data:", error);
                if (retryCount > 0) {
                    setTimeout(() => {
                        this.fetchData(retryCount - 1);  // Coba ulangi jika gagal
                    }, 2000);
                }
            })
            .finally(() => {
                this.isLoading = false;
                this.filterData();
            });
        },
        
        addNewItems() {
            if (!this.newData.nama_kegiatan || !this.newData.deskripsi || !this.newData.desa_id || !this.newData.kategory_id) {
                this.textAlert = "Semua kolom wajib diisi!";
                this.showAlert = true;
                setTimeout(() => { this.showAlert = false; }, 2000);
                return;
            }
        
            this.isLoading = true; 
            
            const send = {
                nama_kegiatan: this.newData.nama_kegiatan,
                deskripsi: this.newData.deskripsi,
                desa_id: this.newData.desa_id,
                kategory_id: this.newData.kategory_id
            };
        
            fetch(storeUrl, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken
                },
                body: JSON.stringify(send)
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    this.textAlert = "Data berhasil ditambahkan!";
                    this.showAlert = true;
                    this.fetchData(); // Update tampilan tanpa reload
                } else {
                    throw new Error("Gagal menyimpan data.");
                }
            })
            .catch(error => {
                console.error("Error:", error);
                this.textAlert = "Gagal menyimpan data. Coba lagi.";
                this.showAlert = true;
            })
            .finally(() => {
                this.isLoading = false; // Matikan indikator loading
                setTimeout(() => { this.showAlert = false; }, 3000);
                this.adding = false;
                this.newData = { nama_kegiatan: '', deskripsi: '', desa_id: '', kategory_id: '' };
            });
        },
        

        filterData() {
            const desaId = this.selectedDesa;
            const jenisId = this.selectedJenis;

            // Update query string di URL
            const url = new URL(window.location);
            desaId ? url.searchParams.set('desa', desaId) : url.searchParams.delete('desa');
            jenisId ? url.searchParams.set('jenis', jenisId) : url.searchParams.delete('jenis');
            window.history.pushState({}, '', url);
            console.log ("Filter Dataaaa: ", JSON.stringify(this.filteredData));

            // Filter data
            this.filteredData = this.data.filter(item =>
                (!desaId || item.desa.id == desaId) &&
                (!jenisId || item.kategory.id == jenisId)
            );
        },

        startEditing(item) {
            this.originalData = { ...item };
            this.editing = item.id;
        },

        saveItem() {
            if (!this.editing) return;
            this.isLoading = true;

            fetch(updateUrl.replace(":id", this.originalData.id), {
                method: "PUT",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken
                },
                body: JSON.stringify(this.originalData)
            })
            .then(response => response.json())
            .then(response => {
                if (response.status === "success") {
                    let index = this.data.findIndex(item => item.id === this.editing);
                    if (index !== -1) {
                        this.data[index] = {
                            ...this.originalData,
                            desa: this.desas.find(d => d.id == this.originalData.desa_id) || this.data[index].desa,
                            kategory: this.kategories.find(k => k.id == this.originalData.kategory_id) || this.data[index].kategory
                        };
                        this.filteredData = [...this.data];
                    }
                    this.showAlertMessage("Data berhasil diperbarui!", "success");
                    this.editing = null;
                }
            })
            .catch(error => console.error(error))
            .finally(() => {this.isLoading = false; this.filterData();});
        },

        cancelEdit() {
            this.editing = null;
        },

        deleteItem(id) {
            if (!confirm("Apakah Anda yakin ingin menghapus data ini?")) return;

            fetch(deleteUrl.replace(":id", id), {
                method: "DELETE",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken
                }
            })
            .then(response => response.json())
            .then(response => {
                if (response.status === "success") {
                    this.data = this.data.filter(item => item.id !== id);
                    this.filteredData = this.filteredData.filter(item => item.id !== id);
                    this.showAlertMessage("Data berhasil dihapus!", "success");
                }
            })
            .catch(error => console.error(error));
        },

        showAlertMessage(message, type) {
            this.textAlert = message;
            this.showAlert = true;
            setTimeout(() => {
                this.showAlert = false;
            }, 2000);
        },

        init() {
            const urlParams = new URLSearchParams(window.location.search);
            this.selectedDesa = urlParams.get('desa') || '';
            this.selectedJenis = urlParams.get('jenis') || '';

            if (this.selectedDesa || this.selectedJenis) {
                this.filterData();
            }
        }
    }));
});
