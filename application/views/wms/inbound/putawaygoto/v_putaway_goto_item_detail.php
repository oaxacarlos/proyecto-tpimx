<div class="container-fluid" style="font-size:20px;">
  <?php echo combine_location($loc,$zone,$area,$rack,$bin);

  ?>
</div>

<table class="table table-bordered table-striped">
    <thead>
      <th>Item</th>
      <th>Master Barcode</th>
      <th>Barcode</th>
      <th>Qty</th>
    </thead>
    <tbody>
      <?php
        $total_qty = 0;
        foreach($var_putaway_d3 as $row){
            echo "<tr>";
              echo "<td>".$row["item_code"]."</td>";
              echo "<td>".$row["sn2_put"]."</td>";

              if($row["qty"] > 1){ echo "<td>-</td>"; }
              else{ echo "<td>".$row["serial_number_put"]."</td>"; }

              echo "<td>".$row["qty"]."</td>";
            echo "</tr>";

            $total_qty += $row["qty"];
        }

        echo "<tr><th colspan='3'>Total</th><th>".$total_qty."</th></tr>";
      ?>

    </tbody>
</table>

<div class="container-fluid text-right">

  <?php echo "<button class='btn btn-success'
  onclick=f_item_put_finish('".$item_code."','".$doc_no."','".$src_no."','".$src_line_no."','".$loc."','".$zone."','".$area."','".$rack."','".$bin."')>FINISH</button>"; ?>

</div>

<?php echo loading_body_full(); ?>

<script>


  function f_item_put_finish(item,doc_no,src_no,src_line_no,loc,zone,area,rack,bin){

      swal({
        title: "Are you sure ?",
        type: "question",
        showCancelButton: true,
        confirmButtonText: "Yes",
        showLoaderOnConfirm: true,
        closeOnConfirm: false
      }).then(function (result) {
            if(result.value){
                $.ajax({
                    url       : "<?php echo base_url();?>index.php/wms/inbound/putaway/update_put_item",
                    type      : 'post',
                    dataType  : 'html',
                    data      :  { item:item, doc_no:doc_no, src_no:src_no, src_line_no:src_line_no, loc:loc, zone:zone, area:area, rack:rack, bin:bin },
                    success   :  function(data){
                        var responsedata = $.parseJSON(data);

                        if(responsedata.status == 1){
                              swal({
                                 title: responsedata.msg,
                                 type: "success", confirmButtonText: "OK",
                              }).then(function(){
                                setTimeout(function(){
                                  $("#myModalItemDetail").modal("toggle");
                                  f_show_hide_btn_item('<?php echo $x; ?>','<?php echo $y; ?>',1);
                                },100)
                              });
                        }
                        else if(responsedata.status == 0){
                            Swal('Error!',responsedata.msg,'error');
                        }
                    }
                });
            }
      })
  }

</script>
