/**
 * SUSTENTACIÓN DE PROYECTO DE GRADO - INVENTORI
 * Controller & Interactive Simulation Engine v4.0
 * Specialized Image Prompt Builder, Live SVG Canvas Generator & High-Res PNG Exporter
 */

document.addEventListener('DOMContentLoaded', () => {
    initTabNavigation();
    initSimulationEngine();
    initPresenterMode();
    initImageModal();
    initPromptBuilder();
    renderLiveSVG();
    initSVGExporter();
});

/* --------------------------------------------------------------------------
   1. Tab Navigation Controller
   -------------------------------------------------------------------------- */
function initTabNavigation() {
    const tabs = document.querySelectorAll('.nav-tab');
    const contents = document.querySelectorAll('.tab-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const targetId = tab.getAttribute('data-tab');

            tabs.forEach(t => t.classList.remove('active'));
            contents.forEach(c => c.classList.remove('active'));

            tab.classList.add('active');
            const targetContent = document.getElementById(targetId);
            if (targetContent) {
                targetContent.classList.add('active');
            }
        });
    });
}

/* --------------------------------------------------------------------------
   2. Dedicated Image Prompt Generator Engine for Midjourney / DALL-E / Flux / Imagen
   -------------------------------------------------------------------------- */
const PROMPT_TEMPLATES = {
    flujo_global: {
        title_es: "Diagrama de Flujo de Información de 3 Capas (UI -> Controller -> DB)",
        english: "Ultra-detailed software engineering data flow diagram, 3-layer architecture diagram showing User Interface (UI Views), Business Logic (Controllers in PHP), and Database Persistence (MySQL Tables: usuarios, computadores, prestamos, traslados, salas, bajas, logs_sistema). Interconnected glowing neon flow lines, executive dark glassmorphic dashboard style, crisp tech icons, 8k resolution, infographic vector composition, volumetric lighting, photorealistic tech presentation visual --ar 16:9 --v 6.0",
        spanish: "Crea una imagen infográfica hiperdetallada de un diagrama de flujo de información para un sistema web de inventario llamado INVENTORI. Debe mostrar 3 columnas o capas principales: 1) Capa de Interfaz de Usuario (UI) con formularios de Login, Dashboard y Préstamos. 2) Capa de Lógica de Negocio (Controladores PHP: AuthController, PrestamoController, TrasladoController). 3) Capa de Base de Datos MySQL con tablas de usuarios, computadores, prestamos, traslados, salas y logs. Usa líneas de neón glowing que conecten el flujo de datos. Estilo técnico ejecutivo oscuro, glassmorphism, resolución 8K.",
        tech_spec: "Entidades clave: UI (Views/AJAX) -> Controller (Router/Validaciones) -> Database (MySQL PDO Transactions)"
    },
    secuencia_prestamo: {
        title_es: "Diagrama de Secuencia: Flujo de Préstamo de Equipos",
        english: "Clean technical sequence diagram illustration showing step-by-step loan registration workflow for computer inventory software. Step 1: User submits form on UI view. Step 2: PrestamoController validates availability. Step 3: Atomic SQL transaction inserts into prestamos table and updates computadores status to 'En Prestamo'. Step 4: System audit log created. Dark sleek UI theme with glowing arrows, professional software engineering thesis diagram style --ar 16:9 --v 6.0",
        spanish: "Crea un diagrama de secuencia técnico para el proceso de Préstamo de Computadores. Muestra 4 pasos numerados: Paso 1: Usuario envía formulario de préstamo en la interfaz web. Paso 2: PrestamoController valida que el equipo esté disponible. Paso 3: Transacción SQL en MySQL que inserta en la tabla 'prestamos' y actualiza la tabla 'computadores' a estado 'En Préstamo'. Paso 4: Registro de auditoría en la tabla 'logs_sistema'. Estilo neón tecnológico oscuro.",
        tech_spec: "Flujo: User -> Form POST /prestamos/create -> PrestamoController::store() -> SQL Transaction -> SweetAlert Alert"
    },
    secuencia_traslado: {
        title_es: "Diagrama de Secuencia: Traslado de Equipos Entre Salas",
        english: "Software architecture transfer sequence diagram. Auxiliary user requests computer transfer between laboratory rooms -> TrasladoController creates pending order in traslados table -> Administrator approves -> Atomic SQL updates computer id_sala_actual to destination room. Sleek isometric 3D tech style, neon cyan and purple accent lighting, high quality engineering schematic --ar 16:9 --v 6.0",
        spanish: "Crea un diagrama de flujo y secuencia para el Traslado de Equipos de Cómputo entre Salas de Laboratorio. Muestra la solicitud del auxiliar desde la vista de traslados, el procesamiento en TrasladoController, la orden pendiente en la tabla 'traslados', la autorización del administrador y la actualización atómica del id_sala_actual del computador en MySQL. Estilo infográfico visual 3D isométrico neón.",
        tech_spec: "Flujo: Solicitud Auxiliar -> TrasladoController::store() -> Estado 'Pendiente' -> Autorización Admin -> Update 'id_sala_actual' en computadores"
    },
    erd_database: {
        title_es: "Diagrama Entidad-Relación (ERD) Completo de Base de Datos MySQL",
        english: "Comprehensive Database Entity Relationship Diagram (ERD) schema visualization. Showing connected tables: 'usuarios', 'roles', 'computadores', 'marcas', 'componentes_internos', 'salas', 'prestamos', 'traslados', 'bajas', 'logs_sistema'. Primary keys PK in gold, foreign keys FK in purple, relational arrows 1-to-N and N-to-M, crisp modern database blueprint style, glowing lines, 8k resolution --ar 16:9 --v 6.0",
        spanish: "Crea un diagrama de base de datos relacional (ERD) completo y súper nítido para MySQL. Debe incluir las tablas principales con sus columnas: usuarios, roles, computadores, marcas, componentes_internos, salas, prestamos, traslados, bajas y logs_sistema. Resalta las Claves Primarias (PK) en amarillo y las Claves Foráneas (FK) en morado, con líneas de relación 1 a Muchos claras. Estilo blueprint técnico moderno en modo oscuro.",
        tech_spec: "Tablas: usuarios, roles, computadores, marcas, componentes_internos, salas, prestamos, traslados, bajas, logs_sistema"
    },
    infraestructura_laboratorios: {
        title_es: "Diagrama de Infraestructura Técnica de Salas y Laboratorios",
        english: "Computer laboratory infrastructure diagram showing room network topology, structured cabling, UPS power stabilizer, grounding earth polo system, computer hardware specs (CPU, RAM, Disks), and inventory tracking barcodes. Futuristic dark mode IT management system visualization, 8k --ar 16:9 --v 6.0",
        spanish: "Crea un diagrama técnico de arquitectura de infraestructura para un laboratorio de cómputo universitario. Muestra la red estructurada, el polo a tierra, el estabilizador de voltaje UPS, las computadoras con sus especificaciones de CPU y RAM, y los códigos QR/barras de inventario. Estilo oscuro futurista de TI.",
        tech_spec: "Atributos de Sala: tiene_polo_a_tierra, tiene_estabilizador, tiene_red_structured, ultima_revision_infraestructura"
    }
};

function initPromptBuilder() {
    const diagramSelect = document.getElementById('builder-diagram-select');
    const styleSelect = document.getElementById('builder-style-select');
    const ratioSelect = document.getElementById('builder-ratio-select');

    const outEn = document.getElementById('prompt-output-en');
    const outEs = document.getElementById('prompt-output-es');
    const outSpec = document.getElementById('prompt-output-spec');

    const btnCopyEn = document.getElementById('btn-copy-en');
    const btnCopyEs = document.getElementById('btn-copy-es');

    if (!diagramSelect || !outEn) return;

    function updateGeneratedPrompts() {
        const key = diagramSelect.value;
        const style = styleSelect ? styleSelect.value : 'dark';
        const ratio = ratioSelect ? ratioSelect.value : '--ar 16:9';

        const template = PROMPT_TEMPLATES[key] || PROMPT_TEMPLATES.flujo_global;

        let styleModEn = "";
        let styleModEs = "";

        if (style === 'blueprint') {
            styleModEn = " engineering blueprint style, blue schematic background, grid layout, crisp white vectors, ";
            styleModEs = " estilo blueprint plano técnico de ingeniería, fondo azul esquemático, líneas blancas vectoriales nítidas, ";
        } else if (style === 'isometric') {
            styleModEs = " estilo 3D isométrico futurista con profundidad de campo, iluminado con luces neón, ";
            styleModEn = " futuristic 3D isometric perspective view, volumetric glowing lights, depth of field, ";
        } else if (style === 'corporate') {
            styleModEn = " clean minimalist corporate white infographic style, modern tech company presentation, ";
            styleModEs = " estilo infográfico corporativo moderno sobre fondo blanco limpio, presentación empresarial de alto nivel, ";
        } else {
            styleModEn = " dark executive glassmorphism theme, glowing neon accents, 8k resolution, ";
            styleModEs = " estilo ejecutivo oscuro con efecto glassmorphism, acentos neón azul y violeta, resolución 8k, ";
        }

        const finalEn = template.english.replace("--ar 16:9 --v 6.0", styleModEn + ratio + " --v 6.0");
        const finalEs = template.spanish + styleModEs;

        outEn.innerText = finalEn;
        outEs.innerText = finalEs;
        if (outSpec) outSpec.innerText = template.tech_spec;
    }

    diagramSelect.addEventListener('change', updateGeneratedPrompts);
    if (styleSelect) styleSelect.addEventListener('change', updateGeneratedPrompts);
    if (ratioSelect) ratioSelect.addEventListener('change', updateGeneratedPrompts);

    updateGeneratedPrompts();

    if (btnCopyEn) {
        btnCopyEn.addEventListener('click', () => copyText(outEn.innerText, btnCopyEn, 'Prompt en Inglés Copiado'));
    }

    if (btnCopyEs) {
        btnCopyEs.addEventListener('click', () => copyText(outEs.innerText, btnCopyEs, 'Prompt en Español Copiado'));
    }
}

function copyText(text, button, successMsg) {
    navigator.clipboard.writeText(text).then(() => {
        const originalText = button.innerHTML;
        button.innerHTML = `<i class="fa-solid fa-check"></i> ${successMsg}`;
        button.style.background = '#10B981';
        setTimeout(() => {
            button.innerHTML = originalText;
            button.style.background = '';
        }, 2500);
    });
}

/* --------------------------------------------------------------------------
   3. Live Vector SVG Canvas Renderer & High-Res PNG Exporter
   -------------------------------------------------------------------------- */
function renderLiveSVG() {
    const container = document.getElementById('live-svg-container');
    if (!container) return;

    const svgContent = `
    <svg id="svg-canvas-element" viewBox="0 0 1200 650" width="100%" height="100%" xmlns="http://www.w3.org/2000/svg" style="background:#090D16; font-family:'Inter', sans-serif; border-radius:12px;">
        <defs>
            <linearGradient id="grad-ui" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" stop-color="#3B82F6" stop-opacity="0.3"/>
                <stop offset="100%" stop-color="#1E40AF" stop-opacity="0.1"/>
            </linearGradient>
            <linearGradient id="grad-logic" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" stop-color="#8B5CF6" stop-opacity="0.3"/>
                <stop offset="100%" stop-color="#5B21B6" stop-opacity="0.1"/>
            </linearGradient>
            <linearGradient id="grad-db" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" stop-color="#06B6D4" stop-opacity="0.3"/>
                <stop offset="100%" stop-color="#0E7490" stop-opacity="0.1"/>
            </linearGradient>
            <filter id="glow">
                <feGaussianBlur stdDeviation="3" result="coloredBlur"/>
                <feMerge>
                    <feMergeNode in="coloredBlur"/>
                    <feMergeNode in="SourceGraphic"/>
                </feMerge>
            </filter>
            <marker id="arrow" viewBox="0 0 10 10" refX="6" refY="5" markerWidth="6" markerHeight="6" orient="auto-start-reverse">
                <path d="M 0 0 L 10 5 L 0 10 z" fill="#3B82F6"/>
            </marker>
            <marker id="arrow-cyan" viewBox="0 0 10 10" refX="6" refY="5" markerWidth="6" markerHeight="6" orient="auto-start-reverse">
                <path d="M 0 0 L 10 5 L 0 10 z" fill="#06B6D4"/>
            </marker>
        </defs>

        <!-- Column 1: UI Layer -->
        <rect x="40" y="40" width="340" height="570" rx="16" fill="url(#grad-ui)" stroke="#3B82F6" stroke-width="2" stroke-dasharray="4"/>
        <text x="60" y="80" fill="#3B82F6" font-size="20" font-weight="bold" font-family="'Outfit', sans-serif">1. INTERFAZ DE USUARIO (UI)</text>

        <!-- UI Node Cards -->
        <g transform="translate(60, 110)">
            <rect width="300" height="75" rx="10" fill="#1E293B" stroke="rgba(255,255,255,0.1)"/>
            <text x="20" y="35" fill="#FFFFFF" font-size="15" font-weight="bold">Formulario de Login</text>
            <text x="20" y="55" fill="#9CA3AF" font-size="12">auth/login.php - POST Credentials</text>
        </g>
        <g transform="translate(60, 205)">
            <rect width="300" height="75" rx="10" fill="#1E293B" stroke="rgba(255,255,255,0.1)"/>
            <text x="20" y="35" fill="#FFFFFF" font-size="15" font-weight="bold">Dashboard de Laboratorios</text>
            <text x="20" y="55" fill="#9CA3AF" font-size="12">dashboard/index.php - KPIs &amp; Charts</text>
        </g>
        <g transform="translate(60, 300)">
            <rect width="300" height="75" rx="10" fill="#1E293B" stroke="#3B82F6" stroke-width="2" filter="url(#glow)"/>
            <text x="20" y="35" fill="#60A5FA" font-size="15" font-weight="bold">Solicitud de Préstamo PC</text>
            <text x="20" y="55" fill="#9CA3AF" font-size="12">prestamos/create.php - AJAX Form</text>
        </g>
        <g transform="translate(60, 395)">
            <rect width="300" height="75" rx="10" fill="#1E293B" stroke="rgba(255,255,255,0.1)"/>
            <text x="20" y="35" fill="#FFFFFF" font-size="15" font-weight="bold">Solicitud de Traslado Sala</text>
            <text x="20" y="55" fill="#9CA3AF" font-size="12">traslados/create.php - Selection</text>
        </g>
        <g transform="translate(60, 490)">
            <rect width="300" height="75" rx="10" fill="#1E293B" stroke="rgba(255,255,255,0.1)"/>
            <text x="20" y="35" fill="#FFFFFF" font-size="15" font-weight="bold">Registro de Baja Técnica</text>
            <text x="20" y="55" fill="#9CA3AF" font-size="12">bajas/create.php - Obsolescencia</text>
        </g>

        <!-- Connection Arrows UI -> Logic -->
        <path d="M 360 337 L 430 337" stroke="#3B82F6" stroke-width="3" fill="none" marker-end="url(#arrow)" filter="url(#glow)"/>

        <!-- Column 2: Controller & Logic Layer -->
        <rect x="430" y="40" width="340" height="570" rx="16" fill="url(#grad-logic)" stroke="#8B5CF6" stroke-width="2" stroke-dasharray="4"/>
        <text x="450" y="80" fill="#A78BFA" font-size="20" font-weight="bold" font-family="'Outfit', sans-serif">2. LÓGICA Y CONTROLADORES</text>

        <!-- Logic Node Cards -->
        <g transform="translate(450, 110)">
            <rect width="300" height="85" rx="10" fill="#1E293B" stroke="rgba(255,255,255,0.1)"/>
            <text x="20" y="35" fill="#FFFFFF" font-size="15" font-weight="bold">Router.php &amp; Middleware</text>
            <text x="20" y="58" fill="#9CA3AF" font-size="12">Verificación de Auth &amp; Rol de Usuario</text>
        </g>
        <g transform="translate(450, 215)">
            <rect width="300" height="110" rx="10" fill="#1E293B" stroke="#8B5CF6" stroke-width="2" filter="url(#glow)"/>
            <text x="20" y="35" fill="#C4B5FD" font-size="15" font-weight="bold">PrestamoController.php</text>
            <text x="20" y="58" fill="#9CA3AF" font-size="12">1. Valida disponibilidad id_computador</text>
            <text x="20" y="78" fill="#9CA3AF" font-size="12">2. Valida docente id_docente activo</text>
        </g>
        <g transform="translate(450, 345)">
            <rect width="300" height="85" rx="10" fill="#1E293B" stroke="rgba(255,255,255,0.1)"/>
            <text x="20" y="35" fill="#FFFFFF" font-size="15" font-weight="bold">TrasladoController.php</text>
            <text x="20" y="58" fill="#9CA3AF" font-size="12">Validación de salas origen y destino</text>
        </g>
        <g transform="translate(450, 450)">
            <rect width="300" height="85" rx="10" fill="#1E293B" stroke="rgba(255,255,255,0.1)"/>
            <text x="20" y="35" fill="#FFFFFF" font-size="15" font-weight="bold">BajaController.php</text>
            <text x="20" y="58" fill="#9CA3AF" font-size="12">Verificación de dictamen técnico PDF</text>
        </g>

        <!-- Connection Arrows Logic -> DB -->
        <path d="M 750 270 L 820 270" stroke="#06B6D4" stroke-width="3" fill="none" marker-end="url(#arrow-cyan)" filter="url(#glow)"/>

        <!-- Column 3: Database & Persistence Layer -->
        <rect x="820" y="40" width="340" height="570" rx="16" fill="url(#grad-db)" stroke="#06B6D4" stroke-width="2" stroke-dasharray="4"/>
        <text x="840" y="80" fill="#22D3EE" font-size="20" font-weight="bold" font-family="'Outfit', sans-serif">3. BASE DE DATOS (MYSQL)</text>

        <!-- Database Node Cards -->
        <g transform="translate(840, 110)">
            <rect width="300" height="75" rx="10" fill="#1E293B" stroke="#06B6D4" stroke-width="2" filter="url(#glow)"/>
            <text x="20" y="35" fill="#67E8F9" font-size="15" font-weight="bold">Tabla prestamos</text>
            <text x="20" y="55" fill="#9CA3AF" font-size="12">INSERT INTO prestamos (...) VALUES (...)</text>
        </g>
        <g transform="translate(840, 205)">
            <rect width="300" height="75" rx="10" fill="#1E293B" stroke="#06B6D4" stroke-width="2" filter="url(#glow)"/>
            <text x="20" y="35" fill="#67E8F9" font-size="15" font-weight="bold">Tabla computadores</text>
            <text x="20" y="55" fill="#9CA3AF" font-size="12">UPDATE computadores SET estado='En Prestamo'</text>
        </g>
        <g transform="translate(840, 300)">
            <rect width="300" height="75" rx="10" fill="#1E293B" stroke="rgba(255,255,255,0.1)"/>
            <text x="20" y="35" fill="#FFFFFF" font-size="15" font-weight="bold">Tabla traslados</text>
            <text x="20" y="55" fill="#9CA3AF" font-size="12">UPDATE computadores SET id_sala_actual</text>
        </g>
        <g transform="translate(840, 395)">
            <rect width="300" height="75" rx="10" fill="#1E293B" stroke="rgba(255,255,255,0.1)"/>
            <text x="20" y="35" fill="#FFFFFF" font-size="15" font-weight="bold">Tabla bajas</text>
            <text x="20" y="55" fill="#9CA3AF" font-size="12">UPDATE computadores SET estado='Dado de Baja'</text>
        </g>
        <g transform="translate(840, 490)">
            <rect width="300" height="75" rx="10" fill="#1E293B" stroke="#10B981" stroke-width="2"/>
            <text x="20" y="35" fill="#34D399" font-size="15" font-weight="bold">Tabla logs_sistema</text>
            <text x="20" y="55" fill="#9CA3AF" font-size="12">INSERT INTO logs_sistema (Auditoría Inmutable)</text>
        </g>
    </svg>
    `;

    container.innerHTML = svgContent;
}

function initSVGExporter() {
    const btnDownload = document.getElementById('btn-download-svg');
    if (!btnDownload) return;

    btnDownload.addEventListener('click', () => {
        const svgElement = document.getElementById('svg-canvas-element');
        if (!svgElement) return;

        const svgData = new XMLSerializer().serializeToString(svgElement);
        const svgBlob = new Blob([svgData], { type: 'image/svg+xml;charset=utf-8' });
        const URL = window.URL || window.webkitURL || window;
        const blobURL = URL.createObjectURL(svgBlob);

        const image = new Image();
        image.onload = () => {
            const canvas = document.createElement('canvas');
            canvas.width = 2400; // 4K high resolution export
            canvas.height = 1300;
            const context = canvas.getContext('2d');
            context.fillStyle = '#090D16';
            context.fillRect(0, 0, canvas.width, canvas.height);
            context.drawImage(image, 0, 0, canvas.width, canvas.height);

            const png = canvas.toDataURL('image/png');
            const downloadLink = document.createElement('a');
            downloadLink.href = png;
            downloadLink.download = 'diagrama_flujo_informacion_INVENTORI.png';
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);
        };
        image.src = blobURL;
    });
}

/* --------------------------------------------------------------------------
   4. Interactive Data Flow Simulation Engine
   -------------------------------------------------------------------------- */
const SIMULATION_PRESETS = {
    prestamos: [
        { layer: 'UI', title: 'Solicitud Préstamo', msg: 'Usuario diligencia formulario de préstamo y presiona "Confirmar Préstamo" (POST /prestamos/create)', delay: 500, node: 'node-ui-prestamos' },
        { layer: 'LOGIC', title: 'PrestamoController::store()', msg: 'Router valida middleware Auth -> PrestamoController invoca validación de reglas de negocio', delay: 1500, node: 'node-logic-prestamo' },
        { layer: 'LOGIC', title: 'Validación de Disponibilidad', msg: 'Comprueba que computadores.estado_activo == "Disponible"', delay: 2500, node: 'node-logic-prestamo' },
        { layer: 'DB', title: 'INSERT INTO prestamos', msg: 'Se inserta registro de préstamo con fecha y auxiliar responsable', delay: 3500, node: 'node-db-prestamos' },
        { layer: 'DB', title: 'UPDATE computadores', msg: 'Actualiza computadores.estado_activo = "En Prestamo"', delay: 4500, node: 'node-db-computadores' },
        { layer: 'DB', title: 'INSERT INTO logs_sistema', msg: 'Registra auditoría inmutable con IP y marca de tiempo', delay: 5500, node: 'node-db-logs' },
        { layer: 'UI', title: 'Respuesta al Usuario', msg: 'Flash message devuelto a la vista con SweetAlert2: "Préstamo registrado exitosamente"', delay: 6500, node: 'node-ui-prestamos' }
    ],
    traslados: [
        { layer: 'UI', title: 'Solicitud de Traslado', msg: 'Auxiliar solicita cambio de sala del PC #104 de Sede Norte a Sede Centro (POST /traslados/store)', delay: 500, node: 'node-ui-traslados' },
        { layer: 'LOGIC', title: 'TrasladoController::store()', msg: 'Valida que id_sala_origen != id_sala_destino y asigna estado "Pendiente"', delay: 1500, node: 'node-logic-traslado' },
        { layer: 'DB', title: 'INSERT INTO traslados', msg: 'Se crea la orden de traslado con estado_traslado = "Pendiente"', delay: 2500, node: 'node-db-traslados' },
        { layer: 'LOGIC', title: 'Aprobación Administrador', msg: 'Administrador ejecuta autorizar($id_traslado)', delay: 3500, node: 'node-logic-traslado' },
        { layer: 'DB', title: 'UPDATE computadores', msg: 'Actualiza atómicamente computadores.id_sala_actual = id_sala_destino', delay: 4500, node: 'node-db-computadores' },
        { layer: 'DB', title: 'UPDATE traslados', msg: 'Actualiza traslados.estado_traslado = "Aprobado"', delay: 5500, node: 'node-db-traslados' },
        { layer: 'UI', title: 'Actualización en Tiempo Real', msg: 'Inventario de salas actualizado en vista Dashboard', delay: 6500, node: 'node-ui-dashboard' }
    ],
    bajas: [
        { layer: 'UI', title: 'Dictamen de Obsolescencia', msg: 'Técnico adjunta dictamen de daño severo y solicita baja (POST /bajas/store)', delay: 500, node: 'node-ui-bajas' },
        { layer: 'LOGIC', title: 'BajaController::store()', msg: 'Verifica rol de Administrador y dictamen técnico en PDF', delay: 1500, node: 'node-logic-baja' },
        { layer: 'DB', title: 'INSERT INTO bajas', msg: 'Crea registro de baja con motivo_baja y dictamen_tecnico', delay: 2500, node: 'node-db-bajas' },
        { layer: 'DB', title: 'UPDATE computadores', msg: 'Actualiza computadores.estado_activo = "Dado de Baja"', delay: 3500, node: 'node-db-computadores' },
        { layer: 'UI', title: 'Inhabilitación del Equipo', msg: 'Equipo queda inactivo para futuros préstamos y traslados', delay: 4500, node: 'node-ui-bajas' }
    ]
};

let simInterval = null;

function initSimulationEngine() {
    const btnSim = document.getElementById('btn-start-sim');
    const selectSim = document.getElementById('sim-select');
    const logBox = document.getElementById('trace-log');

    if (!btnSim) return;

    btnSim.addEventListener('click', () => {
        const scenarioKey = selectSim.value;
        const steps = SIMULATION_PRESETS[scenarioKey];
        if (!steps) return;

        runSimulation(steps, logBox);
    });
}

function runSimulation(steps, logBox) {
    if (simInterval) clearTimeout(simInterval);
    logBox.innerHTML = '';

    document.querySelectorAll('.node-card').forEach(n => n.classList.remove('active-step'));

    let stepIndex = 0;

    function executeStep() {
        if (stepIndex >= steps.length) {
            appendLog(logBox, 'SYSTEM', 'Simulación completada con éxito. Todos los datos fueron procesados y auditados.', 'FINISHED');
            return;
        }

        const step = steps[stepIndex];

        document.querySelectorAll('.node-card').forEach(n => n.classList.remove('active-step'));
        if (step.node) {
            const el = document.getElementById(step.node);
            if (el) el.classList.add('active-step');
        }

        appendLog(logBox, step.layer, step.title, step.msg);

        stepIndex++;
        const nextDelay = stepIndex < steps.length ? steps[stepIndex].delay - step.delay : 1500;
        simInterval = setTimeout(executeStep, Math.max(1000, nextDelay));
    }

    executeStep();
}

function appendLog(logBox, layer, title, msg) {
    const now = new Date().toLocaleTimeString();
    const entry = document.createElement('div');
    entry.className = 'log-entry';
    entry.innerHTML = `
        <span class="log-time">[${now}]</span>
        <span class="log-layer ${layer}">${layer}</span>
        <span class="log-msg"><strong>${title}</strong>: ${msg}</span>
    `;
    logBox.appendChild(entry);
    logBox.scrollTop = logBox.scrollHeight;
}

/* --------------------------------------------------------------------------
   5. Fullscreen Presentation Mode (For Degree Defense)
   -------------------------------------------------------------------------- */
const SLIDES = [
    {
        title: "INVENTORI - Sistema de Gestión de Laboratorios",
        desc: "Arquitectura de Software y Diagrama de Flujo de Información para Sustentación de Proyecto de Grado",
        detail: "Defensa técnica del modelo de datos, la lógica de controladores y las interfaces de usuario."
    },
    {
        title: "Capa 1: Interfaz de Usuario (UI Views)",
        desc: "Formularios Dinámicos + Notificaciones AJAX + Paneles Analíticos",
        detail: "Módulos principales: Login seguro, Dashboard de control, Gestión de Computadores, Préstamos, Traslados y Bajas."
    },
    {
        title: "Capa 2: Lógica de Negocio (MVC Controller)",
        desc: "Routing Dinámico + Filtros de Seguridad + Validaciones Atómicas",
        detail: "Controladores PHP con PDO: AuthController, ComputadorController, PrestamoController, TrasladoController, BajaController."
    },
    {
        title: "Capa 3: Persistencia y Base de Datos (MySQL)",
        desc: "Relaciones Entidad-Relación (ERD) + Logs Inmutables de Auditoría",
        detail: "Tablas normalizadas con claves foráneas, restricciones relacionales e historial de trazabilidad total."
    }
];

let currentSlide = 0;

function initPresenterMode() {
    const btnPresent = document.getElementById('btn-present-mode');
    const modal = document.getElementById('presenter-modal');
    const btnClose = document.getElementById('btn-close-presenter');
    const btnPrev = document.getElementById('btn-prev-slide');
    const btnNext = document.getElementById('btn-next-slide');

    if (!btnPresent || !modal) return;

    btnPresent.addEventListener('click', () => {
        modal.classList.add('active');
        renderSlide();
    });

    if (btnClose) {
        btnClose.addEventListener('click', () => modal.classList.remove('active'));
    }

    if (btnPrev) {
        btnPrev.addEventListener('click', () => {
            if (currentSlide > 0) {
                currentSlide--;
                renderSlide();
            }
        });
    }

    if (btnNext) {
        btnNext.addEventListener('click', () => {
            if (currentSlide < SLIDES.length - 1) {
                currentSlide++;
                renderSlide();
            }
        });
    }

    document.addEventListener('keydown', (e) => {
        if (!modal.classList.contains('active')) return;
        if (e.key === 'ArrowRight' || e.key === 'Space') {
            if (currentSlide < SLIDES.length - 1) { currentSlide++; renderSlide(); }
        } else if (e.key === 'ArrowLeft') {
            if (currentSlide > 0) { currentSlide--; renderSlide(); }
        } else if (e.key === 'Escape') {
            modal.classList.remove('active');
        }
    });
}

function renderSlide() {
    const slide = SLIDES[currentSlide];
    document.getElementById('slide-title').innerText = slide.title;
    document.getElementById('slide-desc').innerText = slide.desc;
    document.getElementById('slide-detail').innerText = slide.detail;
    document.getElementById('slide-indicator').innerText = `Diapositiva ${currentSlide + 1} de ${SLIDES.length}`;
}

function initImageModal() {
    const modal = document.getElementById('image-modal');
    const modalImg = document.getElementById('modal-img-element');
    const closeBtn = document.getElementById('close-image-modal');

    document.querySelectorAll('.diagram-card img, .banner-visual img').forEach(img => {
        img.addEventListener('click', () => {
            if (!modal || !modalImg) return;
            modalImg.src = img.src;
            modal.classList.add('active');
        });
    });

    if (closeBtn) {
        closeBtn.addEventListener('click', () => modal.classList.remove('active'));
    }

    if (modal) {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) modal.classList.remove('active');
        });
    }
}
