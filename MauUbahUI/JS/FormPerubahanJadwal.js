// script.js

document.addEventListener("DOMContentLoaded", () => {
  const tanggal = document.getElementById("tanggal");
  tanggal.value = new Date().toISOString().split("T")[0];

  const ruanganData = {
    4: [
      "4.75 - 1", "4.76 - 2", "4.77 - 3",
      "4.78 - 4", "4.79 - 5", "4.80 - 6"
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

  const inputs = form.querySelectorAll("input, select");
  inputs.forEach(input => {
    input.addEventListener("input", checkFormFilled);
    input.addEventListener("change", checkFormFilled);
  });

  function checkFormFilled() {
    let filled = false;
    inputs.forEach(input => {
      if (input.value.trim()) filled = true;
    });
    btnSementara.disabled = !filled;
    btnPermanen.disabled = !filled;
  }

  const jamInput = document.getElementById("jam");
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
    const requiredFields = form.querySelectorAll("input[required], select[required]");
    for (let field of requiredFields) {
      if (!field.value.trim()) {
        field.reportValidity();
        return false;
      }
    }
    return true;
  }

  function showFeedback(message, type) {
    formFeedback.className = `alert alert-${type}`;
    formFeedback.textContent = message;
    formFeedback.classList.remove("d-none");
  }

  function simulateSubmit(isPermanent) {
    if (!validateForm()) return;

    const btn = isPermanent ? btnPermanen : btnSementara;
    btn.innerHTML = '<span class="loading"></span> Memproses...';
    btn.disabled = true;

    setTimeout(() => {
      const success = Math.random() < 0.9;
      if (success) {
        showFeedback(
          isPermanent ? "Perubahan permanen berhasil disimpan!" : "Perubahan sementara berhasil disimpan!",
          "success"
        );
      } else {
        showFeedback("Terjadi kesalahan. Silakan coba lagi.", "danger");
      }
      btn.innerHTML = isPermanent ? "Ubah Permanen" : "Ubah Sementara";
      btn.disabled = false;
    }, 1500);
  }

  btnSementara.addEventListener("click", () => simulateSubmit(false));
  btnPermanen.addEventListener("click", () => {
    if (confirm("Yakin ingin mengubah kelas secara permanen?")) {
      simulateSubmit(true);
    }
  });
});
