<style>
tr{
  font-size: 12px;
}

textarea {
  resize: none;
}
</style>

<script>
$(document).ready(function() {
    $("#buy_date").datetimepicker({
       timepicker: false,
       format : 'Y-m-d'
    });

    $("#sent_date").datetimepicker({
       timepicker: false,
       format : 'Y-m-d'
    });

    $("#delivered_date").datetimepicker({
       timepicker: false,
       format : 'Y-m-d'
    });

    $.datetimepicker.setLocale('en');
});
</script>

<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      Redeem Process
</div>

<div class="container-fluid">
  <table class="table table-bordered table-sm table-striped">
      <thead>
        <tr>
          <th>Date</th>
          <th>Doc No</th>
          <th>Name</th>
          <th>Email</th>
          <th>Redeem</th>
          <th>Lastest<br>Point</th>
          <th>Remain<br>Point</th>
          <th>Product</th>
          <th>Qty</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $colspan = 9;
          if($var_data == 0){
              echo "<tr><td colspan='".$colspan."'>No Data</td></tr>";
          }
          else{
            foreach($var_data as $row){
                echo "<tr>";
                  echo "<td>".$row["created_at"]."</td>";
                  echo "<td>".$row["doc_no"]."</td>";
                  echo "<td>".$row["name"]."</td>";
                  echo "<td>".$row["email"]."</td>";
                  echo "<td>".$row["point_redeem"]."</td>";
                  echo "<td>".$row["lastest_point"]."</td>";
                  echo "<td>".$row["remain_point"]."</td>";
                  echo "<td>".$row["product_name"]."</td>";
                  echo "<td>".$row["qty"]."</td>";
                echo "</tr>";

                // buy
                if(is_null($row["buy_date"]) or $row["buy_date"]=="")
                  $button_buy = "<button class='btn btn-primary btn-sm' id='btn_table_buy_".$row["doc_no"]."' onclick=f_buy('".$row["doc_no"]."') style='width:100px;'>BOUGHT</button>";
                else $button_buy = $row["buy_date"];

                // sent
                if(is_null($row["sent_date"]) or $row["sent_date"]==""){
                  if(is_null($row["buy_date"]) or $row["buy_date"]=="") $disabled = "disabled";
                  else $disabled = "";

                  $button_sent = "<button class='btn btn-warning btn-sm' ".$disabled." id='btn_table_sent_".$row["doc_no"]."' onclick=f_sent('".$row["doc_no"]."') style='width:100px;'>SENT</button>";
                }
                else $button_sent = $row["sent_date"];

                // delveried
                if(is_null($row["delivered_date"]) or $row["delivered_date"]==""){
                  if(is_null($row["sent_date"]) or $row["sent_date"]=="") $disabled = "disabled";
                  else $disabled = "";

                  $button_delivered = "<button class='btn btn-success btn-sm' ".$disabled." id='btn_table_delivered_".$row["doc_no"]."' onclick=f_delivered('".$row["doc_no"]."') style='width:100px;'>DELIVERED</button>";
                }
                else $button_delivered = $row["delivered_at"];

                echo "<tr><td colspan='".$colspan."'>";
                  echo "<table class='table' style='width:30%;'>";
                    echo "<tr><th>Bought</th><td id='buy_".$row["doc_no"]."'>".$button_buy."</td>";
                    echo "<th>Sent</th><td id='sent_".$row["doc_no"]."'>".$button_sent."</td>";
                    echo "<th>Delivered</th><td id='delivered_".$row["doc_no"]."'>".$button_delivered."</td></tr>";
                  echo "</table>";
                echo "</td>";

                echo "<tr><td colspan='".$colspan."'>";
                  echo "<table class='table'>";
                    echo "<tr>
                      <td><b>Contact</b> : ".$row["addr_contact"]."</td>
                      <td><b>Phone</b> : ".$row["addr_phone"]."</td>
                      <td><b>Address</b> : ".$row["addr_add"]."</td>
                      <td><b>Address 2</b> : ".$row["addr_add2"]."</td>
                      <td><b>Colonia</b> : ".$row["addr_colonia"]."</td>
                      <td><b>Ciudad</b> : ".$row["addr_ciudad"]."</td>
                      <td><b>Estado</b> : ".$row["addr_estado"]."</td>
                      <td><b>Post Code</b> : ".$row["addr_postcode"]."</td>
                      <td><b>Country</b> : ".$row["addr_country"]."</td>
                    </tr>";
                  echo "</table>";
                echo "</td></tr>";

                echo "</tr>";

                echo "<tr><td colspan='".$colspan."' class='table-info'></td></tr>";

            }
          }



        ?>
      </tbody>
  </table>
</div>

<div class="modal" id="myModalBuy">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">BOUGHT</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="modal_detail_buy">
        <table class="table">
          <tr><td>Doc No</td><td id='buy_doc_no'></td></tr>
          <tr><td>Bought Date</td><td><input type="text" id="buy_date" class="form-control" readonly style="background-color:white;"></td></tr>
          <tr><td>Remark</td><td><textarea rows='5' cols='50' id='buy_remark'></textarea></td></tr>
          <tr><td></td><td><button class='btn btn-primary' id='btn_process_buy'>PROCESS</button></td></tr>
        </table>
      </div>
    </div>
  </div>
</div>

<div class="modal" id="myModalSent">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">SENT</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="modal_detail_sent">
        <table class="table">
          <tr><td>Doc No</td><td id='sent_doc_no'></td></tr>
          <tr><td>Sent Date</td><td><input type="text" id="sent_date" class="form-control" readonly style="background-color:white;"></td></tr>
          <tr><td>Remark</td><td><textarea rows='5' cols='50' id='sent_remark'></textarea></td></tr>
          <tr><td></td><td><button class='btn btn-primary' id='btn_process_sent'>PROCESS</button></td></tr>
        </table>
      </div>
    </div>
  </div>
</div>

<div class="modal" id="myModalDelivered">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">DELIVERED</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="modal_detail_delivered">
        <table class="table">
          <tr><td>Doc No</td><td id='delivered_doc_no'></td></tr>
          <tr><td>Delivered Date</td><td><input type="text" id="delivered_date" class="form-control" readonly style="background-color:white;"></td></tr>
          <tr><td></td><td><button class='btn btn-primary' id='btn_process_delivered'>PROCESS</button></td></tr>
        </table>
      </div>
    </div>
  </div>
</div>

<?php echo loading_body_full(); ?>

<script>

function f_buy(doc_no){
    $("#buy_doc_no").text(doc_no);
    $("#buy_date").val('');
    $("#buy_remark").val('');
    $('#myModalBuy').modal();
}
//---

function f_sent(doc_no){
    $("#sent_doc_no").text(doc_no);
    $("#sent_date").val('');
    $("#sent_remark").val('');
    $('#myModalSent').modal();
}
//---

function f_delivered(doc_no){
    $("#delivered_doc_no").text(doc_no);
    $("#delivered_date").val('');
    $('#myModalDelivered').modal();
}
//---

$("#btn_process_buy").click(function(){

    if($("#buy_date").val() == ""){
        show_error("You must input the DATE");
        return false;
    }

    if($("#buy_remark").val() == ""){
        show_error("You must input the REMARK");
        return false;
    }

    // process
    var date = $("#buy_date").val();
    var remark = $("#buy_remark").val();
    var doc_no = $("#buy_doc_no").text();

    $.ajax({
        url  : "<?php echo base_url();?>index.php/sales/loyalty/redeem/update_buy",
        type : "post",
        dataType  : 'html',
        data : { date:date, doc_no:doc_no, remark:remark },
        success: function(data){
            var responsedata = $.parseJSON(data);
            if(responsedata.status == 1){
                  swal({
                     title: responsedata.msg,
                     type: "success", confirmButtonText: "OK",
                  }).then(function(){
                    setTimeout(function(){
                      $('#loading_body').hide();
                      $("#buy_"+doc_no).text(responsedata.date);
                      $("#btn_table_sent_"+doc_no).removeAttr("disabled");
                      $('#myModalBuy').modal('hide');
                    },100)
                  });
            }
            else if(responsedata.status == 0){
                Swal('Error!',responsedata.msg,'error');
                $('#loading_body').hide();
            }
        }
    })

});
//---

$("#btn_process_sent").click(function(){

    if($("#sent_date").val() == ""){
        show_error("You must input the DATE");
        return false;
    }

    if($("#sent_remark").val() == ""){
        show_error("You must input the REMARK");
        return false;
    }

    // process
    var date = $("#sent_date").val();
    var remark = $("#sent_remark").val();
    var doc_no = $("#sent_doc_no").text();

    $.ajax({
        url  : "<?php echo base_url();?>index.php/sales/loyalty/redeem/update_sent",
        type : "post",
        dataType  : 'html',
        data : { date:date, doc_no:doc_no, remark:remark },
        success: function(data){
            var responsedata = $.parseJSON(data);
            if(responsedata.status == 1){
                  swal({
                     title: responsedata.msg,
                     type: "success", confirmButtonText: "OK",
                  }).then(function(){
                    setTimeout(function(){
                      $('#loading_body').hide();
                      $("#sent_"+doc_no).text(responsedata.date);
                      $("#btn_table_delivered_"+doc_no).removeAttr("disabled");
                      $('#myModalSent').modal('hide');
                    },100)
                  });
            }
            else if(responsedata.status == 0){
                Swal('Error!',responsedata.msg,'error');
                $('#loading_body').hide();
            }
        }
    })

});
//---

$("#btn_process_delivered").click(function(){

    if($("#delivered_date").val() == ""){
        show_error("You must input the DATE");
        return false;
    }

    // process
    var date = $("#delivered_date").val();
    var doc_no = $("#delivered_doc_no").text();

    $.ajax({
        url  : "<?php echo base_url();?>index.php/sales/loyalty/redeem/update_delivered",
        type : "post",
        dataType  : 'html',
        data : { date:date, doc_no:doc_no},
        success: function(data){
            var responsedata = $.parseJSON(data);
            if(responsedata.status == 1){
                  swal({
                     title: responsedata.msg,
                     type: "success", confirmButtonText: "OK",
                  }).then(function(){
                    setTimeout(function(){
                      $('#loading_body').hide();
                      $("#delivered_"+doc_no).text(responsedata.date);
                      $('#myModalDelivered').modal('hide');
                    },100)
                  });
            }
            else if(responsedata.status == 0){
                Swal('Error!',responsedata.msg,'error');
                $('#loading_body').hide();
            }
        }
    })

});

</script>
