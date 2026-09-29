<?php
/**
 * Modelo de Préstamos de Equipos a Docentes
 */
class Prestamo extends Model {

    public function __construct() {
        parent::__construct();
        $this->ensureColumnsExist();
    }

    /**
     * Asegurar que la estructura de la tabla prestamos contenga id_docente e id_auxiliar_retorno
     */
    private function ensureColumnsExist() {
        try {
            $stmt = $this->db->query("SHOW COLUMNS FROM prestamos LIKE 'id_docente'");
            if (!$stmt->fetch()) {
                $this->db->exec("ALTER TABLE prestamos ADD COLUMN id_docente INT(11) AFTER id_computador");
                $this->db->exec("UPDATE prestamos SET id_docente = id_usuario WHERE (id_docente IS NULL OR id_docente = 0) AND id_usuario IS NOT NULL");
            }
        } catch (Exception $e) {}

        try {
            $stmt = $this->db->query("SHOW COLUMNS FROM prestamos LIKE 'id_auxiliar_retorno'");
            if (!$stmt->fetch()) {
                $this->db->exec("ALTER TABLE prestamos ADD COLUMN id_auxiliar_retorno INT(11) AFTER fecha_devolucion_real");
            }
        } catch (Exception $e) {}
    }

    /**
     * Listar todos los préstamos
     */
    public function getAll() {
        $stmt = $this->db->query("
            SELECT p.*, c.numero_serial, c.modelo, m.nombre_marca,
                   ud.nombres as docente_nombres, ud.apellidos as docente_apellidos,
                   ua.nombres as auxiliar_nombres, ua.apellidos as auxiliar_apellidos
            FROM prestamos p
            JOIN computadores c ON p.id_computador = c.id_computador
            JOIN marcas m ON c.id_marca = m.id_marca
            JOIN usuarios ud ON p.id_docente = ud.id_usuario
            LEFT JOIN usuarios ua ON p.id_auxiliar_retorno = ua.id_usuario
            ORDER BY p.fecha_prestamo DESC
        ");
        return $stmt->fetchAll();
    }

    /**
     * Obtener préstamo por ID
     */
    public function getById($id) {
        $stmt = $this->db->prepare("
            SELECT p.*, c.numero_serial, c.modelo, m.nombre_marca, c.placa_sed,
                   ud.nombres as docente_nombres, ud.apellidos as docente_apellidos, ud.email as docente_email,
                   ud.documento_identidad as docente_documento
            FROM prestamos p
            JOIN computadores c ON p.id_computador = c.id_computador
            JOIN marcas m ON c.id_marca = m.id_marca
            JOIN usuarios ud ON p.id_docente = ud.id_usuario
            WHERE p.id_prestamo = :id
            LIMIT 1
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Verificar si un docente cumple con los requisitos TIC
     */
    public function checkTeacherRequisites($teacherId) {
        $stmt = $this->db->prepare("
            SELECT capacitacion_tic_aprobada, horas_asistencia_tic 
            FROM usuarios 
            WHERE id_usuario = :id LIMIT 1
        ");
        $stmt->execute(['id' => $teacherId]);
        $user = $stmt->fetch();

        if (!$user) return false;

        // Requisitos: Capacitación aprobada Y horas >= 40
        return ($user['capacitacion_tic_aprobada'] == 1 && $user['horas_asistencia_tic'] >= 40);
    }

    /**
     * Crear un nuevo préstamo (y cambiar el estado del computador a 'En Uso' o similar, o mantener operativo)
     */
    public function create($data) {
        // Registrar en tabla prestamos
        $stmt = $this->db->prepare("
            INSERT INTO prestamos (id_computador, id_docente, fecha_devolucion_estimada, estado_prestamo)
            VALUES (:id_computador, :id_docente, :fecha_estimada, 'Activo')
        ");
        
        $success = $stmt->execute([
            'id_computador' => $data['id_computador'],
            'id_docente' => $data['id_docente'],
            'fecha_estimada' => $data['fecha_devolucion_estimada']
        ]);

        if ($success) {
            // Cambiar estado de computador para reflejar que no está disponible
            // La base de datos tiene: 'Operativo','En Mantenimiento','Obsoleto','Dado de Baja'
            // Mantener en operativo, o podemos dejarlo.
        }

        return $success;
    }

    /**
     * Procesar devolución (Retorno)
     */
    public function returnLoan($id, $data) {
        $stmt = $this->db->prepare("
            UPDATE prestamos 
            SET fecha_devolucion_real = NOW(),
                id_auxiliar_retorno = :auxiliar_id,
                observaciones_retorno = :observaciones,
                estado_prestamo = :estado_prestamo
            WHERE id_prestamo = :id
        ");

        $success = $stmt->execute([
            'id' => $id,
            'auxiliar_id' => $data['id_auxiliar_retorno'],
            'observaciones' => $data['observaciones_retorno'],
            'estado_prestamo' => $data['estado_prestamo'] // 'Devuelto' o 'Siniestro'
        ]);

        if ($success && $data['estado_prestamo'] === 'Siniestro') {
            // Si fue siniestro, marcar el computador como 'Obsoleto' o 'Dado de Baja'
            $stmtComp = $this->db->prepare("
                UPDATE computadores c
                JOIN prestamos p ON c.id_computador = p.id_computador
                SET c.estado_activo = 'Dado de Baja'
                WHERE p.id_prestamo = :id
            ");
            $stmtComp->execute(['id' => $id]);
        }

        return $success;
    }
}
