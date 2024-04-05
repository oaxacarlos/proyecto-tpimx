<table class="table table-striped table-hovered">
  <thead>
      <tr>
        <th>Depot</th>
        <th>Depot Name</th>
        <th>Action</th>
      </tr>
  </thead>
  <tbody>
    <?php
      $i = 1;
      foreach($v_list_plant as $row){
          echo "<tr>";
          echo "<td>".$row['plant_code']."</td>";
          echo "<td id='plant_name_".$row['plant_code']."'>".$row['plant_name']."</td>";
          echo "<td><button class='btn btn-sm btn-primary' onclick=choose_plant('".$row['plant_code']."')>select</td>";
          echo "</tr>";
          $i++;
      }
    ?>
  </tbody>
</table>

<script>
  function choose_plant(plant){
      document.getElementById('empc_plant').value = plant;
      $('#empc_plant_text').text($('#plant_name_'+plant).text());

      document.getElementById('empc_costcenter').value = "";
      $('#empc_costcenter_text').text('');

      $('#myModalPlant').modal('hide');
  }
</script>
