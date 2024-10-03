<?php 

  

  include("includes/connection.php");

  include("includes/function.php");

  include("language/language.php");



  define("PAGE_HEAD","Customers");



  if (!isset($_SESSION['login_name'])) {

    header("Location:index.php");

    exit;

  }



  include 'includes/header.php';

  include 'includes/side_menu.php';

  include 'includes/head.php';



  if(isset($_GET['cust_id'])){ 



    $page_title= 'Edit Customer';

  }

  else{ 



    $page_title='Add Customer'; 

  }



  if (isset($_POST['submit']) && $_POST['form_type']=="add") {



  $store_details = mysqli_fetch_array(mysqli_query($mysqli,"select store_code from tbl_stores where id='".$_POST['store_id']."' and status='1' and is_active='1'")); 



   $store_code = $store_details['store_code']."-";



   $generate_ids=mysqli_query($mysqli,"SELECT id, CONCAT('".$store_code."', LPAD(id+1,4,'0')) as gen_num FROM tbl_customers order by id desc limit 1");

   $generate_rows=mysqli_fetch_array($generate_ids);

       

   if($generate_rows['gen_num']!='')

   {

       $cust_id = $generate_rows['gen_num'];



   } else {



      $cust_id = $store_code."001";

   } 



  

  $data = array(

    'cust_id'  =>  $cust_id,

    'cust_name'  =>  filter_var($_POST['cust_name'], FILTER_SANITIZE_STRING),

    'cust_phone'  =>  filter_var($_POST['cust_phone'], FILTER_SANITIZE_STRING),

    'cust_email'  =>  filter_var($_POST['cust_email'], FILTER_SANITIZE_STRING),

    'cust_dob'  =>  date('Y-m-d',strtotime($_POST['cust_dob'])),

    'gender'  =>  filter_var($_POST['cust_gender'], FILTER_SANITIZE_STRING),

    'address'  =>  filter_var($_POST['cust_address'], FILTER_SANITIZE_STRING),

    'store_id'  =>  filter_var($_POST['store_id'], FILTER_SANITIZE_STRING),

    'country'  =>  filter_var($_POST['country'], FILTER_SANITIZE_STRING),

    'state'  =>  filter_var($_POST['state'], FILTER_SANITIZE_STRING),

    'medical_history'  =>  filter_var($_POST['medical_history'], FILTER_SANITIZE_STRING),  

    'reference_doctor'  =>  filter_var($_POST['reference_doctor'], FILTER_SANITIZE_STRING), 

    'doctor_number'  =>  filter_var($_POST['doctor_number'], FILTER_SANITIZE_STRING),

    'city'  =>  filter_var($_POST['cities'], FILTER_SANITIZE_STRING),  

    'status'  =>  filter_var($_POST['status'], FILTER_SANITIZE_STRING),

    'created_date' => date('Y-m-d')    

  );



 $qry = Insert('tbl_customers', $data);  

  $_SESSION['msg'] = "5";

  header("Location:manage_customers.php");

  exit;



 }



 if (isset($_POST['submit']) && $_POST['form_type']=="edit") {  

  

  $data = array(  

    'cust_name'  =>  filter_var($_POST['cust_name'], FILTER_SANITIZE_STRING),

    'cust_phone'  =>  filter_var($_POST['cust_phone'], FILTER_SANITIZE_STRING),

    'cust_email'  =>  filter_var($_POST['cust_email'], FILTER_SANITIZE_STRING),

    'cust_dob'  =>  date('Y-m-d',strtotime($_POST['cust_dob'])),

    'gender'  =>  filter_var($_POST['cust_gender'], FILTER_SANITIZE_STRING),

    'address'  =>  filter_var($_POST['cust_address'], FILTER_SANITIZE_STRING),

    'store_id'  =>  filter_var($_POST['store_id'], FILTER_SANITIZE_STRING),

    'country'  =>  filter_var($_POST['country'], FILTER_SANITIZE_STRING),

    'state'  =>  filter_var($_POST['state'], FILTER_SANITIZE_STRING),

    'city'  =>  filter_var($_POST['cities'], FILTER_SANITIZE_STRING),  

    'medical_history'  =>  filter_var($_POST['medical_history'], FILTER_SANITIZE_STRING),  

    'reference_doctor'  =>  filter_var($_POST['reference_doctor'], FILTER_SANITIZE_STRING),  

    'doctor_number'  =>  filter_var($_POST['doctor_number'], FILTER_SANITIZE_STRING),

    'status'  =>  filter_var($_POST['status'], FILTER_SANITIZE_STRING)       

  );



  $city_edit = Update('tbl_customers', $data, "WHERE id = '" . $_POST['rec_id'] . "'");



  $_SESSION['msg'] = "6";

  header("Location:manage_customers.php");

  exit;



 }



 if (isset($_GET['cust_id'])) {



  $qry = "SELECT * FROM tbl_customers WHERE id='" . $_GET['cust_id'] . "' and status='1' and is_active='1'";

  $result = mysqli_query($mysqli, $qry);

  $row = mysqli_fetch_assoc($result);



}



?>



 <!-- Table Product -->

  <div class="row">

    <div class="col-12">

      <div class="card card-default">

        <div class="card-header">

          <h2><?php echo $page_title; ?></h2>

          <div class="dropdown">

            <a href="manage_customers.php">

            <h4 class="header-title m-t-0 m-b-30 text-primary pull-left" style="font-size: 20px;color:#e91e63;"><i class="fa fa-arrow-left"></i> Back</h4>

          </a>

          </div>

        </div>

        <div class="card-body">  

            

        <form method="post" autocomplete="off">



         <input type="hidden" class="form-control" name="form_type" id="form_type" value="<?php if(isset($_GET['cust_id'])) { echo "edit"; } else { echo "add"; } ?>">   



         <div class="row">     

         

            <div class="col-md-6">

              <label>Customer Name <span class="field_required">*</span></label>

                <div class="form-group">      

                <input type="hidden" name="rec_id" id="rec_id" value="<?php if(isset($_GET['cust_id'])) { echo $row['id']; } ?>">            

                 <input type="text" class="form-control" name="cust_name" id="cust_name" 

                 value="<?php if(isset($_GET['cust_id'])) { echo $row['cust_name']; } ?>" placeholder="Customer Name" required>            

               </div>

            </div>



            <div class="col-md-6">

              <label>Phone Number <span class="field_required">*</span></label>

                <div class="form-group">            

                 <input type="text" class="form-control" name="cust_phone" id="cust_phone" 

                 value="<?php if(isset($_GET['cust_id'])) { echo $row['cust_phone']; } ?>" placeholder="Phone Number" minlength="10" maxlength="10" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" required>            

               </div>

            </div>



            <div class="col-md-6">

              <label>Email ID </label>

                <div class="form-group">            

                 <input type="email" class="form-control" name="cust_email" id="cust_email" 

                 value="<?php if(isset($_GET['cust_id'])) { echo $row['cust_email']; } ?>" placeholder="Email ID" >            

               </div>

            </div>



            <div class="col-md-6">

              <label>Date of Birth <span class="field_required">*</span></label>

                <div class="form-group">            

                 <input type="date" class="form-control" name="cust_dob" id="cust_dob" 

                 value="<?php if(isset($_GET['cust_id'])) { echo $row['cust_dob']; } ?>" placeholder="Date of Birth" required>            

               </div>

            </div>



            <div class="col-md-6">

              <label>Gender <span class="field_required">*</span></label>

                <div class="form-group">            

                 <input type="radio" name="cust_gender" id="cust_gender" value="Male" <?php if($row['gender']=='Male') { ?> checked <?php } ?> required> Male 

                 <input type="radio" name="cust_gender" id="cust_gender" <?php if($row['gender']=='Female') { ?> checked <?php } ?> value="Female" required> Female            

               </div>

            </div>



            <div class="col-md-6">

              <label >Address <span class="field_required">*</span></label>

                <div class="form-group">            

                 <input type="text" class="form-control" name="cust_address" id="cust_address" value="<?php if(isset($_GET['cust_id'])) { echo $row['address']; } ?>" placeholder="Address" required>            

               </div>

            </div>            



            <div class="col-md-6">

              <label >Store Name <span class="field_required">*</span></label>

                <div class="form-group">            

                 <select class="form-control" name="store_id" id="store_id" required>

                  <option value="">Select Store</option>

                  <?php 

                  $whereSQL="";

                  if($_SESSION['login_role']!='1') {

                    $whereSQL.="and id='".$_SESSION['store_id']."' ";

                  }

                  $tbl_stores =mysqli_query($mysqli,"select * from tbl_stores where status='1' and is_active='1' $whereSQL");

                  while($tbl_stores_row=mysqli_fetch_array($tbl_stores))

                   {?>

                    <option value="<?php echo $tbl_stores_row['id']; ?>" <?php if($tbl_stores_row['id']== $row['store_id']) { ?> selected <?php } ?>> <?php echo $tbl_stores_row['store_name']; ?> </option>

                  <?php } ?>

                </select>        

               </div>

            </div>



            <div class="col-md-6">

              <label >Country </label>

                <div class="form-group">            

                 <select class="form-control" name="country" id="country" onchange="getcountry_states(this.value)" >

                  <option value="">Select Country</option>

                  <?php 

                  $country_details =mysqli_query($mysqli,"select * from master_country");

                  while($country_details_row=mysqli_fetch_array($country_details))

                   {?>

                    <option value="<?php echo $country_details_row['auto_number']; ?>" <?php if($country_details_row['auto_number']==$row['country']) { ?> selected <?php } ?>> <?php echo $country_details_row['country']; ?> </option>

                  <?php } ?>

                </select>   

               </div>

            </div>



            <div class="col-md-6">

              <label>State </label>

                <div class="form-group">            

                 <select class="form-control" name="state" id="state" onchange="getstates_cities(this.value)" > 

                  <option value="">Select State</option>

                  <?php if(isset($_GET['cust_id'])) { 

                  $get_states = mysqli_query($mysqli,"select * from states where country_id='".$row['country']."'");

                  while($get_states_row=mysqli_fetch_array($get_states))

                  {

                  ?>

                   <option value="<?php echo $get_states_row['id']; ?>" <?php if($get_states_row['id']== $row['state']) { ?> selected <?php } ?>> <?php echo $get_states_row['state_name']; ?> </option>

                  <?php } } ?>

                 </select>       

               </div>

            </div>



            <div class="col-md-6">                         

              <label >Cities </label>

                <div class="form-group">            

                 <select class="form-control" name="cities" id="cities" >  

                 <?php if(isset($_GET['cust_id'])) { 

                  $get_cities = mysqli_query($mysqli,"select * from cities where state_id='".$row['state']."'");

                  while($get_cities_row=mysqli_fetch_array($get_cities))

                  {

                  ?>

                   <option value="<?php echo $get_cities_row['id']; ?>" <?php if($get_cities_row['id']== $row['city']) { ?> selected <?php } ?>> <?php echo $get_cities_row['cities_name']; ?> </option>

                  <?php } } ?>                 

                 </select>       

               </div>

            </div>  



            <div class="col-md-6">

              <label>Medical History </label>

                <div class="form-group">            

                 <textarea class="form-control" name="medical_history" id="medical_history" 

                  placeholder="Medical History" ><?php if(isset($_GET['cust_id'])) { echo $row['medical_history']; } ?></textarea>            

               </div>

            </div>



            <div class="col-md-6">

              <label>Reference Doctor</label>

                <div class="form-group">            

                 <input type="text" class="form-control" name="reference_doctor" id="reference_doctor"

                 value="<?php if(isset($_GET['cust_id'])) { echo $row['reference_doctor']; } ?>" placeholder="Reference Doctor" >            

               </div>

            </div>  

            

            <div class="col-md-6">

              <label>Doctor Phone Number </label>

                <div class="form-group">            

                 <input type="text" class="form-control" name="doctor_number" id="doctor_number" 

                 value="<?php if(isset($_GET['cust_id'])) { echo $row['doctor_number']; } ?>" placeholder="Doctor Phone Number" minlength="10" maxlength="10" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">            

               </div>

            </div>

           

             <div class="col-md-6">

              <label >Status <span class="field_required">*</span></label>

                <div class="form-group">            

                 <select class="form-control" name="status" id="status" required> 

                  <option value="">Select Option</option>

                  <option value="1" <?php if($row['status']=="1") { ?> selected <?php } ?>>Active</option>

                  <option value="0" <?php if($row['status']=="0") { ?> selected <?php } ?>>Inactive</option>

                 </select>       

               </div>               

            </div>           



          </div>



          <div class="form-footer mt-6">

            <button type="submit" name="submit" class="btn btn-primary btn-pill">Submit</button>

            <button type="reset" class="btn btn-light btn-pill">Clear</button>

          </div>



        </form>



      </div>

      </div>

    </div>

  </div>



<?php 

  include 'includes/footer.php';

?>



<script type="text/javascript">



  $('a').click(function(){



    $('a.active').each(function(){

      $(this).removeClass('active');

    });

    $(this).addClass('active');

 });





</script>



<script>



  function getcountry_states(id){



     $.ajax({

        url: "ajax.php",

        type: 'POST',

        data: {

            action: 'states',          

            country_id:id           



        },

        success:function(data){

            

          $("#state").html(data);



        },

        error:function(){



        }



     });



    }



  function getstates_cities(id){



      $.ajax({

        url: "ajax.php",

        type: 'POST',

        data: {

            action: 'single_cities',          

            state_id:id           



        },

        success:function(data){

            

          $("#cities").html(data);



        },

        error:function(){



        }



     });





    } 



        

    </script>



