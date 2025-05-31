document.addEventListener("DOMContentLoaded", () => {
    const ruanganData = {
        4: ["4.75 - 1", "4.76 - 2", "4.77 - 3", "4.78 - 4", "4.79 - 5", "4.80 - 6"],
        3: ["LAB DASAR 1", "LAB DASAR 2", "LAB LANJUT 1", "LAB LANJUT 2"]
    };

    const lantaiSelect = document.getElementById("lantai");
    const ruanganDisplay = document.getElementById("ruangan_display");
    const form = document.getElementById("kelasForm");
    const btnSementara = document.getElementById("btnSementara");
    const btnPermanen = document.getElementById("btnPermanen");
    const formFeedback = document.getElementById("formFeedback");
    const actionHidden = document.getElementById("action_hidden");

    // Ganti ruangan otomatis
    lantaiSelect.addEventListener("change", () => {
        const selectedLantai = lantaiSelect.value;
        const ruanganList = ruanganData[selectedLantai];
       if (!ruanganDisplay.value.trim()) {
            ruanganDisplay.value = ruanganList[0];
        }

    });

    // Validasi form input
    function checkFormFilled() {
        const kelas = document.getElementById("kelas").value.trim();
        const prodi = document.getElementById("prodi").value.trim();  // tambahkan ini
        const mataKuliah = document.getElementById("mataKuliah").value.trim();
        const sksChecked = document.querySelector("input[name='sks']:checked");
        const durationInput = document.getElementById("duration");
        const durationValid = !durationInput || (durationInput.value >= 30 && durationInput.value <= 180);

        const isFilled = kelas && prodi && mataKuliah && sksChecked && durationValid;

        if (btnSementara) btnSementara.disabled = !isFilled;
        if (btnPermanen) btnPermanen.disabled = !isFilled;
    }


    // Listener untuk aktifkan tombol
    document.getElementById("kelas").addEventListener("change", checkFormFilled);
    document.getElementById("mataKuliah").addEventListener("input", checkFormFilled);
    document.querySelectorAll("input[name='sks']").forEach(radio => {
        radio.addEventListener("change", checkFormFilled);
    });
    const durationInput = document.getElementById("duration");
    if (durationInput) {
        durationInput.addEventListener("input", checkFormFilled);
    }

    // Catat tombol terakhir diklik
    let lastClicked = null;
    if (btnSementara) btnSementara.addEventListener("click", () => lastClicked = "sementara");
    if (btnPermanen) btnPermanen.addEventListener("click", () => lastClicked = "permanen");

    function validateForm() {
        const requiredFields = form.querySelectorAll("input[required]:not([type=hidden]), select[required]");
        for (let field of requiredFields) {
            if (!field.value.trim()) {
                field.reportValidity();
                return false;
            }
        }
        if (durationInput && (durationInput.value < 30 || durationInput.value > 180)) {
            durationInput.setCustomValidity("Durasi harus antara 30–180 menit");
            durationInput.reportValidity();
            return false;
        }
        return true;
    }

    // Submit via AJAX
    form.addEventListener("submit", (e) => {
        e.preventDefault();
        if (!validateForm()) return;

        actionHidden.value = lastClicked;

        const formData = new FormData(form);
        const action = formData.get("action");
        const btn = action === "permanen" ? btnPermanen : btnSementara;

        btn.innerHTML = '<span class="loading"></span> Memproses...';
        btn.disabled = true;

        fetch("ProsesPerubahanJadwal.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            formFeedback.classList.remove("d-none");
            if (data.success) {
                formFeedback.className = "alert alert-success";
                formFeedback.textContent = action === "permanen"
                    ? "Perubahan permanen berhasil disimpan!"
                    : "Perubahan sementara berhasil disimpan!";
                setTimeout(() => {
                    window.location.href = `PilihKelas.php?hari=${encodeURIComponent(formData.get("hari"))}&jam=${encodeURIComponent(formData.get("jam"))}`;
                }, 1500);
            } else {
                formFeedback.className = "alert alert-danger";
                formFeedback.textContent = data.message || "Terjadi kesalahan. Silakan coba lagi.";
                btn.disabled = false;
            }
            btn.innerHTML = action === "permanen" ? "Ubah Permanen" : "Ubah Sementara";
        })
        .catch(() => {
            formFeedback.className = "alert alert-danger";
            formFeedback.textContent = "Terjadi kesalahan. Silakan coba lagi.";
            formFeedback.classList.remove("d-none");
            btn.innerHTML = action === "permanen" ? "Ubah Permanen" : "Ubah Sementara";
            btn.disabled = false;
        });
    });
});
