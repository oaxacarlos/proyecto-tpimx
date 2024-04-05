<script>
$(document).ready(function() {
    //$('#DataTable').DataTable();
});
</script>

<table id="DataTable" class="table table-bordered table-striped table-sm">
  <thead>
    <tr>
      <th>No</th>
      <th>Item No</th>
      <th>Desc</th>
      <th>Qty</th>
      <th>Received</th>
      <th>Remaining</th>
      <th>Uom</th>
      <th>Value</th>
      <th>Master<br>Barcode</th>
      <th>Process</th>
    </tr>
  </thead>
  <tbody>
    <?php
        $no=1;
        $qty_total=0;
        $qty_rem_total = 0;
        foreach($var_receiving_detail as $row){
            $qty_process = $row['qty'] - $row['qty_received'];
            $qty_rem_total += $qty_process;
            $qty_total += $row['qty'];
            echo "<tr>";
              echo "<td style='width:50px;'>".$no."</td>";
              echo "<td id='item_code_".$no."'>".$row['item_code']."</td>";
              echo "<td id='desc_".$no."'>".$row['description']."</td>";
              echo "<td id='max_qty_".$no."'>".$row['qty']."</td>";
              echo "<td id='qty_received_".$no."'>".$row['qty_received']."</td>";
              echo "<td id='qty_rem_".$no."'>".($row['qty']-$row['qty_received'])."</td>";
              echo "<td id='uom_".$no."'>".$row['uom']."</td>";
              echo "<td id='valuee_per_pcs_".$no."'>".$row['valuee_per_pcs']."</td>";
              echo "<td id='master_barcode_".$no."'>".$row['master_barcode']."</td>";
              echo "<td id='line_".$no."' style='display:none;'>".$row['line_no']."</td>";
              echo "<td id='location_code_".$no."' style='display:none;'>".$row['src_location_code']."</td>";
              echo "<td id='doc_no_".$no."' style='display:none;'>".$row['doc_no']."</td>";

              if($qty_process == 0){
                echo "<td style='width:100px;'><input class='form-control' size='1' type='text' id='process_".$no."' value='-' disabled></td>";
              }
              else{
                echo "<td style='width:100px;'><input class='form-control' size='1' type='text' id='process_".$no."' value='".$qty_process."' onkeyup=f_max_input(".$row['qty'].",".$row['qty_received'].",".$row['line_no'].") onkeypress='return isNumberKey(event)'></td>";
              }

            echo "</tr>";
            $no++;
        }
    ?>

  </tbody>
</table>

<div class='text-right'>
  <button class="btn btn-primary text-right" id="btn_process_received">PROCESS</button>
</div>

<!-- hidden -->
<input type='hidden' id='total_qty' name='total_qty' value='<?php echo $qty_total;  ?>'>
<input type='hidden' id='total_rem_qty' name='total_rem_qty' value='<?php echo $qty_rem_total;  ?>'>
<input type='hidden' id='total_row' name='total_row' value='<?php echo $no;  ?>'>
<input type='hidden' id='doc_no_h' name='doc_no_h' value='<?php echo $doc_no_h;  ?>'>
<input type='hidden' id='loc_code_h' name='loc_code_h' value='<?php echo $loc_code_h;  ?>'>
<input type='hidden' id='from_wh' name='from_wh' value='<?php echo $from_wh;  ?>'>
<input type='hidden' id='transfer_from_wh' name='transfer_from_wh' value='<?php echo $transfer_from_wh;  ?>'>
<!--**-->

<script>

function f_max_input(qty_max,qty_received,id){
    var max = parseFloat(qty_max);
    var received = parseFloat(qty_received);
    var remaining = max - received;
    var proces = parseFloat($('#process_'+id).val());

    if(proces > remaining){
        $('#process_'+id).val(remaining);
    }
}
//---

$('#btn_process_received').click(function(){

    var total_qty = parseFloat($('#total_qty').val());
    var total_row = parseFloat($('#total_row').val());
    var total_rem_qty = parseFloat($('#total_rem_qty').val());

    // check if anyfield is blank
    var field_blank = f_check_anyfield_blank('process_',1,total_row);
    if(field_blank != false){
        var msg = "You didn't fill Qty on No = "+field_blank;
        show_error(msg);
        return false;
    }

    // check if Process qty not greater than remaining
    var check_qty_process_rem = f_check_qty_process_less_than_qty_rem(total_row);
    if(check_qty_process_rem != "true"){
      show_error("Qty Process not allow greater then Qty Remaining at line "+check_qty_process_rem);
      $('#process_'+check_qty_process_rem).val($('#qty_rem_'+check_qty_process_rem).text());
      return false;
    }

    var all_qty_received = f_check_not_all_qty_received('process_',1,total_row,total_qty); // check if all qty received
    var status = f_status_received(all_qty_received); // get status

    // get parameter
    var doc_no_h = $('#doc_no_h').val();
    var loc_code_h = $('#loc_code_h').val();
    var from_wh = $('#from_wh').val();
    var transfer_from_wh = $('#transfer_from_wh').val();
    var item_code = [];
    var qty_process = [];
    var desc = [];
    var location_code = [];
    var line_no = [];
    var doc_no = [];
    var uom = [];
    var master_barcode = [];
    var counter = 0;
    var valuee_per_pcs = []; // valuee 2023-01-30

    for(i=1;i<total_row;i++){
        if(convert_number($('#process_'+i).val()) > 0 ){
          item_code[counter] = $('#item_code_'+i).text();
          qty_process[counter] = $('#process_'+i).val();
          desc[counter] = $('#desc_'+i).text();
          line_no[counter] = $('#line_'+i).text();
          location_code[counter] = $('#location_code_'+i).text();
          doc_no[counter] = $('#doc_no_'+i).text();
          uom[counter] = $('#uom_'+i).text();
          master_barcode[counter] = $('#master_barcode_'+i).text(); // master barcode 2023-01-17
          valuee_per_pcs[counter] = $('#valuee_per_pcs_'+i).text(); // valuee 2023-01-30
          counter++;
        }
    }

    if(counter > 0)
      f_process_received(item_code, qty_process, desc, line_no, location_code, doc_no,1,total_row, doc_no_h, loc_code_h, uom,total_qty, total_rem_qty, master_barcode, valuee_per_pcs, from_wh, transfer_from_wh); // master barcode 2023-01-17
    else{
        show_error("You haven't put Qty to process!!");
    }

});
//---

function f_check_anyfield_blank(id,from,total_row){
    for(i=from;i<=total_row;i++){ if($('#'+id+i).val()=='' || $('#'+id+i).val()=='-') return i; }
    return false;
}
//---

function f_check_not_all_qty_received(id,from,total_row,total_qty){
    var qty = 0;
    for(i=from;i<=total_row;i++){ qty = qty + parseFloat($('#'+id+i).val());}

    if(total_qty == qty) return true; else return false;
}
//---

function f_status_received(result){
    if(result) status="2";
    else status = "1";
}
//---

function f_process_received(item_code, qty_process, desc, line_no, location_code, doc_no,from,total_row, doc_no_h, loc_code_h, uom,total_qty, total_rem_qty,master_barcode, valuee_per_pcs, from_wh, transfer_from_wh){ // master barcode 2023-01-17

  swal({
    input: 'textarea',
    inputPlaceholder: 'Type your message here',
    showCancelButton: true,
    confirmButtonText: 'OK'
  }).then(function (result) {

    if(result.dismiss == "cancel"){}
    else{
      if(result.value == ""){
          show_error("You have to type message");
      }
      else{
          var message = result.value;

          swal({
            title: "Are you sure ?",
            html: "Received this Items",
            type: "question",
            showCancelButton: true,
            confirmButtonText: "Yes",
            showLoaderOnConfirm: true,
            closeOnConfirm: false
          }).then(function (result) {
                if(result.value){
                    $("#loading_text").text("Receiving process, Please wait...");
                    $('#loading_body').show();

                    // master barcode 2023-01-17
                    $.ajax({
                        url  : "<?php echo base_url();?>index.php/wms/inbound/receiving/received_process",
                        type : "post",
                        dataType  : 'html',
                        data : {item_code:JSON.stringify(item_code),qty_process:JSON.stringify(qty_process),desc:JSON.stringify(desc), line_no:JSON.stringify(line_no), location_code:JSON.stringify(location_code), doc_no:JSON.stringify(doc_no),doc_no_h:doc_no_h, loc_code_h:loc_code_h, uom:JSON.stringify(uom),master_barcode:JSON.stringify(master_barcode),total_qty:total_qty,total_rem_qty:total_rem_qty, message:message,valuee_per_pcs:JSON.stringify(valuee_per_pcs),from_wh:from_wh, transfer_from_wh:transfer_from_wh},
                        success: function(data){
                          var responsedata = $.parseJSON(data);

                          if(responsedata.status == 1){
                                swal({
                                   title: responsedata.msg,
                                   type: "success", confirmButtonText: "OK",
                                }).then(function(){
                                  setTimeout(function(){
                                    $('#loading_body').hide();
                                    $('#myModalDetailReceived').modal('toggle');
                                    f_refresh();
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


}


//---
function f_check_qty_process_less_than_qty_rem(total_row){
    var check = 1;
    var line = "true";
    for(i=1;i<=total_row;i++){
        qty_process = parseFloat($('#process_'+i).val());
        qty_rem = parseFloat($('#qty_rem_'+i).text());
        if(qty_process > qty_rem){
            check = 0;
            line = i;
            break;
        }
    }

    return line;
}
//---

</script>
