// Booking modal functions
function openBookingModal() {
    document.getElementById('bookingModal').classList.add('active');
    document.getElementById('gameScoreField').value = '';
}

function openBookingWithScore() {
    document.getElementById('bookingModal').classList.add('active');
    document.getElementById('gameScoreField').value = score;
}

function closeBookingModal() {
    document.getElementById('bookingModal').classList.remove('active');
    document.getElementById('formMessage').className = 'form-message';
    document.getElementById('formMessage').textContent = '';
}

// Close modal on overlay click
document.getElementById('bookingModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeBookingModal();
    }
});

// Close modal on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeBookingModal();
    }
});

// Form submission
document.getElementById('bookingForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const submitBtn = this.querySelector('.submit-btn');
    const formMessage = document.getElementById('formMessage');

    submitBtn.disabled = true;
    submitBtn.textContent = 'SENDING...';

    fetch('booking-handler.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            formMessage.className = 'form-message success';
            formMessage.textContent = 'Thanks! We\'ll be in touch soon.';
            this.reset();
        } else {
            formMessage.className = 'form-message error';
            formMessage.textContent = data.message || 'Something went wrong. Please try again.';
        }
    })
    .catch(error => {
        formMessage.className = 'form-message error';
        formMessage.textContent = 'Something went wrong. Please try again.';
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.textContent = 'SEND INQUIRY';
    });
});
