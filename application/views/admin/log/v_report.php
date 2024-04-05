<script>
$(document).ready(function() {
    $('#DataTable').DataTable({
      dom: 'Bfrtip',
        buttons: [
          {
            extend: 'excel',
            title : 'UsersLogs'
          }
        ],
        pageLength:30,
        order: [[2, 'desc']]
    });
});
</script>

<style>
  tr{
    font-size: 12px;
  }
</style>

<table class="table table-bordered table-striped table-sm" id="DataTable">
  <thead>
    <tr>
      <th>Name</th>
      <th>IP Address</th>
      <th>Date Time</th>
      <th>Activity</th>
    </tr>
  </thead>
  <tbody>
    <?php
      foreach($var_report as $row){
          echo "<tr>";
            echo "<td>".$row["name"]."</td>";
            echo "<td>".$row["ip_address"]."</td>";
            echo "<td>".$row["datetime"]."</td>";
            echo "<td>".$row["activity"]."</td>";
          echo "</tr>";
      }
    ?>
  </tbody>
</table>
