<script>
$(document).ready(function() {
    $('#tbl_putaway_time').DataTable({
      dom: 'Bfrtip',
        buttons: [
          {
            extend: 'excel',
            title : 'PutAwayTime'
          }
        ],
        paging: false,
        order: [[1, 'asc'],[2,'asc']],
    });
});
</script>

<style>
  table#tbl_putaway_time{
    font-size: 12px;
  }
</style>

<div class="row">
  <table id="tbl_putaway_time" class="table table-bordered table-sm table-striped">
    <thead>
      <th>ID</th>
      <th>Name</th>
      <th>Doc No</th>
      <th>Created</th>
      <th>Start</th>
      <th>Finish</th>
      <th>Created-Start</th>
      <th>Start-Finish</th>
      <th>Created-Finish</th>
      <th>Progress</th>
    </thead>
    <tbody>
      <?php
        foreach($var_report as $row){
            $created_to_start_percentage = percentage($row["cal_created_to_start"], $row["cal_created_to_finish"]);
            $start_to_finish_percentage = percentage($row["cal_start_to_finish"], $row["cal_created_to_finish"]);

            echo "<tr>";
              echo "<td>".$row["assign_user"]."</td>";
              echo "<td>".$row["name"]."</td>";
              echo "<td>".$row["doc_no"]."</td>";
              echo "<td>".$row["doc_datetime"]."</td>";
              echo "<td>".$row["start_datetime"]."</td>";
              echo "<td>".$row["all_finished_datetime"]."</td>";
              echo "<td>".$row["created_to_start"]."</td>";
              echo "<td>".$row["start_to_finish"]."</td>";
              echo "<td>".$row["created_to_finish"]."</td>";
              echo "<td>";
                if(!is_null($row["cal_created_to_finish"]) || !$row["cal_created_to_finish"]==""){
                  echo "<div class='progress' style='height: 5px;'>";
                    echo "<div class='progress-bar bg-warning' role='progressbar' style='width: ".$created_to_start_percentage."%' aria-valuenow='15' aria-valuemin='0' aria-valuemax='100'></div>";
                    echo "<div class='progress-bar bg-info' role='progressbar' style='width: ".$start_to_finish_percentage."%' aria-valuenow='".$start_to_finish_percentage."' aria-valuemin='0' aria-valuemax='100'></div>";
                  echo "</div>";
                }
              echo "</td>";
            echo "</tr>";
        }
      ?>
    </tbody>
  </table>
</div>
