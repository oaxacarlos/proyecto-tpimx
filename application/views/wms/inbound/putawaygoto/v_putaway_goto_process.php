<style>
  tr{
    font-size: 12px;
  }

</style>

<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      GoTo Put Away Process
</div>

<div class="modal" id="myModalDetail_put" style='font-size:12px;'>
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Put This Items</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="modal_detail_put">
          <div class="container text-center">
            <button class='btn btn-primary' id='btn_start_put'>START</button>
          </div>
      </div>
    </div>
  </div>
</div>

<div class="modal" id="myModalDetail_confirm" style='font-size:12px;'>
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Finish Put This Items</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="modal_detail_confirm">
          <div class="container text-center">
            <button class='btn btn-warning' id='btn_confirm_put'>FINISH</button>
          </div>
      </div>
    </div>
  </div>
</div>

<div class="modal" id="myModalItemDetail" style='font-size:12px;'>
  <div class="modal-dialog modal-lg modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Detail</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="modal_detail_item">
      </div>
    </div>
  </div>
</div>

<div class="container">

<div class='row'>
  <div class='col-8'>
    Doc No
    <input type='text' value='<?php echo $doc_no; ?>' class='form-control' disabled id='inp_doc_no'>
  </div>
</div>

<div class='row' style='margin-bottom:20px;'>
  <div class='col-8'>
    <button class="btn btn-danger btn-sm" id='btn_cancel' style='margin-top:30px;'>Canceled</button>
    <button class="btn btn-success btn-sm" id='btn_all_finished' style='margin-top:30px; margin-left:2px;'>Finished</button>
  </div>
</div>

    <?php

    $i=1; $j=1; $k=0;
    $loc=""; $zone=""; $area=""; $rack=""; $bin="";
    $first = 1;
    foreach($var_putaway_goto_d2 as $row){
        if($row["location_code"]!=$loc || $row["zone_code"]!=$zone || $row["area_code"]!=$area || $row["rack_code"]!=$rack || $row["bin_code"]!=$bin){
            if($first > 1){
                echo "</tbody></table></div></div>"; $j=1; $k++;
            }

            $location = combine_location($row["location_code"], $row["zone_code"], $row["area_code"], $row["rack_code"], $row["bin_code"]);
            echo "<div class='card' style='margin-bottom:20px;'>";
              echo "<div class='card-header text-right' style='font-size:20px; font-weight:bold;'>";
                echo "<span style='margin-right:10px;'><span id='tbl_loc_".$i."'>".$location."</span></span>
                        <span><button class='btn btn-primary btn-sm text-right' id='tbl_btn_start_".$k."' onclick=f_put_show2(".$k.")>START</button></span>
                        <span><button class='btn btn-warning btn-sm text-right' id='tbl_btn_confirm_".$k."' onclick=f_confirm_show2(".$k.") style='display:none;'>CONFIRM</button></span>
                        <button class='btn btn-success btn-sm  text-right'  id='tbl_btn_finish_".$k."' style='display:none;'>FINISHED</button>";
                echo "</div>";
                echo "<div class='card-body'>";
                  echo "<table class='table table-striped'>";
                    echo "<thead>
                        <tr>
                          <th>No</th>
                          <th>Item</th>
                          <th>desc</th>
                          <th>Qty</th>
                          <th>Action</th>
                          <th style='display:none;'>Doc No</th>
                          <th style='display:none;'>Src line no</th>
                          <th style='display:none;'>Src No</th>
                          <th style='display:none;'>line no</th>
                        </tr>
                        </thead><tbody>";
                    echo "<tr>
                            <td>".$j."</td>
                            <td id='item_code_".$i."_".$j."'>".$row["item_code"]."</td>
                            <td>".$row["description"]."</td>
                            <td>".$row["qty"]."</td>";

                    if(is_null($row["completely_put"]) or $row["completely_put"]=="")
                      echo "<td>
                              <button class='btn btn-primary btn-sm' style='display:none;' id='btn_item_".$k.$j."' onclick=f_put_item_show('".$i."','".$j."','".$k."')>START</button>
                              <button class='btn btn-success btn-sm' id='btn_item_finish_".$k.$j."' disabled style='display:none;'>FINISHED</button>
                            </td>";
                    else echo "<td><button class='btn btn-success btn-sm' id='btn_item_finish_".$k.$j."' disabled>FINISHED</button></td>";

                    echo "  <td style='display:none;' id='doc_no_".$i."_".$j."'>".$row["doc_no"]."</td>
                            <td style='display:none;' id='src_line_no_".$i."_".$j."'>".$row["src_line_no"]."</td>
                            <td style='display:none;' id='src_no_".$i."_".$j."'>".$row["src_no"]."</td>
                            <td style='display:none;' id='line_no_".$i."_".$j."'>".$row["line_no"]."</td>
                            <td style='display:none;' id='loc_".$i."_".$j."'>".$row["location_code"]."</td>
                            <td style='display:none;' id='zone_".$i."_".$j."'>".$row["zone_code"]."</td>
                            <td style='display:none;' id='area_".$i."_".$j."'>".$row["area_code"]."</td>
                            <td style='display:none;' id='rack_".$i."_".$j."'>".$row["rack_code"]."</td>
                            <td style='display:none;' id='bin_".$i."_".$j."'>".$row["bin_code"]."</td>";

                    if(is_null($row["completely_put"]) or $row["completely_put"]=="")
                      echo "<td style='display:none;'><input type='hidden' value='0' id='inp_finished_".$k."_".$j."'></td>";
                    else echo "<td style='display:none;'><input type='hidden' value='1' id='inp_finished_".$k."_".$j."'></td>";

                    echo "</tr>";

                $loc  = $row["location_code"];
                $zone = $row["zone_code"];
                $area = $row["area_code"];
                $rack = $row["rack_code"];
                $bin  = $row["bin_code"];

        }
        else{
            echo "<tr>
                    <td>".$j."</td>
                    <td id='item_code_".$i."_".$j."'>".$row["item_code"]."</td>
                    <td>".$row["description"]."</td>
                    <td>".$row["qty"]."</td>";

            if(is_null($row["completely_put"]) or $row["completely_put"]=="")
              echo "<td>
                      <button class='btn btn-primary btn-sm' id='btn_item_".$k.$j."' onclick=f_put_item_show('".$i."','".$j."','".$k."')>START</button>
                      <button class='btn btn-success btn-sm' id='btn_item_finish_".$k.$j."' disabled style='display:none;'>FINISHED</button>
                    </td>";
            else echo "<td><button class='btn btn-success btn-sm' id='btn_item_finish_".$k.$j."' disabled>FINISHED</button></td>";

            echo   "<td style='display:none;' id='doc_no_".$i."_".$j."'>".$row["doc_no"]."</td>
                    <td style='display:none;' id='src_line_no_".$i."_".$j."'>".$row["src_line_no"]."</td>
                    <td style='display:none;' id='src_no_".$i."_".$j."'>".$row["src_no"]."</td>
                    <td style='display:none;' id='line_no_".$i."_".$j."'>".$row["line_no"]."</td>
                    <td style='display:none;' id='loc_".$i."_".$j."'>".$row["location_code"]."</td>
                    <td style='display:none;' id='zone_".$i."_".$j."'>".$row["zone_code"]."</td>
                    <td style='display:none;' id='area_".$i."_".$j."'>".$row["area_code"]."</td>
                    <td style='display:none;' id='rack_".$i."_".$j."'>".$row["rack_code"]."</td>
                    <td style='display:none;' id='bin_".$i."_".$j."'>".$row["bin_code"]."</td>";

            if(is_null($row["completely_put"]) or $row["completely_put"]=="")
              echo "<td style='display:none;'><input type='hidden' value='0' id='inp_finished_".$k."_".$j."'></td>";
            else echo "<td style='display:none;'><input type='hidden' value='1' id='inp_finished_".$k."_".$j."'></td>";

            echo "</tr>";
        }

        $i++; $j++;
        $first++;
    }
    echo "</tbody></table></div></div>";

    echo "<input type='hidden' id='total_k' value='".$k."'>";
    echo "<input type='hidden' id='total_i' value='".$i."'>";
    echo "<input type='hidden' id='total_j' value='".$j."'>";

    ?>

<?php echo loading_body_full(); ?>

<script>

function f_put_show(id){
    $('#inp_qty').val($('#tbl_qty_'+id).text()+" "+$('#tbl_uom_'+id).text());
    $('#inp_item_code').val($('#tbl_item_code_'+id).text());
    $('#inp_desc').val($('#tbl_description_'+id).text());
    $('#inp_loc').val($('#tbl_loc_code_'+id).text());
    $('#inp_zone').val($('#tbl_zone_code_'+id).text());
    $('#inp_area').val($('#tbl_area_code_'+id).text());
    $('#inp_rack').val($('#tbl_rack_code_'+id).text());
    $('#inp_bin').val($('#tbl_bin_code_'+id).text());
    $('#inp_index').val(id);

    $('#myModalDetail_put').modal();
}
//---

function f_confirm_show(id){
    $('#inp_qty').val($('#tbl_qty_'+id).text()+" "+$('#tbl_uom_'+id).text());
    $('#inp_item_code').val($('#tbl_item_code_'+id).text());
    $('#inp_desc').val($('#tbl_description_'+id).text());
    $('#inp_loc').val($('#tbl_loc_code_'+id).text());
    $('#inp_zone').val($('#tbl_zone_code_'+id).text());
    $('#inp_area').val($('#tbl_area_code_'+id).text());
    $('#inp_rack').val($('#tbl_rack_code_'+id).text());
    $('#inp_bin').val($('#tbl_bin_code_'+id).text());
    $('#inp_index').val(id);

    $('#myModalDetail_confirm').modal();
}
//---

$('#btn_start_put').click(function(){
    var id = $('#inp_index').val();
    $('#tbl_start_time_'+id).text(getfulldatetimenow());
    $('#myModalDetail_put').modal('hide');
    $('#tbl_btn_start_'+id).hide();
    $('#tbl_btn_confirm_'+id).show();
})
//--

$('#btn_confirm_put').click(function(){
    var id = $('#inp_index').val();
    $('#tbl_finish_time_'+id).text(getfulldatetimenow());
    $('#myModalDetail_confirm').modal('hide');
    $('#tbl_btn_confirm_'+id).hide();
    $('#tbl_btn_finish_'+id).show();
})
//---

$("#btn_cancel").click(function(){
  swal({
    title: "Are you sure ?",
    html: "Cancel this Process, all your Data would be lost",
    type: "question",
    showCancelButton: true,
    confirmButtonText: "Yes",
    showLoaderOnConfirm: true,
    closeOnConfirm: false
  }).then(function (result) {
        if(result.value){
            window.location.href = "<?php echo base_url();?>index.php/wms/inbound/putaway/goto";
        }
  })
})
//---

$("#btn_all_finished").click(function(){
    if(!check_all_finished2()){
      show_error("You haven't finished the Put Away");
      return false;
    }

    swal({
      title: "Are you sure ?",
      html: "Finish this Put Away",
      type: "question",
      showCancelButton: true,
      confirmButtonText: "Yes",
      showLoaderOnConfirm: true,
      closeOnConfirm: false
    }).then(function (result) {
          if(result.value){
            $("#loading_text").text("Saving your data, Please wait...");
            $('#loading_body').show();

            var h_doc_no = $("#inp_doc_no").val();

              $.ajax({
                  url  : "<?php echo base_url();?>index.php/wms/inbound/putaway/goto_finish2",
                  type : "post",
                  dataType  : 'html',
                  data : {h_doc_no:h_doc_no},
                  success: function(data){
                    var responsedata = $.parseJSON(data);

                    if(responsedata.status == 1){
                          swal({
                             title: responsedata.msg,
                             type: "success", confirmButtonText: "OK",
                          }).then(function(){
                            setTimeout(function(){
                              $('#loading_body').hide();
                              window.location.href = "<?php echo base_url();?>index.php/wms/inbound/putaway/goto";
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


})
//--

function check_all_finished(){
    var total_row = $("#inp_total_row").val();

    var check = 1;
    for(i=1;i<=total_row;i++){
        if($("#tbl_btn_finish_"+i).is(":hidden")){
          check = 0;
          break
        }
    }

    if(check == 1) return true; else return false;

}
//--

function f_put_show2(k){

    var total = 100;
    for(j=1;j<=total;j++){
      if(check_if_id_exist("#btn_item_"+k+j)){
          $("#btn_item_"+k+j).show();
          $("#tbl_btn_start_"+k).hide();
      }
    }
}
//---

function f_put_item_show(i,j,k){
    var item = $("#item_code_"+i+"_"+j).text();
    var doc_no = $("#doc_no_"+i+"_"+j).text();
    var src_no = $("#src_no_"+i+"_"+j).text();
    var src_line_no = $("#src_line_no_"+i+"_"+j).text();
    var line_no = $("#line_no_"+i+"_"+j).text();
    var loc = $("#loc_"+i+"_"+j).text();
    var zone = $("#zone_"+i+"_"+j).text();
    var area = $("#area_"+i+"_"+j).text();
    var rack = $("#rack_"+i+"_"+j).text();
    var bin = $("#bin_"+i+"_"+j).text();

    data = {'item':item, 'doc_no':doc_no, 'src_no':src_no, 'src_line_no':src_line_no,'line_no':line_no,'loc':loc,'zone':zone,'area':area,'rack':rack,'bin':bin,'x':k,'y':j }
    $('#modal_detail_item').html('Loading, Please wait...');
    //open the modal with selected parameter attached
    $('#modal_detail_item').load(
        "<?php echo base_url();?>index.php/wms/inbound/putaway/get_putaway_goto_item_detail",
        data,
        function(responseText, textStatus, XMLHttpRequest) { } // complete callback
    );

    $('#myModalItemDetail').modal();
}
//---

function f_show_hide_btn_item(k,j,hide){
    if(hide == 1){
        $("#btn_item_"+k+j).hide();
        $("#btn_item_finish_"+k+j).show();
        $("#inp_finished_"+k+"_"+j).val("1");
    }
    else{
        $("#btn_item_"+k+j).show();
        $("#btn_item_finish_"+k+j).hide();
        $("#inp_finished_"+k+"_"+j).val("0");
    }
}
//---

function check_all_finished2(){
    var total_k = $("#total_k").val();
    var total_j = $("#total_j").val();

    check = 1;
    for(i=0;i<=total_k;i++){
        for(j=1;j<=total_j;j++){
            if(check_if_id_exist("#inp_finished_"+i+"_"+j)){
                if($("#inp_finished_"+i+"_"+j).val() == 0){
                    check = 0;
                }
            }
        }
    }

    if(check == 1) return true;
    else return false;
}

</script>
