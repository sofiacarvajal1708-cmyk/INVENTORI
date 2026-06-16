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
                ['id' => 4, 'name' => 'Contralor Escolar', 'desc' => 'Veeduría ciudadana y acceso a reportes públicos de transparencia.']
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
                ['nombre' => 'Sala de Sistemas A', 'polo' => 1, 'estabilizador' => 1, 'red' => 1, 'fecha' => date('Y-m-d H:i:s')],
                ['nombre' => 'Aula Móvil de Primaria', 'polo' => 0, 'estabilizador' => 1, 'red' => 0, 'fecha' => date('Y-m-d H:i:s')], // No cumple todo
                ['nombre' => 'Laboratorio de Física', 'polo' => 1, 'estabilizador' => 0, 'red' => 1, 'fecha' => date('Y-m-d H:i:s')] // No cumple todo
            ];
            $insertSala = $this->db->prepare("
                INSERT INTO salas (nombre_sala, tiene_polo_a_tierra, tiene_estabilizador, tiene_red_structured, ultima_revision_infraestructura) 
                VALUES (:nombre, :polo, :estabilizador, :red, :fecha)
            ");
            foreach ($salas as $s) {
                $insertSala->execute($s);
            }
        }
    }
}
