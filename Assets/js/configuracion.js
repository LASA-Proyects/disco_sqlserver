function verResumenVentas(){
    const url = base_url + "Configuracion/getResumenVentasDet/";
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            const res = JSON.parse(this.responseText);
            let html = '';
            let totalCantidad = 0;
            let totalTotal = 0;
            res.forEach(row => {
                html += `<tr>
                    <td>${row.id_prod}</td>
                    <td>${row.descripcion}</td>
                    <td>${row.codigo}</td>
                    <td>${row.cantidad}</td>
                    <td>${row.unidad_med}</td>
                    <td>${row.tipo_prod}</td>
                    <td>${row.total}</td>
                </tr>`;
                totalCantidad += parseFloat(row.cantidad);
                totalTotal += parseFloat(row.total);
            });
            if ($.fn.DataTable.isDataTable('#tblResumenVentasTBL')) {
                $('#tblResumenVentasTBL').DataTable().destroy();
            }
            document.querySelector('#tblResumenVentasTBLJS').innerHTML = html;

            const tfootHTML = `
            <tr style="color: white;">
                <td colspan="3">Total</td>
                <td>${totalCantidad}</td>
                <td></td> <!-- Espacio en blanco para la columna Unidad -->
                <td></td> <!-- Espacio en blanco para la columna Tipo -->
                <td>${totalTotal}</td>
            </tr>
        `;

        document.getElementById('tblResumenVentasTFoot').innerHTML = tfootHTML;
            $('#tblResumenVentasTBL').DataTable({
                "responsive": true,
                "lengthChange": true,
                "autoWidth": false,
            });
            $('#verResumenVentasModal').modal("show");
        }
    }
}

function verResumenClientes(){
    const url = base_url + "Configuracion/getResumenClientesDet/";
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            const res = JSON.parse(this.responseText);
            let html = '';
            let id_sum = 1;
            let totalTotal = 0;
            res.forEach(row => {
                html += `<tr>
                    <td>${id_sum}</td>
                    <td>${row.dni_cliente}</td>
                    <td>${row.nombre_cliente}</td>
                    <td>${row.ruc}</td>
                    <td>${row.razon_social}</td>
                    <td>${row.total_consumido}</td>
                </tr>`;
                totalTotal += parseFloat(row.total_consumido);
                id_sum++;
            });
            if ($.fn.DataTable.isDataTable('#tblResumenClientesTBL')) {
                $('#tblResumenClientesTBL').DataTable().destroy();
            }
            document.querySelector('#tblResumenClientesTBLJS').innerHTML = html;

            const tfootHTML = `
            <tr style="color: white;">
                <td colspan="5">Total</td>
                <td>${totalTotal}</td>
            </tr>
        `;

        document.getElementById('tblResumenClientesTFoot').innerHTML = tfootHTML;
            $('#tblResumenClientesTBL').DataTable({
                "responsive": true,
                "lengthChange": true,
                "autoWidth": false,
            });
            $('#verResumenClientesModal').modal("show");
        }
    }
}