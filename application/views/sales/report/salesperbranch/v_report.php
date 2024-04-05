<style>
  thead {
    position: sticky;
    top: 0;
  }

  tbody {
    padding-top: 500px;
  }

  tr {
    font-size: 12px;
  }

  th#title {
    text-align: center;
  }

  td#number {
    text-align: right;
  }
</style>
<script>
  $(document).ready(function() {
    $('#DataTable').DataTable({
      pageLength: 12,
      fixedHeader: true,
      dom: 'Bfrtip',
      buttons: [{
        extend: 'excel',
        title: 'TPM-salesperbranch',

      }],


    });
  });
</script>
<table class="table table-bordered table-striped table-sm" id="DataTable">
  <thead class="thead-dark ">
    <tr>
      <th>No.</th>
      <th>Category</th>
      <th>Description</th>
      <th>Item</th>
      <?php
      foreach ($var_branch as $row) {
        echo "<th colspan='3'>" . $row['city_name'] . "</th>";
      }
      ?>
    </tr>
    <tr class="thead-dark">
      <td colspan="4" ></td>
      <?php
      foreach ($var_branch as $row2) {
        echo "<td>" . $last_2year . "</td>";
        echo "<td>" . $last_year . "</td>";
        echo "<td>" . $year . "</td>";
      }
      ?>
    </tr>
  </thead>
  <tbody class="pb-4">




    <?php
    $line_no = 1;
    echo "<tr>";
    foreach ($var_data as $row) {

      echo "<td>" . $line_no . "</td>";
      echo "<td>" . $row["category"] . "</td>";
      echo "<td>" . $row["description"] . "</td>";
      echo "<td>" . $row['item_no'] . "</td>";
      foreach ($var_branch as $row2) {
        $name_b = $row2["city_name"];
        $result = str_replace(' ', '_', $name_b);
        $result_2 = str_replace(".", "", $result);
        $data3 = $result_2 . "_" . $last_2year;
        $data2 = $result_2 . "_" . $last_year;
        $data1 = $result_2 . "_" . $year;
        echo "<td>" . format_number($row[$data3], 1, 0) . "</td>";
        echo "<td>" . format_number($row[$data2], 1, 0) . "</td>";
        echo "<td>" . format_number($row[$data1], 1, 0) . "</td>";
      }
      echo "</tr>";
      $line_no++;
    }
    // }
    ?>
