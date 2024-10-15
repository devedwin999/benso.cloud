<!-- // common modal -->

<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="check_inout_modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <p class="modal-title" id="myLargeModalLabel">Check In / Check Out</p>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form method="post" id="check_inout_form" enctype="multipart/form-data">

                <div class="modal-body">
                    <div style="text-align:center" id="img_space">
                        <input type="hidden" id="inout_latitude" name="inout_latitude">
                        <input type="hidden" id="inout_longitude" name="inout_longitude">
                        <p class="notif_location"><span style="color:red">Location Permission Disabled!</span></p>


                        <div class="row">
                            <div class="col-md-12">
                                <?php
                                $qyu = mysqli_query($mysqli, "SELECT * FROM attendance WHERE out_time IS NULL AND employee_id = '" . $logUser . "' AND date = '" . date('Y-m-d') . "' ORDER BY id DESC");
                                $roww = mysqli_fetch_array($qyu);
                                $nmm = mysqli_num_rows($qyu);

                                if ($nmm == 0) {
                                    print '<a class="btn btn-info checkIn">CHECK IN</a>';
                                } else {
                                    print '<a class="btn btn-warning checkOut">CHECK OUT</a>';
                                    print '<input type="hidden" id="out_id" name="out_id" value="' . $roww['id'] . '">';
                                }
                                ?>
                            </div>
                        </div>
                        <br><br>
                        <?php
                        $at = mysqli_query($mysqli, "SELECT * FROM attendance WHERE employee_id = '" . $logUser . "' AND date = '" . date('Y-m-d') . "' ORDER BY id ASC ");
                        while ($attn = mysqli_fetch_array($at)) {
                            print '<div class="alert alert-success" role="alert">Check in at :' . date('Y-m-d h:i:s A', $attn['in_time']) . '</div>';

                            if ($attn['out_time'] != NULL) {
                                print '<div class="alert alert-danger" role="alert">Check out at :' . date('Y-m-d h:i:s A', $attn['out_time']) . '</div>';
                            }
                        } ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="employee-edit-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Edit Employee</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>

            <form method="post" action="ajax_action.php?save_employee" id="editEmpForm" autocomplete="off"
                enctype="multipart/form-data">
                <input type="hidden" name="insType" id="insType" value="edit">
                <input type="hidden" name="updId" id="updId">
                <div class="modal-body">
                    <div class="row" id="editmodaldetail">
                        Loading..
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <div class="spinner-border m-5 d-none spinClsEdit" role="status">
                        <span class="visually-hidden"></span>
                    </div>
                    <button type="button" onclick="save_employee('edit')"
                        class="btn btn-success scbtnEdit">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="notificationSheet">
    <div class="modal-dialog modal-lg modal-dialog-top" style="max-width: 1000px !important">
        <div class="modal-content" id="modHeader">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <div style="text-align:center" id="">
                    <div class="spinner-grow" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    Loading..
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php if (in_array('report_filter-modal', $modals)) { ?>
    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
        id="report_filter-modal">
        <div class="modal-dialog" style="height:100%;">
            <div class="modal-content" style="height:98%;">
                <div class="modal-header">
                    <h5 class="modal-title" id="myLargeModalLabel">Filters</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>

                <div class="modal-body" style="padding: 2px !important;">
                    <form method="post" id="task_addForm" autocomplete="off" enctype="multipart/form-data">

                        <div class="tab" style="width:100%;">
                            <div class="clearfix d-flex" class="pd-20" style="height:75vh;">
                                <div class="" style="border: 1px solid #e3e0e0;width:35%;">
                                    <ul class="nav flex-column vtabs nav-tabs" role="tablist" style="background: #f1f1f1;">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-toggle="tab" href="#order_no" role="tab"
                                                aria-selected="true">Order No <span
                                                    class="filt_badge badge order_badge float-right d-none"></span></a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#buyer" role="tab"
                                                aria-selected="false">Buyer <span
                                                    class="filt_badge badge buyer_badge float-right d-none"></span></a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#del_at" role="tab"
                                                aria-selected="false">Delivery at <span
                                                    class="filt_badge badge del_dt_badge float-right d-none"></span></a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#entry_at" role="tab"
                                                aria-selected="false">Entry at <span
                                                    class="filt_badge badge ent_dt_badge float-right d-none"></span></a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#ord_ststus" role="tab"
                                                aria-selected="false">Order Status <span
                                                    class="filt_badge badge float-right">1</span></a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#employee" role="tab"
                                                aria-selected="false">Line | Employee <span
                                                    class="filt_badge badge emplo_badge float-right d-none"></span></a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#unitt" role="tab"
                                                aria-selected="false">Unit <span
                                                    class="filt_badge badge unitt_badge float-right d-none"></span></a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#supplier" role="tab"
                                                aria-selected="false">Supplier <span
                                                    class="filt_badge badge supp_badge float-right d-none"></span></a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#input_date" role="tab"
                                                aria-selected="false">Input Date <span
                                                    class="filt_badge badge inp_date_badge float-right d-none"></span></a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#output_date" role="tab"
                                                aria-selected="false">Output Date <span
                                                    class="filt_badge badge out_date_badge float-right d-none"></span></a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#output_status" role="tab"
                                                aria-selected="false">Output Status <span
                                                    class="filt_badge badge out_status_badge float-right d-none"></span></a>
                                        </li>
                                    </ul>
                                </div>

                                <div class=""
                                    style="border: 1px solid #e3e0e0;width:65%;overflow-y:auto;overflow-x:hidden;">
                                    <div class="tab-content">

                                        <div class="tab-pane fade show active" id="order_no" role="tabpanel">
                                            <div class="pd-20 row">
                                                <div class="col-md-12">
                                                    <input type="text" placeholder="Search BO | Style"
                                                        class="form-control filter_search" data-class="bo_dvv"
                                                        style="height: 30px !important;">
                                                    <br>
                                                </div>
                                                <?php
                                                $qry = "SELECT a.id, a.style_no, b.order_code ";
                                                $qry .= " FROM cutting_barcode aa ";
                                                $qry .= " LEFT JOIN sales_order_detalis a ON a.id= aa.style ";
                                                $qry .= " LEFT JOIN sales_order b ON a.sales_order_id= b.id ";
                                                $qry .= " WHERE b.is_dispatch IS NULL GROUP BY aa.style ORDER BY b.id DESC ";
                                                $query = mysqli_query($mysqli, $qry);
                                                $x = 1;
                                                if(mysqli_num_rows($query)>0) {
                                                    while ($sql2 = mysqli_fetch_array($query)) {
                                                        ?>
                                                        <div class="col-md-12 bo_dvv">
                                                            <input type="checkbox" class="f_cbx order_bdg" data-cls="order"
                                                                value="<?= $sql2['id']; ?>" id="order_id<?= $sql2['id']; ?>"> <label
                                                                for="order_id<?= $sql2['id']; ?>"><?= $sql2['order_code'] . ' | ' . $sql2['style_no']; ?></label></br>
                                                            <hr>
                                                        </div>
                                                        <?php $x++;
                                                } } else {
                                                    print '<div class="col-md-12 bo_dvv"><p>Cutting Not Started!</p></div>';
                                                } ?>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" role="tabpanel" id="buyer">
                                            <div class="pd-20 row">
                                                <div class="col-md-12">
                                                    <input type="text" placeholder="Search Buyer"
                                                        class="form-control filter_search" data-class="buyer_dvv"
                                                        style="height: 30px !important;">
                                                    <br>
                                                </div>
                                                <?php
                                                $qry = "SELECT a.id, a.brand_name ";
                                                $qry .= " FROM brand a ";
                                                $qry .= " WHERE a.is_active = 'active' ORDER BY a.brand_name ASC ";
                                                $query = mysqli_query($mysqli, $qry);

                                                while ($sql1 = mysqli_fetch_array($query)) {
                                                    ?>
                                                    <div class="col-md-12 buyer_dvv">
                                                        <input type="checkbox" class="f_cbx buyer_bdg" data-cls="buyer"
                                                            value="<?= $sql1['id']; ?>" id="brand_id<?= $sql1['id']; ?>"> <label
                                                            for="brand_id<?= $sql1['id']; ?>"><?= $sql1['brand_name']; ?></label></br>
                                                        <hr>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" role="tabpanel" id="del_at">
                                            <div class="pd-20 row">

                                                <div class="col-md-12 text-right">
                                                    <input type="checkbox" class="date_cbox f_cbx del_dt_bdg"
                                                        data-cls="del_dt" data-class="del_dte" name="" id="">
                                                </div>

                                                <div class="col-md-12">
                                                    <label>From Date :</label>
                                                    <input type="date" class="form-control del_dte del_dt_start" name=""
                                                        id="" placeholder="From Date" style="width:100%" readonly>
                                                </div>

                                                <div class="col-md-12">
                                                    <label>To Date :</label>
                                                    <input type="date" class="form-control del_dte del_dt_end" name="" id=""
                                                        placeholder="To Date" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" role="tabpanel" id="entry_at">
                                            <div class="pd-20 row">
                                                <div class="col-md-12 text-right">
                                                    <input type="checkbox" class="date_cbox f_cbx ent_dt_bdg"
                                                        data-cls="ent_dt" data-class="ent_dte" name="" id="">
                                                </div>

                                                <div class="col-md-12">
                                                    <label>From Date :</label>
                                                    <input type="date" class="form-control ent_dte ent_dt_start" name=""
                                                        id="" placeholder="From Date" readonly>
                                                </div>

                                                <div class="col-md-12">
                                                    <label>To Date :</label>
                                                    <input type="date" class="form-control ent_dte ent_dt_end" name="" id=""
                                                        placeholder="To Date" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" role="tabpanel" id="ord_ststus">
                                            <div class="pd-20 row">
                                                <div class="col-md-12 form-group">
                                                    <input class="cutting_model" type="radio" id="running"
                                                        name="type_completed_report" value="Running" checked>
                                                    <label for="running">Running</label><br>
                                                    <input class="cutting_model" type="radio" id="completed"
                                                        name="type_completed_report" value="Completed">
                                                    <label for="completed">Completed</label><br>
                                                    <input class="cutting_model" type="radio" id="all"
                                                        name="type_completed_report" value="all">
                                                    <label for="all">All</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" role="tabpanel" id="employee">
                                            <div class="pd-20 row">
                                                <div class="col-md-12">
                                                    <input type="text" placeholder="Search Employee"
                                                        class="form-control filter_search" data-class="emp_dvv"
                                                        style="height: 30px !important;"><br>
                                                </div>
                                                <?php
                                                $qry = "SELECT a.id, a.employee_name ";
                                                $qry .= " FROM employee_detail a ";
                                                $qry .= " WHERE a.is_active = 'active' ORDER BY a.employee_name ASC ";
                                                $query = mysqli_query($mysqli, $qry);

                                                while ($sql1 = mysqli_fetch_array($query)) {
                                                    ?>
                                                    <div class="col-md-12 emp_dvv">
                                                        <input type="checkbox" class="f_cbx emplo_bdg" data-cls="emplo"
                                                            value="<?= $sql1['id']; ?>" id="employee<?= $sql1['id']; ?>"> <label
                                                            for="employee<?= $sql1['id']; ?>"><?= $sql1['employee_name']; ?></label></br>
                                                        <hr>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" role="tabpanel" id="unitt">
                                            <div class="pd-20 row">
                                                <div class="col-md-12">
                                                    <input type="text" placeholder="Search Unit"
                                                        class="form-control filter_search" data-class="unit_dvv"
                                                        style="height: 30px !important;"><br>
                                                </div>
                                                <?php
                                                $qry = "SELECT a.id, a.company_name ";
                                                $qry .= " FROM company a ";
                                                $qry .= " WHERE a.is_active = 'active' ORDER BY a.company_name ASC ";
                                                $query = mysqli_query($mysqli, $qry);

                                                while ($sql1 = mysqli_fetch_array($query)) {
                                                    ?>
                                                    <div class="col-md-12 unit_dvv">
                                                        <input type="checkbox" class="f_cbx unitt_bdg" data-cls="unitt"
                                                            value="<?= $sql1['id']; ?>" id="company_nm<?= $sql1['id']; ?>">
                                                        <label
                                                            for="company_nm<?= $sql1['id']; ?>"><?= $sql1['company_name']; ?></label></br>
                                                        <hr>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" role="tabpanel" id="supplier">
                                            <div class="pd-20 row">
                                                <div class="col-md-12">
                                                    <input type="text" placeholder="Search Supplier"
                                                        class="form-control filter_search" data-class="sup_dvv"
                                                        style="height: 30px !important;"><br>
                                                </div>
                                                <?php
                                                $qry = "SELECT a.id, a.supplier_name ";
                                                $qry .= " FROM supplier a ";
                                                $qry .= " WHERE a.is_active = 'active' ORDER BY a.supplier_name ASC ";
                                                $query = mysqli_query($mysqli, $qry);

                                                while ($sql1 = mysqli_fetch_array($query)) {
                                                    ?>
                                                    <div class="col-md-12 sup_dvv">
                                                        <input type="checkbox" class="f_cbx supp_bdg" data-cls="supp"
                                                            value="<?= $sql1['id']; ?>" id="supplier_nm<?= $sql1['id']; ?>">
                                                        <label
                                                            for="supplier_nm<?= $sql1['id']; ?>"><?= $sql1['supplier_name']; ?></label></br>
                                                        <hr>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" role="tabpanel" id="input_date">
                                            <div class="pd-20 row">

                                                <div class="col-md-12 text-right">
                                                    <input type="checkbox" class="date_cbox f_cbx inp_date_bdg"
                                                        data-cls="inp_date" data-class="inp_datee" name="" id="">
                                                </div>

                                                <div class="col-md-12">
                                                    <label>From Date :</label>
                                                    <input type="date" class="form-control inp_datee inp_date_start" name=""
                                                        id="" placeholder="From Date" style="width:100%" readonly>
                                                </div>

                                                <div class="col-md-12">
                                                    <label>To Date :</label>
                                                    <input type="date" class="form-control inp_datee inp_date_end" name=""
                                                        id="" placeholder="To Date" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" role="tabpanel" id="output_date">
                                            <div class="pd-20 row">

                                                <div class="col-md-12 text-right">
                                                    <input type="checkbox" class="date_cbox f_cbx out_date_bdg"
                                                        data-cls="out_date" data-class="out_datee" name="" id="">
                                                </div>

                                                <div class="col-md-12">
                                                    <label>From Date :</label>
                                                    <input type="date" class="form-control out_datee out_date_start" name=""
                                                        id="" placeholder="From Date" style="width:100%" readonly>
                                                </div>

                                                <div class="col-md-12">
                                                    <label>To Date :</label>
                                                    <input type="date" class="form-control out_datee out_date_end" name=""
                                                        id="" placeholder="To Date" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" role="tabpanel" id="output_status">
                                            <div class="pd-20 row">

                                                <div class="col-md-12 form-group">
                                                    <input class="cutting_model output_status_cbox chbox_fil"
                                                        type="checkbox" id="pending_s" name="output_status" value="pending"
                                                        data-bdg="out_status_badge">
                                                    <label for="pending_s">Pending</label><br>
                                                    <input class="cutting_model output_status_cbox chbox_fil"
                                                        type="checkbox" id="completed_s" name="output_status"
                                                        value="completed" data-bdg="out_status_badge">
                                                    <label for="completed_s">Completed</label><br>
                                                    <input class="cutting_model output_status_cbox chbox_fil"
                                                        type="checkbox" id="all_s" name="output_status" value="all"
                                                        data-bdg="out_status_badge">
                                                    <label for="all_s">All</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary filtttr_search">Apply</button>
                </div>
            </div>
        </div>
    </div>

<?php }
if (in_array('attendance-detail-list-modal', $modals)) { ?>
    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
        id="attendance-detail-list-modal">
        <div class="modal-dialog modal-dialog-top">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title" id="myLargeModalLabel">Check IN/ Check Out Detail List</p>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>

                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <th>Sl.No</th>
                            <th>In Time</th>
                            <th>Out Time</th>
                            <th>Working Time</th>
                        </thead>
                        <tbody id="att_list_body">
                            <tr>
                                <td colspan="3" class="text-center">-- Nothing Found --</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

<?php }
if (in_array('image-modal', $modals)) { ?>
    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
        id="image-modal">
        <div class="modal-dialog modal-dialog-top">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">Image View</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form method="post" id="var_modalform" enctype="multipart/form-data">

                    <div class="modal-body">
                        <div class="text-center" id="img_spacerrrr">
                            Loading..
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php }
if (in_array('fabric-add-modal', $modals)) { ?>
    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
        id="fabric-add-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">Add New Fabric</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form method="post" id="fabricForm" autocomplete="off" enctype="multipart/form-data">
                    <input type="hidden" name="table_name" id="table_name" value="fabric">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Fabric Name <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="fabric_name" id="fabric_name"
                                        placeholder="Fabric Name" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label>Fabric Code <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="fabric_code" id="fabric_code"
                                        placeholder="Fabric Code" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" onclick="save_fabric()" class="btn btn-success">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php }
if (in_array('line-add-modal', $modals)) { ?>
    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
        id="line-add-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">Add New line</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form method="post" id="lineForm" autocomplete="off" enctype="multipart/form-data">
                    <input type="hidden" name="table_name" id="table_name" value="mas_yarn">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Line Name <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="line_name" id="line_name"
                                        placeholder="Line Name" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label>Process <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <select class="custom-select2 form-control" name="process[]" multiple id="process"
                                        style="width:100%;">
                                        <?= select_dropdown_multiple('process', array('id', 'process_name'), 'process_name ASC', '', '', '`'); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label>Pay Type <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <select class="custom-select2 form-control" name="pay_type" id="pay_type"
                                        style="width:100%;">
                                        <option value="1">Shift</option>
                                        <option value="2">Pcs Rate</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label>Cost Generators </label>
                                <div class="form-group">
                                    <select class="custom-select2 form-control" name="cost_generator[]" id="cost_generator"
                                        style="width:100%;" multiple>
                                        <?= select_dropdown_multiple('employee_detail', array('id', 'employee_name'), 'employee_name ASC', '', 'WHERE is_active="active" AND is_cg = "Yes"', '1'); ?>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" onclick="save_line()" class="btn btn-success">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php }
if (in_array('mas_stockgroup_item-add-modal', $modals)) { ?>
    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
        id="mas_stockgroup_item-add-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">Add New Stock Group Item</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form method="post" id="mas_stockgroup_itemForm" autocomplete="off" enctype="multipart/form-data">
                    <input type="hidden" name="table_name" id="table_name" value="mas_stockgroup_item">
                    <div class="modal-body">
                        <div class="row">

                            <div class="col-md-12">
                                <label for="fabric_type">Fabric Type <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <select class="form-control custom-select2" name="fabric_type" id="fabric_type"
                                        style="width:100%" required>
                                        <?php
                                        $type = array('FAB_SOLID' => 'Solid', 'FAB_YANDD' => 'Y/D', 'FAB_MELANGE' => 'Melange');
                                        foreach ($type as $key => $val) {
                                            print '<option value="' . $key . '">' . $val . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label for="fabric_name">Fabric Name <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <select class="form-control custom-select2" name="fabric_name" id="fabric_name"
                                        style="width:100%" required>
                                        <?= select_dropdown('fabric', array('id', 'fabric_name'), 'fabric_name ASC', '', '', ''); ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label for="gsm">GSM <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="gsm" id="gsm" placeholder="GSM" required>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label for="dying_color">Dying Color</label>
                                <div class="form-group">
                                    <select class="form-control custom-select2" name="dying_color" id="dying_color"
                                        style="width:100%">
                                        <?= select_dropdown('color', array('id', 'color_name'), 'color_name ASC', '', '', ''); ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label>AOP Name</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="aop_name" id="aop_name"
                                        placeholder="AOP Name">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Yarn Name</th>
                                            <th>Mising %</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr id="tbody_tr">
                                            <td>
                                                <select class="form-control custom-select2" name="yarn_name[]"
                                                    id="yarn_name" style="width:100%">
                                                    <?= select_dropdown('mas_yarn', array('id', 'yarn_name'), 'yarn_name ASC', '', '', ''); ?>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" name="mixing_percentage[]" id="mixing_percentage"
                                                    class="form-control mw-200" placeholder="Mixing %">
                                            </td>
                                            <td>
                                                <a class="border border-secondary rounded text-secondary addMoreyarn"><i
                                                        class="fa fa-plus"></i></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" onclick="save_stock_item()" class="btn btn-success">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php }
if (in_array('yarn-add-modal', $modals)) { ?>
    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
        id="yarn-add-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">Add New Yarn</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form method="post" id="YarnForm" autocomplete="off" enctype="multipart/form-data">
                    <input type="hidden" name="table_name" id="table_name" value="mas_yarn">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Yarn Name <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="yarn_name" id="yarn_name"
                                        placeholder="Yarn Name" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label>Yarn Code <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="yarn_code" id="yarn_code"
                                        placeholder="Yarn Code" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" onclick="save_Yarn()" class="btn btn-success">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php }
if (in_array('uom-add-modal', $modals)) { ?>
    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
        id="uom-add-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">Add New UOM</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form method="post" id="uomForm" autocomplete="off" enctype="multipart/form-data">
                    <input type="hidden" name="table_name" id="table_name" value="mas_uom">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label>UOM Name <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="uom_name" id="uom_name"
                                        placeholder="UOM Name" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" onclick="save_UOM()" class="btn btn-success">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php }
if (in_array('pack-add-modal', $modals)) { ?>
    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
        id="pack-add-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">Add New Pack Type</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form method="post" id="packForm" autocomplete="off" enctype="multipart/form-data">
                    <input type="hidden" name="table_name" id="table_name" value="mas_pack">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Pack Name <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="pack_name" id="pack_name"
                                        placeholder="Pack Name" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" onclick="save_Pack()" class="btn btn-success">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php }
if (in_array('employeeNew-add-modal', $modals)) { ?>
    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="employeeNew-add-modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">New Employee</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form method="post" id="employeeForm" action="ajax_action.php?save_employee" autocomplete="off" enctype="multipart/form-data">
                    <input type="hidden" name="insType" id="insType" value="add">
                    <div class="modal-body">
                        <div class="tab">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active text-blue" data-toggle="tab" href="#basicInfo" role="tab"
                                        aria-selected="true">Basic Info</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-blue" data-toggle="tab" href="#addressInfo" role="tab"
                                        aria-selected="false">Address Info</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-blue" data-toggle="tab" href="#proofInfo" role="tab"
                                        aria-selected="false">Proof Info</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-blue" data-toggle="tab" href="#bankInfo" role="tab"
                                        aria-selected="false">Bank Info</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-blue" data-toggle="tab" href="#salaryInfo" role="tab"
                                        aria-selected="false">Salary Info</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-blue" data-toggle="tab" href="#loginInfo" role="tab"
                                        aria-selected="false">Benso App Login Info</a>
                                </li>

                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="basicInfo" role="tabpanel">
                                    <div class="pd-20">
                                        <div class="row">

                                            <div class="col-md-6">
                                                <label class="col-form-label">Employee Type <span
                                                        class="text-danger">*</span></label>
                                                <div class="form-group">
                                                    <select class="form-control custom-select2" name="type" id="type"
                                                        style="width:100%">
                                                        <option value="user">Staff</option>
                                                        <option value="employee">Worker</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="col-form-label">Employee Name <span
                                                        class="text-danger">*</span></label>
                                                <div class="form-group">
                                                    <input class="form-control" type="text" name="employee_name"
                                                        id="employee_name" placeholder="Employee Name">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="col-form-label">Employee Photo <span
                                                        class="text-danger">*</span></label>
                                                <div class="form-group">
                                                    <input class="form-control" type="file" name="employee_photo" id="employee_photo">
                                                    <input class="form-control" type="hidden" name="employee_photo_old" id="employee_photo_old">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="col-form-label">Employee Code <span
                                                        class="text-danger"></span></label>
                                                <div class="form-group">
                                                    <input class="form-control d-cursor valid_employee_code" type="text"
                                                        name="employee_code" id="employee_code" placeholder="Employee Code">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="col-form-label">DOB <span class="text-danger">*</span></label>
                                                <div class="form-group">
                                                    <input class="form-control" type="date" name="dob" id="dob"
                                                        placeholder="Date Of Birth">
                                                </div>
                                            </div>
                                            <!--date-picker-->
                                            <div class="col-md-6">
                                                <label class="col-form-label">Age <span class="text-danger">*</span></label>
                                                <div class="form-group">
                                                    <input class="form-control" type="text" name="age" id="age"
                                                        placeholder="Age Will be calculated Automatically" readonly
                                                        style="background-color:#fff">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="col-form-label">Gender <span
                                                        class="text-danger">*</span></label>
                                                <div class="form-group">
                                                    <input style="min-width:20px !important" type="radio" name="gender"
                                                        id="Male" value="Male"> <label for="Male">Male</label>
                                                    <input style="min-width:20px !important" type="radio" name="gender"
                                                        id="Female" value="Female"> <label for="Female">Female</label>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="col-form-label">Email</label>
                                                <div class="form-group">
                                                    <input class="form-control" type="email" name="email" id="email"
                                                        placeholder="Email">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="col-form-label">Mobile Number <span
                                                        class="text-danger">*</span></label>
                                                <div class="form-group">
                                                    <input class="form-control" type="text" name="mobile" id="mobile"
                                                        placeholder="Mobile Number">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="col-form-label">Department <span
                                                        class="text-danger">*</span></label>
                                                <div class="form-group">
                                                    <select class="form-control custom-select2" name="department"
                                                        id="department" style="width:100%">
                                                        <?= select_dropdown('department', array('id', 'department_name'), 'id ASC', '', '', ''); ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="col-form-label">Designation <span
                                                        class="text-danger">*</span></label>
                                                <div class="form-group">
                                                    <select class="form-control custom-select2" name="designation"
                                                        id="designation" style="width:100%">
                                                        <?= select_dropdown('mas_designation', array('id', 'desig_name'), 'id ASC', '', '', ''); ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="col-form-label">Working Place <span
                                                        class="text-danger">*</span></label>
                                                <div class="form-group">
                                                    <select class="form-control custom-select2" name="company" id="company"
                                                        style="width:100%">
                                                        <?= select_dropdown('company', array('id', 'company_name'), 'id ASC', '', '', ''); ?>
                                                    </select>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="addressInfo" role="tabpanel">
                                    <div class="pd-20">
                                        <div class="row">

                                            <div class="col-md-12">
                                                <h4 style="text-decoration:underline;">Communication Address</h4>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="col-form-label">Address 1</label>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" name="address1_com"
                                                        id="address1_com" placeholder="Address 1">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="col-form-label">Address 2</label>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" name="address2_com"
                                                        id="address2_com" placeholder="Address 2">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="col-form-label">Area</label>
                                                <div class="form-group">
                                                    <input class="form-control" type="text" name="area_com" id="area_com"
                                                        placeholder="Area">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="col-form-label">Pincode</label>
                                                <div class="form-group">
                                                    <input class="form-control" type="text" name="pincode_com"
                                                        id="pincode_com" placeholder="Pincode">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="">Country</label>
                                                <div class="form-group">
                                                    <select name="country_com" id="country_com"
                                                        class="custom-select2 form-control" onchange="getState('_com')"
                                                        style="width:100%">
                                                        <?= select_dropdown('master_country', array('auto_number', 'country'), 'country ASC', $sql['country'] ? $sql['country'] : 101, '', ''); ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="col-form-label">State</label>
                                                <div class="form-group">
                                                    <select class="custom-select2 form-control" name="state_com"
                                                        id="state_com" onchange="getCity('_com')" style="width:100%">
                                                        <option value="">Select State</option>
                                                        <?php
                                                        if (isset($_GET['id']) && !empty($sql['country'])) {
                                                            $where = "country_id='" . $sql['country'] . "'";
                                                        } else {
                                                            $where = "country_id='101'";
                                                        }

                                                        $qryd = mysqli_query($mysqli, "SELECT * FROM states WHERE $where ORDER BY state_name ASC ");
                                                        while ($stt = mysqli_fetch_array($qryd)) {
                                                            if (isset($_GET['id']) && !empty($sql['state'])) {
                                                                if ($stt['id'] == $sql['state']) {
                                                                    $sell = 'selected';
                                                                } else {
                                                                    $sell = '';
                                                                }
                                                            } else {
                                                                $sell = '';
                                                            }
                                                            print '<option value="' . $stt['id'] . '" ' . $sell . '>' . $stt['state_name'] . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <label class="col-form-label">City</label>
                                                <div class="form-group">
                                                    <select class="custom-select2 form-control" name="city_com"
                                                        id="city_com" style="width:100%">
                                                        <option value="">Select City</option>
                                                        <?php
                                                        if (isset($_GET['id']) && !empty($sql['state'])) {
                                                            $qryd1 = mysqli_query($mysqli, "SELECT * FROM cities WHERE state_id = '" . $sql['state'] . "' ORDER BY cities_name ASC");

                                                            while ($stt1 = mysqli_fetch_array($qryd1)) {
                                                                if ($stt1['id'] == $sql['city']) {
                                                                    $citt = 'selected';
                                                                } else {
                                                                    $citt = '';
                                                                }
                                                                print '<option value="' . $stt1['id'] . '" ' . $citt . '>' . $stt1['cities_name'] . '</option>';
                                                            }
                                                        } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <hr>
                                            </div>

                                            <div class="col-md-12">
                                                <h4 style="text-decoration:underline;">Permanaent Address</h4>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="col-form-label">Address 1</label>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" name="address1_per"
                                                        id="address1_per" placeholder="Address 1">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="col-form-label">Address 2</label>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" name="address2_per"
                                                        id="address2_per" placeholder="Address 2">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="col-form-label">Area</label>
                                                <div class="form-group">
                                                    <input class="form-control" type="text" name="area_per" id="area_per"
                                                        placeholder="Area">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="col-form-label">Pincode</label>
                                                <div class="form-group">
                                                    <input class="form-control" type="text" name="pincode_per"
                                                        id="pincode_per" placeholder="Pincode">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="">Country</label>
                                                <div class="form-group">
                                                    <select name="country_per" id="country_per"
                                                        class="custom-select2 form-control" onchange="getState('_per')"
                                                        style="width:100%">
                                                        <?= select_dropdown('master_country', array('auto_number', 'country'), 'country ASC', $sql['country'] ? $sql['country'] : 101, '', ''); ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="col-form-label">State</label>
                                                <div class="form-group">
                                                    <select class="custom-select2 form-control" name="state_per"
                                                        id="state_per" onchange="getCity('_per')" style="width:100%">
                                                        <option value="">Select State</option>
                                                        <?php
                                                        if (isset($_GET['id']) && !empty($sql['country'])) {
                                                            $where = "country_id='" . $sql['country'] . "'";
                                                        } else {
                                                            $where = "country_id='101'";
                                                        }

                                                        $qryd = mysqli_query($mysqli, "SELECT * FROM states WHERE $where ORDER BY state_name ASC ");
                                                        while ($stt = mysqli_fetch_array($qryd)) {
                                                            if (isset($_GET['id']) && !empty($sql['state'])) {
                                                                if ($stt['id'] == $sql['state']) {
                                                                    $sell = 'selected';
                                                                } else {
                                                                    $sell = '';
                                                                }
                                                            } else {
                                                                $sell = '';
                                                            }
                                                            print '<option value="' . $stt['id'] . '" ' . $sell . '>' . $stt['state_name'] . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <label class="col-form-label">City</label>
                                                <div class="form-group">
                                                    <select class="custom-select2 form-control" name="city_per"
                                                        id="city_per" style="width:100%">
                                                        <option value="">Select City</option>
                                                        <?php
                                                        if (isset($_GET['id']) && !empty($sql['state'])) {
                                                            $qryd1 = mysqli_query($mysqli, "SELECT * FROM cities WHERE state_id = '" . $sql['state'] . "' ORDER BY cities_name ASC");

                                                            while ($stt1 = mysqli_fetch_array($qryd1)) {
                                                                if ($stt1['id'] == $sql['city']) {
                                                                    $citt = 'selected';
                                                                } else {
                                                                    $citt = '';
                                                                }
                                                                print '<option value="' . $stt1['id'] . '" ' . $citt . '>' . $stt1['cities_name'] . '</option>';
                                                            }
                                                        } ?>
                                                    </select>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="proofInfo" role="tabpanel">
                                    <div class="pd-20">
                                        <div class="row">

                                            <div class="col-md-6">
                                                <label class="col-form-label">Aadhar Card</label>
                                                <div class="form-group">
                                                    <input class="form-control" type="file" name="aadhar_card"
                                                        id="aadhar_card">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="col-form-label">Pan Card</label>
                                                <div class="form-group">
                                                    <input class="form-control" type="file" name="pan_card" id="pan_card">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="col-form-label">License</label>
                                                <div class="form-group">
                                                    <input class="form-control" type="file" name="license" id="license">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="col-form-label">Other Documents</label>
                                                <div class="form-group">
                                                    <input class="form-control" type="file" name="other_docs"
                                                        id="other_docs">
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="bankInfo" role="tabpanel">
                                    <div class="pd-20">
                                        <div class="row">

                                            <div class="col-md-6">
                                                <label class="col-form-label">Account Holder Name</label>
                                                <div class="form-group">
                                                    <input class="form-control" type="text" name="acc_holder_name"
                                                        id="acc_holder_name" placeholder="Account Holder Name">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="col-form-label">Account Number</label>
                                                <div class="form-group">
                                                    <input class="form-control" type="number" name="acc_num" id="acc_num"
                                                        placeholder="Account Number">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="col-form-label">IFSC Code</label>
                                                <div class="form-group">
                                                    <input class="form-control" type="text" name="ifsc" id="ifsc"
                                                        placeholder="IFSC Code">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="col-form-label">Bank Name</label>
                                                <div class="form-group">
                                                    <input class="form-control" type="text" name="bank_name" id="bank_name"
                                                        placeholder="Bank Name">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="col-form-label">Branch</label>
                                                <div class="form-group">
                                                    <input class="form-control" type="text" name="bank_branch"
                                                        id="bank_branch" placeholder="Branch">
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="salaryInfo" role="tabpanel">
                                    <div class="pd-20">
                                        <div class="row">

                                            <div class="col-md-12">
                                                <h4 style="text-decoration:underline;">Actual Salary Info</h4>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="col-form-label">Basic Salary</label>
                                                <div class="form-group">
                                                    <input class="form-control" type="number" name="basic_salary"
                                                        id="basic_salary" placeholder="Basic Salary">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="col-form-label">House Rent Allowance</label>
                                                <div class="form-group">
                                                    <input class="form-control" type="number" name="house_rent"
                                                        id="house_rent" placeholder="House Rent Allowance">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="col-form-label">PF</label>
                                                <div class="form-group">
                                                    <input class="form-control" type="number" name="pf" id="pf"
                                                        placeholder="PF">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="col-form-label">ESI</label>
                                                <div class="form-group">
                                                    <input class="form-control" type="number" name="esi" id="esi"
                                                        placeholder="ESI">
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <label class="col-form-label">Total Salary</label>
                                                <div class="form-group">
                                                    <input class="form-control" type="number" name="salary_total"
                                                        id="salary_total" placeholder="Total Salary">
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <h4 style="text-decoration:underline;">Compliance Salary Info</h4>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="col-form-label">Basic Salary</label>
                                                <div class="form-group">
                                                    <input class="form-control" type="number" name="basic_salary_cmpl"
                                                        id="basic_salary_cmpl" placeholder="Basic Salary">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="col-form-label">House Rent Allowance</label>
                                                <div class="form-group">
                                                    <input class="form-control" type="number" name="house_rent_cmpl"
                                                        id="house_rent_cmpl" placeholder="House Rent Allowance">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="col-form-label">PF</label>
                                                <div class="form-group">
                                                    <input class="form-control" type="number" name="pf_cmpl" id="pf_cmpl"
                                                        placeholder="PF">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="col-form-label">ESI</label>
                                                <div class="form-group">
                                                    <input class="form-control" type="number" name="esi_cmpl" id="esi_cmpl"
                                                        placeholder="ESI">
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <label class="col-form-label">Total Salary</label>
                                                <div class="form-group">
                                                    <input class="form-control" type="number" name="salary_total_cmpl"
                                                        id="salary_total_cmpl" placeholder="Total Salary">
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="loginInfo" role="tabpanel">
                                    <div class="pd-20">
                                        <div class="row">

                                            <div class="col-md-6">
                                                <label class="col-form-label">User Name</label> <label class="uname_found_msg"></label>
                                                <div class="form-group">
                                                    <input class="form-control" type="text" name="username" id="username" placeholder="User Name">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="col-form-label">Password</label>
                                                <div class="form-group">
                                                    <input class="form-control" type="text" name="password" id="password"
                                                        placeholder="Password">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="col-form-label">App Permission</label>
                                                <div class="form-group">
                                                    <select class="form-control custom-select2" name="user_group"
                                                        id="user_group" style="width:100%">
                                                        <?= select_dropdown('user_group', array('id', 'group_name'), 'id ASC', '', '', ''); ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="col-form-label">Remainder Category</label>
                                                <div class="form-group">
                                                    <select class="form-control custom-select2" name="task_remainder_level"
                                                        id="task_remainder_level" style="width:100%">
                                                        <option value="A">Follow Ups</option>
                                                        <option value="B">Supervisor</option>
                                                        <option value="C">Manager</option>
                                                        <option value="D">Management</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <label class="col-form-label">Is Cost Generator </label>
                                                <div class="form-group">
                                                    <input style="min-width:20px !important" type="radio"
                                                        name="cost_generator" id="cg_yes" value="Yes"
                                                        onclick="show_cgName('yes')"> <label for="cg_yes">Yes</label>
                                                    <input style="min-width:20px !important" type="radio"
                                                        name="cost_generator" id="cg_no" value="No" checked
                                                        onclick="show_cgName('no')"> <label for="cg_no">No</label>
                                                </div>
                                            </div>

                                            <div class="col-md-9 cg_nameDiv d-none">
                                                <label class="col-form-label">Cost Generating Name </label>
                                                <div class="form-group">
                                                    <input class="form-control" type="text" name="cg_name" id="cg_name"
                                                        placeholder="Cost Generating Name">
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <div class="spinner-border m-5 d-none spinCls" role="status">
                            <span class="visually-hidden"></span>
                        </div>
                        <button type="button" onclick="save_employee('add')" class="btn btn-success scbtn">Submit</button>

                    </div>
                </form>
            </div>
        </div>
    </div>

<?php }
if (in_array('scanned_pcs-modal', $modals)) { ?>
    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="scanned_pcs-modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title" id="myLargeModalLabel">Scanned pcs detailed list</p>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form method="post" id="colorForm" autocomplete="off" enctype="multipart/form-data">
                    
                    <div class="modal-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Sl.No</th>
                                    <th>Bundle Number</th>
                                    <th>Scanned Count</th>
                                    <th>Scanned Pcs</th>
                                </tr>
                            </thead>
                            <tbody id="scanned_pcs-tbody">
                                <tr>
                                    <td colspan="4" class="text-center"><i class="fa-spinner fa"></i> Loading</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php }
if (in_array('color-add-modal', $modals)) { ?>
    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
        id="color-add-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">Add New Color</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form method="post" id="colorForm" autocomplete="off" enctype="multipart/form-data">
                    <input type="hidden" name="table_name" id="table_name" value="color">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Color Name <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="color_name" id="color_name"
                                        placeholder="Color Name" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" onclick="save_color()" class="btn btn-success">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php }
if (in_array('punit-add-modal', $modals)) { ?>
    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
        id="punit-add-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">New Production Unit</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form method="post" id="productionUnitForm" autocomplete="off" enctype="multipart/form-data">

                    <div class="modal-body">
                        <div class="row">

                            <div class="col-md-12">
                                <label>Production Unit Name <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="full_name" id="full_name"
                                        placeholder="Unit Full Name" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" onclick="save_production_unit()" class="btn btn-success">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php }
if (in_array('unit-add-modal', $modals)) { ?>
    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
        id="unit-add-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">New Unit</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form method="post" id="unitForm" autocomplete="off" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Unit Full Name <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <input type="text" class="form-control valid_unit_name" name="full_name" id="full_name"
                                        placeholder="Unit Full Name" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label>Unit Short Name</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="short_name" id="short_name"
                                        placeholder="Unit Short Name">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label>Part Count<span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <input type="number" value="1" class="form-control" name="part_count" id="part_count"
                                        placeholder="Part Count">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                        <button type="button" onclick="save_unit()" class="btn btn-outline-success btnUnit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php }
if (in_array('merchand-add-modal', $modals)) { ?>
    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
        id="merchand-add-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">New Merchandiser</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form method="post" id="merchand_Form" autocomplete="off" enctype="multipart/form-data">
                    <input type="hidden" name="table_name" id="table_name" value="merchand_detail">
                    <div class="modal-body">
                        <div class="row">

                            <div class="col-md-12">
                                <label>Merchandiser Name <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <select class="custom-select2 form-control" name="merchand_name" id="merchand_name"
                                        style="width:100%;" required>
                                        <?= select_dropdown_multiple('employee_detail', array('id', 'employee_name'), 'employee_name ASC', '', ' WHERE is_active="active"', ''); ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label>Merchandiser Code <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="merchand_code" id="merchand_code"
                                        placeholder="Merchandiser Code">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label>Buyer <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <select class="custom-select2 form-control" name="merch_brand[]" id="merch_brand"
                                        style="width:100%;" multiple required>
                                        <?= select_dropdown_multiple('brand', array('id', 'brand_name'), 'brand_name ASC', '', '', '`'); ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label>Mail Id </label>
                                <div class="form-group">
                                    <input type="email" class="form-control" name="mailid" id="mailid"
                                        placeholder="Mail Id">
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" onclick="save_merchand()" class="btn btn-success">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php }
if (in_array('part-add-modal', $modals)) { ?>
    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
        id="part-add-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">New Part</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form method="post" id="part_addForm" autocomplete="off" enctype="multipart/form-data">
                    <input type="hidden" name="table_name" id="table_name" value="part">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Part Name <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <input type="text" class="form-control valid_part_name" name="part_name" id="part_name"
                                        placeholder="Part Name" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" onclick="save_part()" class="btn btn-success btnPart">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php }
if (in_array('brand-add-modal', $modals)) { ?>
    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
        id="brand-add-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">New Brand</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form method="post" id="brand_addForm" autocomplete="off" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Brand Name <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <input type="text" class="form-control valid_brand_name" name="brand_name"
                                        id="brand_name" placeholder="Brand Name" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label>Brand Code </label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="brand_code" id="brand_code"
                                        placeholder="Brand Code">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label>Approvals </label>
                                <div class="form-group">
                                    <select name="approvals[]" id="approvals" class="form-control custom-select2"
                                        style="width:100%" required multiple>
                                        <?= select_dropdown_multiple('mas_approval', array('id', 'name'), 'id ASC', '', '', '1'); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label>User Name </label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="username" id="username" placeholder="User Name">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label>Password </label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="password" id="password"
                                        placeholder="Password">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                        <button type="button" onclick="save_brand()"
                            class="btn btn-outline-success btnBrand">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php }
if (in_array('task-add-modal', $modals)) { ?>
    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
        id="task-add-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">New Task</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form method="post" id="task_addForm" autocomplete="off" enctype="multipart/form-data">
                    <div class="col-md-12">
                        <label>Process Type</label>
                        <div class="form-group">
                            <select name="task_type" id="task_type" class="form-control custom-select2" style="width:100%">
                                <option value="">Select</option>
                                <option value="production_task">Production Process</option>
                                <option value="fabric_task">Fabric Process</option>
                                <option value="store_task">Store</option>
                                <option value="other_task">Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 otherDiv d-none">
                                <label>Task Name <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="task_name" id="task_name"
                                        placeholder="Task Name" required>
                                </div>
                            </div>
                            <div class="col-md-12 commonDiv d-none">
                                <label>Task Name <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <select class="form-control custom-select2" name="task_process_id" id="task_process_id"
                                        style="width:100%"></select>
                                </div>

                            </div>
                            <div class="col-md-6 commonDiv  d-none">
                                <label>Daily FollowUp Duration<span class="text-danger"></span></label>
                                <div class="form-group">
                                    <input type="number" placeholder="Daily FollowUp Duration" class="form-control"
                                        name="daily_followup_task" id="daily_followup_task">
                                </div>
                            </div>
                            <div class="col-md-6 commonDiv   d-none">
                                <label>&nbsp;&nbsp;Hour / Minute<span class="text-danger"></span></label>
                                <div class="form-group">
                                    : &nbsp;
                                    <select class="form-control custom-select2" name="daily_followup_duration_task"
                                        id="daily_followup_duration_task" style="width:80%">
                                        <option value="hour">Hours</option>
                                        <option value="minute">Minutes</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 commonDiv   d-none">
                                <label>End Followup Duration<span class="text-danger"></span></label>
                                <div class="form-group">
                                    <input type="number" placeholder="End Followup Duration" class="form-control"
                                        name="end_followup_task" id="end_followup_task">
                                </div>
                            </div>

                            <div class="col-md-6 commonDiv  d-none">
                                <label>&nbsp;&nbsp;Hour / Minute<span class="text-danger"></span></label>
                                <div class="form-group">
                                    : &nbsp;
                                    <select class="form-control custom-select2" name="end_followup_duration_task"
                                        id="end_followup_duration_task" style="width:80%">
                                        <option value="hour">Hours</option>
                                        <option value="minute">Minutes</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" onclick="save_task()" class="btn btn-success">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php }
if (in_array('defect-add-modal', $modals)) { ?>
    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
        id="defect-add-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">New Defect</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form method="post" id="defect_addForm" autocomplete="off" enctype="multipart/form-data">

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Defect Name <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="defect_name" id="defect_name"
                                        placeholder="Defect Name" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" onclick="save_defect()" class="btn btn-success">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php }
if (in_array('checking-add-modal', $modals)) { ?>
    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
        id="checking-add-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">New Checking Type</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form method="post" id="checking_addForm" autocomplete="off" enctype="multipart/form-data">

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Checking Name <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="checking_name" id="checking_name"
                                        placeholder="Checking Name" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label>Rework Applicable <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <select class="form-control custom-select2" name="is_rework" id="is_rework"
                                        style="width:100%">
                                        <option value="No">No</option>
                                        <option value="Yes">Yes</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label>Checking Color <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <input type="text" class="complex-colorpicker form-control asColorPicker-input"
                                        name="checking_color" id="checking_color" placeholder="Checking Color"
                                        value="#dddddd" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" onclick="save_checking()" class="btn btn-success">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php }
if (in_array('currency-add-modal', $modals)) { ?>
    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
        id="currency-add-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">New Currency</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form method="post" id="currency_addForm" autocomplete="off" enctype="multipart/form-data">

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Currency Name <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="currency_name" id="currency_name"
                                        placeholder="Currency Name" required>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label>Currency Value <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="currency_value" id="currency_value"
                                        placeholder="Currency Value" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" onclick="save_currency()" class="btn btn-success">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php }
if (in_array('designation-add-modal', $modals)) { ?>
    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
        id="designation-add-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">New Designation</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form method="post" id="designation_addForm" autocomplete="off" enctype="multipart/form-data">

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Designation Name <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="desig_name" id="desig_name"
                                        placeholder="Designation Name" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" onclick="save_designation()" class="btn btn-success">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php }
if (in_array('teamTask-add-modal', $modals)) { ?>
    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
        id="teamTask-add-modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">Create New Team Task</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form method="post" id="task_addForm" autocomplete="off" enctype="multipart/form-data">

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Task <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <select name="task_name" id="task_name" class="form-control custom-select2"
                                        style="width:100%" required>
                                        <?= select_dropdown('mas_task', array('id', 'task_name'), 'id ASC', '', '', ''); ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label>Task Ref</label>
                                <div class="form-group">
                                    <textarea class="form-control" name="task_ref" id="" placeholder="Task Ref"
                                        style="height:47px"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" onclick="save_task()" class="btn btn-success">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php }
if (in_array('accessoriesTyp-add-modal', $modals)) { ?>
    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
        id="accessoriesTyp-add-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">New Accessories Type</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form method="post" id="accessoriesType_addForm" autocomplete="off" enctype="multipart/form-data">

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Accessories Type Name <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="type_name" id="type_name"
                                        placeholder="Accessories Type Name" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" onclick="save_accessoriesType()" class="btn btn-success">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php }
if (in_array('accessories-add-modal', $modals)) { ?>
    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
        id="accessories-add-modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">New Accessories</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form method="post" id="accessories_addForm" autocomplete="off" enctype="multipart/form-data">

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label class="fieldrequired">Accessories Type</label>
                                <div class="form-group">
                                    <select name="acc_type" id="acc_type" class="form-control custom-select2"
                                        style="width:100%" required>
                                        <?= select_dropdown('mas_accessories_type', array('id', 'type_name'), 'id ASC', '', '', ''); ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="fieldrequired">Accessories Name</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="acc_name" id="acc_name"
                                        placeholder="Accessories Name" required>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <label class="fieldrequired">Excess Percetage</label>
                                <div class="form-group">
                                    <input type="number" class="form-control" name="excess" id="excess" placeholder="Excess Percetage">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <hr>
                            </div>

                            <div class="col-md-3">
                                <label class="fieldrequired">Purchase UOM</label>
                                <div class="form-group">
                                    <select name="purchase_uom" id="purchase_uom" class="form-control custom-select2"
                                        style="width:100%" required>
                                        <?= select_dropdown('mas_uom', array('id', 'uom_name'), 'uom_name ASC', '', '', ''); ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="fieldrequired">Consumption UOM</label>
                                <div class="form-group">
                                    <select name="consumption_uom" id="consumption_uom" class="form-control custom-select2"
                                        style="width:100%" required>
                                        <?= select_dropdown('mas_uom', array('id', 'uom_name'), 'uom_name ASC', '', '', ''); ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <label class="fieldrequired">Purchase Unit</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="purchase_unit" id="purchase_unit" placeholder="Purchase Unit" value="1" readonly>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <label class="fieldrequired">Purchase UOM Qty</label>
                                <div class="form-group">
                                    <input type="number" class="form-control" name="uom_qty" id="uom_qty" placeholder="Purchase UOM Qty">
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" onclick="save_accessories1(this)" class="btn btn-success">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php }
if (in_array('component-add-modal', $modals)) { ?>
    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
        id="component-add-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">New Component</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form method="post" id="component_addForm" autocomplete="off" enctype="multipart/form-data">

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Component Name <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="component_name" id="component_name"
                                        placeholder="Component Name" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" onclick="save_component()" class="btn btn-success">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php }
if (in_array('approval-add-modal', $modals)) { ?>
    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
        id="approval-add-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">New Approval</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form method="post" id="approval_addForm" autocomplete="off" enctype="multipart/form-data">

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Approval Name <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="approval_name" id="approval_name"
                                        placeholder="Approval Name" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label>Approval Department <span class="text-danger"></span></label>
                                <div class="form-group">
                                    <select class="form-control custom-select2" name="app_dpt" id="app_dpt"
                                        style="width:100%" required>
                                        <?= select_dropdown('department', array('id', 'department_name'), 'department_name ASC', '', '', ''); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>Daily FollowUp Duration<span class="text-danger"></span></label>
                                <div class="form-group">
                                    <input type="number" placeholder="Daily FollowUp Duration" class="form-control"
                                        name="daily_followup" id="daily_followup">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>&nbsp;&nbsp;Hour / Minute<span class="text-danger"></span></label>
                                <div class="form-group">
                                    : &nbsp;
                                    <select class="form-control custom-select2" name="daily_followup_duration"
                                        id="daily_followup_duration" style="width:90%;">
                                        <option value="hour">Hours</option>
                                        <option value="minute">Minutes</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label>End Followup Duration<span class="text-danger"></span></label>
                                <div class="form-group">
                                    <input type="number" placeholder="End Followup Duration" class="form-control"
                                        name="end_followup" id="end_followup">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label>&nbsp;&nbsp;Hour / Minute<span class="text-danger"></span></label>
                                <div class="form-group">
                                    : &nbsp;
                                    <select class="form-control custom-select2" name="end_followup_duration"
                                        id="end_followup_duration" style="width:90%;">
                                        <option value="hour">Hours</option>
                                        <option value="minute">Minutes</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" onclick="save_approval()" class="btn btn-success">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php }
if (in_array('selection-type-add-modal', $modals)) { ?>
    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
        id="selection-type-add-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">New Selection Type</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form method="post" id="selection_typeForm" autocomplete="off" enctype="multipart/form-data">
                    <input type="hidden" name="table_name" id="table_name" value="selection_type">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Type Name <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <input type="text" class="form-control selection_type_name" name="type_name"
                                        id="type_name" placeholder="Type Name" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                        <button type="button" onclick="save_selection_type()"
                            class="btn btn-outline-success btnType">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php }
if (in_array('showprocessModal', $modals)) { ?>
    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
        id="showprocessModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Sub Process List</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" id="saveprocessForm">
                    <div class="modal-body">
                        <div class="row">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Process</th>
                                        <th>Operation</th>
                                        <th>Price</th>
                                    </tr>
                                </thead>
                                <tbody id="showsub_body"></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php } ?>


<script>
    function save_task() {
        if ($("#task_name").val() == "") {
            message_noload('warning', 'Task Name Required!', 1000);
            return false;
        } else {
            var form = $("#task_addForm").serialize()
            $.ajax({
                type: 'POST',
                url: 'ajax_action.php?save_task=1',
                data: form,
                success: function (msg) {

                    var json = $.parseJSON(msg);
                    if (json.result == 'success') {
                        message_reload('success', 'Task Saved');
                    } else {
                        message_error();
                    }
                }
            })
        }
    }
</script>