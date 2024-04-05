<script>
$(document).ready(function() {
    $('#tbl_report').DataTable({
      "paging": false,
      "ordering": false,
    });
});
//---

</script>

<style>
  tr{
    font-size: 12px;
  }

</style>

<div style="margin-top:50px;" onmousedown="return false" onselectstart="return false">
  <table class="table table-bordered table-striped table-sm" id="tbl_report">
    <thead>
      <th>Sakura</th>
      <th>Desc</th>
      <th>Cat</th>
      <?php
        foreach($var_company as $row){
            echo "<th>".$row["name"]."</th>";
        }
      ?>
    </thead>
    <tbody>
      <?php
        foreach($var_report as $row){
            echo "<tr>";
              echo "<td>".$row["item_code"]."</td>";
              echo "<td>".$row["name"]."</td>";
              echo "<td>".$row["item_category_codee"]."</td>";
              foreach($var_company as $row2){
                  echo "<td>".$row[$row2["id"]]."</td>";
              }
            echo "</tr>";
        }

      ?>
    </tbody>
  </table>
</div>
