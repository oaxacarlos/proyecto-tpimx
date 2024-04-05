<script>
$(document).ready(function() {
    $('#DataTable').DataTable({
      dom: 'Bfrtip',
        buttons: [
          {
            extend: 'excel',
            title : 'Detail-InOutBound'
          }
        ],
        pageLength:20,
    });
});
</script>

<table id="DataTable" class="table table-bordered table-striped table-sm">
    <thead>
      <tr>
        <th>Date</th>
        <th>Doc No</th>
        <th>Location</th>
        <th>SO/TO No</th>
        <th>Line</th>
        <th>Item</th>
        <th>Desc</th>
        <th>Qty</th>
        <th>Uom</th>
        <th>Dest No</th>
        <th>Canceled</th>
      </tr>
    </thead>
    <tbody>
        <?php
          foreach($var_report as $row){
              echo "<tr>";
                echo "<td>".$row["doc_date"]."</td>";
                echo "<td>".$row["doc_no"]."</td>";
                echo "<td>".$row["doc_location_code"]."</td>";
                echo "<td>".$row['src_no']."</td>";
                echo "<td>".$row['line_no']."</td>";
                echo "<td>".$row['item_code']."</td>";
                echo "<td>".$row['description']."</td>";
                echo "<td>".$row[$qty]."</td>";
                echo "<td>".$row['uom']."</td>";
                echo "<td>".$row['dest_no']."</td>";
                echo "<td>".$row['canceled']."</td>";
              echo "</tr>";
          }
        ?>
    </tbody>
</table>
