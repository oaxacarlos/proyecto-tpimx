<style>
  tr{
    font-size: 12px;
  }

  th#title{
    text-align: center;
  }

  td#number{
    text-align: right;
  }

</style>

<button class="btn btn-success btn-sm"  id="btn_export_xlsx_report">EXCEL</button>

<table class="table table-bordered table-sm table" style="margin-top:10px;" id="tbl_report">
  <thead>
    <tr>
      <th></th>
      <th>Created<br>at</th>
      <th>Doc<br>Date</th>
      <th>Doc<br>No</th>
      <th>Sending<br>Date</th>
      <th>Destination</th>
      <th>State</th>
      <th>Driver</th>
      <th>Vendor<br>No</th>
      <th>Vendor<br>Name</th>
      <th>Tracking<br>No</th>
      <th>Folio</th>
      <th>Domicili</th>
      <th>Payment<br>Term</th>
      <th>SubTotal</th>
      <th>Total</th>
      <th>Remarks</th>
      <th>Box</th>
      <th>Pallet</th>
      <th>Delivery<br>Status</th>
      <th>Received<br>date</th>
      <th>Received<br>by</th>
      <th>Created<br>by</th>
      <th>Approved<br>by</th>
      <th>Payment<br>Date</th>
      <th>Payment<br>Status</th>
      <th>Status</th>
    </tr>
  </thead>
  <tbody>
    <?php
      foreach($var_data_h as $row_h){
        echo "<tr>";
          echo "<td><button data-toggle='collapse' href='#detail_".$row_h['doc_no']."'>+</button></td>";
          echo "<td>".$row_h["created_at"]."</td>";
          echo "<td>".$row_h["doc_date"]."</td>";
          echo "<td>".$row_h["doc_no"]."</td>";
          echo "<td>".$row_h["delv_date"]."</td>";
          echo "<td>".$row_h["destination"]."</td>";
          echo "<td>".$row_h["state"]."</td>";
          echo "<td>".$row_h["driver"]."</td>";
          echo "<td>".$row_h["vendor_no"]."</td>";
          echo "<td>".$row_h["vendor_name"]."</td>";
          echo "<td>".$row_h["tracking_no"]."</td>";
          echo "<td>".$row_h["folio"]."</td>";
          echo "<td>".$row_h["domicili"]."</td>";
          echo "<td>".$row_h["payment_term"]."</td>";
          echo "<td id='number' style='text-align:right;'>".format_number($row_h["subtotal"],1,2)."</td>";
          echo "<td id='number' style='text-align:right;'>".format_number($row_h["total"],1,2)."</td>";
          echo "<td>".$row_h["remark1"]."</td>";
          echo "<td id='number'>".$row_h["box"]."</td>";
          echo "<td id='number'>".$row_h["pallet"]."</td>";
          echo "<td>".$row_h["delv_status"]."</td>";
          echo "<td>".$row_h["receiv_date"]."</td>";
          echo "<td>".$row_h["receiv_person"]."</td>";
          echo "<td>".$row_h["created_by"]."</td>";
          echo "<td>".$row_h["approved_by"]."</td>";
          echo "<td>".$row_h["payment_date"]."</td>";
          echo "<td>".$row_h["payment_status"]."</td>";

          if($row_h["statuss"] == 1) $badge_status = "info";
          else if($row_h["statuss"] == 2) $badge_status = "warning";
          else if($row_h["statuss"] == 3) $badge_status = "secondary";
          else if($row_h["statuss"] == 4) $badge_status = "danger";
          else if($row_h["statuss"] == 5) $badge_status = "info";
          else if($row_h["statuss"] == 6) $badge_status = "success";
          else if($row_h["statuss"] == 7) $badge_status = "success";

          echo "<td><div class='badge badge-".$badge_status."'>".$row_h["statuss_name"]."</a></td>";
        echo "</tr>";

        // detail data
        $first_row = 1;
        foreach($var_data_d as $row_d){
            if($row_d["doc_no"] == $row_h["doc_no"]){
              if($first_row == 1){
                echo "<tr class='collapse table-secondary' id='detail_".$row_d["doc_no"]."'>";
                  echo "<td></td>";
                  echo "<td><b>Doc Type</b></td>";
                  echo "<td><b>Invoice Date</b></td>";
                  echo "<td><b>Invoice No</b></td>";
                  echo "<td><b>SO Ref</b></td>";
                  echo "<td><b>Cust No</b></td>";
                  echo "<td><b>Cust Name</b></td>";
                  echo "<td><b>Address</b></td>";
                  echo "<td><b>Address 2</b></td>";
                  echo "<td><b>City</b></td>";
                  echo "<td><b>State</b></td>";
                  echo "<td><b>Post Code</b></td>";
                  echo "<td><b>Country</b></td>";
                  echo "<td></td>";
                  echo "<td><b>SubTotal</b></td>";
                  echo "<td><b>Total</b></td>";
                  echo "<td><b>Remarks</b></td>";
                  echo "<td><b>Qty</b></td>";
                  echo "<td></td>";
                  echo "<td></td>";
                  echo "<td></td>";
                  echo "<td></td>";
                  echo "<td></td>";
                  echo "<td></td>";
                  echo "<td></td>";
                  echo "<td></td>";
                  echo "<td></td>";
                echo "<tr>";
                $first_row++;
              }

              if($row_d["doc_type"] == "1") $doc_type_text = "Factura";
              else if($row_d["doc_type"] == "2") $doc_type_text = "Non Factura";
              else if($row_d["doc_type"] == "3") $doc_type_text = "Consigment";

              echo "<tr class='collapse table-secondary' id='detail_".$row_d["doc_no"]."'>";
                echo "<td></td>";
                echo "<td>".$doc_type_text."</td>";
                echo "<td>".$row_d["invc_doc_date"]."</td>";
                echo "<td>".$row_d["invc_doc_no"]."</td>";
                echo "<td>".$row_d["so_ref"]."</td>";
                echo "<td>".$row_d["invc_cust_no"]."</td>";
                echo "<td>".$row_d["invc_cust_name"]."</td>";
                echo "<td>".$row_d["invc_address"]."</td>";
                echo "<td>".$row_d["invc_address2"]."</td>";
                echo "<td>".$row_d["invc_city"]."</td>";
                echo "<td>".$row_d["invc_state"]."</td>";
                echo "<td>".$row_d["invc_post_code"]."</td>";
                echo "<td>".$row_d["invc_country"]."</td>";
                echo "<td></td>";
                echo "<td id='number'>".format_number($row_d["d_subtotal"],1,2)."</td>";
                echo "<td id='number'>".format_number($row_d["d_total"],1,2)."</td>";
                echo "<td>".$row_d["remark1"]."</td>";
                echo "<td id='number'>".$row_d["qty"]."</td>";
                echo "<td></td>";
                echo "<td></td>";
                echo "<td></td>";
                echo "<td></td>";
                echo "<td></td>";
                echo "<td></td>";
                echo "<td></td>";
                echo "<td></td>";
                echo "<td></td>";
              echo "</tr>";


            }

        }
        //--

      }
    ?>
  </tbody>
</table>

<script>
// product review
var table2excel3 = new Table2Excel();
document.getElementById('btn_export_xlsx_report').addEventListener('click', function() {
  alert("Your converted to Excel, check your DOWNLOAD folder");
  setTimeout(table2excel3.export(document.querySelector('#tbl_report'),"DeliveryReport"),1000);
});
//---

</script>
