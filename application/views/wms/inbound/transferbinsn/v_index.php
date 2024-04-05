

<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      Transfer Bin SN
</div>

<div class="modal" id="myModalNewLoc">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">New Location</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body ui-front" id="modal_detail_new_loc"></div>
    </div>
  </div>
</div>

<div class="modal" id="myModalSummary">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Summary</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body ui-front" id="modal_detail_summary"></div>
    </div>
  </div>
</div>

<div class="container">
  <div class="row">
    <div class="col-md-6">
      SN / MASTER
      <input type="text" value="" id="inp_sn" class="form-control" onkeypress='return get_sn(event)'>
    </div>

    <div class="col-md-1">
      Process<br>
      <button class="btn btn-primary" id="btn_go">GO</button>
    </div>

    <div class="col-md-2">
      Clear ALL<br>
      <button class="btn btn-danger" id="btn_clear_all">CLEAR ALL</button>
    </div>

    <div class="col-md-2">
      SUMMARY<br>
      <button class="btn btn-secondary" id="btn_summary">SUMMARY</button>
    </div>
  </div>
</div>

<div class="container" style="margin-top:20px;">
  <div class="row">
    <div class="col-md-12">
      <table class="table table-striped table-bordered" id="tbl_sn">
        <thead>
          <tr>
            <th>SN</th>
            <th>Master</th>
            <th>Item Code</th>
            <th>Item Name</th>
            <th>Status</th>
            <th>Location</th>
            <th>Zone</th>
            <th>Area</th>
            <th>Rack</th>
            <th>Bin</th>
            <th>Action</th>
          </tr>
        </thead>

        <tbody>
        </tbody>

      </table>
    </div>
  </div>

  <div class="row" style="margin-bottom:20px;">
    <div class="col-md-12">
      <button class="btn btn-success" id="btn_process">PROCESS</button>
    </div>
  </div>
</div>

<?php echo loading_body_full(); ?>

<script>

var glb_idx = 0;

$("#btn_clear_all").click(function(){
    glb_idx = 0;
    $("#tbl_sn tbody").empty();
})

$("#btn_go").click(function(){

  $("#loading_text").text("Getting Information, Please wait...");
  $('#loading_body').show();

    // get sn information
    var sn = $("#inp_sn").val();
    $.ajax({
        url  : "<?php echo base_url();?>index.php/wms/inbound/transferbinsn/get_sn_information",
        type : "post",
        dataType  : 'html',
        data : {id:sn},
        success: function(response){
            responsedata = $.parseJSON(response);
            if(responsedata.status == 0){
              show_error("SN / Master no hay en sistema");
            }
            else{
                add_table(responsedata.data);
            }

            $("#inp_sn").val("");
            $("#inp_sn").focus();
            $('#loading_body').hide();
        }
    })

})
//---

function check_if_sn_has_been_scaned(sn){
    var check = 0;
    for(j=0;j<glb_idx;j++){
        if(check_if_id_exist($("#tbl_dtl_sn_"+j))){
          sn_tbl = $("#tbl_dtl_sn_"+j).text();
          if(sn_tbl == sn){
            check = 1;
            break;
          }
        }
    }

    if(check == 1) return true;
    else return false;
}
//--

function add_table(data){
  text = "";
  for(i=0;i<data.length;i++){
      if(!check_if_sn_has_been_scaned(data[i].sn)){
        text = text + "<tr id='tbl_sn_"+glb_idx+"'>";
          text = text + "<td id='tbl_dtl_sn_"+glb_idx+"'>"+data[i].sn+"</td>";
          text = text + "<td id='tbl_dtl_sn2_"+glb_idx+"'>"+data[i].sn2+"</td>";
          text = text + "<td id='tbl_dtl_item_"+glb_idx+"'>"+data[i].item_code+"</td>";
          text = text + "<td id='tbl_dtl_itemname_"+glb_idx+"'>"+data[i].item_name+"</td>";
          text = text + "<td id='tbl_dtl_stsname_"+glb_idx+"'>"+data[i].status_name+"</td>";
          text = text + "<td id='tbl_dtl_loc_"+glb_idx+"'>"+data[i].location+"</td>";
          text = text + "<td id='tbl_dtl_zone_"+glb_idx+"'>"+data[i].zone+"</td>";
          text = text + "<td id='tbl_dtl_area_"+glb_idx+"'>"+data[i].area+"</td>";
          text = text + "<td id='tbl_dtl_rack_"+glb_idx+"'>"+data[i].rack+"</td>";
          text = text + "<td id='tbl_dtl_bin_"+glb_idx+"'>"+data[i].bin+"</td>";
          text = text + "<td><button class='btn btn-danger btn-sm' onclick=f_delete("+glb_idx+")>X</button></td>";
          text = text + "<td id='tbl_dtl_sts_"+glb_idx+"' style='display:none;'>"+data[i].status+"</td>";
        text = text + "</tr>";
        glb_idx++;
      }
  }
  if(text!="") $("#tbl_sn tbody").append(text);
}
//--

function get_sn(event){
    if(event.keyCode == 13){
      $("#btn_go").click();
    }
}
//--

function f_delete(idx){
    $("#tbl_sn_"+idx).remove();
}
//---

function check_if_all_in_same_location(){
  loc = "";
  check = 1;
  for(i=0;i<glb_idx;i++){
      if(check_if_id_exist($("#tbl_dtl_sn_"+i))){
          if(loc == "") loc = $("#tbl_dtl_loc_"+i).text();
          else{
            if(loc != $("#tbl_dtl_loc_"+i).text()){
                check = 0;
                break;
            }
          }
      }
  }

  if(check == 0) return false;
  else return true;

}

//

$("#btn_process").click(function(){

    //check if have data to transfer
    var tbody = $("#tbl_sn tbody");
    if(tbody.children().length == 0) {
        show_error("No Hay DATA para Transferencia");
        return false;
    }
    else if(!check_if_all_in_same_location()){
        show_error("Tiene Datos no en Mismo Location");
        return false;
    }
    else{

      for(i=0;i<glb_idx;i++){
        if(check_if_id_exist($("#tbl_dtl_sn_"+i))){
            var location_code = $("#tbl_dtl_loc_"+i).text();
            break;
        }
      }

      data2 = {'location_code':location_code}

      $('#modal_detail_new_loc').html('Loading, Please wait...');
      //open the modal with selected parameter attached
      $('#modal_detail_new_loc').load(
          "<?php echo base_url();?>index.php/wms/inbound/transferbinsn/get_location",
          data2,
          function(responseText, textStatus, XMLHttpRequest) { } // complete callback
      );

      $('#myModalNewLoc').modal();

    }
    //--
})
//--

$("#btn_summary").click(function(){

  var tbody = $("#tbl_sn tbody");

  if(tbody.children().length == 0) {
      show_error("No DATA");
      return false;
  }
  else{
      // get summary SN2
      var sum_sn2 = [];
      var sum_item = [];
      first = 1;
      counter = 0;
      for(i=0;i<glb_idx;i++){
          if(check_if_id_exist($("#tbl_dtl_sn_"+i))){
              if(first == 1){
                  sum_sn2[counter] = $("#tbl_dtl_sn2_"+i).text();
                  sum_item[counter] = $("#tbl_dtl_item_"+i).text();
                  counter++;
                  first++;
              }
              else{
                  if(!sum_sn2.includes($("#tbl_dtl_sn2_"+i).text())){
                    sum_sn2[counter] = $("#tbl_dtl_sn2_"+i).text();
                    sum_item[counter] = $("#tbl_dtl_item_"+i).text();
                    counter++;
                  }
              }
          }
      }
      //--

      // get sum sn2 with total
      var sum_sn2_total = [];
      counter = 0;
      for(i=0;i<sum_sn2.length;i++){
          var total = 0;
          for(j=0;j<glb_idx;j++){
            if(sum_sn2[i] == $("#tbl_dtl_sn2_"+j).text()){
              total = total + 1;
            }
          }

          sum_sn2_total[counter] = total;
          counter++;
      }
      //--
  }

  // show data
  var total_sum = 0;
  html = "";
  html = html + "<table class='table table-bordered table-striped'>";
    html = html + "<thead>";
      html = html + "<tr>";
        html = html + "<th>MASTER</th>";
        html = html + "<th>ITEM</th>";
        html = html + "<th>QTY</th>";
      html = html + "<tr>";
    html = html + "</thead>";

    html = html + "<tbody>";
    for(i=0;i<sum_sn2.length;i++){
        html = html + "<tr>";
          html = html + "<td>"+sum_sn2[i]+"</td>";
          html = html + "<td>"+sum_item[i]+"</td>";
          html = html + "<td>"+sum_sn2_total[i]+"</td>";
        html = html + "</tr>";

        total_sum = total_sum + sum_sn2_total[i];
    }
    html = html + "</tbody>";

    html = html + "<tfoot>";
      html = html + "<tr>";
        html = html + "<th colspan='2'>TOTAL</th>";
        html = html + "<th>"+total_sum+"</th>";
      html = html + "</tr>";
    html = html + "</tfoot>";

  html = html + "</table>";

  $('#modal_detail_summary').html(html);
  $('#myModalSummary').modal();
  //--

})

</script>
