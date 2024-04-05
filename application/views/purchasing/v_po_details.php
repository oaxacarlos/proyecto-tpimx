<style>
    a {
  position: relative;
  cursor: pointer;
}

a .ico {
  position: absolute;
  float: rigth;
  display: none;
}

a:hover .ico {
  display: inline-block;
  left: 30px;
  top: 26px;
  
}
</style>    

<script>
$(document).ready(function() {
    //$('#DataTable').DataTable();
});
</script>

<table id="DataTable" class="table table-bordered table-striped table-sm">
  <thead>
    <tr>
      <th>No</th>
      <th>Loc</th>
      <th>Item</th>
      <th>Description</th>
      <th>Qty</th>
      <th>Uom</th>
      <th>Img</th>
      <th>Link</th>
      <th>Remarks</th>
    </tr>
  </thead>
  <tbody>
    <?php
    
        $no=1;
        $qty_total=0;
        $qty_rem_total = 0;
        echo "<div class='mb-2'><span class='badge badge-primary'> Delivery To: ".strtoupper($var_d_approval[0]['delivery_to'])."</span></div>";
        foreach($var_d_approval as $row){
            $qty_total += $row['qty'];
            $local_link="uploads/purchasing_folder/img/";
            $link = $local_link.$row['request_img'];
            $link1 = base_url().$link;
            $link2 = base_url().$local_link."empty.jpg";
            echo "<tr>";
              echo "<td style='width:50px;'>".$no."</td>";
              echo "<td id='src_loc_".$no."'>".$row['src_loc']."</td>";
              echo "<td id='item_code_".$no."'>".$row['item_code']."</td>";
              echo "<td id='description_".$no."'>".$row['description']."</td>";
              echo "<td id='qty".$no."'>".$row['qty']."</td>";
              echo "<td id='uom_".$no."'>".$row['uom']."</td>";
              if ( is_file($link)) echo "<td id='request_img_".$no."'><a>🖼️<img class='ico' src='".$link1."' width='250'/><a/></td>";
              else echo "<td id='request_img_".$no."'><a>🖼️<img class='ico' src='".$link2."' width='250'/><a/></td>";
              echo "<td id='request_link_".$no."'><a href='".$row['request_link']."' target='_blank'>LINK</a></td>";
              echo "<td id='remarks_".$no."'>".$row['remarks']."</td>";
            echo "</tr>";
            $no++;
        }
    ?>

  </tbody>
</table>
<?php
if ($id_statuss[0]=='1')
echo "<div class=''><button class='btn btn-primary btn-lg btn-block' onclick=f_po_approval_a('".$doc_no."')>Approve</botton></div>";
else if ($id_statuss[0]=='2')
echo "<div class=''><button class='btn btn-info btn-lg btn-block' onclick=f_po_process('".$doc_no."')>PROCESS</botton></div>";
?>
<script>
  function f_po_approval_a(doc_no){
    swal({
            title: "Are you sure ?",
            html: "Approve this Purchase Order",
            type: "question",
            showCancelButton: true,
            confirmButtonText: "Yes",
            showLoaderOnConfirm: true,
            closeOnConfirm: false
          }).then(function (result) {
    $.ajax({
      url  : "<?php echo base_url();?>index.php/purchasing/po_approval/update_approval_po",
      type : "post",
      dataType  : 'html',
      async: false,
      data : {doc_no:doc_no},
      success: function(data){
        var responsedata = $.parseJSON(data);
        if (responsedata.status == 1) {
          swal({
            title: responsedata.msg,
            type: "success", confirmButtonText: "OK",
          }).then(function () {
            setTimeout(function () {
              $('#loading_body').hide();
              window.location.href = "<?php echo base_url(); ?>index.php/purchasing/po_approval";
            }, 100)
          });
        
        }
      }     
  })
  })
}


      </script>