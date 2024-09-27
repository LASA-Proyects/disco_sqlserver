<?php
class ConfiguracionModel extends Query{
    private $nombre, $data, $id;
    public function __construct()
    {
        parent::__construct();
    }

    public function getResumenVentasGen()
    {
        $sql = "SELECT COUNT(*) as cantidad, SUM(total) AS total FROM ".db_conect."pedidos where estado = 0 AND tipo_pedido = 1";
        $data = $this->select($sql);
        return $data;
    }

    public function getResumenVentasDet()
    {
        $sql = "SELECT 
                    p.id AS id_prod, 
                    p.descripcion, 
                    p.codigo, 
                    COUNT(p.id) AS cantidad, 
                    p.unidad_med, 
                    ta.nombre AS tipo_prod, 
                    SUM(dp.total) AS total
                FROM ".db_conect."detalle_pedidos dp
                LEFT JOIN ".db_conect."productos p ON p.id = dp.id_producto
                LEFT JOIN ".db_conect."t_articulo ta ON ta.id = p.id_tarticulo
                LEFT JOIN ".db_conect."pedidos pd ON pd.id = dp.id_pedido
                WHERE pd.estado = 0 
                  AND dp.id_producto_asoc IS NULL 
                  AND pd.tipo_pedido = 1
                GROUP BY 
                    p.id, 
                    p.descripcion, 
                    p.codigo, 
                    p.unidad_med, 
                    ta.nombre
                ORDER BY cantidad DESC";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getResumenClientesDet()
    {
        $sql = "SELECT 
        CASE
            WHEN dni = '99999999' OR dni IS NULL THEN '99999999' 
            ELSE dni
        END AS dni_cliente,
        CASE
            WHEN dni = '99999999' OR dni IS NULL THEN 'CLIENTES VARIOS' 
            ELSE CONCAT(nombres, ' ', apellido_paterno, ' ', apellido_materno)
        END AS nombre_cliente,
        MAX(ruc) AS ruc,
        MAX(razon_social) AS razon_social, 
        SUM(total) AS total_consumido
    FROM 
        ".db_conect."pedidos 
    WHERE 
        estado = 0 
        AND tipo_pedido = 1 
    GROUP BY 
        CASE
            WHEN dni = '99999999' OR dni IS NULL THEN '99999999' 
            ELSE dni
        END,
        CASE
            WHEN dni = '99999999' OR dni IS NULL THEN 'CLIENTES VARIOS' 
            ELSE CONCAT(nombres, ' ', apellido_paterno, ' ', apellido_materno)
        END
        ORDER BY 
                total_consumido DESC";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function verificarPermiso(int $id_usuario, string $permiso)
    {
        $sql = "SELECT p.id, p.permiso, d.id, d.id_usuario, d.id_permiso FROM ".db_conect."permisos p
        INNER JOIN ".db_conect."detalle_permisos d ON p.id = d.id_permiso
        WHERE d.id_usuario = $id_usuario AND p.permiso = '$permiso'";
        $data = $this->selectAll($sql);
        return $data;
    }
}
?>