<script>
$(document).ready(function() {
    $('#DataTable').DataTable({
        "paging": false,
    });
});
</script>

<div class="container">
  <button class="btn btn-primary" id="btn_add2">Add</button>
</div>

<table class="table table-bordered table-sm table-striped" id="DataTable" style="margin-top:20px;">
  <thead>
  <tr>
    <th></th>
    <th>Item Code</th>
    <th>Desc</th>
    <th>Qty</th>
    <th>Uom</th>
  </tr>
  </thead>
  <tbody>
  <?php
    $i=0;
    foreach($var_bin_src as $row){
        echo "<tr>";
          echo "<td style='width:20px;'><input type='checkbox' id='src_check_".$i."'></td>";
          echo "<td id='src_item_".$i."'>".$row["item_code"]."</td>";
          echo "<td id='src_desc_".$i."'>".$row["description"]."</td>";
          echo "<td id='src_qty_".$i."'>".$row["total"]."</td>";
          echo "<td id='src_uom_".$i."'>".$row["uom"]."</td>";
        echo "</tr>";
        $i++;
    }
  ?>

</tbody>

</table>

<input type='hidden' id="total_row_src_bin" value="<?php echo $i; ?>">
<input type='hidden' id="location_code" value="<?php echo $inp_location; ?>">

<div class="container text-right">
  <button class="btn btn-primary text-right" id="btn_add">Add</button>
</div>

<?php //echo loading_body_full(); ?>

<script>

$("#btn_add").click(function(){
    // check if not all checked
    if(!check_if_checkbox_not_blank()){
        show_error("You have not checked any data");
        return false;
    }

    //add_data(); // add data
    add_data2(); // add data

})
//---

$("#btn_add2").click(function(){
    $("#btn_add").click();
})
//---

//---
function check_if_checkbox_not_blank(){
    var table = $('#DataTable').DataTable();
    table.search("").draw();

    var total_row = $("#total_row_src_bin").val();

    var no_blank = 0;
    for(i=0;i<total_row;i++){
          if($('#src_check_'+i).is(':checked') == true){
            no_blank = 1;
            break;
          }
    }
    return no_blank;
}
//---

function add_data(){
    var total_row = $("#total_row_src_bin").val();

    for(i=0;i<total_row;i++){
        if($('#src_check_'+i).is(':checked') == true){
            if(check_is_data_already_exist($("#inp_bin_src").val(),$('#src_item_'+i).text()) == 0){
                text = add_data_to_table($("#inp_bin_src").val(),$('#src_item_'+i).text(),$('#src_qty_'+i).text(),$('#src_desc_'+i).text(), $('#src_uom_'+i).text());

                $("#tbl_result tbody").append(text);
            }
            else{
              show_error("Item & Bin already added");
            }
        }
    }

    $("#myModalBinSrc").modal("toggle");
    $("#inp_bin_src").focus();
    $("#inp_bin_src").val("");
}
//--

function check_is_data_already_exist(bin, item_no){
    var new_bin = bin.split("-");
    var tb = $('#tbl_result tbody');
    var row = tb.find("tr").length;
    if(row == 0) return 0;
    else{
      var found = 0;
      tb.find("tr").each(function(index, element) {
          index = 0;
          $(element).find('td').each(function(index, element) {
              var colVal = $(element).text();
              if(index == 0) loc = colVal;
              else if(index == 1) zone =  colVal;
              else if(index == 2) area = colVal;
              else if(index == 3) rack = colVal;
              else if(index == 4) bin = colVal;
              else if(index == 5) item = colVal;
              index++;
          })

          if(loc==new_bin[0] && zone==new_bin[1] && area==new_bin[2] && rack==new_bin[3] && bin==new_bin[4] && item==item_no) found = 1;
      })
    }

    if(found == 1) return 1; else return 0;
}
//---

function add_data_to_table(bin,item,qty, desc, uom){

    var new_bin = bin.split("-");
    text = "";
    text = text + "<tr id='tbl_result_id_"+glb_idx+"'>";
      text = text + "<td id='tbl_result_loc_"+glb_idx+"'>"+new_bin[0]+"</td>";
      text = text + "<td id='tbl_result_zone_"+glb_idx+"'>"+new_bin[1]+"</td>";
      text = text + "<td id='tbl_result_area_"+glb_idx+"'>"+new_bin[2]+"</td>";
      text = text + "<td id='tbl_result_rack_"+glb_idx+"'>"+new_bin[3]+"</td>";
      text = text + "<td id='tbl_result_bin_"+glb_idx+"'>"+new_bin[4]+"</td>";
      text = text + "<td id='tbl_result_item_"+glb_idx+"'>"+item+"</td>";
      text = text + "<td id='tbl_result_desc_"+glb_idx+"'>"+desc+"</td>";
      text = text + "<td id='tbl_result_uom_"+glb_idx+"'>"+uom+"</td>";
      text = text + "<td id='tbl_result_qty_"+glb_idx+"'>"+qty+"</td>";
      text = text + "<td><input type='text' id='tbl_result_inp_qty_"+glb_idx+"' class='form-control' onkeypress='return isNumberKey(event)'></td>";
      text = text + "<td><button class='btn btn-danger btn-sm' onclick=f_delete_tbl_result("+glb_idx+")>X</button></td>";
    text = text + "</tr>";
    glb_idx++;

    return text;
}
//---

function add_data_to_table2(item, loc, zone, area, rack, bin, autocomplete){

	 text = "";
    // get master barcode & barcode
    $.ajax({
        url  : "<?php echo base_url();?>index.php/wms/inbound/transferbin/get_item_sn_by_master_code_and_barcode",
        type : "post",
        dataType  : 'html',
        data : {item:item, loc:loc, zone:zone, area:area, rack:rack, bin:bin},
        success: function(data){
            responsedata = $.parseJSON(data);

            if(responsedata.status == 0){
                show_error("No Data");
                $('#loading_body').hide();
                return false;
            }
            else{
                for(i=0;i<responsedata.data.length;i++){
                  text = text + "<tr id='tbl_result_id_"+glb_idx+"'>";
                    text = text + "<td id='tbl_result_loc_"+glb_idx+"'>"+loc+"</td>";
                    text = text + "<td id='tbl_result_zone_"+glb_idx+"'>"+zone+"</td>";
                    text = text + "<td id='tbl_result_area_"+glb_idx+"'>"+area+"</td>";
                    text = text + "<td id='tbl_result_rack_"+glb_idx+"'>"+rack+"</td>";
                    text = text + "<td id='tbl_result_bin_"+glb_idx+"'>"+bin+"</td>";
                    text = text + "<td id='tbl_result_item_"+glb_idx+"'>"+responsedata.data[i].item_code+"</td>";
                    text = text + "<td id='tbl_result_desc_"+glb_idx+"'>"+responsedata.data[i].name+"</td>";
                    text = text + "<td id='tbl_result_sn2_"+glb_idx+"'>"+responsedata.data[i].sn2+"</td>";
                    text = text + "<td id='tbl_result_sn_"+glb_idx+"'>"+responsedata.data[i].serial_number+"</td>";
                    text = text + "<td id='tbl_result_qty_"+glb_idx+"'>"+responsedata.data[i].qty+"</td>";
                    text = text + "<td id='tbl_result_uom_"+glb_idx+"'>PZA</td>";
                    text = text + "<td><input type='text' id='tbl_result_inp_rack_"+glb_idx+"' class='form-control'></td>";
                    text = text + "<td><button class='btn btn-danger btn-sm' onclick=f_delete_tbl_result("+glb_idx+")>X</button></td>";
                  text = text + "</tr>";

                  glb_idx++;
                }

				         $("#tbl_result tbody").append(text);
            }
        }
    })
}
//---

function add_data2(){
    var total_row = $("#total_row_src_bin").val();

    var loc = $("#inp_bin_src").val();
    loc = loc.split("-");

	  $("#loading_text").text("Adding data, Please Wait...");
    $('#loading_body').show();

    for(i=0;i<total_row;i++){
        if($('#src_check_'+i).is(':checked') == true){

            if(!check_item_type_must_be_same($('#src_item_'+i).text())){
                show_error("No Puedes Combinar Filtros y Bandas");
                $('#loading_body').hide();
                return false;
            }
            else{
              if(check_is_data_already_exist($("#inp_bin_src").val(),$('#src_item_'+i).text()) == 0){
                  //text = add_data_to_table($("#inp_bin_src").val(),$('#src_item_'+i).text(),$('#src_qty_'+i).text(),$('#src_desc_'+i).text(), $('#src_uom_'+i).text());
                  //text =  add_data_to_table2($('#src_item_'+i).text(), loc[0], loc[1], loc[2], loc[3], loc[4],autocomplete);

          item = $('#src_item_'+i).text();
          locc = loc[0]; zone = loc[1]; area=loc[2]; rack=loc[3]; bin=loc[4];

                   // get master barcode & barcode
           text = "";

                   $.ajax({
                       url  : "<?php echo base_url();?>index.php/wms/inbound/transferbin/get_item_sn_by_master_code_and_barcode",
                       type : "post",
                       dataType  : 'html',
                       data : {item:item, loc:locc, zone:zone, area:area, rack:rack, bin:bin},
                       success: function(data){
                           responsedata = $.parseJSON(data);

                           if(responsedata.status == 0){
                               show_error("No Data");
                               return false;
                           }
                           else{
                               for(i=0;i<responsedata.data.length;i++){
                                 text = text + "<tr id='tbl_result_id_"+glb_idx+"'>";
                                   text = text + "<td id='tbl_result_loc_"+glb_idx+"'>"+locc+"</td>";
                                   text = text + "<td id='tbl_result_zone_"+glb_idx+"'>"+zone+"</td>";
                                   text = text + "<td id='tbl_result_area_"+glb_idx+"'>"+area+"</td>";
                                   text = text + "<td id='tbl_result_rack_"+glb_idx+"'>"+rack+"</td>";
                                   text = text + "<td id='tbl_result_bin_"+glb_idx+"'>"+bin+"</td>";
                                   text = text + "<td id='tbl_result_item_"+glb_idx+"'>"+responsedata.data[i].item_code+"</td>";
                                   text = text + "<td id='tbl_result_desc_"+glb_idx+"'>"+responsedata.data[i].name+"</td>";
                                   text = text + "<td id='tbl_result_sn2_"+glb_idx+"'>"+responsedata.data[i].sn2+"</td>";
                                   text = text + "<td id='tbl_result_sn_"+glb_idx+"'>"+responsedata.data[i].serial_number+"</td>";
                                   text = text + "<td id='tbl_result_qty_"+glb_idx+"'>"+responsedata.data[i].qty+"</td>";
                                   text = text + "<td id='tbl_result_uom_"+glb_idx+"'>PZA</td>";
                                   text = text + "<td><input type='text' id='tbl_result_inp_rack_"+glb_idx+"' class='form-control'></td>";
                                   text = text + "<td><button class='btn btn-danger btn-sm' onclick=f_delete_tbl_result("+glb_idx+")>X</button></td>";
                                 text = text + "</tr>";

                                 glb_idx++;
                               }

                                $("#tbl_result tbody").append(text);
                                $('#loading_body').hide();

                                setTimeout(() => {

                                  var loc = $("#location_code").val();
                                  var counter = 0;
                                  var new_autocomplete = [];

                                  for(i=0;i<autocompleteall.length;i++){
                                      wh = autocompleteall[i].split('-');
                                      if(loc == wh[0]){
                                          new_autocomplete[counter] = autocompleteall[i];
                                          counter++;
                                      }
                                  }

                                  var total = 0; // 2023-08-02
                                  for(j=0;j<glb_idx;j++){
                                    $("#tbl_result_inp_rack_"+j).autocomplete({ source: new_autocomplete });
                                    // 2023-08-02
                                    if(check_if_id_exist("#tbl_result_qty_"+j)){
                                        total = total + parseInt($("#tbl_result_qty_"+j).text());
                                    }
                                    //---
                                  }
                                  $("#total_qty").text(total); // 2023-08-02

                                  //--

                                }, "1000")
                           }
                       }
                   })
              }
              else{
                //show_error("Item & Bin already added");
              }
            }



        }
    }

    /*for(i=0;i<glb_idx;i++){
      $("#tbl_result_inp_rack_"+i).autocomplete({ source: autocomplete });
    }*/


    //$("#myModalBinSrc").modal("toggle");
    //$("#inp_bin_src").focus();
    //$("#inp_bin_src").val("");
}
//--

// 2023-10-26
function check_item_type_must_be_same(item_no){
  var tb = $('#tbl_result tbody');
  var row = tb.find("tr").length;
  if(row == 0) return 1;
  else{
    var error = 0;

    banda = "TYP";
    new_item_code = item_no.substring(0,3);

    if(new_item_code == banda) isbanda = 1;
    else isbanda = 0;

    tb.find("tr").each(function(index, element) {
        index = 0;
        $(element).find('td').each(function(index, element) {
            var colVal = $(element).text();
            if(index == 5) item = colVal;
            index++;
        })

        new_item = item.substring(0,3);
        if(isbanda == 1){
            if(new_item != banda){
                error = 1;
            }
        }
        else{
          if(new_item == banda){
              error = 1;
          }
        }

    })
  }

  if(error == 1) return 0; else return 1;
}
//---


</script>
