document.addEventListener("alpine:init", () => {
  Alpine.data("crudDesa", ({ desas, storeUrl, updateUrl, deleteUrl, csrfToken }) => ({
    desas,
    fetchUrl: '../desajson',
    editing: null,
    isLoading: false,
    showAlert: false,
    textAlert:'data berhasil di simpan',
    originalData: { id: null, nama: null, longitude: null, latitude: null },
    newData: {nama: '', longitude : null, latitude: null },
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
              this.desas = res.data; // Ambil array dari dalam objek
              this.filteredData = [...this.desas];
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
        });
    },
    showNotification(message) {
        this.textAlert = message;
        this.showAlert = true;
        setTimeout(() => { this.showAlert = false; }, 2000);
    },

    addNewItems() {
        if (!this.newData.nama || this.newData.longitude === '' || this.newData.latitude === '') {
            this.showNotification("Semua kolom wajib diisi!");
            return;
        }
        
        if (isNaN(this.newData.longitude) || isNaN(this.newData.latitude)) {
            this.showNotification("Koordinat harus berupa angka!");
            return;
        }
        
        const lon = parseFloat(this.newData.longitude);
        const lat = parseFloat(this.newData.latitude);
        
        if (lon < -180 || lon > 180 || lat < -90 || lat > 90) {
            this.showNotification("Koordinat tidak valid!");
            return;
        }
        
        this.isLoading = true; 
        
        const send = {
            nama: this.newData.nama,
            longitude: lon,
            latitude: lat
        };
        // Aktifkan indikator loading
        fetch (storeUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(send)
        }).then(response => response.json())
          .then(data => {
            if (data.status === "success") {
                this.textAlert = "Data berhasil ditambahkan!";
                this.showAlert = true;
                this.fetchData(); 
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
            this.newData = {nama: null, longitude: null, latitude: null };
        });
    },

    //function untuk mengedit data
    startEditing(id, nama, longitude, latitude) {
        this.originalData = { id, nama, longitude, latitude };
        this.editing = id;
    },
    //function untuk menyimpan data
    saveItem() {
        if (!this.editing) return;
        this.isLoading = true;
        const updatedData = this.originalData;
        const dataToSend = {
            nama: updatedData.nama,
            longitude: updatedData.longitude,
            latitude: updatedData.latitude
        };
        //untuk mengirim data ke backend
        fetch(updateUrl.replace(':id', updatedData.id), {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(dataToSend)
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                this.textAlert = "Data berhasil di simpan!";
                  let index = this.desas.findIndex(item => item.id === this.editing);
                  if (index !== -1) {
                      this.desas[index] = {
                          ...this.desas[index], // Tetap ambil data lama
                          nama: updatedData.nama,
                          longitude: updatedData.longitude,
                          latitude: updatedData.latitude
                      };
                  }
              }
        })
        .catch(error => console.error('Error:', error))
        .finally(()=>{
            this.isLoading = false;
            this.showAlert = true
                  setTimeout(() => {
                    this.showAlert = false;
                }, 2000);
            this.editing = null;
          })
    },deleteItem(id) {
        if (!confirm("Apakah Anda yakin ingin menghapus data ini?")) return;
    
        fetch(deleteUrl.replace(":id", id), {
            method: "DELETE",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                // Hapus data dari tampilan lokal

                this.desas = this.desas.filter(item => item.id !== id);
                this.showAlert = true;
                this.textAlert = "Data berhasil dihapus!";
                setTimeout(() => {
                    this.showAlert = false;
                }, 2000);
            }
        })
        .catch(error => console.error("Error:", error));
    },
    cancelEdit() {
        this.editing = null;
    }
  }));
});