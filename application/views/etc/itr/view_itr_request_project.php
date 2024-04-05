<script>
$(document).ready(function() {
    $('#DataTableProject').DataTable({
      "paging": false
    });
} );

</script>

<table id="DataTableProject" class="table table-striped table-hovered">
  <thead>
      <tr>
        <th>Code</th>
        <th>Project</th>
        <th>Text</th>
        <th>Action</th>
      </tr>
  </thead>
  <tbody>
    <?php
      $i = 1;
      foreach($v_list_project as $row){
          echo "<tr>";
          echo "<td>".$row['itr_project_code']."</td>";
          echo "<td id='itr_project_name_".$row['itr_project_code']."'>".$row['itr_project_name']."</td>";
          echo "<td>".$row['itr_project_text1']."</td>";
          echo "<td><button class='btn btn-sm btn-primary' onclick=choose_itr_project('".$row['itr_project_code']."')>select</td>";
          echo "</tr>";
          $i++;
      }
    ?>
  </tbody>
</table>

<script>
  function choose_itr_project(itr_project){
      document.getElementById('itr_project').value = itr_project;
      $('#itr_project_text').text($('#itr_project_name_'+itr_project).text());
      $('#myModalProject').modal('hide');
  }
</script>
