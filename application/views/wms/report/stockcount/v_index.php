<script>
$(document).ready(function() {
    $('#DataTable').DataTable({
      dom: 'Bfrtip',
        buttons: [
          {
            extend: 'excel',
            title : 'Stock-Count'
          }
        ],
    });

    $("#datepicker_from").datetimepicker({
       timepicker: false,
       format : 'Y-m-d'
    });

    $("#datepicker_to").datetimepicker({
       timepicker: false,
       format : 'Y-m-d'
    });

    $("#datepicker_from_duplicate").datetimepicker({
       timepicker: false,
       format : 'Y-m-d'
    });

    $("#datepicker_to_duplicate").datetimepicker({
       timepicker: false,
       format : 'Y-m-d'
    });

    $.datetimepicker.setLocale('en');
});
</script>

<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      Stock Count
</div>


<ul class="nav nav-tabs container" id="myTab" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link active" id="input-tab" data-toggle="tab" data-target="#input" type="button" role="tab" aria-controls="input" aria-selected="true">Input</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="data-tab" data-toggle="tab" data-target="#data" type="button" role="tab" aria-controls="data" aria-selected="false">Data</button>
  </li>
  <?php if(isset($_SESSION['user_permis']["37"])){ ?>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="duplicate-tab" data-toggle="tab" data-target="#duplicate" type="button" role="tab" aria-controls="data" aria-selected="false">Duplicate</button>
  </li>
  <?php } ?>
</ul>

<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="input" role="tabpanel" aria-labelledby="input-tab">
    <div class="container" style="margin-top:20px;">
      <div class="row">
        <div class="col-12">
          Rack
          <input type="text" id="inp_rack" class="form-control" onkeypress="f_check_input(event)" autofocus>
        </div>
        <div class="col-2">
          -<br>
          <button class="btn btn-primary" id="btn_process" style="display:none;">PROCESS</button>
        </div>
      </div>
    </div>

    <div class="container" id="report_view" style="margin-top:20px;"></div>
  </div>

  <div class="tab-pane fade" id="data" role="tabpanel" aria-labelledby="data-tab">
    <div class="container" style="margin-top:20px;">
      <div class="row">
        <div class="col-md-2">
          <span class="badge badge-primary">From *</span>
          <input type='text' name='datepicker_check' value="<?php echo date("Y-m-d"); ?>" id='datepicker_from' class='required form-control' placeholder='Period From'>
        </div>
        <div class="col-md-2">
          <span class="badge badge-primary">To *</span>
          <input type='text' name='datepicker_check' value="<?php echo date("Y-m-d"); ?>" id='datepicker_to' class='required form-control' placeholder='Period To'>
        </div>
        <div>
          <span class="badge badge-primary">User</span>
          <select id='inp_user' class='form-control'>
            <?php
              $selected = ""; $has_selected = 0;
              foreach($var_user as $row){
                  if($row['user_id'] == $var_user_login){
                    $selected = "selected";
                    $has_selected = 1;
                  }
                  echo "<option value='".$row['user_id']."' ".$selected.">".$row["name"]."</option>";
                  $selected = "";
              }

              if($has_selected == 0) $selected = "selected";

              echo "<option value='all' ".$selected.">ALL</option>";
            ?>
          </select>
        </div>
        <div class="col-md-2">
          <button class="btn btn-primary" id="btn_go" style="margin-top:18px;">GO</button>
        </div>
      </div>
    </div>
    <div class="container" id="stockcount_data" style="margin-top:20px;"></div>
  </div>


  <div class="tab-pane fade" id="duplicate" role="tabpanel" aria-labelledby="duplicate-tab">
    <div class="container" style="margin-top:20px;">
      <div class="row">
        <div class="col-md-2">
          <span class="badge badge-primary">From *</span>
          <input type='text' value="<?php echo date("Y-m-d"); ?>" id='datepicker_from_duplicate' class='required form-control' placeholder='Period From'>
        </div>
        <div class="col-md-2">
          <span class="badge badge-primary">To *</span>
          <input type='text' value="<?php echo date("Y-m-d"); ?>" id='datepicker_to_duplicate' class='required form-control' placeholder='Period To'>
        </div>
        <div>
          <span class="badge badge-primary">User</span>
          <select id='inp_user_duplicate' class='form-control'>
            <?php
              $selected = ""; $has_selected = 0;
              foreach($var_user as $row){
                  if($row['user_id'] == $var_user_login){
                    $selected = "selected";
                    $has_selected = 1;
                  }
                  echo "<option value='".$row['user_id']."' ".$selected.">".$row["name"]."</option>";
                  $selected = "";
              }

              if($has_selected == 0) $selected = "selected";

              echo "<option value='all' ".$selected.">ALL</option>";
            ?>
          </select>
        </div>
        <div class="col-md-2">
          <button class="btn btn-primary" id="btn_go_duplicate" style="margin-top:18px;">GO</button>
        </div>
      </div>
    </div>
    <div class="container" id="stockcount_data_duplicate" style="margin-top:20px;"></div>
  </div>

</div>

<?php echo loading_body_full(); ?>

<?php

unset($autocomplete);
$i=0;

foreach($var_bin as $row){
    $value2 = $row['location_code']."-".$row['zone_code']."-".$row['area_code']."-".$row['rack_code']."-".$row['code'];
    $autocomplete[$i] = $value2;
    $i++;
}

$js_array_autocomplete = json_encode($autocomplete);

?>

<script>
var autocomplete = <?php echo $js_array_autocomplete; ?>;
var glb_idx=0;

$( function() {
  $( "#inp_rack").autocomplete({ source: autocomplete});
})
//---

$("#btn_process").click(function(){
    var inp_rack = $("#inp_rack").val();

    inp_rack = convert_wh3(inp_rack);

    if(inp_rack == ""){
        show_error("You have to input Rack");
        $("#inp_rack").val("");
        return false;
    }

    $("#report_view").html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/wms/report/stockcount/get_report",
        type      : "post",
        dataType  : 'html',
        data      : {inp_rack:inp_rack},
        success   : function(respons){
          $('#progress').hide();
          $('#report_view').fadeIn("5000");
          $("#report_view").html(respons);
          $("#inp_rack").val("");

        }
    })
})
//--


function f_check_input(){
    if(event.keyCode == 13){
        $("#btn_process").click();
    }
}
//--

function convert_wh3(rack){
    var temp_rack = rack.split("-");

    if(temp_rack[0] != "W3") return rack;
    else{
        loc = "WH3";
        zone = "";
        area = "";
        rack = temp_rack[2];
        bin = temp_rack[3];

        area = temp_rack[1].substr(temp_rack[1].length - 1);
        zone = temp_rack[1].slice(0, (temp_rack[1].length-1));

        result = loc+"-"+zone+"-"+area+"-"+rack+"-"+bin;

        return result;
    }
}
//---

$("#btn_go").click(function(){
    var from = $("#datepicker_from").val();
    var to = $("#datepicker_to").val();
    var user = $("#inp_user").val();

    if(from == ""){
      show_error("You haven't fill DATE FROM");
      return false;
    }

    if(to == ""){
      show_error("You haven't fill DATE TO");
      return false;
    }

    $("#stockcount_data").html("Loading, Please Wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/wms/report/stockcount/get_data",
        type      : 'post',
        dataType  : 'html',
        data      :  {from:from, to:to, user:user},
        success   :  function(respons){
            $("#stockcount_data").fadeIn("5000");
            $("#stockcount_data").html(respons);
        }
    });
})
//---

$("#btn_go_duplicate").click(function(){
    var from = $("#datepicker_from_duplicate").val();
    var to = $("#datepicker_to_duplicate").val();
    var user = $("#inp_user_duplicate").val();

    if(from == ""){
      show_error("You haven't fill DATE FROM");
      return false;
    }

    if(to == ""){
      show_error("You haven't fill DATE TO");
      return false;
    }

    $("#stockcount_data_duplicate").html("Loading, Please Wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/wms/report/stockcount/get_data_duplicate",
        type      : 'post',
        dataType  : 'html',
        data      :  {from:from, to:to, user:user},
        success   :  function(respons){
            $("#stockcount_data_duplicate").fadeIn("5000");
            $("#stockcount_data_duplicate").html(respons);
        }
    });
})
//---

</script>
