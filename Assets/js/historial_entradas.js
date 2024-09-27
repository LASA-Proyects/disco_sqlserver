document.addEventListener("DOMContentLoaded", function(){
    tblHistorialEntradas = $('#tblHistorialEntradas').DataTable({
        ajax: {
            url: base_url + "Pedidos/listarHistorialEntradas",
            dataSrc: '',
            contentType: "application/json; charset=utf-8"
        },
        columns: [
        {
            'data' : 'id'
        },
        {
            'data' : 'fecha'
        },
        {
            'data' : 'nombre_usuario'
        },
        {
            'data' : 'nombre_almacen'
        },
        {
            'data' : 'documento'
        },
        {
            'data' : 'serie'
        },
        {
            'data' : 'correlativo'
        },
        {
            'data' : 'estado'
        }
    ],
    "responsive": true, "lengthChange": false, "autoWidth": false
    });
    document.getElementById('pdfEntrada').addEventListener('click', function () {
        document.getElementById('entradasExport').action = base_url + "Pedidos/pdfEntradasPorFecha";
        document.getElementById('entradasExport').submit();
    });
});