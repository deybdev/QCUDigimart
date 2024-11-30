<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Modal</title>
</head>
<body>

<!-- SUCCESS MODAL START-->
<div id="successModal" class="success-modal">
    <div class="success-modal-content">
        <div class="check-animation">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52" class="checkmark">
                <circle class="checkmark-circle" cx="26" cy="26" r="25" fill="none" />
                <path class="checkmark-check" fill="none" d="M14 26l8 8 17-17" />
            </svg>
        </div>
        <h2>Success</h2>
        <p id="successMessage">Your action was successful!</p>
        <button onclick="closeModal('successModal')">OK</button>
    </div>
</div>
<!-- SUCCESS MODAL END-->

<!-- INFO MODAL START -->
<div id="infoModal" class="info-modal">
    <div class="info-modal-content">
        <div class="info-animation">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52" class="info-icon">
                <circle class="info-circle" cx="26" cy="26" r="25" fill="none" />
                <line class="info-line" x1="26" y1="14" x2="26" y2="30" />
                <circle class="info-dot" cx="26" cy="38" r="2" />
            </svg>
        </div>
        <p id="infoMessage">Here is some helpful information.</p>
        <button onclick="closeModal('infoModal')">OK</button>
    </div>
</div>
<!-- INFO MODAL END -->


<script>

function showSuccessModal(success_message) {
    const successModal = document.getElementById('successModal');
    const successMessage = document.getElementById('successMessage');

    successMessage.textContent = success_message;

    successModal.classList.add('active');

}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('active');
    }
}

function showInfoModal(info_message) {
    const infoModal = document.getElementById('infoModal');
    const infoMessage = document.getElementById('infoMessage');

    infoMessage.textContent = info_message;

    infoModal.classList.add('active');
}

// Function to close the modal
function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('active');
    }
}

</script>

</body>
</html>