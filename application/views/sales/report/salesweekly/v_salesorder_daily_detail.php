<style>
tr{
  font-size: 12px;
}
</style>


  <table class="table table-bordered table-sm table-striped">
    <thead>
      <tr>
        <th colspan='4' class='table-info' style='text-align:center;'>Sales Order Daily <button onclick=f_refresh_salesorder_daily()>refresh</button></th>
      </tr>
      <tr>
        <th>Order Date</th>
        <th>Day</th>
        <th>Total SO</th>
        <th>Total Amount</th>
      </tr>
    </thead>
    <tbody>
      <?php

      function star($array_highest_date,$date){
          $star = "";
          foreach($array_highest_date as $row2){
              if($row2 == $date){
                  $star = "*"; break;
              }
          }

          return $star;
      }
      //---

      // get highest in a week
      $first_day = "Monday";
      $day_run = "";
      $highest_date = "";
      $highest_amount = 0;
      unset($array_highest_date);
      foreach($var_detail as $row){
          if($row['date_name'] == $first_day){
              $array_highest_date[] = $highest_date;
              $highest_amount = $row['total_amount'];
              $highest_date = $row['order_date'];
          }
          else{
              if($row['total_amount'] > $highest_amount){
                  $highest_amount = $row['total_amount'];
                  $highest_date = $row['order_date'];
              }
          }
      }
      $array_highest_date[] = $highest_date;
      //---

      $total = 0;
      $total_so = 0;
      foreach($var_detail as $row){
        $star = star($array_highest_date,$row['order_date']);
        if($row['date_name'] == "Monday"){
          echo "<tr style='height:0.01px;' class='table-dark'><td colspan='4'></td></tr>";

        }

        echo "<tr>";
          echo "<td>".$row['order_date']." ".$star."</td>";
          echo "<td>".$row['date_name']." ".$star."</td>";
          echo "<td>".$row["total_so"]." ".$star."</td>";
          echo "<td>".format_number($row['total_amount'],1,2)." ".$star."</td>";
        echo "</tr>";
        $total += $row['total_amount'];
        $total_so += $row['total_so'];
      }
      echo "<tr class='table-info'>";
        echo "<th colspan='2'>TOTAL</th>";
        echo "<th>".$total_so."</th>";
        echo "<th>".format_number($total,1,2)."</th>";
      echo "</tr>";
      ?>

    </tbody>
  </table>
  Noted : * = Highest Sales day on that Week

<script>

function f_refresh_salesorder_daily(){
    var year = $("#dsh_salesnational_year").val();
    var month = $("#dsh_salesnational_month").val();

    if(year == ""){
        show_error("Year could not blank");
        return false
    }

    gen_report_salesnational_view_dailysalesorder(year, month);
}

</script>
