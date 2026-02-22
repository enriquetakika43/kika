// ===== CONFIGURACIÓN =====
const API_BASE_URL = './PHP';

// ===== INICIALIZAR =====
document.addEventListener('DOMContentLoaded', async function() {
    await cargarPerfil();
    inicializarEventos();
});

// ===== CARGAR PERFIL =====
async function cargarPerfil() {
    try {
        const response = await fetch(`${API_BASE_URL}/obtener_usuario.php`, {
            method: 'GET',
            credentials: 'same-origin' // Mantener sesión
        });

        const resultado = await response.json();

        if (!resultado.success) {
            console.error('Error del servidor:', resultado.message);
            document.getElementById('usernameDisplay').textContent = resultado.message;
            return;
        }

        const userData = resultado.data;

        // Llenar datos
        document.getElementById('usernameDisplay').textContent = userData.usuario;
        document.getElementById('emailDisplay').textContent = userData.correo_electronico;
        document.getElementById('securityCode').textContent = userData.codigo_seguridad;

        // Foto
        if (userData.foto_perfil_url) {
            document.getElementById('profilePhoto').src = userData.foto_perfil_url;
        }

    } catch (error) {
        console.error('Error en cargarPerfil:', error);
        document.getElementById('usernameDisplay').textContent = 'Error al cargar';
    }
}

// ===== INICIALIZAR EVENTOS =====
function inicializarEventos() {
    // Subir foto
    const photoInput = document.getElementById('photoInput');
    if (photoInput) {
        photoInput.addEventListener('change', subirFoto);
    }

    // Copiar código
    const copyCodeBtn = document.getElementById('copyCodeBtn');
    if (copyCodeBtn) {
        copyCodeBtn.addEventListener('click', copiarCodigo);
    }

    // Cerrar sesión
    const logoutBtn = document.getElementById('logoutBtn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', cerrarSesion);
    }
}

// ===== SUBIR FOTO =====
async function subirFoto(e) {
    const file = e.target.files[0];
    if (!file) return;

    const formData = new FormData();
    formData.append('foto_perfil', file);

    const statusDiv = document.getElementById('uploadStatus');
    statusDiv.textContent = 'Subiendo...';

    try {
        const response = await fetch(`${API_BASE_URL}/subir_foto.php`, {
            method: 'POST',
            body: formData,
            credentials: 'same-origin'
        });

        const resultado = await response.json();

        if (resultado.success) {
            document.getElementById('profilePhoto').src = resultado.foto_perfil + '?t=' + Date.now();
            statusDiv.textContent = '✅ Foto guardada';
            setTimeout(() => {
                statusDiv.textContent = '';
            }, 2000);
        } else {
            statusDiv.textContent = '❌ ' + resultado.message;
        }
    } catch (error) {
        console.error('Error:', error);
        statusDiv.textContent = '❌ Error de conexión';
    }
}

// ===== COPIAR CÓDIGO =====
function copiarCodigo() {
    const code = document.getElementById('securityCode').textContent;
    
    if (code === 'Cargando...' || code === 'Error al cargar') {
        alert('⚠️ Espera a que cargue');
        return;
    }

    navigator.clipboard.writeText(code).then(() => {
        const btn = document.getElementById('copyCodeBtn');
        const originalHTML = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i>';
        btn.classList.add('copied');
        setTimeout(() => {
            btn.innerHTML = originalHTML;
            btn.classList.remove('copied');
        }, 2000);
    }).catch(() => {
        alert('❌ Error al copiar');
    });
}

// ===== CERRAR SESIÓN =====
function cerrarSesion() {
    if (confirm('¿Estás seguro de que deseas cerrar sesión?')) {
        fetch('./PHP/logout.php', {
            method: 'GET',
            credentials: 'same-origin'
        })
        .then(() => {
            window.location.href = 'login.html';
        })
        .catch(() => {
            // Aunque falle, redirige al login
            window.location.href = 'login.html';
        });
    }
}