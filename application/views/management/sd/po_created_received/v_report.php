<script>
$(document).ready(function() {
    $('#DataTable').DataTable({
      dom: 'Bfrtip',
        buttons: [
          {
            extend: 'excel',
            title : 'PO-Created-Received'
          }
        ],
    });
});
</script>

<table class="table table-bordered table-striped" id="DataTable">
  <thead>
    <tr>
      <th>PO Doc</th>
      <th>Vendor</th>
      <th>PO Date</th>
      <th>Loc Code</th>
      <th>Line No</th>
      <th>Item</th>
      <th>Qty</th>
      <th>Qty Received</th>
      <th>GR Doc</th>
      <th>GR Date</th>
      <th>Lead Time</th>
    </tr>
  </thead>

  <tbody>
    <?php
      foreach($var_report as $row){
          echo "<tr>";
            echo "<td>".$row["po_doc"]."</td>";
            echo "<td>".$row["po_Vendor"]."</td>";
            echo "<td>".$row["po_date"]."</td>";
            echo "<td>".$row["location_code"]."</td>";
            echo "<td>".$row["Line No_"]."</td>";
            echo "<td>".$row["item_no"]."</td>";
            echo "<td>".round($row["po_qty"],0)."</td>";
            echo "<td>".round($row["pr_rcv"],0)."</td>";
            echo "<td>".$row["pr_doc"]."</td>";
            echo "<td>".$row["gr_date"]."</td>";
            echo "<td>".$row["diff_po_gr_day"]."</td>";
          echo "</tr>";
      }
    ?>
  </tbody>
</table>
