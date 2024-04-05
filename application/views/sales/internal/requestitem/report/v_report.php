<script>
$(document).ready(function() {
    $('#DataTable').DataTable({
      dom: 'Bfrtip',
        buttons: [
          {
            extend: 'excel',
            title : 'ItemRequestReport'
          }
        ],
    });
});
</script>

<style>
.modal {
  padding: 0 !important; // override inline padding-right added from js
}
.modal .modal-dialog {
  width: 100%;
  max-width: none;
  height: 100%;
  margin: 0;
}
.modal .modal-content {
  height: 100%;
  border: 0;
  border-radius: 0;
}
.modal .modal-body {
  overflow-y: auto;
}

</style>

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
        <th>Requested By</th>
        <th>Cust No</th>
        <th>Cust Name</th>
        <th>Type</th>
        <th>WHS</th>
        <th>Qty</th>
        <th>Ref Doc</th>
        <th>Doc Status</th>
        <th>Action</th>
        <th>Action 2</th>
      </tr>
    </thead>
    <tbody>
        <?php
          $i=0;
          foreach($var_report as $row){

              $disabled_print_button = "disabled";

              if($row["status1"] == "17"){ $badge_status = "badge-warning"; }
              else if($row["status1"] == "18"){ $badge_status = "badge-info"; }
              else if($row["status1"] == "19"){
                $badge_status = "badge-success";
                $disabled_print_button = "";
              }

              echo "<tr id='row_".$i."'>";
                echo "<td>".$row["doc_datetime"]."</td>";
                echo "<td id='table_doc_no_".$i."'>".$row["doc_no"]."</td>";
                echo "<td>".$row["username"]."</td>";
                echo "<td id='table_cust_no_".$i."'>".$row['cust_no']."</td>";
                echo "<td id='table_cust_name_".$i."'>".$row['cust_name']."</td>";
                echo "<td>".$row['doc_typename']."</td>";
                echo "<td>".$row["doc_location_code"]."</td>";
                echo "<td>".$row["qty_edited"]."</td>";
                echo "<td>".$row["ref_doc"]."</td>";
                echo "<td><span class='badge ".$badge_status."'>".$row["status_name"]."</span></td>";
                echo "<td><button class='btn btn-outline-primary btn-sm' onclick=f_show_detail(".$i.")>DETAIL</button></td>";

                echo "<td><a href='".base_url()."index.php/sales/internal/report/itemrequest_print?docno=".$row["doc_no"]."' class='btn btn-danger btn-sm ".$disabled_print_button."' target='_blank' >PRINT</a></td>";

                echo "<td style='display:none;' id='table_address_".$i."'>".$row['address']."</td>";
                echo "<td style='display:none;' id='table_address2_".$i."'>".$row['address2']."</td>";
                echo "<td style='display:none;' id='table_city_".$i."'>".$row['city']."</td>";
                echo "<td style='display:none;' id='table_contact_".$i."'>".$row['contact']."</td>";
                echo "<td style='display:none;' id='table_country_region_code_".$i."'>".$row['country_region_code']."</td>";
                echo "<td style='display:none;' id='table_post_code_".$i."'>".$row['post_code']."</td>";
                echo "<td style='display:none;' id='table_county_".$i."'>".$row['county']."</td>";
                echo "<td style='display:none;' id='table_status_".$i."'>".$row['status1']."</td>";
              echo "</tr>";
              $i++;
          }
        ?>
    </tbody>
</table>

<script>

function f_show_detail(i){

  doc_no = $("#table_doc_no_"+i).text();
  cust_no = $("#table_cust_no_"+i).text();
  cust_name = $("#table_cust_name_"+i).text();
  address = $("#table_address_"+i).text();
  address2 = $("#table_address2_"+i).text();
  city = $("#table_city_"+i).text();
  contact = $("#table_contact_"+i).text();
  country_region_code = $("#table_country_region_code_"+i).text();
  post_code = $("#table_post_code_"+i).text();
  county = $("#table_county_"+i).text();
  status = $("#table_status_"+i).text();

  data = {'doc_no':doc_no, 'cust_no':cust_no, 'cust_name':cust_name, 'address':address, 'address2':address2, 'city':city, 'contact':contact, 'country_region_code':country_region_code,'post_code':post_code,'county':county, 'status':status,'idx_row':i }
  $('#modal_detail').html('Loading, Please wait...');
  //open the modal with selected parameter attached
  $('#modal_detail').load(
      "<?php echo base_url();?>index.php/sales/internal/report/itemrequest_detail",
      data,
      function(responseText, textStatus, XMLHttpRequest) { } // complete callback
  );

  $('#myModalDetail').modal();


}
//---

</script>
