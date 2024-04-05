<script>
$(document).ready(function() {
    $('#DataTableGL').DataTable({
      "paging": false
    });
} );

</script>

<table id="DataTableGL" class="table table-striped table-hovered table-sm">
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
          echo "<td id='gl_text1_".$row['gl_id']."'>".$row['gl_text1']."</td>";
          echo "<td id='gl_text2_".$row['gl_id']."'>".$row['gl_text2']."</td>";
          echo "<td id='gl_depart_".$row['gl_id']."'>".$row['depart_name']."</td>";
          echo "<td><button class='btn btn-sm btn-primary' onclick=choose_gl('".$row['gl_id']."','".$row['gl_code']."')>select</td>";
          echo "</tr>";
          $i++;
      }
    ?>
  </tbody>
</table>

<script>
  function choose_gl(gl_id,gl_code){
      document.getElementById('gl_code_apprv_edit').value = gl_code;
      document.getElementById('gl_id_apprv_edit').value = gl_id;
      $('#gl_name_apprv_edit').text($('#gl_name_'+gl_id).text());
      $('#gl_text1_apprv_edit').text($('#gl_text1_'+gl_id).text());
      $('#gl_text2_apprv_edit').text($('#gl_text2_'+gl_id).text());
      $('#gl_depart_apprv_edit').text($('#gl_depart_'+gl_id).text());
      $('#myModalGL').modal('hide');
  }
</script>
