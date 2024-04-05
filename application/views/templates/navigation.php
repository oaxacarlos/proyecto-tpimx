<?php
include('header.php');
include('loading_page.php');
?>


<?php
$CI =& get_instance();
$CI->load->model('model_navigation');
?>


<nav class="navbar navbar-expand-lg navbar-dark bg-dark" onselectstart="return false">
  <a class="navbar-brand" href="#" ><?php echo $website_header_left; ?></a>

  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

<div class="collapse navbar-collapse" id="navbarSupportedContent">

    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="<?php echo base_url(); ?>index.php/home" onselectstart="return false">Home</a>
      </li>

    <?php
    // load menu 1
    $session_data = $this->session->userdata('z_tpimx_logged_in');
    $userid = $session_data['z_tpimx_user_id'];
    unset($_SESSION['menus_list_user']);

    $result_menu1 = $CI->model_navigation->list_menu1_by_user($userid);
    foreach($result_menu1 as $row){
        echo "<li class='nav-item'>";
          echo "<a class='nav-link' onclick=openMenu('".$row['menu1_initial']."') href='".$row['menu1_link']."'>".strtoupper($row['menu1_name'])."</a>";
        echo "</li>";
    }
    ?>
    </ul>
  <!--<button onclick="darkMode()">DarkMode</button>-->
  <ul class="navbar-nav sticky-top mr-sm-2" onselectstart="return false">

    <!-- notification -->
    <li class="nav-item dropdown" >
      <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown" onclick=f_notif_global_click()>
        <i class="bi bi-bell-fill"></i><span id="notif_global_count"></span>
      </a>
      <div class="dropdown-menu dropdown-menu-right"  style="font-size:12px;">
        <div id="notif_global_list" style="width:100%;"></div>
      </div>
    </li>
    <!-- end of notification -->

    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">User</a>
        <div class="dropdown-menu dropdown-menu-right">
            <?php $session_data = $this->session->userdata('z_tpimx_logged_in'); ?>
            <div class="dropdown-item">User : <?php echo $session_data['z_tpimx_name'] ?></div>
            <a class="dropdown-item" href="#" data-target="#myModalEditPassword" data-toggle="modal">Change Password</a>
            <a class="dropdown-item" href="<?php echo base_url(); ?>index.php/logout">Logout</a>
        </div>
    </li>
  </ul>

</div>

</nav>

<?php

foreach($result_menu1 as $row1){
    echo "<div id='".$row1['menu1_initial']."' class='menubar container-fluid menuShow bg-light border-bottom' style='display:none;' onselectstart='return false'>";
      echo "<span onclick=this.parentElement.style.display='none' style='font-size:30px; cursor:pointer;'>&times;</span>";
      echo "<h2 style='padding-top:20px;'>".strtoupper($row1['menu1_name'])."</h2>";
      echo "<div class='row' style='padding-top:10px;'></div>";
      echo "<div class='row'>";
          $result_menu2 = $CI->model_navigation->list_menu2_by_user($userid,$row1['menu1_code']);
          foreach($result_menu2 as $row2){
              echo "<div class='col-md-3'>";
              echo "<div class='border-bottom'><h5>".strtoupper($row2['menu2_name'])."</h5></div>";
              $result_menu3 = $CI->model_navigation->list_menu3_by_user($userid,$row2['menu2_code']);
              foreach($result_menu3 as $row3){
                  echo "<div class='menuDetail' ".$line."><a href='".base_url()."index.php/".$row3['menu3_initial']."' class='btn btn-outline-info btn-block btn-sm' style='margin-top:5px;'>".strtoupper($row3['menu3_name']);

                  if($row3['menu3_code'] == "MN3053"){
                    echo "<span class='badge badge-danger text-right' style='margin-left:5px;' id='notif_loyalty_verification'></span>";
                  }

                  if($row3['menu3_code'] == "MN3054"){
                    echo "<span class='badge badge-danger text-right' style='margin-left:5px;' id='notif_customer_redeem'></span>";
                  }

                  echo "</a></div>";

                  $_SESSION['menus_list_user'][$row3['menu3_initial']] = 1;

                  if($row3['line'] == 1){
                      echo "<div class='border-bottom' style='margin-top:10px;'></div>";
                  }
              }
              echo "</div>";
              echo "<div class='col-sm-1'></div>";
          }
      echo "</div>";
      echo "<div style='padding-top:30px;'></div>";
    echo "</div>";
}

?>

<div style="padding-top:0px;"></div>

<div class="modal" id="myModalEditPassword">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit Password</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
          <div class="row" style="margin-top:20px;">
            <span class="col-md-4">Password</span>
            <span class="col-md-8"><input type="password" value="" id="password1" class="form-control"></span>
          </div>
          <div class="row" style="margin-top:20px;">
            <span class="col-md-4">Repeat Password</span>
            <span class="col-md-8"><input type="password" value="" id="password2" class="form-control"></span>
          </div>
          <div class="row text-right" style="margin-top:20px;">
            <span class="col-md-12">
              <button class="btn btn-success" id="btn_process_edit_password">Edit Password</button>
            </span>
          </div>
      </div>
    </div>
  </div>
</div>

<?php

$session_data = $this->session->userdata('z_tpimx_logged_in');
$change_password = $session_data['z_tpimx_change_pass'];

?>

<script>
function openMenu(menuName) {
    var i;
    var x = document.getElementsByClassName("menubar");
    for (i = 0; i < x.length; i++) {
       x[i].style.display = "none";
    }
    document.getElementById(menuName).style.display = "block";
}
//----

$('#btn_process_edit_password').click(function(){
    var password1 = $("#password1").val();
    var password2 = $("#password2").val();

    if(password1 == '' || password2 == '') swal('Error','Password could not blank','error');
    else if(password1 != password2){
        swal('Error','Password not matched','error');
        document.getElementById('password1').value = "";
        document.getElementById('password2').value = "";
    }
    else{
      swal({
        title: "Are You Sure?",
        html: "Change password for this user",
        type: "question",
        showCancelButton: true,
        confirmButtonText: "Yes",
        showLoaderOnConfirm: true,
        closeOnConfirm: false
      }).then(function (result) {
            if(result.value){
                swal({ title: "Please Wait", text: "Progressing...", showConfirmButton: false });

              $.ajax({
                  url       : "<?php echo base_url();?>index.php/admin/admin_userlist/edit_password_navigation",
                  type      : 'post',
                  dataType  : 'html',
                  data      : {password:password1},
                  success   :  function(respons){
                    if(respons != 0){
                        //$('#myModalEditPassword').modal('hide');
                        swal({
                           title: "Changed User Password has been successfull, you need to relogin",
                           type: "success", confirmButtonText: "OK",
                        }).then(function(){
                          setTimeout(function () {
                            window.location.href = "<?php echo base_url();?>index.php/logout";
                          },100)
                        });
                    }
                    else{
                        Swal('Error!','Changed User Password not successfull, Password could not be same with preivous one or the Password is not match','error');
                    }
                  }
              });
            }
      })
    }
});
//------------------

function change_password(){
    var change = <?php echo $change_password; ?>;
    if(change == 1){
      $("#password1").val('');
      $("#password2").val('');
      $("#myModalEditPassword").modal({"backdrop": "static"});
      $("#myModalEditPassword").modal("show");
    }
}
change_password();
//---

$("#myModalEditPassword").on('hidden.bs.modal', function(){
    var change = <?php echo $change_password; ?>;
    if(change == 1){
        alert("You have to Change your password / Tienes que cambiar tu contraseña");
        window.location.href = "<?php echo base_url();?>index.php/logout";
    }
})
//---

function notif(){
  $.ajax({
      url       : "<?php echo base_url();?>index.php/navigation/notif",
      type      : 'post',
      dataType  : 'html',
      success   :  function(data){
          var responsedata = $.parseJSON(data);

          if(responsedata.notif_count_loyalty_verification > 0){
              $("#"+responsedata.link_notif_loyalty_verification).text(responsedata.notif_count_loyalty_verification);
          }

          if(responsedata.notif_count_customer_redeem > 0){
              $("#"+responsedata.link_notif_customer_redeem).text(responsedata.notif_count_customer_redeem);
          }
      }
  });
}
//notif();

setInterval(function() {
    //notif();
    notif_global();
}, 5000);
//--

function notif_global(){
    $.ajax({
        url       : "<?php echo base_url();?>index.php/navigation/notif_global",
        type      : 'post',
        dataType  : 'html',
        success   :  function(data){
            var responsedata = $.parseJSON(data);

            if(responsedata.total_notif > 0){
              $("#notif_global_count").html("<span class='badge badge-danger'>"+responsedata.total_notif+"</span>");
            }
            else{
            }
        }
    });
}
notif_global();
//--

function f_notif_global_click(){

    // update all as read
    $.ajax({
        url       : "<?php echo base_url();?>index.php/navigation/update_notif_global_as_read",
        type      : 'post',
        dataType  : 'html',
        success   :  function(data){
            var responsedata = $.parseJSON(data);

            if( typeof responsedata.detail === 'undefined'){
                $("#notif_global_list").html("<li>0 Notification</li>");
            }
            else{
              var html = "";
              for(i=0;i<responsedata.detail.length;i++){
                  html = html + "<li class='border-bottom dropdown-item'>"+responsedata.detail[i].message;

                  if(responsedata.detail[i].read == 0)
                    html = html + "<span class='badge badge-danger' style='margin-left:5px;'>*</span>";

                  html = html + "</li>";
              }

              $("#notif_global_list").html(html);
            }

            $("#notif_global_count").html("<span></span>");
        }
    });
}
//--

</script>
