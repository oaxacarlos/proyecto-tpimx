<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      Stock Rack Two
</div>

<div class="container-fluid">
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

<div class="container-fluid" id="report_view" style="margin-top:20px;"></div>

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
        url       : "<?php echo base_url();?>index.php/wms/report/stockrackthree/get_report",
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

</script>
