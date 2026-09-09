<?php
/**
 * Modelo de Usuario
 */
class User extends Model {

    /**
     * Obtener usuario por email para login
     */
    public function getByEmail($email) {
        // Auto-sembrar roles y usuarios si están vacíos
        $this->checkAndSeedDatabase();

        $stmt = $this->db->prepare("
            SELECT u.*, r.nombre_rol as role_name 
            FROM usuarios u 
            JOIN roles r ON u.id_rol = r.id_rol 
            WHERE u.email = :email AND u.estado_usuario = 'Activo'
            LIMIT 1
        ");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }

    /**
     * Obtener usuario por ID
     */
    public function getById($id) {
        $stmt = $this->db->prepare("
            SELECT u.*, r.nombre_rol as role_name 
            FROM usuarios u 
            JOIN roles r ON u.id_rol = r.id_rol 
            WHERE u.id_usuario = :id
            LIMIT 1
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Listar todos los usuarios para la gestión de administración
     */
    public function getAll($filters = []) {
        $sql = "
            SELECT u.*, r.nombre_rol as role_name 
            FROM usuarios u 
            JOIN roles r ON u.id_rol = r.id_rol 
            WHERE 1=1
        ";
        $params = [];

        if (!empty($filters['rol'])) {
            $sql .= " AND u.id_rol = :rol";
            $params['rol'] = $filters['rol'];
        }

        if (!empty($filters['estado'])) {
            $sql .= " AND u.estado_usuario = :estado";
            $params['estado'] = $filters['estado'];
        }

        if (!empty($filters['search'])) {
            $sql .= " AND (u.nombres LIKE :search OR u.apellidos LIKE :search OR u.email LIKE :search OR u.documento_identidad LIKE :search)";
            $params['search'] = '%' . $filters['search'] . '%';
        }

        $sql .= " ORDER BY u.id_usuario DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Obtener todos los roles disponibles
     */
    public function getRoles() {
        $stmt = $this->db->query("SELECT * FROM roles ORDER BY id_rol ASC");
        return $stmt->fetchAll();
    }

    /**
     * Crear un nuevo usuario
     */
    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO usuarios 
            (documento_identidad, nombres, apellidos, email, password_hash, id_rol, capacitacion_tic_aprobada, horas_asistencia_tic, estado_usuario) 
            VALUES 
            (:doc, :nombres, :apellidos, :email, :pass, :rol, :tic, :horas, :estado)
        ");

        $stmt->execute([
            'doc' => $data['documento_identidad'],
            'nombres' => $data['nombres'],
            'apellidos' => $data['apellidos'],
            'email' => $data['email'],
            'pass' => password_hash($data['password'], PASSWORD_BCRYPT),
            'rol' => $data['id_rol'],
            'tic' => !empty($data['capacitacion_tic_aprobada']) ? 1 : 0,
            'horas' => !empty($data['horas_asistencia_tic']) ? (int)$data['horas_asistencia_tic'] : 0,
            'estado' => $data['estado_usuario'] ?? 'Activo'
        ]);

        return $this->db->lastInsertId();
    }

    /**
     * Actualizar datos del usuario
     */
    public function update($id, $data) {
        $stmt = $this->db->prepare("
            UPDATE usuarios 
            SET documento_identidad = :doc,
                nombres = :nombres, 
                apellidos = :apellidos, 
                email = :email, 
                id_rol = :rol, 
                capacitacion_tic_aprobada = :tic, 
                horas_asistencia_tic = :horas, 
                estado_usuario = :estado
            WHERE id_usuario = :id
        ");

        return $stmt->execute([
            'id' => $id,
            'doc' => $data['documento_identidad'],
            'nombres' => $data['nombres'],
            'apellidos' => $data['apellidos'],
            'email' => $data['email'],
            'rol' => $data['id_rol'],
            'tic' => !empty($data['capacitacion_tic_aprobada']) ? 1 : 0,
            'horas' => !empty($data['horas_asistencia_tic']) ? (int)$data['horas_asistencia_tic'] : 0,
            'estado' => $data['estado_usuario'] ?? 'Activo'
        ]);
    }

    /**
     * Actualizar contraseña de un usuario
     */
    public function updatePassword($id, $newPassword) {
        $stmt = $this->db->prepare("
            UPDATE usuarios 
            SET password_hash = :pass 
            WHERE id_usuario = :id
        ");

        return $stmt->execute([
            'id' => $id,
            'pass' => password_hash($newPassword, PASSWORD_BCRYPT)
        ]);
    }

    /**
     * Alternar estado Activo / Inactivo
     */
    public function toggleStatus($id) {
        $stmt = $this->db->prepare("
            UPDATE usuarios 
            SET estado_usuario = IF(estado_usuario = 'Activo', 'Inactivo', 'Activo') 
            WHERE id_usuario = :id
        ");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Eliminar usuario
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM usuarios WHERE id_usuario = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Listar todos los docentes para los préstamos
     */
    public function getDocentes() {
        $stmt = $this->db->prepare("
            SELECT u.*, r.nombre_rol as role_name 
            FROM usuarios u 
            JOIN roles r ON u.id_rol = r.id_rol 
            WHERE r.nombre_rol = 'Docente' AND u.estado_usuario = 'Activo'
            ORDER BY u.nombres ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Semilla automatizada para facilitar las pruebas del usuario (WOW factor)
     */
    private function checkAndSeedDatabase() {
        // Verificar si ya existen roles
        $stmt = $this->db->query("SELECT COUNT(*) FROM roles");
        $countRoles = $stmt->fetchColumn();

        if ($countRoles == 0) {
            // 1. Insertar roles requeridos
            $roles = [
                ['id' => 1, 'name' => 'Rector', 'desc' => 'Ordenador de gasto y responsable máximo del inventario.'],
                ['id' => 2, 'name' => 'Almacenista', 'desc' => 'Administración física, catálogo, aprobación de traslados e infraestructura.'],
                ['id' => 3, 'name' => 'Docente', 'desc' => 'Solicitud de préstamos de terminales y planeación pedagógica.'],
                ['id' => 4, 'name' => 'Contralor', 'desc' => 'Veeduría ciudadana y acceso a reportes públicos de transparencia.'],
                ['id' => 5, 'name' => 'Administrador', 'desc' => 'Superadministrador con control total del sistema, gestión de usuarios y CRUD centralizado.']
            ];

            $insertRol = $this->db->prepare("INSERT INTO roles (id_rol, nombre_rol, descripcion) VALUES (:id, :name, :desc)");
            foreach ($roles as $r) {
                $insertRol->execute($r);
            }

            // 2. Insertar usuarios de prueba por defecto
            $users = [
                [
                    'doc' => '1001', 'nombres' => 'Guillermo', 'apellidos' => 'Rendón',
                    'email' => 'rector@santa.edu.co', 'pass' => password_hash('rector123', PASSWORD_DEFAULT),
                    'rol' => 1, 'tic' => 1, 'horas' => 80
                ],
                [
                    'doc' => '1002', 'nombres' => 'Marta', 'apellidos' => 'Gómez',
                    'email' => 'almacenista@santa.edu.co', 'pass' => password_hash('almacenista123', PASSWORD_DEFAULT),
                    'rol' => 2, 'tic' => 0, 'horas' => 0
                ],
                [
                    'doc' => '1003', 'nombres' => 'Carlos', 'apellidos' => 'Herrera (Docente TIC)',
                    'email' => 'docente_tic@santa.edu.co', 'pass' => password_hash('docente123', PASSWORD_DEFAULT),
                    'rol' => 3, 'tic' => 1, 'horas' => 45 // Cumple requisitos TIC
                ],
                [
                    'doc' => '1004', 'nombres' => 'Lucía', 'apellidos' => 'Pérez (Docente No TIC)',
                    'email' => 'docente_notic@santa.edu.co', 'pass' => password_hash('docente123', PASSWORD_DEFAULT),
                    'rol' => 3, 'tic' => 0, 'horas' => 12 // No cumple requisitos
                ],
                [
                    'doc' => '1005', 'nombres' => 'Mateo', 'apellidos' => 'Restrepo',
                    'email' => 'contralor@santa.edu.co', 'pass' => password_hash('contralor123', PASSWORD_DEFAULT),
                    'rol' => 4, 'tic' => 0, 'horas' => 0
                ],
                [
                    'doc' => '10000000', 'nombres' => 'Administrador', 'apellidos' => 'General',
                    'email' => 'admin@santa.edu.co', 'pass' => password_hash('admin123', PASSWORD_DEFAULT),
                    'rol' => 5, 'tic' => 1, 'horas' => 100
                ]
            ];

            $insertUser = $this->db->prepare("
                INSERT INTO usuarios 
                (documento_identidad, nombres, apellidos, email, password_hash, id_rol, capacitacion_tic_aprobada, horas_asistencia_tic, estado_usuario) 
                VALUES 
                (:doc, :nombres, :apellidos, :email, :pass, :rol, :tic, :horas, 'Activo')
            ");
            foreach ($users as $u) {
                $insertUser->execute($u);
            }

            // 3. Insertar algunas marcas por defecto
            $marcas = ['HP', 'Lenovo', 'Dell', 'Asus', 'Apple'];
            $insertMarca = $this->db->prepare("INSERT INTO marcas (nombre_marca) VALUES (:nombre)");
            foreach ($marcas as $m) {
                $insertMarca->execute(['nombre' => $m]);
            }

            // 4. Insertar algunas salas por defecto
            $salas = [
                ['nombre' => 'Sala de sistemas de la Pedro Nel Ospina', 'sede' => 'Pedro Nel Ospina', 'categoria' => 'Salas de Sistemas', 'polo' => 1, 'estabilizador' => 1, 'red' => 1, 'fecha' => date('Y-m-d H:i:s')],
                ['nombre' => 'Sala de sistemas de la Santa Margarita', 'sede' => 'Santa Margarita', 'categoria' => 'Salas de Sistemas', 'polo' => 1, 'estabilizador' => 1, 'red' => 1, 'fecha' => date('Y-m-d H:i:s')],
                ['nombre' => 'Sala EPM de Bachillerato', 'sede' => 'Bachillerato Santa Margarita', 'categoria' => 'Salas de Sistemas', 'polo' => 1, 'estabilizador' => 1, 'red' => 1, 'fecha' => date('Y-m-d H:i:s')],
                ['nombre' => 'Sala Medellín de Bachillerato Digital', 'sede' => 'Bachillerato Santa Margarita', 'categoria' => 'Salas Digitales', 'polo' => 1, 'estabilizador' => 1, 'red' => 1, 'fecha' => date('Y-m-d H:i:s')],
                ['nombre' => 'Sala Medellín Digital', 'sede' => 'General / Otras', 'categoria' => 'Salas Digitales', 'polo' => 1, 'estabilizador' => 1, 'red' => 1, 'fecha' => date('Y-m-d H:i:s')],
                ['nombre' => 'Sala EPM', 'sede' => 'General / Otras', 'categoria' => 'Salas General', 'polo' => 1, 'estabilizador' => 1, 'red' => 1, 'fecha' => date('Y-m-d H:i:s')],
                ['nombre' => 'Laboratorio de Física', 'sede' => 'Santa Margarita', 'categoria' => 'Laboratorios', 'polo' => 1, 'estabilizador' => 0, 'red' => 1, 'fecha' => date('Y-m-d H:i:s')],
                ['nombre' => 'Laboratorio de Química', 'sede' => 'Santa Margarita', 'categoria' => 'Laboratorios', 'polo' => 1, 'estabilizador' => 1, 'red' => 1, 'fecha' => date('Y-m-d H:i:s')],
                ['nombre' => 'Laboratorio de Ciencias', 'sede' => 'Bachillerato Santa Margarita', 'categoria' => 'Laboratorios', 'polo' => 0, 'estabilizador' => 1, 'red' => 0, 'fecha' => date('Y-m-d H:i:s')]
            ];
            $insertSala = $this->db->prepare("
                INSERT INTO salas (nombre_sala, sede, categoria, tiene_polo_a_tierra, tiene_estabilizador, tiene_red_structured, ultima_revision_infraestructura) 
                VALUES (:nombre, :sede, :categoria, :polo, :estabilizador, :red, :fecha)
            ");
            foreach ($salas as $s) {
                $insertSala->execute($s);
            }
        }
    }
}
