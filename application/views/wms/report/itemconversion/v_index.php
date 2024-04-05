<script>
$(document).ready(function() {
    $('#DataTable').DataTable({
      dom: 'Bfrtip',
        buttons: [
          {
            extend: 'excel',
            title : 'ItemConversion'
          }
        ],
        pageLength:20,
    });
});
</script>

<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      Item Conversion
</div>

<div class="container">
  <table class="table table-bordered table-striped table-sm" id="DataTable">
    <thead>
      <tr>
        <th>Item Code</th>
        <th>CAJA</th>
        <th>PIEZA</th>
      </tr>
    </thead>
    <tbody>
      <?php
        foreach($var_report as $row){
            echo "<tr>";
              echo "<td>".$row["item_code"]."</td>";
              echo "<td>".$row["ctn"]."</td>";
              echo "<td>".$row["pcs"]."</td>";
            echo "</tr>";
        }
      ?>
    </tbody>
  </table>
</div>
