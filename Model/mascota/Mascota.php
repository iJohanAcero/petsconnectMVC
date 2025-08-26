<?php
// Requiere la conexión a la base de datos
namespace App\Model\Mascota;

use App\Model\Conexion;
use PDO;
use PDOException;
use Exception;

class Mascota
{
    private $db;

    public function __construct()
    {
        $this->db = (new Conexion())->getConexion();
    }

    // 1️⃣ Agregar nueva mascota 
    public function add($id_mascota, $nombre, $edad_meses, $sexo, $imagen, $id_tipo_mascota, $nit_fundacion, $id_estado_adopcion, $public_id = null)
    {
        $statement = $this->db->prepare("INSERT INTO t_mascota
            (id_mascota, nombre, edad_meses, sexo, imagen, id_tipo_mascota, nit_fundacion, id_estado_adopcion, public_id)
            VALUES (:id_mascota, :nombre, :edad_meses, :sexo, :imagen, :id_tipo_mascota, :nit_fundacion, :id_estado_adopcion, :public_id)");

        $statement->bindParam(':id_mascota', $id_mascota);
        $statement->bindParam(':nombre', $nombre);
        $statement->bindParam(':edad_meses', $edad_meses);
        $statement->bindParam(':sexo', $sexo);
        $statement->bindParam(':imagen', $imagen);
        $statement->bindParam(':id_tipo_mascota', $id_tipo_mascota);
        $statement->bindParam(':nit_fundacion', $nit_fundacion);
        $statement->bindParam(':id_estado_adopcion', $id_estado_adopcion);
        $statement->bindParam(':public_id', $public_id);

        return $statement->execute();
    }

    // 2️⃣ Obtener todas las mascotas con JOINs para mostrar datos legibles
    public function getMascota()
    {
        $sql = "SELECT 
                    m.id_mascota,
                    m.nombre,
                    m.edad_meses,
                    m.sexo,
                    m.imagen,
                    m.nit_fundacion,
                    m.id_tipo_mascota,
                    m.id_estado_adopcion,
                    m.public_id,
                    tm.especie,
                    ea.tipo_estado
                FROM t_mascota m
                LEFT JOIN t_tipo_mascota tm ON m.id_tipo_mascota = tm.id_tipo_mascota
                LEFT JOIN t_estado_adopcion ea ON m.id_estado_adopcion = ea.id_estado_adopcion";
        
        error_log("Consulta SQL ejecutada: " . $sql);  // Para depuración
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 3️⃣ Obtener mascota por ID (CORREGIDO - devuelve un solo array)
    public function getId($id)
    {
        $sql = "SELECT 
                    m.*, 
                    tm.especie,
                    ea.tipo_estado
                FROM t_mascota m
                INNER JOIN t_tipo_mascota tm ON m.id_tipo_mascota = tm.id_tipo_mascota
                INNER JOIN t_estado_adopcion ea ON m.id_estado_adopcion = ea.id_estado_adopcion
                WHERE m.id_mascota = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // ✅ CORREGIDO: Devuelve un solo registro, no array de arrays
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 4️⃣ Actualizar mascota
    public function update($id_mascota, $nombre, $edad_meses, $sexo, $imagen, $id_tipo_mascota, $id_estado_adopcion, $public_id = null)
    {
        $statement = $this->db->prepare("UPDATE t_mascota SET
        nombre = :nombre,
        edad_meses = :edad_meses,
        sexo = :sexo,
        imagen = :imagen,
        id_tipo_mascota = :id_tipo_mascota,
        id_estado_adopcion = :id_estado_adopcion,
        public_id = :public_id
        WHERE id_mascota = :id_mascota");

        $statement->bindParam(':id_mascota', $id_mascota);
        $statement->bindParam(':nombre', $nombre);
        $statement->bindParam(':edad_meses', $edad_meses);
        $statement->bindParam(':sexo', $sexo);
        $statement->bindParam(':imagen', $imagen);
        $statement->bindParam(':id_tipo_mascota', $id_tipo_mascota);
        $statement->bindParam(':id_estado_adopcion', $id_estado_adopcion);
        $statement->bindParam(':public_id', $public_id);

        return $statement->execute();
    }

    // 5️⃣ Eliminar mascota 
    public function delete($id_mascota)
    {
        $stmt = $this->db->prepare("DELETE FROM t_mascota WHERE id_mascota = :id");
        $stmt->bindParam(':id', $id_mascota);

        return $stmt->execute();
    }

    // 6️⃣ NUEVO: Obtener todas las mascotas para carrusel/cartas
    public function getAllMascotasCarrusel()
    {
        $sql = "SELECT 
                    m.id_mascota,
                    m.nombre,
                    m.edad_meses,
                    m.sexo,
                    m.imagen,
                    m.public_id,
                    tm.especie,
                    ea.tipo_estado,
                    f.nombre as nombre_fundacion,
                    f.nit_fundacion,
                    CASE 
                        WHEN m.edad_meses < 12 THEN 'cachorro'
                        WHEN m.edad_meses BETWEEN 12 AND 36 THEN 'joven'
                        WHEN m.edad_meses BETWEEN 37 AND 84 THEN 'adulto'
                        ELSE 'senior'
                    END as categoria_edad,
                    CASE 
                        WHEN tm.especie IN ('Canino') AND m.edad_meses < 24 THEN 'pequeño'
                        WHEN tm.especie IN ('Canino') AND m.edad_meses BETWEEN 24 AND 60 THEN 'mediano'
                        WHEN tm.especie IN ('Canino') AND m.edad_meses > 60 THEN 'grande'
                        WHEN tm.especie IN ('Felino') THEN 'pequeño'
                        ELSE 'mediano'
                    END as tamano_estimado
                FROM t_mascota m
                INNER JOIN t_tipo_mascota tm ON m.id_tipo_mascota = tm.id_tipo_mascota
                INNER JOIN t_estado_adopcion ea ON m.id_estado_adopcion = ea.id_estado_adopcion
                INNER JOIN t_fundacion f ON m.nit_fundacion = f.nit_fundacion
                WHERE ea.tipo_estado IN ('EN ADOPCION', 'TRANSITO')
                ORDER BY m.id_mascota DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 7️⃣ NUEVO: Obtener detalles específicos de una mascota
    public function getDetallesPorId($id)
    {
        $sql = "SELECT 
                    m.*,
                    tm.especie,
                    ea.tipo_estado,
                    f.nombre as nombre_fundacion,
                    f.nit_fundacion,
                    u.nombre as rep_nombre,
                    u.apellido as rep_apellido,
                    u.telefono as rep_telefono,
                    u.email as rep_email,
                    p.descripcion as fundacion_descripcion,
                    p.imagen as fundacion_imagen,
                    CASE 
                        WHEN m.edad_meses < 12 THEN 'cachorro'
                        WHEN m.edad_meses BETWEEN 12 AND 36 THEN 'joven'
                        WHEN m.edad_meses BETWEEN 37 AND 84 THEN 'adulto'
                        ELSE 'senior'
                    END as categoria_edad
                FROM t_mascota m
                INNER JOIN t_tipo_mascota tm ON m.id_tipo_mascota = tm.id_tipo_mascota
                INNER JOIN t_estado_adopcion ea ON m.id_estado_adopcion = ea.id_estado_adopcion
                INNER JOIN t_fundacion f ON m.nit_fundacion = f.nit_fundacion
                INNER JOIN t_usuario u ON f.id_usuario = u.id_usuario
                INNER JOIN t_perfil p ON f.id_perfil = p.id_perfil
                WHERE m.id_mascota = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 8️⃣ NUEVO: Obtener información de adopción específica
    public function getInfoAdopcionPorId($id)
    {
        $sql = "SELECT 
                    m.id_mascota,
                    m.nombre,
                    m.edad_meses,
                    m.sexo,
                    tm.especie,
                    ea.tipo_estado,
                    f.nombre as nombre_fundacion,
                    f.nit_fundacion,
                    u.nombre as rep_nombre,
                    u.apellido as rep_apellido,
                    u.telefono as rep_telefono,
                    u.email as rep_email,
                    u.direccion as fundacion_direccion,
                    p.descripcion as fundacion_descripcion,
                    -- Obtener redes sociales de la fundación
                    GROUP_CONCAT(
                        CONCAT(pr.tipo_red, ':', pr.url_red) 
                        SEPARATOR '|'
                    ) as redes_sociales
                FROM t_mascota m
                INNER JOIN t_tipo_mascota tm ON m.id_tipo_mascota = tm.id_tipo_mascota
                INNER JOIN t_estado_adopcion ea ON m.id_estado_adopcion = ea.id_estado_adopcion
                INNER JOIN t_fundacion f ON m.nit_fundacion = f.nit_fundacion
                INNER JOIN t_usuario u ON f.id_usuario = u.id_usuario
                INNER JOIN t_perfil p ON f.id_perfil = p.id_perfil
                LEFT JOIN t_perfil_redes pr ON p.id_perfil = pr.id_perfil
                WHERE m.id_mascota = :id
                GROUP BY m.id_mascota";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Procesar redes sociales
        if ($resultado && $resultado['redes_sociales']) {
            $redes = [];
            $redesArray = explode('|', $resultado['redes_sociales']);
            foreach ($redesArray as $red) {
                if (strpos($red, ':') !== false) {
                    list($tipo, $url) = explode(':', $red, 2);
                    $redes[] = ['tipo' => $tipo, 'url' => $url];
                }
            }
            $resultado['redes_sociales_array'] = $redes;
        }
        
        return $resultado;
    }

    // 9️⃣ NUEVO: Filtrar mascotas por criterios
    public function filtrarMascotas($especie = '', $tamano = '', $edad = '', $genero = '')
    {
        $sql = "SELECT 
                    m.id_mascota,
                    m.nombre,
                    m.edad_meses,
                    m.sexo,
                    m.imagen,
                    m.public_id,
                    tm.especie,
                    ea.tipo_estado,
                    f.nombre as nombre_fundacion,
                    f.nit_fundacion,
                    CASE 
                        WHEN m.edad_meses < 12 THEN 'cachorro'
                        WHEN m.edad_meses BETWEEN 12 AND 36 THEN 'joven'
                        WHEN m.edad_meses BETWEEN 37 AND 84 THEN 'adulto'
                        ELSE 'senior'
                    END as categoria_edad,
                    CASE 
                        WHEN tm.especie IN ('Canino') AND m.edad_meses < 24 THEN 'pequeño'
                        WHEN tm.especie IN ('Canino') AND m.edad_meses BETWEEN 24 AND 60 THEN 'mediano'
                        WHEN tm.especie IN ('Canino') AND m.edad_meses > 60 THEN 'grande'
                        WHEN tm.especie IN ('Felino') THEN 'pequeño'
                        ELSE 'mediano'
                    END as tamano_estimado
                FROM t_mascota m
                INNER JOIN t_tipo_mascota tm ON m.id_tipo_mascota = tm.id_tipo_mascota
                INNER JOIN t_estado_adopcion ea ON m.id_estado_adopcion = ea.id_estado_adopcion
                INNER JOIN t_fundacion f ON m.nit_fundacion = f.nit_fundacion
                WHERE ea.tipo_estado IN ('EN ADOPCION', 'TRANSITO')";

        $params = [];

        // Filtro por especie
        if (!empty($especie)) {
            if ($especie === 'perro') {
                $sql .= " AND tm.especie = 'Canino'";
            } elseif ($especie === 'gato') {
                $sql .= " AND tm.especie = 'Felino'";
            } else {
                $sql .= " AND LOWER(tm.especie) LIKE LOWER(:especie)";
                $params[':especie'] = '%' . $especie . '%';
            }
        }

        // Filtro por género
        if (!empty($genero)) {
            $sql .= " AND m.sexo = :genero";
            $params[':genero'] = $genero;
        }

        // Filtro por edad (usando HAVING para usar el campo calculado)
        $havingConditions = [];
        
        if (!empty($edad)) {
            switch ($edad) {
                case 'cachorro':
                    $havingConditions[] = "categoria_edad = 'cachorro'";
                    break;
                case 'joven':
                    $havingConditions[] = "categoria_edad = 'joven'";
                    break;
                case 'adulto':
                    $havingConditions[] = "categoria_edad = 'adulto'";
                    break;
                case 'senior':
                    $havingConditions[] = "categoria_edad = 'senior'";
                    break;
            }
        }

        // Filtro por tamaño (usando HAVING para usar el campo calculado)
        if (!empty($tamano)) {
            switch ($tamano) {
                case 'pequeno':
                    $havingConditions[] = "tamano_estimado = 'pequeño'";
                    break;
                case 'mediano':
                    $havingConditions[] = "tamano_estimado = 'mediano'";
                    break;
                case 'grande':
                    $havingConditions[] = "tamano_estimado = 'grande'";
                    break;
            }
        }

        // Agregar HAVING si hay condiciones
        if (!empty($havingConditions)) {
            $sql .= " HAVING " . implode(' AND ', $havingConditions);
        }

        $sql .= " ORDER BY m.id_mascota DESC";

        $stmt = $this->db->prepare($sql);
        
        // Vincular parámetros
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 🔟 NUEVO: Obtener perfil completo de mascota
    public function getPerfilCompletoPorId($id)
    {
        $sql = "SELECT 
                    m.*,
                    tm.especie,
                    ea.tipo_estado,
                    f.nombre as nombre_fundacion,
                    f.nit_fundacion,
                    u.nombre as rep_nombre,
                    u.apellido as rep_apellido,
                    u.telefono as rep_telefono,
                    u.email as rep_email,
                    u.direccion as fundacion_direccion,
                    p.descripcion as fundacion_descripcion,
                    p.imagen as fundacion_imagen,
                    p.nombre as fundacion_perfil_nombre,
                    CASE 
                        WHEN m.edad_meses < 12 THEN 'cachorro'
                        WHEN m.edad_meses BETWEEN 12 AND 36 THEN 'joven'
                        WHEN m.edad_meses BETWEEN 37 AND 84 THEN 'adulto'
                        ELSE 'senior'
                    END as categoria_edad,
                    -- Contar otras mascotas de la misma fundación
                    (SELECT COUNT(*) FROM t_mascota m2 
                     WHERE m2.nit_fundacion = m.nit_fundacion 
                     AND m2.id_estado_adopcion IN (1, 4) 
                     AND m2.id_mascota != m.id_mascota) as otras_mascotas_disponibles
                FROM t_mascota m
                INNER JOIN t_tipo_mascota tm ON m.id_tipo_mascota = tm.id_tipo_mascota
                INNER JOIN t_estado_adopcion ea ON m.id_estado_adopcion = ea.id_estado_adopcion
                INNER JOIN t_fundacion f ON m.nit_fundacion = f.nit_fundacion
                INNER JOIN t_usuario u ON f.id_usuario = u.id_usuario
                INNER JOIN t_perfil p ON f.id_perfil = p.id_perfil
                WHERE m.id_mascota = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Si existe la mascota, obtener también las redes sociales
        if ($resultado) {
            $sqlRedes = "SELECT tipo_red, url_red 
                        FROM t_perfil_redes 
                        WHERE id_perfil = :id_perfil";
            
            $stmtRedes = $this->db->prepare($sqlRedes);
            $stmtRedes->bindParam(':id_perfil', $resultado['id_perfil'], PDO::PARAM_INT);
            $stmtRedes->execute();
            $resultado['redes_sociales'] = $stmtRedes->fetchAll(PDO::FETCH_ASSOC);
        }
        
        return $resultado;
    }

    // 1️⃣1️⃣ NUEVO: Crear solicitud de adopción
    public function crearSolicitudAdopcion($id_mascota, $id_usuario, $mensaje = '')
    {
        try {
            // Verificar si ya existe una solicitud activa
            $sqlCheck = "SELECT COUNT(*) as total 
                        FROM t_proceso_adopcion 
                        WHERE id_mascota = :id_mascota 
                        AND id_usuario = :id_usuario 
                        AND id_estado IN (1, 2)"; // EN ADOPCION o EN TRAMITE
            
            $stmtCheck = $this->db->prepare($sqlCheck);
            $stmtCheck->bindParam(':id_mascota', $id_mascota, PDO::PARAM_INT);
            $stmtCheck->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmtCheck->execute();
            
            $existe = $stmtCheck->fetch(PDO::FETCH_ASSOC);
            
            if ($existe['total'] > 0) {
                throw new Exception('Ya tienes una solicitud activa para esta mascota');
            }

            // Obtener NIT de la fundación de la mascota
            $sqlMascota = "SELECT nit_fundacion FROM t_mascota WHERE id_mascota = :id";
            $stmtMascota = $this->db->prepare($sqlMascota);
            $stmtMascota->bindParam(':id', $id_mascota, PDO::PARAM_INT);
            $stmtMascota->execute();
            $mascota = $stmtMascota->fetch(PDO::FETCH_ASSOC);
            
            if (!$mascota) {
                throw new Exception('Mascota no encontrada');
            }

            // Crear la solicitud
            $sqlInsert = "INSERT INTO t_proceso_adopcion 
                         (fecha_inicio, fecha_actualizada, id_usuario, id_mascota, nit_fundacion, id_estado)
                         VALUES (NOW(), NOW(), :id_usuario, :id_mascota, :nit_fundacion, 2)"; // Estado 2 = EN TRAMITE

            $stmtInsert = $this->db->prepare($sqlInsert);
            $stmtInsert->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmtInsert->bindParam(':id_mascota', $id_mascota, PDO::PARAM_INT);
            $stmtInsert->bindParam(':nit_fundacion', $mascota['nit_fundacion'], PDO::PARAM_INT);

            return $stmtInsert->execute();

        } catch (Exception $e) {
            error_log("Error en crearSolicitudAdopcion: " . $e->getMessage());
            throw $e;
        }
    }

    // 1️⃣2️⃣ NUEVO: Toggle favoritos (agregar/quitar)
    public function toggleFavorito($id_mascota, $id_usuario)
    {
        try {
            // Nota: Necesitarías crear una tabla t_favoritos si no existe
            // Por ahora, simularemos con comentarios lo que haría
            
            // Verificar si ya está en favoritos (necesitas crear tabla t_favoritos)
            /*
            CREATE TABLE t_favoritos (
                id_favorito int(11) PRIMARY KEY AUTO_INCREMENT,
                id_usuario int(11) NOT NULL,
                id_mascota int(11) NOT NULL,
                fecha_agregado datetime DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (id_usuario) REFERENCES t_usuario(id_usuario),
                FOREIGN KEY (id_mascota) REFERENCES t_mascota(id_mascota),
                UNIQUE KEY unique_favorito (id_usuario, id_mascota)
            );
            */
            
            $sqlCheck = "SELECT COUNT(*) as total FROM t_favoritos 
                        WHERE id_usuario = :id_usuario AND id_mascota = :id_mascota";
            
            $stmtCheck = $this->db->prepare($sqlCheck);
            $stmtCheck->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmtCheck->bindParam(':id_mascota', $id_mascota, PDO::PARAM_INT);
            $stmtCheck->execute();
            
            $resultado = $stmtCheck->fetch(PDO::FETCH_ASSOC);
            
            if ($resultado['total'] > 0) {
                // Eliminar de favoritos
                $sqlDelete = "DELETE FROM t_favoritos 
                             WHERE id_usuario = :id_usuario AND id_mascota = :id_mascota";
                $stmtDelete = $this->db->prepare($sqlDelete);
                $stmtDelete->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                $stmtDelete->bindParam(':id_mascota', $id_mascota, PDO::PARAM_INT);
                $stmtDelete->execute();
                
                return ['action' => 'removed', 'message' => 'Mascota eliminada de favoritos'];
            } else {
                // Agregar a favoritos
                $sqlInsert = "INSERT INTO t_favoritos (id_usuario, id_mascota, fecha_agregado) 
                             VALUES (:id_usuario, :id_mascota, NOW())";
                $stmtInsert = $this->db->prepare($sqlInsert);
                $stmtInsert->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                $stmtInsert->bindParam(':id_mascota', $id_mascota, PDO::PARAM_INT);
                $stmtInsert->execute();
                
                return ['action' => 'added', 'message' => 'Mascota agregada a favoritos'];
            }
            
        } catch (PDOException $e) {
            // Si la tabla no existe, devolver mensaje informativo
            if (strpos($e->getMessage(), "t_favoritos' doesn't exist") !== false) {
                return ['action' => 'error', 'message' => 'Funcionalidad de favoritos no disponible aún'];
            }
            throw $e;
        }
    }

    // ========== MÉTODOS EXISTENTES MANTENIDOS ==========

    // Obtener todos los tipos de mascota (para llenar selects)
    public function getTiposMascota()
    {
        $rows = null;
        $stmt = $this->db->prepare("SELECT * FROM t_tipo_mascota");
        $stmt->execute();

        while ($resultado = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $rows[] = $resultado;
        }

        return $rows;
    }

    // Obtener mascotas por fundación específica
    public function getMascotasPorFundacion($nit_fundacion)
    {
        $sql = "SELECT 
                    m.id_mascota,
                    m.nombre,
                    m.edad_meses,
                    m.sexo,
                    m.imagen,
                    m.nit_fundacion,
                    m.public_id,
                    tm.especie,
                    ea.tipo_estado
                FROM t_mascota m
                LEFT JOIN t_tipo_mascota tm ON m.id_tipo_mascota = tm.id_tipo_mascota
                LEFT JOIN t_estado_adopcion ea ON m.id_estado_adopcion = ea.id_estado_adopcion
                WHERE m.nit_fundacion = :nit";
        $statement = $this->db->prepare($sql);
        $statement->bindParam(':nit', $nit_fundacion, PDO::PARAM_STR);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener todos los estados de adopción (para el select)
    public function getEstadosAdopcion()
    {
        $rows = [];
        $stmt = $this->db->prepare("SELECT * FROM t_estado_adopcion");
        $stmt->execute();

        while ($resultado = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $rows[] = $resultado;
        }

        return $rows;
    }

    // Obtener todos los NIT de fundaciones (solo para admin)
    public function getNitsFundacion()
    {
        $rows = [];

        $stmt = $this->db->prepare("SELECT nit_fundacion FROM t_fundacion");
        $stmt->execute();

        while ($resultado = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $rows[] = $resultado; // Deja el resultado completo como arreglo asociativo
        }

        return $rows;
    }
}