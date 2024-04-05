<script>
$(document).ready(function() {
    $('#DataTable').DataTable();
});
</script>


  <table id="DataTable" class="table table-bordered table-striped table-sm">
      <thead>
        <tr>
          <th>Date</th>
          <th>Customer</th>
          <th>Doc No</th>
          <th>Invoice No</th>
          <th>Qty</th>
          <th>Point</th>
          <th>File</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
          <?php
            $target_file  = $this->config->item('loyalty_client_invc');
            if($var_report != 0){
              foreach($var_report as $row){
                  echo "<tr>";
                    echo "<td>".$row["created_at"]."</td>";
                    echo "<td>".$row["name"]."</td>";
                    echo "<td>".$row["doc_no"]."</td>";
                    echo "<td>".$row['invc_no']."</td>";
                    echo "<td>".$row['qty']."</td>";
                    echo "<td>".$row['points']."</td>";
                    echo "<td><a class='btn btn-danger' href='http://localhost/client/".$target_file.$row["invc_file"]."' target=_blank>FILE</a></td>";
                    echo "<td>";
                      echo "<button class='btn btn-sm btn-outline-primary' onclick=f_show_detail('".$row['doc_no']."')>Detail</button>";
                    echo "</td>";
                  echo "</tr>";
              }
            }
          ?>
      </tbody>
  </table>


<script>

function f_show_detail(id){
  data = {'id':id }
  $('#modal_detail').html('Loading, Please wait...');
  //open the modal with selected parameter attached
  $('#modal_detail').load(
      "<?php echo base_url();?>index.php/sales/loyalty/verified/get_detail_invc_d",
      data,
      function(responseText, textStatus, XMLHttpRequest) { } // complete callback
  );

  $('#myModalDetail').modal();
}
//---

</script>
