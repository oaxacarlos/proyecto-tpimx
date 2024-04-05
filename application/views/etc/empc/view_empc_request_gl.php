<script>
$(document).ready(function() {
    $('#DataTableGL').DataTable({
      "paging": false
    });
} );

</script>

<table id="DataTableGL" class="table table-striped" style="width:100%">
  <thead>
      <tr>
        <th>GL CODE</th>
        <th style="display:none;"></th>
        <th>GL SAP</th>
        <th>GL NAME</th>
        <th>Text1</th>
        <th>Text2</th>
        <th>Department</th>
        <th>Action</th>
      </tr>
  </thead>
  <tbody>
    <?php
      $i = 1;
      foreach($v_list_gl as $row){
          echo "<tr>";
          echo "<td id='gl_code_view_".$row['gl_id']."'>".$row['gl_code_view']."</td>";
          echo "<td style='display:none;'>".$row['gl_id']."</td>";
          echo "<td>".$row['gl_code']."</td>";
          echo "<td id='gl_name_".$row['gl_id']."'>".$row['gl_name']."</td>";
          echo "<td>".$row['gl_text1']."</td>";
          echo "<td>".$row['gl_text2']."</td>";
          echo "<td>".$row['depart_name']."</td>";
          echo "<td><button class='btn btn-sm btn-primary' onclick=choose_gl('".$row['gl_id']."','".$row['gl_code']."')>select</td>";
          echo "</tr>";
          $i++;
      }
    ?>
  </tbody>
</table>

<script>
  function choose_gl(gl_id,gl_code){
      document.getElementById('empc_gl').value = gl_id;
      document.getElementById('empc_gl_code').value = gl_code;
      $('#empc_gl_code_text').text($('#gl_code_view_'+gl_id).text()+' - '+$('#gl_name_'+gl_id).text());
      $('#myModalGL').modal('hide');
  }
</script>
