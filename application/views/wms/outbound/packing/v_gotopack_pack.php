
<div class="row fontsize">
  <div class="col-3">
      Doc Date
      <span><input type='text' class="form-control fontsize" value="<?php echo get_date_now(); ?>" disabled></span>
  </div>
  <div class="col-3">
      Process<br>
      <button class="btn btn-primary fontsize" id="btn_create_packlist" disabled>Save Packing</button>
  </div>
</div>

<div class="row fontsize" style="margin-top:10px;">
  <div class="col-4">
    Serial Number
    <input type="text" class="form-control fontsize" id="scan_sn" onkeypress="f_check_scan_sn(event)">
  </div>
</div>


<div class="container-fluid" style="margin-top:20px;">
  <table class="table table-bordered table-sm table-striped" id="table_pack_list">
    <thead>
      <tr>
        <th>Item Code</th>
        <th>Desc</th>
        <th>Qty</th>
        <th>Uom</th>
      </tr>
    </thead>
    <tbody>
    </tbody>
  </table>
</div>

<div class="container-fluid" style="margin-top:10px;">
  <table class="table table-bordered table-sm table-striped" id="table_pack_list2">
    <thead>
      <tr>
        <th>Item Code</th>
        <th>Desc</th>
        <th>Qty</th>
        <th>Uom</th>
        <th>S/N</th>
      </tr>
    </thead>
    <tbody>
    </tbody>
  </table>
</div>

<script>

var sum_item = [];
var sum_desc = [];
var sum_qty = [];
var sum_uom = [];
var reset = [];

function f_check_scan_sn(event){
    if(event.keyCode == 13){
        var sn = $("#scan_sn").val();
        f_check_in_list(sn);
        $("#scan_sn").val("");
        $("#scan_sn").focus();
    }
}
//---

function f_check_in_list(sn){
    var total_row = $("#total_row_list").val();

    for(i=0;i<total_row;i++){
        if($('#tbl_list_show_'+i).text() == "show"){
            if($('#tbl_list_sn_'+i).text() == sn){
                show_hide_list(i,"hide");
                add_to_table_pack2(i);
                calculate_to_sum(i);
                disabled_save_packing();
                $("#tbl_list_show_"+i).text("hide");
            }
        }
    }
}
//---

function show_hide_list(i,status){
    if(status == "hide"){
      $("#tbl_list_item_"+i).hide();
      $("#tbl_list_desc_"+i).hide();
      $("#tbl_list_qtypick_"+i).hide();
      $("#tbl_list_uom_"+i).hide();
      $("#tbl_list_sn_"+i).hide();
      $("#tbl_list_index_"+i).hide();
      $("#tbl_list_show_"+i).hide();
    }
    else if(status == "show"){
      $("#tbl_list_item_"+i).show();
      $("#tbl_list_desc_"+i).show();
      $("#tbl_list_qtypick_"+i).show();
      $("#tbl_list_uom_"+i).show();
      $("#tbl_list_sn_"+i).show();
      $("#tbl_list_index_"+i).show();
      $("#tbl_list_show_"+i).show();
    }
}
//---

function add_to_table_pack2(i){
    var text = "";
    text = text + "<tr id='tbl_pack2_"+i+"'>";
    text = text + "<td id='tbl_pack2_item_"+i+"'>"+$("#tbl_list_item_"+i).text()+"</td>";
    text = text + "<td id='tbl_pack2_desc_"+i+"'>"+$("#tbl_list_desc_"+i).text()+"</td>";
    text = text + "<td id='tbl_pack2_qty_"+i+"'>"+$("#tbl_list_qtypick_"+i).text()+"</td>";
    text = text + "<td id='tbl_pack2_uom_"+i+"'>"+$("#tbl_list_uom_"+i).text()+"</td>";
    text = text + "<td id='tbl_pack2_sn_"+i+"'>"+$("#tbl_list_sn_"+i).text()+"</td>";
    text = text + "<td id='tbl_pack2_index_"+i+"'>"+$("#tbl_list_index_"+i).text()+"</td>";
    text = text + "<td><button class='btn btn-danger btn-sm' onclick=f_remove_pack2("+i+")>X</td>";
    text = text + "</tr>";

    $("#table_pack_list2").append(text);
}
//---

function f_remove_pack2(i){
    show_hide_list(i,"show");

    // calculate
    var tb = $('#table_pack_list tbody');
    var row = tb.find("tr").length;
    var index_temp = "";
    var found = 0;

    tb.find("tr").each(function(index, element) {
        $(element).find('td').each(function(index, element) {
            var colVal = $(element).text();
            if((index + 1) == 1){
                if($("#tbl_pack2_item_"+i).text() == colVal){
                    found = 1;
                }
            }

            if(found == 1){
                if((index + 1) == 2){index_temp = colVal; }

                if((index + 1) == 4){
                    qty_temp = parseFloat(colVal);
                    qty_list = parseFloat($('#tbl_pack2_qty_'+i).text());
                    qty = qty_temp - qty_list;
                    $("#tbl_pack_qty_"+index_temp).text(qty);
                }
            }
        })
    })
    //---
    remove_tbl_pack_zero();
    $("#tbl_pack2_"+i).remove();
    disabled_save_packing();
    $("#tbl_list_show_"+i).text("show");
    $("#scan_sn").focus();
}
//---

function calculate_to_sum(i){
      /*var sum_item = reset;
      var sum_desc = reset;
      var sum_qty = reset;
      var sum_uom = reset;
      var counter = 1;

      var tb = $('#table_pack_list2 tbody');
      var size = tb.find("tr").length;
      tb.find("tr").each(function(index, element) {
        $(element).find('td').each(function(index, element) {
            var colVal = $(element).text();
            //console.log("    Value in col " + (index + 1) + " : " + colVal.trim());

            f_insert_to_array(index + 1, colVal.trim(),counter);

        });
        counter++;
      })*/

      var tb = $('#table_pack_list tbody');
      var row = tb.find("tr").length;
      var index_temp = "";

      if(row == 0){
          add_to_table_pack(i);
      }
      else{
          var found = 0;

          tb.find("tr").each(function(index, element) {
              $(element).find('td').each(function(index, element) {
                  var colVal = $(element).text();
                  if((index + 1) == 1){
                      if($("#tbl_list_item_"+i).text() == colVal){
                          found = 1;
                      }
                  }

                  if(found == 1){
                      if((index + 1) == 2){index_temp = colVal; }

                      if((index + 1) == 4){
                          qty_temp = parseFloat(colVal);
                          qty_list = parseFloat($('#tbl_list_qtypick_'+i).text());
                          qty = qty_temp + qty_list;
                          $("#tbl_pack_qty_"+index_temp).text(qty);
                      }
                  }
              })
          })

          if(found == 0){ add_to_table_pack(i); }

      }
}
//---

function add_to_table_pack(i){
    var text = "";
    text = text + "<tr id='tbl_pack_"+i+"'>";
    text = text + "<td id='tbl_pack_item_"+i+"'>"+$("#tbl_list_item_"+i).text()+"</td>";
    text = text + "<td id='tbl_pack_index_"+i+"' style='display:none;'>"+$("#tbl_list_index_"+i).text()+"</td>";
    text = text + "<td id='tbl_pack_desc_"+i+"'>"+$("#tbl_list_desc_"+i).text()+"</td>";
    text = text + "<td id='tbl_pack_qty_"+i+"'>"+$("#tbl_list_qtypick_"+i).text()+"</td>";
    text = text + "<td id='tbl_pack_uom_"+i+"'>"+$("#tbl_list_uom_"+i).text()+"</td>";
    text = text + "</tr>";

    $("#table_pack_list").append(text);
}
//---

function remove_tbl_pack_zero(){
    var tb = $('#table_pack_list tbody');
    var row = tb.find("tr").length;
    var index_temp = "";
    var del = [];
    var counter = 0;


    tb.find("tr").each(function(index, element) {
        $(element).find('td').each(function(index, element) {
            var colVal = $(element).text();

            if((index + 1) == 2){index_temp = colVal; }

            if((index + 1) == 4){
              if(parseFloat(colVal) == 0){
                  del[counter] = index_temp;
                  counter++;
              }
            }
        })
    })

    if(del.length > 0){
        for(i=0;i<del.length;i++){
            $("#tbl_pack_"+i).remove();
        }
    }
}

function disabled_save_packing(){
    var tb = $('#table_pack_list tbody');
    var row = tb.find("tr").length;
    if(row == 0) $("#btn_create_packlist").attr("disabled", "disabled");
    else $("#btn_create_packlist").removeAttr("disabled");
}
//---

function initial(){
  $("#scan_sn").focus();
}
initial();
//---

$("#btn_create_packlist").click(function(){

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
              html: "Proceed this Packing List",
              type: "question",
              showCancelButton: true,
              confirmButtonText: "Yes",
              showLoaderOnConfirm: true,
              closeOnConfirm: false
            }).then(function (result) {
                if(result.value){


                    //var tb = $('#table_pack_list2 tbody');
                    //var row_pack2 = tb.find("tr").length;


                }
            })
        }
      }
    })

})
//---

function get_pack_data(){
    var tb = $('#table_pack_list tbody');
    var row_pack = tb.find("tr").length;

    tb.find("tr").each(function(index, element) {
      $(element).find('td').each(function(index, element) {
          var colVal = $(element).text();
          //console.log("    Value in col " + (index + 1) + " : " + colVal.trim());

          

      });
    })
}
//---


</script>
