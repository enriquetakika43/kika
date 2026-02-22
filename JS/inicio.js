// Datos de las 5 etapas con sus unidades y lecciones
const etapasData = {
    1: {
        nombre: 'Fundamentos Biológicos y Cuerpo',
        icono: '🧬',
        descripcion: 'Aprende los conceptos básicos de anatomía, hormonas y ciclos reproductivos',
        color: '#a78bfa',
        unidades: [
            {
                id: 'anatomia',
                nombre: 'Anatomía',
                icono: '🫀',
                descripcion: 'Nombres reales y funciones del cuerpo',
                lecciones: [
                    { numero: 1, titulo: 'Nombres reales: Vulva, pene, vagina y testículos', xp: 15, enlace: '#' },
                    { numero: 2, titulo: 'Anatomía interna: Útero, ovarios y próstata', xp: 15, enlace: '#' },
                    { numero: 3, titulo: 'El clítoris: Anatomía y funciones', xp: 15, enlace: '#' },
                    { numero: 4, titulo: 'Zonas erógenas y terminaciones nerviosas', xp: 15, enlace: '#' },
                    { numero: 5, titulo: 'Diversidad corporal y variaciones naturales', xp: 15, enlace: '#' }
                ]
            },
            {
                id: 'hormonas',
                nombre: 'Hormonas',
                icono: '⚗️',
                descripcion: 'Cómo funcionan las hormonas',
                lecciones: [
                    { numero: 1, titulo: 'Estrógeno, progesterona y testosterona', xp: 15, enlace: '#' },
                    { numero: 2, titulo: 'Cambios físicos y emocionales en la pubertad', xp: 15, enlace: '#' },
                    { numero: 3, titulo: 'Hormonas y deseo sexual', xp: 15, enlace: '#' },
                    { numero: 4, titulo: 'Neuroquímica: Oxitocina, dopamina y placer', xp: 15, enlace: '#' },
                    { numero: 5, titulo: 'Transiciones biológicas: Menopausia y andropausia', xp: 15, enlace: '#' }
                ]
            },
            {
                id: 'ciclo',
                nombre: 'Ciclo Menstrual',
                icono: '🔄',
                descripcion: 'Las fases del ciclo menstrual',
                lecciones: [
                    { numero: 1, titulo: 'Las cuatro fases del ciclo', xp: 15, enlace: '#' },
                    { numero: 2, titulo: 'La menstruación: Mitos y realidades', xp: 15, enlace: '#' },
                    { numero: 3, titulo: 'Métodos de gestión menstrual', xp: 15, enlace: '#' },
                    { numero: 4, titulo: 'Salud menstrual y detección de anomalías', xp: 15, enlace: '#' },
                    { numero: 5, titulo: 'Ventana de fertilidad y ovulación', xp: 15, enlace: '#' }
                ]
            }
        ]
    },
    2: {
        nombre: 'Salud y Prevención',
        icono: '🏥',
        descripcion: 'Higiene, anticonceptivos e infecciones de transmisión sexual',
        color: '#fb923c',
        unidades: [
            {
                id: 'higiene',
                nombre: 'Higiene Básica',
                icono: '🧼',
                descripcion: 'Cuidados de salud genital',
                lecciones: [
                    { numero: 1, titulo: 'Cuidado y limpieza de la vulva y vagina', xp: 15, enlace: '#' },
                    { numero: 2, titulo: 'Higiene del pene y el prepucio', xp: 15, enlace: '#' },
                    { numero: 3, titulo: 'Salud genital y selección de textiles', xp: 15, enlace: '#' },
                    { numero: 4, titulo: 'Prácticas de higiene post-coital', xp: 15, enlace: '#' },
                    { numero: 5, titulo: 'Autoexploración y detección temprana', xp: 15, enlace: '#' }
                ]
            },
            {
                id: 'anticonceptivos',
                nombre: 'Métodos Anticonceptivos',
                icono: '🛡️',
                descripcion: 'Opciones de planificación familiar',
                lecciones: [
                    { numero: 1, titulo: 'Métodos de barrera: Condones y lubricantes', xp: 15, enlace: '#' },
                    { numero: 2, titulo: 'Anticonceptivos hormonales de corta duración', xp: 15, enlace: '#' },
                    { numero: 3, titulo: 'Métodos de larga duración (LARC): DIU e implantes', xp: 15, enlace: '#' },
                    { numero: 4, titulo: 'Anticoncepción de emergencia', xp: 15, enlace: '#' },
                    { numero: 5, titulo: 'Métodos definitivos: Vasectomía y ligadura', xp: 15, enlace: '#' }
                ]
            },
            {
                id: 'its',
                nombre: 'ITS y ETS',
                icono: '⚠️',
                descripcion: 'Infecciones y enfermedades de transmisión sexual',
                lecciones: [
                    { numero: 1, titulo: 'Diferencia entre infección (ITS) y enfermedad (ETS)', xp: 15, enlace: '#' },
                    { numero: 2, titulo: 'Infecciones bacterianas y virales comunes', xp: 15, enlace: '#' },
                    { numero: 3, titulo: 'Prevención y realidades sobre el VIH', xp: 15, enlace: '#' },
                    { numero: 4, titulo: 'Vías de transmisión y reducción de riesgos', xp: 15, enlace: '#' },
                    { numero: 5, titulo: 'Pruebas de detección y comunicación con la pareja', xp: 15, enlace: '#' }
                ]
            }
        ]
    },
    3: {
        nombre: 'Identidad y Diversidad',
        icono: '🌈',
        descripcion: 'Género, orientación sexual e identidad',
        color: '#38bdf8',
        unidades: [
            {
                id: 'sexo-biologico',
                nombre: 'Diferencia de Sexo Biológico',
                icono: '🧬',
                descripcion: 'Características biológicas',
                lecciones: [
                    { numero: 1, titulo: 'Cromosomas, gónadas y hormonas', xp: 15, enlace: '#' },
                    { numero: 2, titulo: 'Introducción a la intersexualidad', xp: 15, enlace: '#' },
                    { numero: 3, titulo: 'Espectro biológico más allá del binarismo', xp: 15, enlace: '#' },
                    { numero: 4, titulo: 'Desarrollo embrionario y diferenciación', xp: 15, enlace: '#' },
                    { numero: 5, titulo: 'Sexo asignado al nacer y registros médicos', xp: 15, enlace: '#' }
                ]
            },
            {
                id: 'identidad-genero',
                nombre: 'Identidad de Género',
                icono: '👥',
                descripcion: 'Expresión e identidad personal',
                lecciones: [
                    { numero: 1, titulo: 'Conceptos de identidad y expresión de género', xp: 15, enlace: '#' },
                    { numero: 2, titulo: 'Personas cisgénero y transgénero', xp: 15, enlace: '#' },
                    { numero: 3, titulo: 'Identidades no binarias y fluidez', xp: 15, enlace: '#' },
                    { numero: 4, titulo: 'Socialización y roles de género', xp: 15, enlace: '#' },
                    { numero: 5, titulo: 'Procesos de transición y acompañamiento', xp: 15, enlace: '#' }
                ]
            },
            {
                id: 'orientacion-sexual',
                nombre: 'Orientación Sexual',
                icono: '💓',
                descripcion: 'Atracción y orientaciones',
                lecciones: [
                    { numero: 1, titulo: 'Atracción sexual vs. atracción romántica', xp: 15, enlace: '#' },
                    { numero: 2, titulo: 'Heterosexualidad, homosexualidad y bisexualidad', xp: 15, enlace: '#' },
                    { numero: 3, titulo: 'Pansexualidad, asexualidad y arromanticismo', xp: 15, enlace: '#' },
                    { numero: 4, titulo: 'La fluidez de la orientación sexual', xp: 15, enlace: '#' },
                    { numero: 5, titulo: 'Procesos personales y salida del clóset', xp: 15, enlace: '#' }
                ]
            },
            {
                id: 'vocabulario',
                nombre: 'Vocabulario Inclusivo',
                icono: '💬',
                descripcion: 'Lenguaje respetuoso y correcto',
                lecciones: [
                    { numero: 1, titulo: 'Uso correcto de los pronombres', xp: 15, enlace: '#' },
                    { numero: 2, titulo: 'Glosario de siglas y terminología LGBTQIA+', xp: 15, enlace: '#' },
                    { numero: 3, titulo: 'Lenguaje clínico vs. lenguaje estigmatizante', xp: 15, enlace: '#' },
                    { numero: 4, titulo: 'Comunicación asertiva y preguntas respetuosas', xp: 15, enlace: '#' },
                    { numero: 5, titulo: 'Gestión de errores y disculpas efectivas', xp: 15, enlace: '#' }
                ]
            }
        ]
    },
    4: {
        nombre: 'Relaciones y Bienestar',
        icono: '💕',
        descripcion: 'Consentimiento, relaciones saludables y placer',
        color: '#f87171',
        unidades: [
            {
                id: 'consentimiento',
                nombre: 'Consentimiento y Límites',
                icono: '🤝',
                descripcion: 'Autonomía y comunicación',
                lecciones: [
                    { numero: 1, titulo: 'Los pilares del consentimiento (F.R.I.E.S.)', xp: 15, enlace: '#' },
                    { numero: 2, titulo: 'Comunicación de límites personales', xp: 15, enlace: '#' },
                    { numero: 3, titulo: 'Recepción del rechazo y respeto mutuo', xp: 15, enlace: '#' },
                    { numero: 4, titulo: 'Lenguaje no verbal y señales de duda', xp: 15, enlace: '#' },
                    { numero: 5, titulo: 'Situaciones de coerción y vulnerabilidad', xp: 15, enlace: '#' }
                ]
            },
            {
                id: 'banderas',
                nombre: 'Banderas (Red y Green)',
                icono: '🚩',
                descripcion: 'Indicadores en relaciones',
                lecciones: [
                    { numero: 1, titulo: 'Indicadores de una relación saludable (Green flags)', xp: 15, enlace: '#' },
                    { numero: 2, titulo: 'Comportamientos neutros e incompatibilidades', xp: 15, enlace: '#' },
                    { numero: 3, titulo: 'Señales de alerta y violencia (Red flags)', xp: 15, enlace: '#' },
                    { numero: 4, titulo: 'Desmontando los mitos del amor romántico', xp: 15, enlace: '#' },
                    { numero: 5, titulo: 'Recursos de apoyo y planes de seguridad', xp: 15, enlace: '#' }
                ]
            },
            {
                id: 'placer',
                nombre: 'Placer',
                icono: '💫',
                descripcion: 'Sexualidad y satisfacción',
                lecciones: [
                    { numero: 1, titulo: 'Derecho al placer y autonomía corporal', xp: 15, enlace: '#' },
                    { numero: 2, titulo: 'Masturbación y autoconocimiento', xp: 15, enlace: '#' },
                    { numero: 3, titulo: 'Comunicación de preferencias sexuales', xp: 15, enlace: '#' },
                    { numero: 4, titulo: 'Factores psicológicos en la respuesta sexual', xp: 15, enlace: '#' },
                    { numero: 5, titulo: 'Herramientas complementarias y lubricación', xp: 15, enlace: '#' }
                ]
            }
        ]
    },
    5: {
        nombre: 'Avanzado y Especializado',
        icono: '⭐',
        descripcion: 'Temas especializados y educación avanzada',
        color: '#fdba74',
        unidades: [
            {
                id: 'kink-bdsm',
                nombre: 'Kink y BDSM',
                icono: '⛓️',
                descripcion: 'Prácticas consensuales seguras',
                lecciones: [
                    { numero: 1, titulo: 'Conceptos básicos y vocabulario', xp: 15, enlace: '#' },
                    { numero: 2, titulo: 'Negociación y límites claros', xp: 15, enlace: '#' },
                    { numero: 3, titulo: 'Seguridad y palabras de seguridad', xp: 15, enlace: '#' },
                    { numero: 4, titulo: 'Prácticas populares y consideraciones', xp: 15, enlace: '#' },
                    { numero: 5, titulo: 'Cuidados posteriores (aftercare)', xp: 15, enlace: '#' }
                ]
            }
        ]
    }
};

let etapaActual = null;
let unidadActual = null;

// Generar estrellas
function generateStars() {
    const starsContainer = document.getElementById('stars');
    for (let i = 0; i < 100; i++) {
        const star = document.createElement('div');
        star.className = 'star';
        star.style.left = Math.random() * 100 + '%';
        star.style.top = Math.random() * 100 + '%';
        star.style.animationDelay = Math.random() * 3 + 's';
        starsContainer.appendChild(star);
    }
}

// Abrir una etapa (planeta)
function abrirEtapa(etapaNum) {
    if (etapaNum === 0) return;

    etapaActual = etapaNum;
    unidadActual = null;

    const etapa = etapasData[etapaNum];
    const vistaSistemaSolar = document.getElementById('vistaSistemaSolar');
    const vistaEtapa = document.getElementById('vistaEtapaExpandida');
    const leccionesExpandidas = document.getElementById('leccionesExpandidas');

    // Actualizar header
    document.getElementById('etapaLabel').textContent = `ETAPA ${etapaNum}`;
    document.getElementById('etapaTitulo').textContent = etapa.nombre;

    // Actualizar vista de etapa
    document.getElementById('etapaIconoGrande').textContent = etapa.icono;
    document.getElementById('etapaTituloExpandido').textContent = etapa.nombre;
    document.getElementById('etapaDescripcion').textContent = etapa.descripcion;

    // Renderizar unidades
    renderUnidades(etapa.unidades);

    // Mostrar/ocultar vistas
    vistaSistemaSolar.style.display = 'none';
    vistaEtapa.style.display = 'block';
    leccionesExpandidas.style.display = 'none';
    
    // Scroll al inicio
    vistaEtapa.scrollTop = 0;
}

// Renderizar unidades
function renderUnidades(unidades) {
    const container = document.getElementById('unidadesContainer');
    container.innerHTML = '';

    unidades.forEach((unidad) => {
        const card = document.createElement('div');
        card.className = 'unidad-card';
        card.onclick = () => abrirLecciones(unidad);

        card.innerHTML = `
            <div class="unidad-card-header">
                <div class="unidad-icono">${unidad.icono}</div>
                <div>
                    <div class="unidad-titulo">${unidad.nombre}</div>
                    <div class="unidad-descripcion">${unidad.descripcion}</div>
                </div>
            </div>
            <div class="unidad-meta">
                <div class="unidad-lecciones">${unidad.lecciones.length} lecciones</div>
                <div class="unidad-arrow"><i class="fas fa-arrow-right"></i></div>
            </div>
        `;

        container.appendChild(card);
    });
}

// Abrir lecciones de una unidad
function abrirLecciones(unidad) {
    unidadActual = unidad.id;

    document.getElementById('unidadTituloExpandido').textContent = unidad.nombre;
    document.getElementById('unidadDescripcionExpandida').textContent = unidad.descripcion;
    
    const listContainer = document.getElementById('leccionesLista');
    listContainer.innerHTML = '';

    unidad.lecciones.forEach((leccion) => {
        const item = document.createElement('div');
        item.className = 'leccion-item';
        item.onclick = () => {
            if (leccion.enlace !== '#') {
                window.location.href = leccion.enlace;
            }
        };

        item.innerHTML = `
            <div class="leccion-header">
                <div class="leccion-numero">${leccion.numero}</div>
                <div>
                    <div class="leccion-titulo">${leccion.titulo}</div>
                    <div class="leccion-descripcion">Haz clic para comenzar</div>
                </div>
            </div>
            <div class="leccion-footer">
                <div class="leccion-xp">
                    <span>+${leccion.xp}</span>
                    <span>XP</span>
                </div>
                <div class="leccion-arrow"><i class="fas fa-arrow-right"></i></div>
            </div>
        `;

        listContainer.appendChild(item);
    });

    // Mostrar lecciones expandidas
    document.getElementById('leccionesExpandidas').style.display = 'block';
}

// Volver al sistema solar
function volverASistemaSolar() {
    etapaActual = null;
    unidadActual = null;

    document.getElementById('vistaSistemaSolar').style.display = 'flex';
    document.getElementById('vistaEtapaExpandida').style.display = 'none';
    document.getElementById('leccionesExpandidas').style.display = 'none';

    // Restaurar header
    document.getElementById('etapaLabel').textContent = 'ETAPA 1';
    document.getElementById('etapaTitulo').textContent = 'Explora el Sistema Solar';
}

// Volver a unidades
function volverAUnidades() {
    document.getElementById('leccionesExpandidas').style.display = 'none';
    unidadActual = null;
}

// Inicializar
document.addEventListener('DOMContentLoaded', function() {
    generateStars();
});