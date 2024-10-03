<script src="vendors/scripts/core.js"></script>
<script src="vendors/scripts/script.min.js"></script>
<script src="vendors/scripts/process.js"></script>
<script src="vendors/scripts/layout-settings.js"></script>
<script src="vendors/jquery-validation/dist/jquery.validate.min.js"></script>
<script src="src/plugins/datatables/js/jquery.dataTables.min.js"></script>
<script src="src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
<script src="src/plugins/datatables/js/dataTables.responsive.min.js"></script>
<script src="src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
<!-- buttons for Export datatable -->
<script src="src/plugins/datatables/js/dataTables.buttons.min.js"></script>
<script src="src/plugins/datatables/js/buttons.bootstrap4.min.js"></script>
<script src="src/plugins/datatables/js/buttons.print.min.js"></script>
<script src="src/plugins/datatables/js/buttons.html5.min.js"></script>
<script src="src/plugins/datatables/js/buttons.flash.min.js"></script>
<script src="src/plugins/datatables/js/pdfmake.min.js"></script>
<script src="src/plugins/datatables/js/vfs_fonts.js"></script>
<!-- Datatable Setting js -->
<script src="vendors/scripts/datatable-setting.js"></script>

<!-- sweetalert -->
<script src="src/plugins/sweetalert2/sweetalert2.all.js"></script>
<script src="src/plugins/sweetalert2/sweet-alert.init.js"></script>

<!-- autocomplete script -->
<!--<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>-->

<!-- common scripts -->
<script src="quickaction.js"></script>

<script>
    $(document).ready(function () {
        $('.d-cursor').focus();
    });
</script>

<script>
    $(document).ready(function () {
        $('a.margin-5').text('test');
    });
    
    $(document).ready(function() {
        setTimeout(function() {
            $(".alert-dismissible").fadeOut('slow');
        }, 2500);
    });
</script>