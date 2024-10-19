// functions
// alert();

// https://benso.cloud/

var base_url = 'http://127.0.0.1:8080/benso_cloud/';

// var base_url = 'http://192.168.1.77:8080/benso_cloud/';

// sidebar auto show and hide functions START
{
    $(document).ready(function () {
        hidesidebar();
        searchNoti();
    });
    $(".hidesidebar").click(function () {
        hidesidebar();
    });
    $(".left-side-bar").mouseout(function () {
        hidesidebar();
    });
    
    $(".left-side-bar").mouseover(function () {
        showsidebar();
    });
    
    $(".ti-view-list").click(function () {
        showsidebar();
    });
    
    window.onresize = reportWindowSize;
    
    function reportWindowSize() {
        var a = window.screen.width;
        if (a < 1366) {
            $(".main-container").removeClass('nw-cont');
            $(".header").removeClass('nw-head');
            $(".left-side-bar").removeClass('nw-sidebar');
            $(".brand-logo").removeClass('nw-brand-logo');
    
            $(".ti-close").hide();
            $(".ti-view-list").hide();
            $(".ovWindow").show();
    
            // $(".mtn1").show();
            $(".mtn1").removeClass('d-none');
            $(".nw-min-head").html('BENSO');
        } else {
            hidesidebar();
        }
    }
    
    function hidesidebar() {
        var a = window.screen.width;
    
        if (a >= 1360) {
            $(".nw-min-head").html('<img src="vendors/images/favicon-32x32.png" style="width:40px">');
    
            $(".main-container").addClass('nw-cont');
            $(".header").addClass('nw-head');
            $(".left-side-bar").addClass('nw-sidebar');
            $(".brand-logo").addClass('nw-brand-logo');
            $(".nw-noarrow").addClass('no-arrow');
    
    
            $(".ti-close").hide();
            $(".ti-view-list").show();
            $(".ovWindow").hide();
    
            // setTimeout(function () {
            //     alert();
            $(".mtn1").addClass('d-none');
            // $(".mtn1").hide();
            // }, 100)
        }
    }
    
    function showsidebar() {
        var a = window.screen.width;
        if (a >= 1360) {
            $(".main-container").removeClass('nw-cont');
            $(".header").removeClass('nw-head');
            $(".left-side-bar").removeClass('nw-sidebar');
            $(".brand-logo").removeClass('nw-brand-logo');
            $(".nw-noarrow").removeClass('no-arrow');
    
            // $(".mtn1").show();
            $(".mtn1").removeClass('d-none');
    
            $(".ti-close").show();
            $(".ti-view-list").hide();
            $(".ovWindow").hide();
    
            $(".nw-min-head").html('BENSO');
        }
    }
}
// sidebar auto show and hide functions END


// duplication validation start
{
    // master brand
    $(".valid_brand_name").change(function() {
        var value = $(".valid_brand_name").val();
        
        validate_Duplication('brand', 'brand_name', value, 'valid_brand_name');
    });
    
    // master unit
    $(".valid_unit_name").change(function() {
        var value = $(".valid_unit_name").val();
        
        validate_Duplication('unit', 'full_name', value, 'valid_unit_name');
    });
    
    // master Department
    $(".valid_department").change(function() {
        var value = $(".valid_department").val();
        
        validate_Duplication('department', 'department_name', value, 'valid_department');
    });
    
    // master Department
    $(".selection_type_name").change(function() {
        
        var value = $(".selection_type_name").val();
        
        validate_Duplication('selection_type', 'type_name', value, 'selection_type_name');
    });
    
    // master part
    $(".valid_part_name").change(function() {
        
        var value = $(".valid_part_name").val();
        
        validate_Duplication('part', 'part_name', value, 'valid_part_name');
    });
    
    // master part
    $(".valid_employee_code").change(function() {
        
        var value = $(".valid_employee_code").val();
        
        validate_Duplication('employee_detail', 'employee_code', value, 'valid_employee_code');
    });
    
    
    
    function validate_Duplication(table, table_field, value, input_field) {
        
        
        var data = 'table=' + table + '&field=' + table_field + '&value=' + value;
        $.ajax({
            
            type: 'POST',
            url: 'ajax_search2.php?validate_Duplication=1',
            data: data,
            success: function(msg){
                
                var json = $.parseJSON(msg);
                
                if(json.count>0) {
                    $("." + input_field).focus();
                    $("." + input_field).val('');
                    
                    message_noload('error', '' + value +' Already Exists!');
                    return false;
                }
            }
        });
    }
}
// duplication validation end


// production filter modal start
{
    $(".show_filter").click(function() {
        
        $("#report_filter-modal").modal('show');
    });
    
    
    $('.filter_search').on('input', function() {
        var css = $(this).data('class');
        var searchText = $(this).val().toLowerCase();
        
        $('.' + css).each(function() {
            var container = $(this);
            var name = container.find('label').text().toLowerCase();
            
            if (name.includes(searchText)) {
                container.css('display', 'block');
            } else {
                container.css('display', 'none');
            }
        });
    });
    
    
    $(".date_cbox").click(function() {
        
        var ch = $(this).is(':checked');
        var dt = $(this).data('class');
        
        if(ch==true) {
            $("." + dt).removeAttr('readonly');
        } else {
            $("." + dt).prop('readonly', true).val('');
        }
    });
    
    
    $(".f_cbx").click(function() {
        var dt = $(this).data('cls');
        var s = $("." + dt + "_bdg:checked").length;
        
        $("."+ dt +"_badge").toggleClass('d-none', s === 0).text(s);
    });
    
    $(".nav-item").click(function() {
       $("#overlay").fadeIn(100);
       
      $("#overlay").fadeOut(500);
    });
    
    $(".filtttr").click(function() {
        $(this).html('<i class="fa fa-spinner"></i> Generating..');
    });
    
    $(".chbox_fil").click(function() {
        
        var ch = $(this).is(':checked');
        var nm = $(this).attr('name');
        
        var ln = $("."+ nm +"_cbox:checked").length;
        
        var bdg = $(this).data('bdg');
        
        if(ch==true) {
            $("."+ nm +"_cbox").not(this).prop('checked', false);
        }
        
        (ln>0) ? $("." + bdg).removeClass('d-none').text(1) : $("." + bdg).addClass('d-none').text('');
    });
    
}
// production filter modal end


$(".cancel_spinner").click(function() {
    $("#overlay").fadeOut(500);
});

$('.number_input').on('input', function() {
    
    var inputVal = $(this).val();
    var pattern = /^\d*\.?\d*$/;
    if (!pattern.test(inputVal)) {
        $(this).val('');
    }
});

$('.uppercase_valid').on('input', function() {
    var currentValue = $(this).val();
    $(this).val(currentValue.toUpperCase());
});

        
$('.imagefield').change(function() {
    var file = this.files[0];
    
    var tt = $(this).closest('.image_head_tag').find('.imagename');

    var width = tt.data('width');
    
    if (!file || !file.type.startsWith('image/')) {
        tt.html('Accept Images Only');
        message_noload('info', 'Select only image files!', 2000);
        $(this).val('');
        return;
    }
    
    var reader = new FileReader();
    
    reader.onload = function(event) {
        var imageData = event.target.result;
        tt.html('<img src="' + imageData + '" width="'+ (width ? width : '') +'">');
    };
    
    reader.readAsDataURL(file);
});


$(".checkOut").click(function() {

   var inout_latitude = $("#inout_latitude").val();
   
   if(inout_latitude == "") {
       message_noload('warning', 'Location Permission Disabled!', 2000);
       return false;
   } else {
       
       $(this).addClass('pe-none').text('Updating..');
       
       var form = $("#check_inout_form").serialize();
       
      $.ajax({
            type: 'POST',
            url: 'ajax_action2.php?employee_check_out=1',
            data: form,
            success: function (msg) {
                
                var json = $.parseJSON(msg);
                if (json.result == 0) {
                    message_reload('success', 'Check out Success!');
                } else {
                    message_error();
                }
            }
        });
   }
   
});


$(".checkIn").click(function() {
   
   var inout_latitude = $("#inout_latitude").val();
   
   if(inout_latitude == "") {
       message_noload('warning', 'Location Permission Disabled!', 2000);
       return false;
   } else {
       
       $(this).addClass('pe-none').text('Updating..');
       
       var form = $("#check_inout_form").serialize();
       
      $.ajax({
            type: 'POST',
            url: 'ajax_action2.php?employee_check_in=1',
            data: form,
            success: function (msg) {
                
                var json = $.parseJSON(msg);
                if (json.result == 0) {
                    message_reload('success', 'Check IN Success!');
                } else {
                    message_error();
                }
            }
        });
   }
   
});


$(".check_inout_").click(function() {
    
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            var latitude = position.coords.latitude;
            var longitude = position.coords.longitude;
            // document.getElementById('browser_location').value = latitude + ',' + longitude;
            
            $("#inout_latitude").val(latitude);
            $("#inout_longitude").val(longitude);
            
            $(".notif_location").text('');
        });
    } else {
        alert('Geolocation is not supported by this browser.');
    }
    
    $("#check_inout_modal").modal('show');
});


$(".editEmployee").click(function () {

    var id = $(this).attr('data-id');
    
    $("#updId").val(id);
    $("#overlay").fadeIn(100);
    // $.ajax({
    //     type: 'POST',
    //     url: 'ajax_search.php?getemployeeedit=1&id=' + id,
    //     success: function (msg) {
    //         $("#editmodaldetail").html(msg);
    //     }
    // })
    
    $("#editmodaldetail").load('emp_edit.php?type=employee_edit&id=' + id);
    $("#overlay").fadeOut(500);
    $("#employee-edit-modal").modal('show');
});

$("#dob").change(function() {
    dob = new Date($(this).val());
    var today = new Date();
    var age = Math.floor((today-dob) / (365.25 * 24 * 60 * 60 * 1000));
    $('#age').val(age);
    
    // alert(age);
});

$("#dob_edit").change(function() {
    
    dob = new Date($(this).val());
    var today = new Date();
    var age = Math.floor((today-dob) / (365.25 * 24 * 60 * 60 * 1000));
    $('#age_edit').val(age);
    
    // alert(age);
});


$(".showSubprocess").click(function () {

    var a = $(this).attr('data-id');

    $.ajax({
        type: 'POST',
        url: 'ajax_search.php?getSubprocessDet=1&id=' + a,
        success: function (msg) {
            var json = $.parseJSON(msg);
            $("#showsub_body").html(json.table);
        }
    })

    $("#showprocessModal").modal('show');
});


$(".date-picker").datepicker({ dateFormat: 'yy-mm-dd' });


function save_fabric() {
    if ($("#fabric_name").val() == "") {
        message_noload('warning', 'Fabric Name Required!', 1000);
        return false;
    } else if ($("#fabric_code").val() == "") {
        message_noload('warning', 'Fabric Code Required!', 1000);
        return false;
    } else {
        var form = $("#fabricForm").serialize()
        $.ajax({
            type: 'POST',
            url: 'ajax_action.php?save_fabric=1',
            data: form,
            success: function (msg) {

                var json = $.parseJSON(msg);
                if (json.result == 'success') {
                    message_reload('success', 'Fabric Saved');
                } else {
                    message_error();
                }
            }
        })
    }
}


function save_stock_item() {
    
    var req = required_validation('mas_stockgroup_itemForm');
    
    if (req == 0) {
        
        var form = $("#mas_stockgroup_itemForm").serialize()
        $.ajax({
            type: 'POST',
            url: 'ajax_action2.php?save_stock_item=1',
            data: form,
            success: function (msg) {

                var json = $.parseJSON(msg);
                
                if(json.error==1) {
                    message_noload('info', '' + json.message + '');
                } else if (json.result == 0) {
                    message_reload('success', 'Stock Item Saved');
                } else {
                    message_error();
                }
            }
        })
    }
}


function save_Pack() {
    if ($("#pack_name").val() == "") {
        message_noload('warning', 'Pack Name Required!', 1000);
        return false;
    } else {
        var form = $("#packForm").serialize()
        $.ajax({
            type: 'POST',
            url: 'ajax_action.php?save_pack=1',
            data: form,
            success: function (msg) {

                var json = $.parseJSON(msg);
                if (json.result == 'success') {
                    message_reload('success', 'Pack Saved');
                } else {
                    message_error();
                }
            }
        })
    }
}


function save_Yarn() {
    if ($("#yarn_name").val() == "") {
        message_noload('warning', 'Yarn Name Required!', 1000);
        return false;
    } else {
        var form = $("#YarnForm").serialize()
        $.ajax({
            type: 'POST',
            url: 'ajax_action.php?save_yarn=1',
            data: form,
            success: function (msg) {

                var json = $.parseJSON(msg);
                if (json.result == 'success') {
                    message_reload('success', 'Yarn Saved');
                } else if (json.result == 'duplicate') {
                    message_noload('error', 'Yarn Already Found');
                } else {
                    message_error();
                }
            }
        })
    }
}


function save_UOM() {
    if ($("#uom_name").val() == "") {
        message_noload('warning', 'UOM Name Required!', 1000);
        return false;
    } else {
        var form = $("#uomForm").serialize()
        $.ajax({
            type: 'POST',
            url: 'ajax_action.php?save_uom=1',
            data: form,
            success: function (msg) {

                var json = $.parseJSON(msg);
                if (json.result == 'success') {
                    message_reload('success', 'UOM Saved');
                } else if (json.result == 'duplicate') {
                    message_noload('error', 'UOM Already Found');
                } else {
                    message_error();
                }
            }
        })
    }
}


function save_employee(typ) {
    
    if ($("#employee_name").val() == "") {
        $("#employee_name").focus();
        message_noload('warning', 'Employee Name Required!', 1000);
        return false;
    } else if ($("#employee_photo").val() == "" && $("#employee_photo_old").val() == "") {
        $("#employee_photo").focus();
        message_noload('warning', 'Employee Photo Required!', 1000);
        return false;
    } else if ($("#employee_code").val() == "") {
        $("#employee_code").focus();
        message_noload('warning', 'Employee Code Required!', 1000);
        return false;
    } else if ($("#mobile").val() == "") {
        $("#mobile").focus();
        message_noload('warning', 'Mobile Number Required!', 1000);
        return false;
    } else if ($("#department").val() == "") {
        $("#department").focus();
        message_noload('warning', 'Department Required!', 1000);
        return false;
    } else if ($("#designation").val() == "") {
        $("#designation").focus();
        message_noload('warning', 'Designation Required!', 1000);
        return false;
    } else if ($("#company").val() == "") {
        $("#company").focus();
        message_noload('warning', 'Working Place Required!', 1000);
        return false;
    } else {
        // var form = $("#employeeForm").serialize()
        // $.ajax({
        //     type: 'POST',
        //     url: 'ajax_action.php?save_employee=1',
        //     data: form,
        //     success: function (msg) {

        //         var json = $.parseJSON(msg);
        //         if (json.result == 'success') {
        //             message_reload('success', 'Employee Saved');
        //         } else {
        //             message_error();
        //         }
        //     }
        // })
        
        if(typ == 'add') {
            $(".spinCls").removeClass('d-none');
            $(".scbtn").text('Saving..');
            $(".scbtn").prop('disabled', true);
            $("#employeeForm").submit();
        } else if(typ == 'edit') {
            $(".spinClsEdit").removeClass('d-none');
            $(".scbtnEdit").text('Updating..');
            $(".scbtnEdit").prop('disabled', true);
            $("#editEmpForm").submit();
        } else if(typ == 'move') {
            $(".movebtn").removeClass('d-none');
            $(".scbtnMove").addClass('d-none');
            $("#editEmpForm").submit();
        }
    }
}


function save_color() {
    if ($("#color_name").val() == "") {
        message_noload('warning', 'Color Name Required!', 1000);
        return false;
    } else {
        var form = $("#colorForm").serialize()
        $.ajax({
            type: 'POST',
            url: 'ajax_action.php?save_color=1',
            data: form,
            success: function (msg) {

                var json = $.parseJSON(msg);
                if (json.result == 'success') {
                    message_reload('success', 'Color Saved');
                } else if (json.result == 'exists') {
                    message_noload('warning', 'Color Already Exists!', 1500);
                }else {
                    message_error();
                }
            }
        })
    }
}


function save_unit() {
    // if ($("#full_name").val() == "") {
    //     message_noload('warning', 'Unit full Name Required!', 1000);
    //     $("#full_name").focus();
    //     return false;
    // } else
     if ($("#part_count").val() <= 0) {
        message_noload('warning', 'Part Count Must < 0', 2000);
        return false;
    } else {
        
        $(".btnUnit").prop('disabled', true).html('<i class="fa fa-spinner"></i> Saving..');
        
        var form = $("#unitForm").serialize()
        $.ajax({
            type: 'POST',
            url: 'ajax_action.php?save_unit=1',
            data: form,
            success: function (msg) {

                var json = $.parseJSON(msg);
                if (json.result == 'success') {
                    message_reload('success', 'Unit Saved');
                } else {
                    message_error();
                }
            }
        })
    }
}


function save_merchand() {
    if ($("#merchand_name").val() == "") {
        message_noload('warning', 'Merchandiser Name Required!', 2000);
        return false;
    } else if ($("#merchand_code").val() == "") {
        message_noload('warning', 'Enter Code!', 2000);
        return false;
    } else if ($("#merch_brand").val() == "") {
        message_noload('warning', 'Buyer Name Required!', 2000);
        return false;
    } else {
        var form = $("#merchand_Form").serialize()
        $.ajax({
            type: 'POST',
            url: 'ajax_action.php?save_merchand=1',
            data: form,
            success: function (msg) {

                var json = $.parseJSON(msg);
                if(json.error==1) {
                    message_noload('error', '' + json.message + '');
                } else if (json.result == 'success') {
                    message_reload('success', 'Merchandiser Saved');
                } else {
                    message_error();
                }
            }
        })
    }
}


function save_production_unit() {
    if ($("#full_name").val() == "") {
        message_noload('warning', 'Production Unit Name Required!', 1000);
        return false;
    } else {
        var form = $("#productionUnitForm").serialize();
        $.ajax({
            type: 'POST',
            url: 'ajax_action.php?save_production_unit=1',
            data: form,
            success: function (msg) {

                var json = $.parseJSON(msg);
                if (json.result == 'success') {
                    message_reload('success', 'Production Unit Saved');
                } else {
                    message_error();
                }
            }
        })
    }
}


function save_part() {
    if ($("#part_name").val() == "") {
        message_noload('warning', 'Part Name Required!', 1000);
        return false;
    } else {
        
        $(".btnPart").prop('disabled', true).html('<i class="fa fa-spinner"></i> Saving..');
        
        var form = $("#part_addForm").serialize()
        $.ajax({
            type: 'POST',
            url: 'ajax_action.php?save_part=1',
            data: form,
            success: function (msg) {

                var json = $.parseJSON(msg);
                if (json.result == 'success') {
                    $(".btnPart").prop('disabled', false).html('Submit');
                    message_reload('success', 'Part Saved');
                } else if (json.result == 'exists') {
                    $(".btnPart").prop('disabled', false).html('Submit');
                    message_noload('warning', 'Part Already Exists');
                } else {
                    message_error();
                }
            }
        })
    }
}

$('#part-add-modal').on('shown.bs.modal', function () {
    $('#part_name').focus();
});

$('#color-add-modal').on('shown.bs.modal', function () {
    $('#color_name').focus();
});

$('#selection-type-add-modal').on('shown.bs.modal', function () {
    $('#type_name').focus();
});

$('#brand-add-modal').on('shown.bs.modal', function () {
    $('#brand_name').focus();
});

$('#merchand-add-modal').on('shown.bs.modal', function () {
    $('#merchand_name').focus();
});


function save_brand() {
    
    if ($("#brand_name").val() == "") {
        message_noload('warning', 'Brand Name Required!', 1000);
        return false;
    } else {
        
        $(".btnBrand").prop('disabled', true).html('<i class="fa fa-spinner"></i> Saving..');
        
        var form = $("#brand_addForm").serialize()
        $.ajax({
            type: 'POST',
            url: 'ajax_action.php?save_brand=1',
            data: form,
            success: function (msg) {

                var json = $.parseJSON(msg);
                if (json.result == 'success') {
                    message_reload('success', 'Brand Saved');
                } else {
                    message_error();
                }
            }
        })
    }
}



function save_component() {
    if ($("#component_name").val() == "") {
        message_noload('warning', 'Component Name Required!', 1000);
        return false;
    } else {
        var form = $("#component_addForm").serialize()
        $.ajax({
            type: 'POST',
            url: 'ajax_action.php?save_component=1',
            data: form,
            success: function (msg) {

                var json = $.parseJSON(msg);
                if (json.result == 'success') {
                    message_reload('success', 'Component Saved');
                } else {
                    message_error();
                }
            }
        })
    }
}


function save_approval() {

    if ($("#approval_name").val() == "") {
        message_noload('warning', 'Approval Name Required!', 1000);
        return false;
    } else if ($("#app_dpt").val() == "") {
        message_noload('warning', 'Approval Deartment Required!', 1000);
        return false;
    } else {
        var form = $("#approval_addForm").serialize()
        $.ajax({
            type: 'POST',
            url: 'ajax_action.php?save_approval_=1',
            data: form,
            success: function (msg) {

                var json = $.parseJSON(msg);
                if (json.result == 'success') {
                    message_reload('success', 'Approval Saved');
                } else {
                    message_error();
                }
            }
        })
    }
}


function save_selection_type() {
    if ($(".mas_type").val() == "") {
        message_noload('warning', 'Type Name Required!', 1000);
        return false;
    } else {
        
        $(".btnType").prop('disabled', true).html('<i class="fa fa-spinner"></i> Saving..');
        
        var form = $("#selection_typeForm").serialize()
        $.ajax({
            type: 'POST',
            url: 'ajax_action.php?save_selection_type=1',
            data: form,
            success: function (msg) {

                var json = $.parseJSON(msg);
                if (json.result == 'success') {
                    message_reload('success', 'Type Saved');
                } else {
                    message_error();
                }
            }
        })
    }
}


$("#type_name").keypress(function (e) {
    if (e.which == 13) {
        save_selection_type();
    }
});


function message_noload(type, text, timer) {
    swal({
        type: type,
        text: text,
        timer: timer
    }).then(
        function () {
            swal.close();
        })
}

function message_title(type, title, text) {
    
    swal(
        {
            type: type,
            title: title,
            text: text,
        }
    )
}


function message_error() {
    swal(
        'Something went wrong',
        '',
        'error'
    )
}


function message_redirect(type, title, timer, location) {
    swal({
        type: type,
        title: title,
        showConfirmButton: true,
        timer: timer
    }).then(
        function () {
            window.location.href="" + location + "";
        })
}


function message_reload(type, title) {
    swal({
        type: type,
        title: title,
        showConfirmButton: true,
        timer: 1500
    }).then(
        function () {
            location.reload();
        })
}


function changeStatus(id, table, status) {
    swal({
        title: 'Are you sure?',
        text: "Do you want to change the status!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, change it!',
        cancelButtonText: 'No, cancel!',
        confirmButtonClass: 'btn btn-success margin-5',
        cancelButtonClass: 'btn btn-danger margin-5',
        buttonsStyling: false
    }).then(function (dd) {
        if (dd['value'] == true) {
            $.ajax({
                type: 'POST',
                url: 'ajax_action.php?changeStatus=1&id=' + id + '&table=' + table + '&status=' + status,
                success: function (msg) {
                    var json = $.parseJSON(msg);
                    if (json.result == 'success') {
                        swal({
                            type: 'success',
                            title: 'Changed',
                            showConfirmButton: true,
                            timer: 1500
                        }).then(
                            function () {
                                location.reload();
                            })
                    } else {
                        swal(
                            'Something went wrong',
                            '',
                            'error'
                        )
                    }
                }
            })
        } else {
            swal(
                'Cancelled',
                '',
                'error'
            )
        }
    })
};


function delete_data(id, table) {
    // swal({
    //     title: 'Are you sure?',
    //     text: "You won't be able to revert this!",
    //     type: 'warning',
    //     showCancelButton: true,
    //     confirmButtonText: 'Yes, delete it!',
    //     cancelButtonText: 'No, cancel!',
    //     confirmButtonClass: 'btn btn-success margin-5',
    //     cancelButtonClass: 'btn btn-danger margin-5',
    //     buttonsStyling: false
    // }).then(function (dd) {
    //     if (dd['value'] == true) {
    //         $.ajax({
    //             type: 'POST',
    //             url: 'ajax_action.php?delete_data=' + id + '&table=' + table,
    //             success: function (msg) {
    //                 if (msg == 0) {
    //                     swal({
    //                         type: 'success',
    //                         title: 'Deleted',
    //                         showConfirmButton: true,
    //                         timer: 1500
    //                     }).then(
    //                         function () {
    //                             location.reload();
    //                         })
    //                 } else {
    //                     swal(
    //                         'Something went wrong',
    //                         '',
    //                         'error'
    //                     )
    //                 }
    //             }
    //         })
    //     } else {
    //         swal(
    //             'Cancelled',
    //             '',
    //             'error'
    //         )
    //     }
    // });
    
    var delete_pwd = $("#delete_pwd").val();
    
    swal({
        title: 'Enter Password to Delete!',
        html: '<input id="password-input" class="swal2-input" type="password" placeholder="Password">',
        showCancelButton: true,
        confirmButtonText: 'Submit',
        showLoaderOnConfirm: true,
        confirmButtonClass: 'btn btn-success',
        cancelButtonClass: 'btn btn-danger',
        preConfirm: function (email) {
            var email = document.getElementById('password-input').value;
            return new Promise(function (resolve, reject) {
                setTimeout(function () {
                    if (email == delete_pwd) {
                        
                        $.ajax({
                            type: 'POST',
                            url: 'ajax_action.php?delete_data=' + id + '&table=' + table,
                            success: function (msg) {
                                if (msg == 0) {
                                    swal({
                                        type: 'success',
                                        title: 'Deleted',
                                        showConfirmButton: true,
                                        timer: 1500
                                    }).then(
                                        function () { location.reload(); });
                                } else {
                                    swal('Something went wrong','','error');
                                }
                            }
                        });
                    } else {
                        message_noload('error', 'Incorrect Password!', 2000);
                    }
                }, 1000);
            })
        },
        allowOutsideClick: false
    });
};

$(".showImagePopup").click(function() {
    var url = $(this).find('img').attr('src');
    $("#overlay").fadeIn(100);
    setTimeout(function() {
        var img = '<img src="' + url + '">';
        $("#img_spacerrrr").html(img);
        $("#image-modal").modal('show');
        $("#overlay").fadeOut(500);
    }, 300);
});

$(".showImagePopup_wo_image").click(function() {
    var url = $(this).data('src');
    $("#overlay").fadeIn(100);
    setTimeout(function() {
        var img = '<img src="' + url + '">';
        $("#img_spacerrrr").html(img);
        $("#image-modal").modal('show');
        $("#overlay").fadeOut(500);
    }, 300);
});


function openTaskSheet(id) {
    $("#modHeader").load(base_url + '/searchNotification.php?search_type=getTaskSheet&id=' + id);
    $("#notificationSheet").modal('show');
}


function open_orderTask(id) {
    $("#modHeader").load(base_url + '/searchNotification.php?search_type=order_task_sheet&id=' + id);
    $("#notificationSheet").modal('show');
}


function openTaskSheet_OnlyView(id) {

    $("#modHeader").load(base_url + '/searchNotification.php?search_type=openTaskSheet_OnlyView&id=' + id);

    $("#notificationSheet").modal('show');
    
}


function startOrderTask(taskId) {
    
    $(".timer_order").text('Updating..');
    $(".timer_order").addClass("blockAll");

    var data = { id: taskId, };
    
    $.ajax({
        type: 'POST',
        url: 'searchNotification.php?search_type=startOrderTask',
        data: data,

        success: function (msg) {
            var json = $.parseJSON(msg);
            
            setTimeout(function() {
                var btn = '<a class="btn btn-outline-danger timer_order" onclick="stopOrderTask('+ json.inid +')"><i class="icon-copy fa fa-clock-o" aria-hidden="true"></i> Stop Timer</a>';
                $("#timerButton_ord").html(btn);
                
                $("#modTaskStatus_ord").html('<span style="color:#17a2b8;">In Progress</span>');
                $(".compl").removeClass('d-none');
            }, 2000);
            
        }
    });
}


function stopOrderTask(taskId) {
    
    $(".timer_order").text('Updating..');
    $(".timer_order").addClass("blockAll");
    
    var data = { id: taskId, };
    $.ajax({
        type: 'POST',
        url: 'searchNotification.php?search_type=stopOrderTask',
        data: data,

        success: function (msg) {
            
            var json = $.parseJSON(msg);
            
            setTimeout(function() {
                var btn = '<a class="btn btn-outline-success timer_order" onclick="startOrderTask('+ json.inid +')"><i class="icon-copy fa fa-clock-o" aria-hidden="true"></i> Start Timer</a>';
                $("#timerButton_ord").html(btn);
            }, 2000);
            
            
        }
    });
}


function startTeamTask(taskId) {
    
    $(".timerA").text('Updating..');
    $(".timerA").addClass("blockAll");
    
    $.ajax({
        type: 'POST',
        url: 'searchNotification.php?search_type=startTeamTask&id=' + taskId,

        success: function (msg) {
            
            var json = $.parseJSON(msg);
            
            setTimeout(function() {
                var btn = '<a class="btn btn-outline-danger timerA" onclick="stopTeamTask('+ json.inid +')"><i class="icon-copy fa fa-clock-o" aria-hidden="true"></i> Stop Timer</a>';
                $("#timerButton").html(btn);
                
                $("#modTaskStatus").html('<span style="color:#17a2b8;">In Progress</span>');
                $(".compl").removeClass('d-none');
            }, 2000);
            
        }
    });
}


function stopTeamTask(taskId) {
    
    $(".timerA").text('Updating..');
    $(".timerA").addClass("blockAll");
    
    $.ajax({
        type: 'POST',
        url: 'searchNotification.php?search_type=stopTeamTask&id=' + taskId,

        success: function (msg) {
            
            var json = $.parseJSON(msg);
            
            setTimeout(function() {
                var btn = '<a class="btn btn-outline-success timerA" onclick="startTeamTask('+ json.inid +')"><i class="icon-copy fa fa-clock-o" aria-hidden="true"></i> Start Timer</a>';
                $("#timerButton").html(btn);
            }, 2000);
            
            
        }
    });
}


function save_ordertask_comment(id) {

    var data = {
        id: id,
        comment: $("#order_task_comment").val(),
    }
    
    $.ajax({
        type: 'POST',
        url: 'searchNotification.php?search_type=save_ordertask_comment',
        data: data,

        success: function (msg) {
            var json = $.parseJSON(msg);
            gatAllorderTaskComments(json.task_id);
        }
    });
}

function gatAllorderTaskComments(id) {
    
    $.ajax({
        type: 'POST',
        url: 'searchNotification.php?search_type=gatAllorderTaskComments&id=' + id,

        success: function (msg) {
            
            $(".cmtBtn_ord").addClass('d-none');
            $("#order_task_comment").val('');
            $("#addedComments_ord").html(msg);
            
        }
    });
}

function saveTeamTaskComment(id) {

    var data = {
        id: id,
        comment: $("#team_task_comment").val(),
    }
    
    $.ajax({
        type: 'POST',
        url: 'searchNotification.php?search_type=saveTeamTaskComment',
        data: data,

        success: function (msg) {
            var json = $.parseJSON(msg);
            gatAllteamTaskComments(json.task_id);
        }
    });
}


function gatAllteamTaskComments(id) {
    
    $.ajax({
        type: 'POST',
        url: 'searchNotification.php?search_type=gatAllteamTaskComments&id=' + id,

        success: function (msg) {
            
            $(".cmtBtn").addClass('d-none');
            $("#team_task_comment").val('');
            $("#addedComments").html(msg);
            
        }
    });
}


function markAsComplete(id, val) {
    
    if($("#proof_image_" + val).val() == "") {
        $("#proof_image_" + val).focus();
        message_noload('warning', 'Attach Proof to Complete the Task!', 1500);
        return false;
    } else {
    
        swal({
            title: 'Mark As Complete the task?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes!',
            cancelButtonText: 'No!',
            confirmButtonClass: 'btn btn-success margin-5',
            cancelButtonClass: 'btn btn-danger margin-5',
            buttonsStyling: false
        }).then(function (dd) {
            if (dd['value'] == true) {
                
                var form = new FormData();
                
                var proof_image = $("#proof_image_" + val)[0].files[0];
                form.append('proof_image', proof_image);
                
                $.ajax({
                    type: 'POST',
                    url: 'searchNotification.php?search_type=markAsComplete&id=' + id +'&type=' + val,
                    data : form,
                    contentType: false,
                    processData: false,
            
                    success: function (msg) {
                        
                        var json = $.parseJSON(msg);
                        
                        if(json.res == 0) {
                            message_reload('success', 'Task Completed.', 1500);
                        } else {
                            message_reload('error', 'Something Went.', 1500);
                        }
                    }
                });
            } else {
                swal( 'Cancelled', '', 'error' )
            }
        })
    }
}

function remove_tr(element) {

    var tr = $(element).closest('tr').remove();
}

function required_validation(form) {
            
    var a = 0;
    $('#'+ form +' :input').each(function() {
        var labelForInput = $('label[for="' + $(this).attr('id') + '"]');
        
        if ($(this).prop('required') && $(this).val() == "") {
            
            a++;
            $(this).addClass('form-control-danger');
            labelForInput.addClass('req');
        } else {
            $(this).removeClass('form-control-danger');
            labelForInput.removeClass('req');
        }
    });
    
    return a;
}


function divToPrint(id) {

    var css= '<link rel="stylesheet" type="text/css" href="vendors/styles/core.css"><link rel="stylesheet" type="text/css" href="vendors/styles/icon-font.min.css"><link rel="stylesheet" type="text/css" href="vendors/styles/style.css">';
    var printContent = document.getElementById(id);

    var WinPrint = window.open('', '', 'width=1500,height=1000');
    WinPrint.document.write(printContent.innerHTML);
    WinPrint.document.head.innerHTML = css;
    WinPrint.document.close();
    WinPrint.focus();
    WinPrint.print();
    WinPrint.close();
}


$(function () {
    $('.date_time_picker').datetimepicker();
});


$(document).ready(function() {
    
    $('#employeeNew-add-modal').on('click', function(event) {
        
      if (!$(event.target).closest('#username').length) {

        var uname = $("#username").val();
        var data = {
            uname : uname,
        }

        $.post('ajax_search2.php?validate_username', data, function(msg){

            var j = $.parseJSON(msg);

            if(j.found >0) {
                $("#username").val('').select();
                message_noload('info', ''+ uname + ' - User Name Already Found!', 1500);
                $(".uname_found_msg").html(('Already Found! <b class="text-danger">`'+ uname + '`</b>'));
                return false;   
            } else {
                $(".uname_found_msg").html('');
            }
        });
        
        // alert('Clicked outside the input!');
      }
    });
});

function show_total_scanned(element) {
    
    var data = {
        id: $(element).data('id'),
        from: $(element).data('from')
    }

    $.post('ajax_search2.php?scanned_pcs_list', data, function(msg) {
        var j = $.parseJSON(msg);
        $("#scanned_pcs-tbody").html(j.tbody)
        $("#scanned_pcs-modal").modal('show');
    });
}


function swal_processing(title) {

    swal({
        title: title,
        width: 200,
        padding: 85,
        closeOnClickOutside: false, 
        background: '#fff url(src/logo/processing-gif.gif)'
    });

    $(".swal2-confirm").addClass('d-none');
}




// swal({
//     title: 'Are you sure?',
//     text: "Confirm Update the fabric?",
//     type: 'warning',
//     showCancelButton: true,
//     confirmButtonText: 'Yes, Update!',
//     cancelButtonText: 'No, cancel!',
//     confirmButtonClass: 'btn btn-success margin-5',
//     cancelButtonClass: 'btn btn-danger margin-5',
//     buttonsStyling: false
// }).then(function (dd) {
//     if (dd['value'] == true) {
//         $("#overlay").fadeIn(300);
//         var form = $("#programForm" + id).submit();
//     } else {
//         swal(
//             'Update Cancelled',
//             '',
//             'error'
//         )
//     }
// })