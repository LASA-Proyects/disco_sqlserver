<?php
class CargaModel extends Query{
    public function __construct()
    {
        parent::__construct();
    }

    public function getCargaInvitados(int $id_usuario)
    {
        $sql = "SELECT a.*, m.nombre AS nombre_mesa, u.nombre AS nombre_usuario FROM ".db_conect."asistencia_entrada a
        JOIN ".db_conect."mesas m ON a.mesa = m.id
        JOIN ".db_conect."usuarios u ON a.id_usuario = u.id
        WHERE a.id_usuario = $id_usuario";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getUsuarioId($id_usu)
    {
        $sql = "SELECT * FROM ".db_conect."usuarios WHERE id = $id_usu";
        $data = $this->select($sql);
        return $data; 
    }

    public function getUsuarios()
    {
        $sql = "SELECT * FROM ".db_conect."usuarios";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getRangoFechasGen(string $desde, string $hasta)
    {
        $sql = "SELECT * FROM ".db_conect."asistencia_entrada WHERE fecha_asist BETWEEN '$desde' AND '$hasta'";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getRangoFechas(string $desde, string $hasta, int $id_usuario)
    {
        $sql = "SELECT * FROM ".db_conect."asistencia_entrada WHERE fecha_asist BETWEEN '$desde' AND '$hasta' AND id_usuario = $id_usuario";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getMesas()
    {
        $sql = "SELECT * FROM ".db_conect."mesas";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getPromotor()
    {
        $sql = "SELECT DISTINCT u.nombre, u.id FROM ".db_conect."asistencia_entrada a
        JOIN ".db_conect."usuarios u ON u.id = a.id_usuario";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function buscarInvitado(int $id_promotor)
    {
        $sql = "SELECT * FROM ".db_conect."asistencia_entrada WHERE id_usuario = $id_promotor AND estado = 0 AND CAST(fecha AS DATE) = CAST(GETDATE() AS DATE)";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function actualizarInvitado(string $fecha_asist, string $hora_asist, int $estado, int $id)
    {
        $sql = "UPDATE ".db_conect."asistencia_entrada SET fecha_asist = ?, hora_asist = ?, estado = ? WHERE id = ?";
        $datos = array($fecha_asist, $hora_asist, $estado, $id);
        try {
            $data = $this->save_trans($sql, $datos);
            $datos = "UPDATE ".db_conect."asistencia_entrada SET fecha_asist = $fecha_asist, hora_asist = $hora_asist, estado = $estado WHERE id = $id";
            return $datos;
        } catch (PDOException $e) {
            $error_message = $e->getMessage();
            throw $e;
        }
    }

    public function consultar(int $id_usuario)
    {
        $sql = "SELECT * FROM ".db_conect."asistencia_entrada WHERE fecha_asist BETWEEN DATE_ADD(CURDATE(), INTERVAL 1 - DAYOFWEEK(CURDATE()) DAY) AND DATE_ADD(CURDATE(), INTERVAL 6 - DAYOFWEEK(CURDATE()) DAY) AND id_usuario = $id_usuario";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function consultarTotal(int $id_usuario)
    {
        $sql = "SELECT
        conteo_registros,
        CASE
            WHEN conteo_registros >= 30 THEN conteo_registros * 3.00
            ELSE conteo_registros * 1.50
        END AS pago_comision
    FROM (
        SELECT COUNT(*) AS conteo_registros
        FROM ".db_conect."asistencia_entrada
        WHERE fecha_asist BETWEEN
            DATE_ADD(CURDATE(), INTERVAL 1 - DAYOFWEEK(CURDATE()) DAY) AND
            DATE_ADD(CURDATE(), INTERVAL 6 - DAYOFWEEK(CURDATE()) DAY) AND
            id_usuario = $id_usuario
    ) AS subconsulta;";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function consultarTotalGen()
    {
        $sql = "SELECT
        conteo_registros,
        CASE
            WHEN conteo_registros >= 30 THEN conteo_registros * 3.00
            ELSE conteo_registros * 1.50
        END AS pago_comision
    FROM (
        SELECT COUNT(*) AS conteo_registros
        FROM ".db_conect."asistencia_entrada
        WHERE fecha_asist BETWEEN
            DATE_ADD(CURDATE(), INTERVAL 1 - DAYOFWEEK(CURDATE()) DAY) AND
            DATE_ADD(CURDATE(), INTERVAL 6 - DAYOFWEEK(CURDATE()) DAY)
    ) AS subconsulta;";
        $data = $this->selectAll($sql);
        return $data;
    }


    public function registrarCarga(int $id_usuario, ?int $dni = NULL, string $apellidoPaterno, string $apellidoMaterno, string $nombres, int $mesa, string $fecha_actual)
    {
        $sql = "EXEC ".db_conect." registrar_carga @p_id_usuario = ?, @p_dni = ?, @p_apellido_paterno = ?, @p_apellido_materno = ?, @p_nombres = ?, @p_mesa = ?, @p_fecha_actual = ?";
        $datos = array($id_usuario, $dni, $apellidoPaterno, $apellidoMaterno, $nombres, $mesa, $fecha_actual);
        try {
            $data['id'] = $this->save_reg($sql, $datos);
            $data['sql'] = "CALL registrar_carga(id_usuario=$id_usuario, dni=$dni, apellidoPaterno=$apellidoPaterno, apellidoMaterno=$apellidoMaterno, nombres=$nombres, mesa=$mesa, fecha_actual=$fecha_actual)";
            return $data;
        } catch (PDOException $e) {
            $error_message = $e->getMessage();
            throw $e;
        }
    }

    public function marcarAsist(string $fecha_asist, string $hora_asist, int $estado, int $id)
    {
        $sql = "EXEC ".db_conect." marcar_asistencia @p_fecha_asist = ?, @p_hora_asist = ?, @p_estado = ?, @p_id = ?";
        $datos = array($fecha_asist, $hora_asist, $estado, $id);
        $data = $this->save($sql, $datos);
        if($data == 1){
            $res = "ok";
        }else{
            $res = "error";
        }
        return $res;
    }

    public function verificarPermiso(int $id_usuario, int $permiso_padre)
    {
        $sql = "SELECT * FROM ".db_conect."permiso_usuario WHERE id_permiso_padre = $permiso_padre AND id_usuario = $id_usuario";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function verificarPermisoTerm(int $id_usuario, string $permiso)
    {
        $sql = "SELECT p.id, p.permiso, d.id, d.id_usuario, d.id_permiso FROM ".db_conect."permisos p
        INNER JOIN ".db_conect."detalle_permisos d ON p.id = d.id_permiso
        WHERE d.id_usuario = $id_usuario AND p.permiso = '$permiso'";
        $data = $this->selectAll($sql);
        return $data;
    }
    public function registrarLog(int $id_usuario, string $accion, string $fecha, int $id_almacen, ?string $tabla_nombre = NULL)
    {
        $sql = "INSERT INTO ".db_conect."logs(id_usuario, accion, fecha_hora, id_almacen, tabla_nombre) VALUES (?,?,?,?,?)";
        $datos = array($id_usuario, $accion, $fecha, $id_almacen, $tabla_nombre);
        $data = $this->save($sql, $datos);
        if($data == 1){
            $res = "ok";
        }else{
            $res = "error";
        }
        return $res;
    }
}
?>