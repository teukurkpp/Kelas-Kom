document.addEventListener("DOMContentLoaded", () => {
    const ruanganData = {
        4: [
            "4.75 - 1", "4.76 - 2", "4.77 - 3",
            "4.78 - 4", "4.79 - 5", "4.80 - 6", "4.77 - 5"
        ],
        3: [
            "LAB DASAR 1", "LAB DASAR 2",
            "LAB LANJUT 1", "LAB LANJUT 2"
        ]
    };

    const lantaiSelect = document.getElementById("lantai");
    const ruanganSelect = document.getElementById("ruangan");
    const form = document.getElementById("kelasForm");
    const btnSementara = document.getElementById("btnSementara");
    const btnPermanen = document.getElementById("btnPermanen");
    const formFeedback = document.getElementById("formFeedback");

    lantaiSelect.addEventListener("change", () => {
        const selected = lantaiSelect.value;
        ruanganSelect.innerHTML = '<option disabled selected value="">Pilih Ruangan</option>';
        ruanganData[selected]?.forEach(r => {
            const opt = document.createElement("option");
            opt.value = r;
            opt.textContent = r;
            ruanganSelect.appendChild(opt);
        });
    });

    const inputs = form.querySelectorAll("input:not([type=hidden]), select:not([id=lantai])");
    inputs.forEach(input => {
        input.addEventListener("input", checkFormFilled);
        input.addEventListener("change", checkFormFilled);
    });

    function checkFormFilled() {
        let filled = true;
        inputs.forEach(input => {
            if (input.hasAttribute('required') && !input.value.trim()) {
                filled = false;
            }
        });
        btnSementara.disabled = !filled;
        if (btnPermanen) btnPermanen.disabled = !filled;
    }

    const jamInput = document.getElementById("jam_display");
    jamInput.addEventListener("blur", function () {
        const pattern = /^([01]?[0-9]|2[0-3])[.:]([0-5][0-9]) - ([01]?[0-9]|2[0-3])[.:]([0-5][0-9])$/;
        if (!pattern.test(this.value) && this.value !== '') {
            this.setCustomValidity("Format jam harus: 00.00 - 00.00");
            this.reportValidity();
        } else {
            this.setCustomValidity("");
        }
    });

    function validateForm() {
        const requiredFields = form.querySelectorAll("input[required]:not([type=hidden]), select[required]");
        for (let field of requiredFields) {
            if (!field.value.trim()) {
                field.reportValidity();
                return false;
            }
        }
        const duration = document.getElementById("duration");
        if (duration && (duration.value < 30 || duration.value > 180)) {
            duration.setCustomValidity("Durasi harus antara 30-180 menit");
            duration.reportValidity();
            return false;
        }
        return true;
    }

    form.addEventListener("submit", (e) => {
        e.preventDefault();
        if (!validateForm()) return;

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
            if (data.success) {
                formFeedback.className = `alert alert-success`;
                formFeedback.textContent = action === "permanen" ? "Perubahan permanen berhasil disimpan!" : "Perubahan sementara berhasil disimpan!";
                formFeedback.classList.remove("d-none");
                setTimeout(() => {
                    window.location.href = `PilihKelas.php?hari=${encodeURIComponent(formData.get("hari"))}&jam=${encodeURIComponent(formData.get("jam"))}`;
                }, 1500);
            } else {
                formFeedback.className = `alert alert-danger`;
                formFeedback.textContent = data.message || "Terjadi kesalahan. Silakan coba lagi.";
                formFeedback.classList.remove("d-none");
            }
            btn.innerHTML = action === "permanen" ? "Ubah Permanen" : "Ubah Sementara";
            btn.disabled = false;
        })
        .catch(() => {
            formFeedback.className = `alert alert-danger`;
            formFeedback.textContent = "Terjadi kesalahan. Silakan coba lagi.";
            formFeedback.classList.remove("d-none");
            btn.innerHTML = action === "permanen" ? "Ubah Permanen" : "Ubah Sementara";
            btn.disabled = false;
        });
    });
});