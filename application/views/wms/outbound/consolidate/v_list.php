<script>
$(document).ready(function() {
    $('#DataTable').DataTable({
      "order": [[ 1, "desc" ]]
    });
});
</script>

<style>
tr{
  font-size:12px;
}
</style>

<?php echo loading_body_full(); ?>

<div class="modal" id="myModalDetail">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Detail</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="modal_detail"></div>
    </div>
  </div>
</div>

<table id="DataTable" class="table table-bordered table-striped table-sm">
  <thead>
    <tr>
      <th>Date</th>
      <th>Doc No</th>
      <th>Pack</th>
      <th>Message</th>
      <th>Created User</th>
      <th>Dest</th>
      <th>Name</th>
      <th>Addr</th>
      <th>Addr2</th>
      <th>City</th>
      <th>Contact</th>
      <th>Post Code</th>
      <th>County</th>
      <th>Country</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php
        foreach($var_console as $row){
            echo "<tr id='row_".$row['doc_no']."'>";
              echo "<td>".$row['doc_date']."</td>";
              echo "<td>".$row['doc_no']."</td>";
              echo "<td>".$row['pack']."</td>";
              echo "<td>".$row['text1']."</td>";
              echo "<td>".$row['name']."</td>";
              echo "<td>".$row['dest_no']."</td>";
              echo "<td>".$row['dest_name']."</td>";
              echo "<td>".$row['dest_addr']."</td>";
              echo "<td>".$row['dest_addr2']."</td>";
              echo "<td>".$row['dest_city']."</td>";
              echo "<td>".$row['dest_contact']."</td>";
              echo "<td>".$row['dest_post_code']."</td>";
              echo "<td>".$row['dest_county']."</td>";
              echo "<td>".$row['dest_country']."</td>";
              echo "<td><button class='btn btn-sm btn-outline-primary' onclick=f_show_detail('".$row['doc_no']."')>Detail</button></td>";
            echo "</tr>";
        }
    ?>
  </tbody>
</table>

<script>

function f_show_detail(doc_no){
  data = {'doc_no':doc_no}

  $('#modal_detail').html('Loading, Please wait...');
  //open the modal with selected parameter attached
  $('#modal_detail').load(
      "<?php echo base_url();?>index.php/wms/outbound/consolidate/detail",
      data,
      function(responseText, textStatus, XMLHttpRequest) { } // complete callback
  );

  $('#myModalDetail').modal();
}
//---

</script>
