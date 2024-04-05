<script>

$(document).ready(function(){
  //Initialize the datePicker(I have taken format as mm-dd-yyyy, you can     //have your owh)
  $("#datepicker_from").datetimepicker({
    timepicker: true,
    format : 'Y-m-d H:i:s',
    container: '#myModalAdd modal-body'
  });

  $("#datepicker_to").datetimepicker({
     timepicker: true,
     format : 'Y-m-d H:i:s'
  });

  $.datetimepicker.setLocale('en');

});

</script>

<style>
.ui-datepicker {
    z-index: 9999 !important;
}
</style>

<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      Setup Month End
</div>

<div class="container-fluid" style="margin-top:30px;">
  <button class="btn btn-outline-primary" id="btn_refresh"><i class="bi-arrow-clockwise"></i></button>
</div>

<div class="container">
  <div class="row col-md-6">
    Name
    <input type="text" value="" id="add_name" class="form-control">
  </div>
  <div class="row col-md-6">
    Warehouse
    <?php
        $user_plant = get_plant_code_user();
        $new_user_plant = explode(",",$user_plant);

        echo "<select id='add_whs' name='add_whs' class='form-control'>";
          foreach($var_location as $row){
              for($i=0; $i<count($new_user_plant); $i++){
                  $temp = trim($new_user_plant[$i],"\'");
                  if($row["code"] == $temp){
                      echo "<option value='".$row["code"]."'>".$row["name"]."</option>";
                  }
              }
          }
        echo "</select>";
    ?>
  </div>

  <div class="row">
    <div class="col-md-3">
      From
      <input type="text" value="<?php echo ""; ?>" id='datepicker_from' class="form-control">
    </div>
    <div class="col-3">
      To
      <input type="text" value="<?php echo ""; ?>" id='datepicker_to' class="form-control">
    </div>
  </div>

  <div class="row" style="margin-top:10px;">
    <div class="col-md-3">
      <button class="btn btn-primary" id="btn_add" onclick=f_add()>Add</button>
      <button class="btn btn-danger" id="btn_clear" onclick=clear_all()>Clear</button>
    </div>
  </div>
</div>

<div class="container" style="margin-top:30px;">
  <?php echo load_progress("progress"); ?>
  <div id="list_data"></div>
</div>

<?php echo loading_body_full(); ?>

<script>
// first load
f_refresh();
//--

$('#btn_refresh').click(function(){
    f_refresh();
});
//---

function f_refresh(){
    $('#list_data').hide();
    $('#progress').show();
    $.ajax({
        url       : "<?php echo base_url();?>index.php/wms/outbound/setup/monthend_get_list",
        type      : 'post',
        dataType  : 'html',
        success   :  function(respons){
            $('#progress').hide();
            $('#list_data').fadeIn("5000");
            $("#list_data").html(respons);
        }
    });
}
//---

function f_add(){
    var name = $("#add_name").val();
    var from = $("#datepicker_from").val();
    var to = $("#datepicker_to").val();
    var whs = $("#add_whs").val();

    if(name == "") show_error("Name could not be blank");
    else if(from == "") show_error("Date From could not be blank");
    else if(to == "") show_error("Date To could not be blank");
    else if(from > to) show_error("'Date From' not allow greater than 'Date To'");
    else{

      if(check_if_data_no_redundant(from,to,whs)=="0"){
          show_error("You have already Month End data in database");
      }
      else{
        swal({
          title: "Are you sure ?",
          html: "Add this Month End",
          type: "question",
          showCancelButton: true,
          confirmButtonText: "Yes",
          showLoaderOnConfirm: true,
          closeOnConfirm: false
        }).then(function (result) {
            if(result.value){
                $("#loading_text").text("Inserting data, Please wait...");
                $('#loading_body').show();

                $.ajax({
                    url  : "<?php echo base_url();?>index.php/wms/outbound/setup/monthend_add",
                    type : "post",
                    dataType  : 'html',
                    data : {name:name, from:from, to:to, whs:whs},
                    success: function(data){
                        var responsedata = $.parseJSON(data);

                        if(responsedata.status == 1){
                              swal({
                                 title: responsedata.msg,
                                 type: "success", confirmButtonText: "OK",
                              }).then(function(){
                                setTimeout(function(){
                                  f_refresh();
                                  clear_all();
                                  $('#loading_body').hide();
                                },100)
                              });
                        }
                        else if(responsedata.status == 0){
                            Swal('Error!',responsedata.msg,'error');
                            $('#loading_body').hide();
                        }
                    }
                })

            }
        })
      }
    }
}
//---

function check_if_data_no_redundant(from,to, whs){
    $.ajax({
        url  : "<?php echo base_url();?>index.php/wms/outbound/setup/monthend_check",
        type : "post",
        dataType  : 'json',
        async: false,
        data : {from:from, to:to, whs:whs},
        success: function(data){
            result = $.parseJSON(data);
        }
    })

    return result;
}
//---

function clear_all(){
    $("#add_name").val("");
    $("#datepicker_from").val("");
    $("#datepicker_to").val("");
}

</script>
