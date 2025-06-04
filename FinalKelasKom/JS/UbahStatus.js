function goToStep(step) {
    document.querySelectorAll('.step-container').forEach(container => {
        container.classList.remove('active');
    });

    document.getElementById('offlineAlert').style.display = 'none';

    if (step === 2) {
        document.getElementById('step2').classList.add('active');
    } else if (step === 3) {
        document.getElementById('step3').classList.add('active');
    } else if (step === 4) {
        document.getElementById('successMessage').classList.add('active');
    } else {
        document.getElementById('step1').classList.add('active');
    }
}

function showOfflineMessage() {
    document.getElementById('offlineAlert').style.display = 'block';
    setTimeout(() => {
        document.getElementById('offlineAlert').style.display = 'none';
    }, 3000);
}

document.getElementById('statusForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const duration = document.getElementById('duration').value;
    if (duration < 30 || duration > 180) {
        alert('Durasi harus antara 30-180 menit');
        return;
    }

    const formData = new FormData(this);
    fetch('ProsesUbahStatus.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            goToStep(4);
        } else {
            alert(data.message || 'Terjadi kesalahan. Silakan coba lagi.');
        }
    })
    .catch(() => {
        alert('Terjadi kesalahan. Silakan coba lagi.');
    });
});