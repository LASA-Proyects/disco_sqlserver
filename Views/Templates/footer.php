<script src="<?php echo base_url;?>Assets/js/jquery-3.7.1.min.js"></script>
<script src="<?php echo base_url;?>Assets/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo base_url;?>Assets/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url;?>Assets/js/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo base_url;?>Assets/js/dataTables.responsive.min.js"></script>
<script src="<?php echo base_url;?>Assets/js/responsive.bootstrap4.min.js"></script>
<script src="<?php echo base_url;?>Assets/js/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url;?>Assets/js/buttons.bootstrap4.min.js"></script>
<script src="<?php echo base_url;?>Assets/js/jszip.min.js"></script>
<script src="<?php echo base_url;?>Assets/js/buttons.html5.min.js"></script>
<script src="<?php echo base_url;?>Assets/js/buttons.print.min.js"></script>
<script src="<?php echo base_url;?>Assets/js/buttons.colVis.min.js"></script>
<script src="<?php echo base_url;?>Assets/js/adminlte.min.js"></script>
<script src="<?php echo base_url;?>Assets/js/sweetalert2.all.min.js"></script>
<script src="<?php echo base_url;?>Assets/js/select2.full.min.js"></script>
<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>
</body>
</html>