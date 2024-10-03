
<!--timeline function-->
timeline_history('Insert', 'sales_order_fabric_program', $programId, 'Fabric Program Added with components, yarn details, and process details');

<!--single dropdown-->
select_dropdown('department', array('id', 'department_name'), 'department_name ASC', $asd['department'], '', '');

<!--call a function when modal close-->
$('#AddedComponentDetails').on('hidden.bs.modal', function (e) {
    $("#AddedComponentDetails_tbody").html();
})

<!--modal outside click false-->
$('#viewModal').modal({
    backdrop: 'static',
    keyboard: false
})


<!--duplication validation-->
<script>
    $("#brand_name").change(function() {
        var value = $("#brand_name").val();
        
        validate_Duplication('brand', 'brand_name', value, 'brand_name');
        // validate_Duplication(table, table_field, value, input_field)
    });
</script>

<!-- // hide -->

<!-- new Fields -->
<?php
// BO 536 size range
// ["variation_value_id=1,,quantity=03552,,excess_per=3","variation_value_id=3,,quantity=15260,,excess_per=3","variation_value_id=4,,quantity=16132,,excess_per=3","variation_value_id=5,,quantity=6104,,excess_per=3","variation_value_id=6,,quantity=3552,,excess_per=3","variation_value_id=7,,quantity=0,,excess_per=3","variation_value_id=8,,quantity=0,,excess_per=3","variation_value_id=9,,quantity=0,,excess_per=3","variation_value_id=10,,quantity=0,,excess_per=3","variation_value_id=11,,quantity=0,,excess_per=3","variation_value_id=12,,quantity=0,,excess_per=3","variation_value_id=13,,quantity=0,,excess_per=3","variation_value_id=41,,quantity=0,,excess_per=3","variation_value_id=42,,quantity=0,,excess_per=3"]


// 07-05

// => new table [ sod_combo ]
// => new table [ sod_size ]
// => new table [ mas_pack ]
// => new file [ mas_pack_type.php ]