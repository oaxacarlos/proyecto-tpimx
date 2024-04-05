<script>
$(document).ready(function() {
    $('#DataTable_duplicate').DataTable({
      dom: 'Bfrtip',
        buttons: [
          {
            extend: 'excel',
            title : 'Stock-Count'
          }
        ],
        pageLength : 25,
        order: [[1, 'desc']]
    });
});
</script>

<style>
  tr#stockcountdata{
    font-size:14px;
  }

  td#number{
    text-align: right;
  }
</style>

<table class="table table-bordered table-sm table-striped" id="DataTable_duplicate">
  <thead>
    <tr id="stockcountdata">
      <th>DOC DATE</th>
      <th>ITEM</th>
      <th>WHS</th>
      <th>LOCATION</th>
      <th>QTY</th>
      <th>USER</th>
    </tr>
  </thead>
  <tbody>
    <?php

      $color["WH2"] = "danger";
      $color["WH3"] = "info";

      foreach($var_report as $row){
          echo "<tr>";
            echo "<td>".$row["doc_date"]."</td>";
            echo "<td>".$row["item_code"]."</td>";
            echo "<td class='table-".$color[$row["location_code"]]."'>".$row["location_code"]."</td>";
            echo "<td>".combine_location($row["location_code"], $row["zone_code"], $row["area_code"], $row["rack_code"], $row["bin_code"])."</td>";
            echo "<td id='number'>".$row["qty"]."</td>";
            echo "<td id='number'>".$row["name"]."</td>";
          echo "</tr>";
      }
    ?>
  </tbody>
</table>
