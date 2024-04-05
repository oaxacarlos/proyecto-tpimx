<script>
$(document).ready(function() {
    $('#DataTableCostCenter').DataTable({
      "paging": false
    });
} );

</script>

<table id="DataTableCostCenter" class="table table-striped table-hovered">
  <thead>
      <tr>
        <th>No</th>
        <th>Cost Center</th>
        <th>Cost Center Name</th>
        <th>Action</th>
      </tr>
  </thead>
  <tbody>
    <?php
      $i = 1;
      foreach($v_list_costcenter as $row){
          echo "<tr>";
          echo "<td>".$i."</td>";
          echo "<td>".$row['costcenter_code']."</td>";
          echo "<td id='costcenter_name_".$row['costcenter_code']."'>".$row['costcenter_name']."</td>";
          echo "<td><button class='btn btn-sm btn-primary' onclick=choose_costcenter('".$row['costcenter_code']."')>select</td>";
          echo "</tr>";
          $i++;
      }
    ?>
  </tbody>
</table>

<script>
  function choose_costcenter(costcenter){
      document.getElementById('empc_costcenter').value = costcenter;
      $('#empc_costcenter_text').text($('#costcenter_name_'+costcenter).text());
      $('#myModalCostCenter').modal('hide');
  }
</script>
