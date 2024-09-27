<?php
class BancosModel extends Query{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function getAlmacen(int $id_almacen)
    {
        $sql = "SELECT * FROM ".db_conect."almacen WHERE id = $id_almacen";
        $data = $this->select($sql);
        return $data;
    }

    public function getBancos($id_almacen)
    {
        $sql = "SELECT * FROM ".db_conect."bancos where id = $id_almacen";
        $data = $this->selectAll($sql);
        return $data; 
    }

    public function getBancosTodos()
    {
        $sql = "SELECT * FROM ".db_conect."bancos";
        $data = $this->selectAll($sql);
        return $data; 
    }

    public function getBancoInicial(int $id_banco_ini)
    {
        $sql = "SELECT * FROM ".db_conect."bancos where id = $id_banco_ini";
        $data = $this->select($sql);
        return $data; 
    }

    public function getBancoFinal(int $id_banco_fin)
    {
        $sql = "SELECT * FROM ".db_conect."bancos where id = $id_banco_fin";
        $data = $this->select($sql);
        return $data; 
    }

    public function getGlosas()
    {
        $sql = "SELECT * FROM ".db_conect."glosa_compra";
        $data = $this->selectAll($sql);
        return $data; 
    }

    public function getTipoDoc()
    {
        $sql = "SELECT * FROM ".db_conect."tipo_documento";
        $data = $this->selectAll($sql);
        return $data; 
    }

    public function registrarIngresoBancos(int $id_usuario, int $id_almacen, int $id_banco_ini, ?int $id_banco_fin= NULL, ?string $numero_operacion = NULL, ?int $documento = NULL, ?string $nombre = NULL, ?int $tipo_doc = NULL, ?string $serie = NULL, ?string $numero = NULL, float $monto_ingreso, float $monto_total, string $glosa, $b_200, $b_100, $b_50, $b_20, $b_10, $m_5, $m_2, $m_1, $m_050, $m_020, $m_010, string $fecha, int $tipo_operacion_banco)
    {
        $sql = "EXEC ".db_conect." registrarIngresoBancos @p_id_usuario=?, @p_id_almacen=?, @p_id_banco_ini=?, @p_id_banco_fin=?, @p_numero_operacion=?, @p_documento=?, @p_nombre=?, @p_tipo_doc=?, @p_serie=?, @p_numero=?, @p_monto_ingreso=?, @p_monto_total=?, @p_glosa=?, @p_b_200=?, @p_b_100=?, @p_b_50=?, @p_b_20=?, @p_b_10=?, @p_m_5=?, @p_m_2=?, @p_m_1=?, @p_m_050=?, @p_m_020=?, @p_m_010=?, @p_fecha=?, @p_tipo_operacion_banco=?";
        $datos = array(
            $id_usuario, $id_almacen, $id_banco_ini, $id_banco_fin, $numero_operacion, $documento, $nombre, $tipo_doc, $serie, $numero, $monto_ingreso, $monto_total, $glosa,
            empty($b_200) ? null : $b_200,
            empty($b_100) ? null : $b_100,
            empty($b_50) ? null : $b_50,
            empty($b_20) ? null : $b_20,
            empty($b_10) ? null : $b_10,
            empty($m_5) ? null : $m_5,
            empty($m_2) ? null : $m_2,
            empty($m_1) ? null : $m_1,
            empty($m_050) ? null : $m_050,
            empty($m_020) ? null : $m_020,
            empty($m_010) ? null : $m_010,
            $fecha, $tipo_operacion_banco
        );
        try {
            $data['id'] = $this->save_reg($sql, $datos);
            $data['sql'] = "CALL registrarIngresoBancos(id_usuario=$id_usuario, id_almacen=$id_almacen, id_banco_ini=$id_banco_ini, id_banco_fin=$id_banco_fin, numero_operacion=$numero_operacion, documento=$documento, nombre=$nombre, tipo_doc=$tipo_doc, serie=$serie, numero=$numero, monto_ingreso=$monto_ingreso, monto_total=$monto_total, glosa=$glosa, b_200=$b_200, b_100=$b_100, b_50=$b_50, b_20=$b_20, b_10=$b_10, m_5=$m_5, m_2=$m_2, m_1=$m_1, m_050=$m_050, m_020=$m_020, m_010=$m_010, fecha=$fecha, tipo_operacion_banco=$tipo_operacion_banco)";
            return $data;
        } catch (PDOException $e) {
            $error_message = $e->getMessage();
            throw $e;
        }
    }
    
    public function buscarKardexBancoGen(string $fecha_desde, string $fecha_hasta)
    {
        $sql = "SELECT bo.id, u.nombre AS nombre_usuario, g.descripcion, bo.b_200, bo.b_100,bo.b_50,bo.b_20,bo.b_10,bo.m_5,bo.m_2,bo.m_1,bo.m_050, bo.m_020, bo.m_010, bo.fecha, 'MOVIMIENTO DE INGRESO' AS operacion, bo.numero_operacion, b.nombre, b.cuenta, b.cuenta_contable, bo.monto_ingreso AS 'ingresos', 0 AS 'salidas' FROM ".db_conect."banco_operaciones bo 
        JOIN ".db_conect."bancos b on b.id = bo.id_banco_ini
        JOIN ".db_conect."usuarios u on u.id = bo.id_usuario
        JOIN ".db_conect."glosa_compra g ON g.id = bo.glosa
        WHERE bo.tipo_operacion_banco = 1 AND bo.fecha BETWEEN '$fecha_hasta' AND '$fecha_hasta' 
        UNION ALL
        SELECT bo.id, u.nombre AS nombre_usuario, g.descripcion, bo.b_200, bo.b_100,bo.b_50,bo.b_20,bo.b_10,bo.m_5,bo.m_2,bo.m_1,bo.m_050, bo.m_020, bo.m_010, bo.fecha, 'MOVIMIENTO DE SALIDA' AS operacion, bo.numero_operacion, b.nombre , b.cuenta, b.cuenta_contable, 0 AS 'ingresos', bo.monto_ingreso AS 'salidas' FROM ".db_conect."banco_operaciones bo 
        JOIN ".db_conect."bancos b on b.id = bo.id_banco_ini 
        JOIN ".db_conect."usuarios u on u.id = bo.id_usuario
        JOIN ".db_conect."glosa_compra g ON g.id = bo.glosa
        WHERE bo.tipo_operacion_banco = 2 AND bo.fecha BETWEEN '$fecha_hasta' AND '$fecha_hasta' ORDER BY fecha";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function buscarBancoOp(int $id_banco)
    {
        $sql = "select bo.*,
        (select x.nombre from ".db_conect."tipo_documento x where x.id = bo.tipo_doc) AS documento_nombre,
        (select y.nombre from ".db_conect."usuarios y where y.id = bo.id_usuario) AS nombre_usuario
        from ".db_conect."banco_operaciones bo
        where bo.id = $id_banco";
        $data = $this->select($sql);
        return $data; 
    }

    public function buscarKardexBanco(string $id_banco, string $fecha_desde, string $fecha_hasta)
    {
        $sql = "SELECT bo.id, u.nombre AS nombre_usuario, g.descripcion, bo.b_200, bo.b_100,bo.b_50,bo.b_20,bo.b_10,bo.m_5,bo.m_2,bo.m_1,bo.m_050, bo.m_020, bo.m_010, bo.fecha, 'MOVIMIENTO DE INGRESO' AS operacion, bo.numero_operacion, b.nombre, b.cuenta, b.cuenta_contable, bo.monto_ingreso AS 'ingresos', 0 AS 'salidas' FROM ".db_conect."banco_operaciones bo 
        JOIN ".db_conect."bancos b on b.id = bo.id_banco_ini 
        JOIN ".db_conect."usuarios u on u.id = bo.id_usuario
        JOIN ".db_conect."glosa_compra g ON g.id = bo.glosa
        WHERE bo.tipo_operacion_banco = 1 AND bo.fecha BETWEEN '$fecha_desde' AND '$fecha_hasta' AND bo.id_banco_ini = $id_banco
        UNION ALL
        SELECT bo.id, u.nombre AS nombre_usuario, g.descripcion, bo.b_200, bo.b_100,bo.b_50,bo.b_20,bo.b_10,bo.m_5,bo.m_2,bo.m_1,bo.m_050, bo.m_020, bo.m_010, bo.fecha, 'MOVIMIENTO DE SALIDA' AS operacion, bo.numero_operacion, b.nombre , b.cuenta, b.cuenta_contable, 0 AS 'ingresos', bo.monto_ingreso AS 'salidas' FROM ".db_conect."banco_operaciones bo 
        JOIN ".db_conect."bancos b on b.id = bo.id_banco_ini 
        JOIN ".db_conect."usuarios u on u.id = bo.id_usuario
        JOIN ".db_conect."glosa_compra g ON g.id = bo.glosa
        WHERE bo.tipo_operacion_banco = 2 AND bo.fecha BETWEEN '$fecha_desde' AND '$fecha_hasta' AND bo.id_banco_ini = $id_banco ORDER BY fecha";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function verificarPermiso(int $id_usuario, int $permiso_padre, int $permiso_hijo)
    {
        $sql = "SELECT * FROM ".db_conect."permiso_usuario WHERE id_permiso_padre = $permiso_padre AND id_permiso_hijo = $permiso_hijo AND id_usuario = $id_usuario";
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

    public function getEmpresa()
    {
        $sql = "SELECT * FROM ".db_conect."configuracion";
        $data = $this->select($sql);
        return $data;
    }
}
?>