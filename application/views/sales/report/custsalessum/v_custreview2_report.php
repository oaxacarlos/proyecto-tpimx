<script>
$(document).ready(function() {
    $('#tbl_report_custreview').DataTable({
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

  th#title{
    text-align: center;
  }

  td#number{
    text-align: right;
  }

</style>

<div>
  The parameter of this report is only Year.
</div>

<div class="containter-fluid">
  <?php if(isset($_SESSION['user_permis']["5"])){ ?>
    <button class="btn btn-success btn-sm"  id="btn_export_xlsx_custreview">EXCEL</button>
  <?php } ?>

  <?php if(isset($_SESSION['user_permis']["6"])){ ?>
    <button class='btn btn-primary btn-sm' id="copy_button_custreview" style='margin-left:20px;'>Copy ALL</button>
  <?php } ?>
</div>

<table class="table table-bordered table-striped table-sm" id="tbl_report_custreview" style="margin-top:10px;">
  <?php

    // calculate total sales amount
    $total_sales_amount_this_year = 0;
    $total_cost_amount_this_year = 0;
    foreach($var_report as $row){
      $total_sales_amount_this_year+=$row["line_amount_this_year"];
      $total_cost_amount_this_year+=$row["line_cost_amount_this_year"];
    }
    //--

    echo "<thead>";
      echo "<tr>";
        echo "<td colspan='2'>Year</td>";
        echo "<td>".$year."</td>";
        echo "<td colspan='14'></td>";
      echo "</tr>";
    echo "</thead>";

    echo "<thead>";
      echo "<tr>";
        echo "<th rowspan='2' id='title' class='table-dark'>No</th>";
        echo "<th rowspan='2' id='title' class='table-dark'>Cust No</th>";
        echo "<th rowspan='2' id='title' class='table-dark'>Cust Name</th>";
        echo "<th rowspan='2' id='title' class='table-dark'>Sales Name</th>";
        echo "<th rowspan='2' id='title' class='table-dark'>CS Name</th>";
        echo "<th id='title' class='table-dark'>Last 2 Year</th>";
        echo "<th id='title' class='table-dark'>Last Year</th>";
        echo "<th colspan='12' id='title' class='table-dark'>This Year (".$year.")</th>";
        echo "<th rowspan='2' id='title' class='table-dark'>Total<br>(".$year.")</th>";
        echo "<th rowspan='2' id='title' class='table-dark'>% Sales<br>Contribution<br>(".$year.")</th>";
        echo "<th rowspan='2' id='title' class='table-dark'>GP %<br>(".$year.")</th>";
        //echo "<th rowspan='2' id='title' class='table-dark'>Distribution<br>Cost %<br>(".$year.")</th>";
        //echo "<th rowspan='2' id='title' class='table-dark'>Total Sales Amount<br>(".$year.")</th>";
        echo "<th rowspan='2' id='title' class='table-dark'>Payment<br>Terms</th>";
        echo "<th rowspan='2' id='title' class='table-dark'>Avg<br>Collection<br>Days</th>";
      echo "</tr>";

      echo "<tr>";
        echo "<th id='title' class='table-dark'>".$last_2year."</th>";
        echo "<th id='title' class='table-dark'>".$last_year."</th>";
        foreach($months as $row){ echo "<th id='title' class='table-dark'>".$row."</th>"; }
      echo "</tr>";
    echo "</thead>";

    echo "<tbody>";

      // initial
      $total_last_year = 0;
      $total_last_2year = 0;
      $total_sls_contrib = 0;
      foreach($months as $row2){ $total["total_".$year."_".$row2]=0;}
      //---

      $no=1;
      foreach($var_report as $row){
        $total_per_line = 0;

        echo "<tr>";
          echo "<td>".$no."</td>";
          echo "<td>".$row["cust_no"]."</td>";
          echo "<td>".$row["name"]."</td>";
          echo "<td>".$row["slsname"]."</td>";
          echo "<td>".$row["cs_name"]."</td>";
          echo "<td id='number'>".format_number($row["si_qty_last_2year"],$amount_format,$comma_digit)."</td>"; $total_last_2year+=$row["si_qty_last_2year"]; //$total_per_line+=$row["si_qty_last_2year"];
          echo "<td id='number'>".format_number($row["si_qty_last_year"],$amount_format,$comma_digit)."</td>"; $total_last_year+=$row["si_qty_last_year"]; //$total_per_line+=$row["si_qty_last_year"];

          foreach($months as $row2){
              echo "<td id='number'>".format_number($row["now_".$year."_".$row2],$amount_format,$comma_digit)."</td>"; $total_per_line+=$row["now_".$year."_".$row2];
              $total["total_".$year."_".$row2]+=$row["now_".$year."_".$row2];
          }

          echo "<td class='table-secondary' id='number'>".format_number($total_per_line,$amount_format,$comma_digit)."</td>";

          $sls_contrib = percentage($row["line_amount_this_year"], $total_sales_amount_this_year);
          $total_sls_contrib+=$sls_contrib;

          echo "<td class='table-secondary' id='number'>".convert_number3($sls_contrib)."</td>";

          echo "<td class='table-secondary' id='number'>".convert_number3($row["gp_percent"])."</td>";
          //echo "<td class='table-secondary' id='number'>".$row["dist_cost_percent"]."</td>";
          echo "<td class='table-secondary' id='number'>".$row["payment_terms_code"]."</td>";
          //echo "<td class='table-secondary' id='number'>".format_number($row["line_amount_this_year"],1,2)."</td>";


          echo "<td class='table-secondary' id='number'>".convert_number3($row["avg_collection_days"])."</td>";
        echo "</tr>";

        $no++;
      }

      // total
      $total_all = 0;
      echo "<tr class='table-secondary'>";
        echo "<td colspan='5'>Total</td>";
        echo "<td id='number'>".format_number($total_last_2year,$amount_format,$comma_digit)."</td>"; //$total_all+=$total_last_2year;
        echo "<td id='number'>".format_number($total_last_year,$amount_format,$comma_digit)."</td>"; //$total_all+=$total_last_year;

        foreach($months as $row2){
            echo "<td id='number'>".format_number($total["total_".$year."_".$row2],$amount_format,$comma_digit)."</td>";
            $total_all+=$total["total_".$year."_".$row2];
        }

        echo "<td id='number'>".format_number($total_all,$amount_format,$comma_digit)."</td>";
        echo "<td id='number'>".percentage($total_sales_amount_this_year-$total_cost_amount_this_year, $total_sales_amount_this_year)."</td>";
        //echo "<td id='number'>".format_number($total_sales_amount_this_year,1,2)."</td>";
        //echo "<td id='number'>".$total_sls_contrib."</td>";
      echo "</tr>";
      //--

    echo "</tbody>";

  ?>
</table>

<script>
// product review
var table2excel3 = new Table2Excel();
document.getElementById('btn_export_xlsx_custreview').addEventListener('click', function() {
  alert("Your converted to Excel, check your DOWNLOAD folder");
  setTimeout(table2excel3.export(document.querySelector('#tbl_report_custreview'),"CustomerReview-"),1000);
});
//---
</script>
