document.addEventListener("alpine:init", () => {
  Alpine.data("crudKategory", ({ kategories, storeUrl, updateUrl,deleteUrl, csrfToken }) => ({
    kategories,
    fetchUrl: '../kategoryjson',
    editing: null,
    isLoading: false,
    originalData: { id: null, jenis : null },
    newData: {jenis: '' },
    adding: false,
    textAlert:'data berhasil di simpan',
    showAlert: false,
    
    
    startAdding() {
      this.adding = true;
    },
    fetchData(retryCount = 3) {
        this.isLoading = true;
        
        fetch(this.fetchUrl)  
        .then(response => response.json())
        .then(res => {
            if (res.status === "success") {
              this.kategories = res.data; 
              this.filteredData = [...this.kategories];
            } else {
              console.error("Gagal mengambil data:", res);
            }
          })
        .catch(error => {
            console.error("Error fetching data:", error);
            if (retryCount > 0) {
                setTimeout(() => {
                    this.fetchData(retryCount - 1);  
                }, 2000);
            }
        })
        .finally(() => {
            this.isLoading = false;
        });
    },
    addNewItems() {
      this.isLoading = true;
      const send = {
          jenis: this.newData.jenis
      }
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
        this.isLoading = false;
        setTimeout(() => { this.showAlert = false; }, 3000);
        this.adding = false;
        this.newData = {jenis: null };
    });
    },
    startEditing(id, jenis) {
      this.originalData = { id, jenis };
      this.editing = id;
   },

    saveItem() {
      if (!this.editing) return;
      const updatedData = this.originalData;
      this.isLoading = true;
      fetch(updateUrl.replace(":id", updatedData.id), {
          method: "PUT",
          headers: {
              "Content-Type": "application/json",
              "X-CSRF-TOKEN": csrfToken
          },
          body: JSON.stringify(updatedData)
      })
      .then(response => response.json())
      .then(data => {
          console.log("Parsed JSON:",data);
          if (data.status === "success") {
              this.textAlert = "Data berhasil di simpan!";
            let index = this.kategories.findIndex(item => item.id === this.editing);
                  if (index !== -1) {
                      this.kategories[index] = {
                          ...this.kategories[index], 
                          jenis: updatedData.jenis,
                      };
                  }
              }
      })
      .catch(error => console.error(error))
      .finally(()=>{
          this.isLoading = false;
          this.showAlert = true
              setTimeout(() => {
                this.showAlert = false;
            }, 2000);
        this.editing = null;
      })
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
    .then(data => {
        if (data.status === "success") {
            this.kategories = this.kategories.filter(item => item.id !== id);
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