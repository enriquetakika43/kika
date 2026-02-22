// ===== CONFIGURACIÓN GEMINI =====
const GEMINI_API_KEY = 'AIzaSyBSwC8HTwnYOJm22_56-noHRLrlu8vGLhg';
const GEMINI_API_URL = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent';

// ===== VARIABLES GLOBALES =====
let messageCount = 1;
let isLoading = false;
let conversationHistory = [];  // ✅ Guardar historial
let systemPromptAdded = false; // ✅ Controlar si ya se agregó el system prompt

// ===== SISTEMA DE PROMPT PARA EDUCACIÓN SEXUAL =====
const systemPrompt = `Eres Zellin, un asistente educativo especializado en educación sexual integral, respetuoso y científico.

SOLO responde preguntas sobre:
- Métodos anticonceptivos (píldora, condón, DIU, implante, etc.)
- Anticonceptivos de emergencia
- Infecciones de transmisión sexual (ITS) y prevención
- Salud sexual y reproductiva
- Consentimiento, comunicación y relaciones saludables
- Pubertad y desarrollo sexual
- Ciclo menstrual y fertilidad
- Derechos sexuales y reproductivos

Si alguien pregunta sobre otro tema, responde: "Soy especialista en educación sexual. ¿Tienes alguna duda sobre métodos anticonceptivos, ITS, consentimiento o relaciones saludables?"

Sé breve, claro y respeta a los adolescentes.`;

// ===== GET CURRENT TIME =====
function getCurrentTime() {
    const now = new Date();
    return now.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' });
}

// ===== SEND MESSAGE =====
document.getElementById('messageForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const input = document.getElementById('messageInput');
    const message = input.value.trim();

    if (!message || isLoading) return;

    // Add user message
    addMessage(message, 'user');
    
    // ✅ Guardar en historial
    conversationHistory.push({
        role: 'user',
        parts: [{ text: message }]
    });
    
    input.value = '';
    input.disabled = true;
    document.querySelector('.send-button').disabled = true;
    isLoading = true;

    // Show loading indicator
    showLoadingMessage();

    try {
        const botResponse = await getBotResponseFromGemini(message);
        removeLoadingMessage();
        addMessage(botResponse, 'bot');
        
        // ✅ Guardar respuesta en historial
        conversationHistory.push({
            role: 'model',
            parts: [{ text: botResponse }]
        });
    } catch (error) {
        console.error('Error completo:', error);
        removeLoadingMessage();
        addMessage('⚠️ Hubo un error. Por favor intenta de nuevo en unos momentos.', 'bot');
    } finally {
        input.disabled = false;
        document.querySelector('.send-button').disabled = false;
        isLoading = false;
    }
});

// ===== ADD MESSAGE TO CHAT =====
function addMessage(text, sender) {
    const container = document.getElementById('messagesContainer');
    const messageWrapper = document.createElement('div');
    messageWrapper.className = `message-wrapper ${sender}`;

    const messageDiv = document.createElement('div');
    messageDiv.className = `message ${sender === 'user' ? 'user-msg' : 'bot-msg'}`;

    const textP = document.createElement('p');
    textP.textContent = text;

    const timeSpan = document.createElement('span');
    timeSpan.className = 'message-time';
    timeSpan.textContent = getCurrentTime();

    messageDiv.appendChild(textP);
    messageDiv.appendChild(timeSpan);
    messageWrapper.appendChild(messageDiv);
    container.appendChild(messageWrapper);

    // Scroll to bottom
    setTimeout(() => {
        container.scrollTop = container.scrollHeight;
    }, 100);
}

// ===== SHOW LOADING MESSAGE =====
function showLoadingMessage() {
    const container = document.getElementById('messagesContainer');
    const messageWrapper = document.createElement('div');
    messageWrapper.className = 'message-wrapper bot';
    messageWrapper.id = 'loading-message';

    const messageDiv = document.createElement('div');
    messageDiv.className = 'message bot-msg loading';

    const spinner = document.createElement('i');
    spinner.className = 'fas fa-spinner spinner';

    const textP = document.createElement('p');
    textP.textContent = 'Zellin está escribiendo...';

    messageDiv.appendChild(spinner);
    messageDiv.appendChild(textP);
    messageWrapper.appendChild(messageDiv);
    container.appendChild(messageWrapper);

    container.scrollTop = container.scrollHeight;
}

// ===== REMOVE LOADING MESSAGE =====
function removeLoadingMessage() {
    const loadingMessage = document.getElementById('loading-message');
    if (loadingMessage) {
        loadingMessage.remove();
    }
}

// ===== GET BOT RESPONSE FROM GEMINI API =====
async function getBotResponseFromGemini(userMessage) {
    try {
        console.log('Enviando mensaje a Gemini 2.5 Flash...');
        console.log('Historial de conversación:', conversationHistory);

        // ✅ CORRECTO: Incluir system prompt en el primer mensaje del usuario
        const contents = [];
        
        // Solo la primera vez, incluir el system prompt con el primer mensaje
        if (!systemPromptAdded) {
            contents.push({
                role: 'user',
                parts: [{ text: systemPrompt + '\n\nPrimer mensaje del usuario: ' + userMessage }]
            });
            systemPromptAdded = true;
            
            // No agregar el primer mensaje al historial nuevamente
            conversationHistory.pop(); // Quitar el último mensaje agregado
        } else {
            // Agregar todo el historial de conversación normalmente
            contents.push(...conversationHistory);
        }

        const requestBody = {
            contents: contents
        };

        console.log('Request body:', JSON.stringify(requestBody, null, 2));

        const response = await fetch(`${GEMINI_API_URL}?key=${GEMINI_API_KEY}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(requestBody),
            timeout: 30000
        });

        console.log('Estado de respuesta:', response.status);

        // Leer la respuesta primero
        const textResponse = await response.text();
        console.log('Respuesta del servidor:', textResponse);

        if (!response.ok) {
            console.error('Error HTTP:', response.status, textResponse);
            
            if (response.status === 400) {
                return '❌ Error en la solicitud. Por favor reformula tu pregunta.';
            }
            if (response.status === 401) {
                return '❌ La API key no es válida. Verifica la configuración.';
            }
            if (response.status === 403) {
                return '❌ Acceso denegado. Verifica que tu API key esté habilitada.';
            }
            if (response.status === 404) {
                return '❌ El modelo no se encuentra.';
            }
            if (response.status === 429) {
                return '⏳ Demasiadas solicitudes. Espera un momento e intenta de nuevo. (Límite gratis: 60 req/min)';
            }
            if (response.status === 500) {
                return '⚠️ El servidor de Gemini está experimentando problemas. Intenta más tarde.';
            }
            
            throw new Error(`HTTP ${response.status}: ${textResponse}`);
        }

        // Ahora parsea como JSON
        let data;
        try {
            data = JSON.parse(textResponse);
        } catch (e) {
            console.error('Error al parsear JSON:', e, textResponse);
            return '❌ Error al procesar la respuesta del servidor.';
        }

        console.log('Datos parseados:', data);

        if (data.candidates && data.candidates[0] && data.candidates[0].content) {
            const botText = data.candidates[0].content.parts[0].text;
            console.log('Respuesta del bot:', botText);
            return botText || '❓ No pude generar una respuesta. Intenta de nuevo.';
        }

        if (data.error) {
            console.error('Error en la API:', data.error);
            return `❌ Error: ${data.error.message}`;
        }

        console.error('Respuesta inesperada:', data);
        return '❌ Respuesta inesperada del servidor.';

    } catch (error) {
        console.error('Error en la solicitud:', error);
        console.error('Tipo de error:', error.name);
        console.error('Mensaje de error:', error.message);
        
        if (error.message.includes('Failed to fetch')) {
            return '🌐 Error de conexión. Verifica tu conexión a internet.';
        }
        
        throw error;
    }
}

// ===== LIMPIAR CONVERSACIÓN =====
function clearConversation() {
    conversationHistory = [];
    systemPromptAdded = false;
    document.getElementById('messagesContainer').innerHTML = '';
    console.log('✅ Conversación limpiada');
}

// ===== NAVIGATION =====
document.querySelectorAll('.dock-item').forEach(item => {
    item.addEventListener('click', function(e) {
        // Si tiene data-page, es una navegación interna
        const pageId = this.getAttribute('data-page');
        
        if (pageId) {
            // Navegación interna
            e.preventDefault();

            document.querySelectorAll('.dock-item').forEach(i => i.classList.remove('active'));
            this.classList.add('active');

            document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));

            const page = document.getElementById(pageId);
            if (page) {
                page.classList.add('active');
            }
        } else {
            // Si no tiene data-page, es un enlace externo (como ajustes.html)
            // Dejar que funcione normalmente sin preventDefault
        }
    });
});

// ===== ENTER KEY TO SEND MESSAGE =====
document.getElementById('messageInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter' && !e.shiftKey && !isLoading) {
        e.preventDefault();
        document.getElementById('messageForm').dispatchEvent(new Event('submit'));
    }
});

// Initialize first time
document.addEventListener('DOMContentLoaded', function() {
    const initialTime = getCurrentTime();
    const timeElement = document.getElementById('time-1');
    if (timeElement) {
        timeElement.textContent = initialTime;
    }
});