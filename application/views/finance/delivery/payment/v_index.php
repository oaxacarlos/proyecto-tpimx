<script>
$(document).ready(function() {
    $('#DataTable').DataTable({
      "paging": false,
      dom: 'Bfrtip',
        buttons: [
          {
            extend: 'excel',
            title : 'TPM-DeliveryPayment'
          }
        ],
    });
});
</script>

<style>
  tr{
    font-size:12px;
  }
</style>

<div class="container-fluid" style="margin-top:20px;">
  <button class="btn btn-warning btn-sm" id="btn_check_all">Check All</button>
  <button class="btn btn-danger btn-sm" id="btn_uncheck_all" style="margin-left:10px;">UnCheck All</button>
  <button class="btn btn-success btn-sm" style="margin-left:10px;" onclick=tableToCSV()>Download Template</button>
</div>

<div style="margin-top:30px;">
<table class="table table-sm table-striped" id="DataTable">
  <thead>
    <tr>
      <th></th>
      <th>Doc Date</th>
      <th>Doc No</th>
      <th>Sending Date</th>
      <th>Destination</th>
      <th>State</th>
      <th>Driver</th>
      <th>Vendor No</th>
      <th>Vendor Name</th>
      <th>Tracking No</th>
      <th>Folio</th>
      <th>Domicili</th>
      <th>Payment Term</th>
      <th>Delivery Status</th>
      <th>SubTotal</th>
      <th>Total</th>
      <th>Remarks</th>
      <th>Box</th>
      <th>Pallet</th>
      <th>Action</th>
      <th class="table-dark" style="display:none;"></th>
      <th style="display:none;">Invoice Vendor No</th>
      <th style="display:none;">Invoice Vendor Date</th>
      <th style="display:none;">Payment Date</th>
      <th style="display:none;">UUID</th>
      <th style="display:none;">Remarks</th>
    </tr>
  </thead>
  <tbody>
    <?php
      $i=0;
      foreach($var_data as $row){
          echo "<tr>";
            echo "<td><input type='checkbox' id='no_".$i."' name='no_".$i."'></td>";
            echo "<td>".$row["doc_date"]."</td>";
            echo "<td>".$row["doc_no"]."</td>";
            echo "<td>".$row["delv_date"]."</td>";
            echo "<td>".$row["destination"]."</td>";
            echo "<td>".$row["state"]."</td>";
            echo "<td>".$row["driver"]."</td>";
            echo "<td>".$row["vendor_no"]."</td>";
            echo "<td>".$row["vendor_name"]."</td>";
            echo "<td>".$row["tracking_no"]."</td>";
            echo "<td>".$row["folio"]."</td>";
            echo "<td>".$row["domicili"]."</td>";
            echo "<td>".$row["payment_term"]."</td>";
            echo "<td>".$row["delv_status"]."</td>";
            echo "<td style='text-align:right;'>".$row["subtotal"]."</td>";
            echo "<td style='text-align:right;'>".$row["total"]."</td>";
            echo "<td>".$row["remark1"]."</td>";
            echo "<td>".$row["box"]."</td>";
            echo "<td>".$row["pallet"]."</td>";
            echo "<td><a href='".base_url()."index.php/finance/delivery/payment/edit?docno=".$row["doc_no"]."' class='btn btn-sm btn-success'>".$row["statuss_name"]."</a></td>";
            echo "<td style='display:none;'>".$i."</td>"; // 2023-11-03
            echo "<td style='display:none;'>".$row["invc_vendor_no"]."</td>"; // 2023-11-03
            echo "<td style='display:none;'>".$row["invc_vendor_date"]."</td>"; // 2023-11-03
            echo "<td style='display:none;'>".$row["payment_date"]."</td>"; // 2023-11-03
            echo "<td style='display:none;'>".$row["uuid"]."</td>"; // 2023-11-03
            echo "<td style='display:none;'>".$row["remark2"]."</td>"; // 2023-11-03
          echo "</tr>";
          $i++;
      }
    ?>
  </tbody>
</table>

</div>

<script>

$("#btn_check_all").click(function(){
  var table = $('#DataTable').DataTable();

  table.column(20, { search:'applied' } ).data().each(function(value, index) {
    $("#no_"+value).prop( "checked", true );  // "checked"
  });

})
//--

$("#btn_uncheck_all").click(function(){
  var table = $('#DataTable').DataTable();

  table.column(20, { search:'applied' } ).data().each(function(value, index) {
    $("#no_"+value).prop( "checked", false );  // "checked"
  });

})
//--

function tableToCSV() {

  if(!check_if_checked()){
      show_error("You haven't checked any DATA");
  }
  else{
    // Variable to store the final csv data
    var csv_data = [];
    var column_data = [2,14,15,21,22,23,24,25];

    // header
    var csvrow = []; // Stores each csv row data
    csvrow.push("Doc No");
    csvrow.push("SubTotal");
    csvrow.push("Total");
    csvrow.push("Invoice Vendor No");
    csvrow.push("Invoice Vendor Date Format (YYYY-MM-DD)");
    csvrow.push("Payment Date Format (YYYY-MM-DD)");
    csvrow.push("UUID");
    csvrow.push("Remarks");
    csv_data.push(csvrow.join(",")); // Combine each column value with comma
    //--

    // Get each row data
    var rows = document.getElementsByTagName('tr');
    for (var i = 0; i < rows.length; i++) {

        var cols = rows[i].querySelectorAll('td,th'); // Get each column data

        if ($("#no_"+cols[20].innerHTML).is(':checked')) {
          var csvrow = []
          for (var j = 0; j < cols.length; j++) {
            if(column_data.includes(j)){
                var value = cols[j].innerHTML;
                csvrow.push(value); // Get the text data of each cell // of a row and push it to csvrow
            }
          }

          csv_data.push(csvrow.join(",")); // Combine each column value with comma
        }
    }

    csv_data = csv_data.join('\n'); // Combine each row data with new line character
    downloadCSVFile(csv_data); // Call this function to download csv file
  }
}

function downloadCSVFile(csv_data) {

    // Create CSV file object and feed
    // our csv_data into it
    CSVFile = new Blob([csv_data], {
      type: "text/csv"
    });

    // Create to temporary link to initiate
    // download process
    var temp_link = document.createElement('a');

    // Download csv file
    temp_link.download = "FinanceDeliveryPaymentTemplate.csv";
    var url = window.URL.createObjectURL(CSVFile);
    temp_link.href = url;

    // This link should not be displayed
    temp_link.style.display = "none";
    document.body.appendChild(temp_link);

    // Automatically click the link to
    // trigger download
    temp_link.click();
    document.body.removeChild(temp_link);
}
//--

function check_if_checked(){
  var table = $('#DataTable').DataTable();

  var checked = 0;
  table.column(20, { search:'applied' } ).data().each(function(value, index) {
    //$("#no_"+value).prop( "checked", false );  // "checked"
    if ($("#no_"+value).is(':checked')) {
      checked = 1;
    }
  });

  if(checked == 1) return true;
  else return false;

}
//--

</script>
