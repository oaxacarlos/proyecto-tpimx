<script>
$(document).ready(function() {
    $('#DataTable').DataTable({
        pageLength:25,
        order: [[1, 'desc']]
    });
});
</script>

<style>
  tr{
    font-size: 14px;
  }
</style>

<table class="table table-bordered table-striped table-sm" id="DataTable">
  <thead>
    <tr>
      <th>Type</th>
      <th>Created at</th>
      <th>Created by</th>
      <th>Doc No</th>
      <th>To</th>
      <th>CC</th>
      <th>Subject</th>
      <th>From</th>
      <th>From Info</th>
      <th>Message</th>
      <th>Sent</th>
      <th>Sent at</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php
      foreach($var_report as $row){

          if($row["email_type"] == 1){
            $email_type_text = "WHS Receipt";
            $type_color = "warning";
          }
          else if($row["email_type"] == 2){
            $email_type_text = "WHS Shipment";
            $type_color = "success";
          }
          else if($row["email_type"] == 3){
            $email_type_text = "Edit Doc by Picker";
            $type_color = "info";
          }
          else if($row["email_type"] == 4){
            $email_type_text = "Edit to Operacion";
            $type_color = "primary";
          }
          else if($row["email_type"] == 5){
            $email_type_text = "Edit by User";
            $type_color = "secondary";
          }

          if($row["sent"] == 1){
              $icon_sent = "<i class='bi bi-check-lg' style='font-size:27px; font-weight:bold; color:green;'></i>";
              $button_disabled = "";
          }
          else{
              $icon_sent = "<i class='bi bi-x-lg' style='font-size:25px; font-weight:bold; color:red;'></i>";
              $button_disabled = "disabled";
          }

          echo "<tr>";
            echo "<td><span class='badge badge-".$type_color."' style='font-size:12px;'>".$email_type_text."</span></td>";
            echo "<td>".$row["added_at"]."</td>";
            echo "<td>".$row["name"]."</td>";
            echo "<td>".$row["doc_no"]."</td>";
            echo "<td>".$row["to"]."</td>";
            echo "<td>".$row["cc"]."</td>";
            echo "<td>".$row["subject"]."</td>";
            echo "<td>".$row["from"]."</td>";
            echo "<td>".$row["from_info"]."</td>";
            echo "<td>".$row["message"]."</td>";
            echo "<td id='td_icon_sent_".$row["id"]."'>".$icon_sent."</td>";
            echo "<td>".$row["sent_at"]."</td>";
            echo "<td><button class='btn btn-warning btn-sm' ".$button_disabled." onclick=f_unsent('".$row['id']."') id='btn_unsent_".$row["id"]."'>Update to UnSent</button></td>";
          echo "</tr>";
      }
    ?>
  </tbody>
</table>

<script>

function f_unsent(id){
  $.ajax({
      url  : "<?php echo base_url();?>index.php/admin/admin_emailnotif/update_to_unsent",
      type : "post",
      dataType  : 'html',
      data : {id: id},
      success: function(data){
          var responsedata = $.parseJSON(data);

          if(responsedata.status == 1){
              icon = "<i class='bi bi-x-lg' style='font-size:25px; font-weight:bold; color:red;'></i>";
              $("#td_icon_sent_"+id).html(icon);
              $("#btn_unsent_"+id).attr("disabled",true);
              toast_message_success("Document has been changed to UnSent");
          }
      }
  })
}

</script>
