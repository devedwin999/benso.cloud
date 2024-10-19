
<!--timeline function-->
timeline_history('Insert', 'sales_order_fabric_program', $programId, 'Fabric Program Added with components, yarn details, and process details');

<!--single dropdown-->
select_dropdown('department', array('id', 'department_name'), 'department_name ASC', $asd['department'], '', '');

<!--call a function when modal close-->
$('#AddedComponentDetails').on('hidden.bs.modal', function (e) {
    $("#AddedComponentDetails_tbody").html();
})

<!--call a function when modal show-->
$('#AddedComponentDetails').on('shown.bs.modal', function (e) {
    // Call your function here
    yourFunction();
});

<!--modal outside click false-->
$('#viewModal').modal({
    backdrop: 'static',
    keyboard: false
})

$(".partName, .compNm").each(function() {
    $(this).select2({
        dropdownParent: $('#parent_modal'),
    });
});


<!--duplication validation-->
<script>
    $("#brand_name").change(function() {
        var value = $("#brand_name").val();
        
        validate_Duplication('brand', 'brand_name', value, 'brand_name');
        // validate_Duplication(table, table_field, value, input_field)
    });
</script>