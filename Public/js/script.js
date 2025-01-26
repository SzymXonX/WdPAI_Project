document.addEventListener('DOMContentLoaded', function () {
    const eyeIcon = document.getElementById('eye');
    if (eyeIcon) {
        eyeIcon.addEventListener('click', function () {
            const passwordInput = document.getElementById('password');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.src = 'Public/images/open_eye_password.png'; // Change to open eye icon
            } else {
                passwordInput.type = 'password';
                eyeIcon.src = 'Public/images/closed_eye_password.png'; // Change to closed eye icon
            }
        });
    }

    const confirmEyeIcon = document.getElementById('confirm-eye');
    if (confirmEyeIcon) {
        confirmEyeIcon.addEventListener('click', function () {
            const confirmPasswordInput = document.getElementById('confirm-password');
            if (confirmPasswordInput.type === 'password') {
                confirmPasswordInput.type = 'text';
                confirmEyeIcon.src = 'Public/images/open_eye_password.png'; // Change to open eye icon
            } else {
                confirmPasswordInput.type = 'password';
                confirmEyeIcon.src = 'Public/images/closed_eye_password.png'; // Change to closed eye icon
            }
        });
    }
});
