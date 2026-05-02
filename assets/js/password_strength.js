// Función para verificar la fortaleza de la contraseña (reutilizable)
function checkPasswordStrength(password) {
    const strengthBars = document.querySelectorAll('.strength-bar');
    const strengthText = document.querySelector('.strength-text');
    const errorElement = document.getElementById('newPasswordError');
    const submitBtn = document.getElementById('submitBtn');
    
    // Resetear estilos
    strengthBars.forEach(bar => {
        bar.style.backgroundColor = '#e9ecef';
        bar.style.height = '4px';
    });
    errorElement.textContent = '';
    
    // Verificar longitud mínima
    if (password.length < 6) {
        strengthText.textContent = 'Seguridad: muy baja';
        strengthText.style.color = '#dc3545';
        errorElement.textContent = 'La contraseña debe tener al menos 6 caracteres';
        submitBtn.disabled = true;
        return;
    }
    
    // Calcular fortaleza
    let strength = 0;
    
    // Longitud
    if (password.length >= 8) strength++;
    if (password.length >= 12) strength++;
    
    // Caracteres diversos
    if (/[A-Z]/.test(password)) strength++; // Mayúsculas
    if (/[0-9]/.test(password)) strength++; // Números
    if (/[^A-Za-z0-9]/.test(password)) strength++; // Símbolos
    
    // Actualizar UI según fortaleza
    let color, text;
    if (strength <= 2) {
        color = '#dc3545'; // Rojo
        text = 'Seguridad: baja';
    } else if (strength <= 4) {
        color = '#fd7e14'; // Naranja
        text = 'Seguridad: media';
    } else {
        color = '#28a745'; // Verde
        text = 'Seguridad: alta';
    }
    
    // Colorear las barras
    for (let i = 0; i < strengthBars.length; i++) {
        if (i < strength) {
            strengthBars[i].style.backgroundColor = color;
        }
    }
    
    strengthText.textContent = text;
    strengthText.style.color = color;
    
    // Verificar coincidencia de contraseñas
    if (typeof checkPasswordMatch === 'function') {
        checkPasswordMatch();
    }
}