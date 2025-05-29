function goToStep(step) {
    document.querySelectorAll('.step-container').forEach(container => {
        container.classList.remove('active');
    });

    document.getElementById('offlineAlert').style.display = 'none';

    if (step === 2) {
        document.getElementById('step2').classList.add('active');
        document.getElementById('startTime').textContent = formatTime(document.getElementById('timeStart').value);
        document.getElementById('endTime').textContent = formatTime(document.getElementById('timeEnd').value);
        document.getElementById('duration').textContent = document.getElementById('timerInput').value;
    } else if (step === 3) {
        document.getElementById('step3').classList.add('active');
    } else {
        document.getElementById('step1').classList.add('active');
    }
}

function formatTime(timeString) {
    return timeString.replace(':', '.');
}

function showOfflineMessage() {
    document.getElementById('offlineAlert').style.display = 'block';
    setTimeout(() => {
        document.getElementById('offlineAlert').style.display = 'none';
    }, 3000);
}

function showSuccessMessage() {
    document.getElementById('finalStatus').textContent = 'Online';
    document.querySelectorAll('.step-container').forEach(container => {
        container.classList.remove('active');
    });
    document.getElementById('successMessage').classList.add('active');
}

function resetForm() {
    window.location.href = '#'; // Simulasi redirect
    goToStep(1);
}
