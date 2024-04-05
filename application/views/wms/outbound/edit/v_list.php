<?php
    if($status == 0){
        echo $message;
        return 0;
    }

?>

<style>
  tr{
    font-size: 14px;
  }
</style>

<div class="modal" id="myModalEdit" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit<span id="modal_text_item"></span></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="modal_detail_edit">
        <div class="container-fluid">
          Qty <input type="number" class="form-control" id="modal_qty" onkeypress='return isNumberKey_local(event)' style="font-size:40px;">
          <input type="hidden" class="form-control" id="modal_index">
        </div>
        <div class="container-fluid" style="margin-top:40px;">
          <button class="btn btn-primary form-control" id="btn_edit_detail">Edit</button>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row" style="margin-top:20px;">

<div class="col-6">

  <!-- detail -->
  <table class="table table-bordered table-striped table-sm">
      <tr>
        <th>Doc No</th>
        <th>Line No</th>
        <th>Item Code</th>
        <th>Description</th>
        <th>Qty to Ship</th>
        <th>Pick No</th>
        <th>Pick Line No</th>
        <th>Qty Pick</th>
        <th>Location</th>
        <th>SO</th>
        <th>Cust Code</th>
        <th>Cust Name</th>
        <th>Action</th>
      </tr>

      <?php
        $i=1;
        foreach($var_doc_d as $row){
            echo "<tr>";
              echo "<td id='tbl_list_doc_no_".$i."'>".$row["doc_no"]."</td>";
              echo "<td id='tbl_list_line_no_".$i."'>".$row["line_no"]."</td>";
              echo "<td id='tbl_list_item_code_".$i."'>".$row["item_code"]."</td>";
              echo "<td id='tbl_list_desc_".$i."'>".$row["description"]."</td>";

              if($row["pick_no"] == "") echo "<td id='tbl_list_qty_to_ship_".$i."'>".$row["qty_to_ship"]."</td>";
              else echo "<td id='tbl_list_qty_to_ship_".$i."'>".$row["qty_to_picked2"]."</td>";

              echo "<td id='tbl_list_pick_no_".$i."'>".$row["pick_no"]."</td>";
              echo "<td id='tbl_list_pick_line_no_".$i."'>".$row["pick_line_no"]."</td>";
              echo "<td id='tbl_list_qty_pick_".$i."'>".$row["qty_to_picked2"]."</td>";
              echo "<td id='tbl_list_location_".$i."'>".combine_location($row["location_code"], $row["zone_code"], $row["area_code"], $row["rack_code"], $row["bin_code"])."</td>";
              echo "<td id='tbl_list_so_no_".$i."'>".$row["src_no"]."</td>";
              echo "<td id='tbl_list_cust_code_".$i."'>".$row["bill_cust_no"]."</td>";
              echo "<td id='tbl_list_cust_name_".$i."'>".$row["bill_cust_name"]."</td>";
              echo "<td><button class='btn btn-primary btn-sm' onclick=f_edit(".$i.") >Edit</button></td>";
            echo "</tr>";
            $i++;
        }
      ?>

  </table>
</div>

<div class="col-6">
  <table class="table table-bordered table-striped table-sm" id="tbl_list_edited">
    <thead>
      <tr>
        <th>Doc No</th>
        <th>Line No</th>
        <th>Item Code</th>
        <th>Description</th>
        <th>Qty to Ship</th>
        <th>Qty Minus</th>
        <th>Qty After Edited</th>
        <th>Pick No</th>
        <th>Pick line no</th>
        <th>Location</th>
        <th>SO no</th>
        <th>Cust Code</th>
        <th>Cust Name</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody></tbody>
    <tfoot>
      <tr>
        <td colspan='13'><button class="btn btn-success" disabled id="btn_process_edited">PROCESS</button></td>
      </tr>
    </tfoot>
  </table>
</div>

</div>

<script>

var glb_idx = 0;

function f_edit(index){
    var min = parseInt($("#tbl_list_qty_to_ship_"+index).text())*-1;
    $("#modal_qty").attr({
       "max" : -1,        // substitute your own
       "min" : min          // values (or variables) here
    });
    $("#modal_qty").val("-1");
    $("#modal_index").val(index);
    $("#myModalEdit").modal("toggle");
}
//---

$("#btn_edit_detail").click(function(){
    var qty_minus = $("#modal_qty").val();
    var index = $("#modal_index").val();

    // check if already exist
    if(f_already_exist_in_table_edited(index)){
        show_error("The Data already exist, you have to Delete first and add again...");
        return false;
    }

    text = add_row(index, qty_minus);
    $("#tbl_list_edited").append(text);
    check_button_disabled();
    $("#myModalEdit").modal("hide");
})
//---

function f_already_exist_in_table_edited(index){
    found = 0;

    for(i=0;i<=glb_idx;i++){
        if(check_if_id_exist("#tbl_list_edited_row_"+i)){
            if($('#tbl_list_doc_no_'+index).text() == $("#tbl_list_edited_doc_no_"+i).text()
              && $('#tbl_list_line_no_'+index).text() == $("#tbl_list_edited_line_no_"+i).text()
              && $('#tbl_list_location_'+index).text() == $("#tbl_list_edited_location_"+i).text()
            ){
                  found = 1;
                  break;
              }
        }
    }

    if(found == 1) return 1
    else return 0;
}
//---

function add_row(index, qty_minus){
    var qty_result = convert_number($('#tbl_list_qty_to_ship_'+index).text()) + convert_number(qty_minus);

    var text = "";
    text = text + "<tr id='tbl_list_edited_row_"+glb_idx+"'>";
      text = text + "<td id='tbl_list_edited_doc_no_"+glb_idx+"'>"+$('#tbl_list_doc_no_'+index).text()+"</td>";
      text = text + "<td id='tbl_list_edited_line_no_"+glb_idx+"'>"+$('#tbl_list_line_no_'+index).text()+"</td>";
      text = text + "<td id='tbl_list_edited_item_code_"+glb_idx+"'>"+$('#tbl_list_item_code_'+index).text()+"</td>";
      text = text + "<td id='tbl_list_edited_desc_"+glb_idx+"'>"+$('#tbl_list_desc_'+index).text()+"</td>";
      text = text + "<td id='tbl_list_edited_qty_to_ship_"+glb_idx+"'>"+$('#tbl_list_qty_to_ship_'+index).text()+"</td>";
      text = text + "<td id='tbl_list_edited_qty_minus_"+glb_idx+"'>"+qty_minus+"</td>";
      text = text + "<td id='tbl_list_edited_qty_result_"+glb_idx+"'>"+qty_result+"</td>";
      text = text + "<td id='tbl_list_edited_pick_no_"+glb_idx+"'>"+$('#tbl_list_pick_no_'+index).text()+"</td>";
      text = text + "<td id='tbl_list_edited_pick_line_no_"+glb_idx+"'>"+$('#tbl_list_pick_line_no_'+index).text()+"</td>";
      text = text + "<td id='tbl_list_edited_location_"+glb_idx+"'>"+$('#tbl_list_location_'+index).text()+"</td>";
      text = text + "<td id='tbl_list_edited_so_no_"+glb_idx+"'>"+$('#tbl_list_so_no_'+index).text()+"</td>";
      text = text + "<td id='tbl_list_edited_cust_code_"+glb_idx+"'>"+$('#tbl_list_cust_code_'+index).text()+"</td>";
      text = text + "<td id='tbl_list_edited_cust_name_"+glb_idx+"'>"+$('#tbl_list_cust_name_'+index).text()+"</td>";
      text = text + "<td id='tbl_list_edited_delete_"+glb_idx+"'><button class='btn btn-sm btn-danger' onclick=f_tbl_list_edited_delete("+glb_idx+")>X</button></td>";
    text = text + "</tr>";

    glb_idx++;

    return text;
}
//--

function f_tbl_list_edited_delete(line){
    $("#tbl_list_edited_row_"+line).remove();
    check_button_disabled();
}
//----

function check_button_disabled(){
    var check = 0;
    for(i=0;i<=glb_idx;i++){
        if(check_if_id_exist("#tbl_list_edited_row_"+i)){
            check = 1;
            break;
        }
    }
    //--

    if(check == 1) $("#btn_process_edited").removeAttr('disabled');
    else  $("#btn_process_edited").attr('disabled','disabled');
}
//----

$("#btn_process_edited").click(function(){
    swal({
      input: 'textarea',
      inputPlaceholder: 'Type your message here',
      showCancelButton: true,
      confirmButtonText: 'OK'
    }).then(function (result) {
        if(result.dismiss == "cancel"){}
        else{
          if(result.value == ""){ show_error("You have to type message");}
          else{
              var message = result.value;
              swal({
                title: "Are you sure ?",
                html: "Proceed this Edited",
                type: "question",
                showCancelButton: true,
                confirmButtonText: "Yes",
                showLoaderOnConfirm: true,
                closeOnConfirm: false
              }).then(function (result) {
                  if(result.value){
                      $("#loading_text").text("Creating New Edited Document, Please wait...");
                      $('#loading_body').show();

                      var doc_no = [];
                      var line_no = [];
                      var item_code = [];
                      var desc = [];
                      var qty_to_ship = [];
                      var qty_minus = [];
                      var qty_result = [];
                      var pick_no = [];
                      var pick_line_no = [];
                      var so_no = [];
                      var cust_code = [];
                      var cust_name = [];

                      var counter = 0;

                      for(i=0;i<=glb_idx;i++){
                          if(check_if_id_exist("#tbl_list_edited_row_"+i)){
                              doc_no[counter] = $("#tbl_list_edited_doc_no_"+i).text();
                              line_no[counter] = $("#tbl_list_edited_line_no_"+i).text();
                              item_code[counter] = $("#tbl_list_edited_item_code_"+i).text();
                              desc[counter] = $("#tbl_list_edited_desc_"+i).text();
                              qty_to_ship[counter] = $("#tbl_list_edited_qty_to_ship_"+i).text();
                              qty_minus[counter] = $("#tbl_list_edited_qty_minus_"+i).text();
                              qty_result[counter] = $("#tbl_list_edited_qty_result_"+i).text();
                              pick_no[counter] = $("#tbl_list_edited_pick_no_"+i).text();
                              pick_line_no[counter] = $("#tbl_list_edited_pick_line_no_"+i).text();
                              so_no[counter] = $("#tbl_list_edited_so_no_"+i).text();
                              cust_code[counter] = $("#tbl_list_edited_cust_code_"+i).text();
                              cust_name[counter] = $("#tbl_list_edited_cust_name_"+i).text();
                              counter++;
                          }
                      }

                      $.ajax({
                          url  : "<?php echo base_url();?>index.php/wms/outbound/edit/create_new",
                          type : "post",
                          dataType  : 'html',
                          data : {doc_no:JSON.stringify(doc_no), line_no:JSON.stringify(line_no), item_code:JSON.stringify(item_code), desc:JSON.stringify(desc), qty_to_ship:JSON.stringify(qty_to_ship), qty_minus:JSON.stringify(qty_minus), qty_result:JSON.stringify(qty_result),pick_no:JSON.stringify(pick_no), pick_line_no:JSON.stringify(pick_line_no), message:message, so_no:JSON.stringify(so_no),
                          cust_code:JSON.stringify(cust_code), cust_name:JSON.stringify(cust_name)},
                          success: function(data){
                            var responsedata = $.parseJSON(data);

                            if(responsedata.status == 1){
                                  swal({
                                     title: responsedata.msg,
                                     type: "success", confirmButtonText: "OK",
                                  }).then(function(){
                                    setTimeout(function(){
                                      $('#loading_body').hide();
                                      location.reload();
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
    })
})

</script>
