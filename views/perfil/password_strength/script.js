// Función para verificar coincidencia de contraseñas
function checkPasswordMatch() {
    const newPassword = document.getElementById('nuevo_password').value;
    const confirmPassword = document.getElementById('confirmar_password').value;
    const errorElement = document.getElementById('confirmPasswordError');
    const submitBtn = document.getElementById('submitBtn');
    
    if (confirmPassword === '') {
        errorElement.textContent = '';
        submitBtn.disabled = newPassword.length < 6;
        return;
    }
    
    if (newPassword !== confirmPassword) {
        errorElement.textContent = 'Las contraseñas no coinciden';
        submitBtn.disabled = true;
    } else {
        errorElement.textContent = '';
        submitBtn.disabled = newPassword.length < 6;
    }
}

// Validación al enviar el formulario
document.getElementById('passwordForm').addEventListener('submit', function(e) {
    const newPassword = document.getElementById('nuevo_password').value;
    const confirmPassword = document.getElementById('confirmar_password').value;
    
    if (newPassword.length < 6) {
        e.preventDefault();
        document.getElementById('newPasswordError').textContent = 'La contraseña debe tener al menos 6 caracteres';
        return;
    }
    
    if (newPassword !== confirmPassword) {
        e.preventDefault();
        document.getElementById('confirmPasswordError').textContent = 'Las contraseñas no coinciden';
        return;
    }
});