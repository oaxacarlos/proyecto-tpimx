<script>
$(document).ready(function() {
    $('#DataTable').DataTable({
      dom: 'Bfrtip',
		    buttons: [
          {
            extend: 'excel',
            title : 'KWS-BuyPast3Months'
          }
        ],
    });
} );

</script>

<style>
  tr{
      font-size: 12px;
  }
</style>

<table id="DataTable" class="table table-bordered table-striped table-sm">
  <thead>
    <tr>
      <th>Region</th>
      <th>Customer buy Past 3 Months</th>
    </tr>
  </thead>
  <tbody>
  <?php
  foreach($v_list_kws_buy_past3months as $row){
      echo "<tr>";
        echo "<td>".$row['region']."</td>";
        echo "<td>".$row['last3monthbuy']."</td>";
        echo "</tr>";
  }

  ?>
  </tbody>
</table>
