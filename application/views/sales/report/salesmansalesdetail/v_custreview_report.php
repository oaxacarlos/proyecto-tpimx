<script>

  $(document).ready(function() {
      $('#tbl_report_custreview').DataTable({
        "paging": false,
        "ordering": false,
        "searching": true,
        dom: 'Bfrtip',
          buttons: [
            {
              extend: 'excel',
              title : 'CustReviewbySalesman'
            }
          ],
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

<div class="containter-fluid text-right">
  <?php if(isset($_SESSION['user_permis']["11"])){ ?>
    <!--<button class="btn btn-success btn-sm"  id="btn_export_xlsx_customer">EXCEL</button>-->
  <?php } ?>

  <?php if(isset($_SESSION['user_permis']["12"])){ ?>
    <button class='btn btn-primary btn-sm' id="copy_button_customer" style='margin-left:20px;'>Copy ALL</button>
  <?php } ?>
</div>

<table class="table table-bordered table-striped table-sm display" id="tbl_report_custreview" style="margin-top:10px; width:100%;">
  <?php

    // calculate total sales amount
    $total_sales_amount_this_year = 0;
    $total_cost_amount_this_year = 0;
    foreach($var_report as $row){
      $total_sales_amount_this_year+=$row["line_amount_this_year"];
      $total_cost_amount_this_year+=$row["line_cost_amount_this_year"];
    }
    //--

    // initial
    if(isset($_SESSION['user_permis']["28"])) $gp_minus = 0;
    else $gp_minus = 35;
    //--

    /*echo "<thead>";
      echo "<tr>";
        echo "<td colspan='2'>Year</td>";
        echo "<td>".$year."</td>";
        echo "<td colspan='17'></td>";
      echo "</tr>";
    echo "</thead>";
    */
    echo "<thead>";
      echo "<tr>";
        echo "<th rowspan='2' id='title' class='table-dark'>No</th>";
        echo "<th rowspan='2' id='title' class='table-dark'>Cust No</th>";
        echo "<th rowspan='2' id='title' class='table-dark'>Cust Name</th>";
        echo "<th rowspan='2' id='title' class='table-dark'>Salesman</th>";
        echo "<th rowspan='2' id='title' class='table-dark'>State</th>";
        echo "<th id='title' class='table-dark'>Last 2 Year</th>";
        echo "<th colspan='2' id='title' class='table-dark'>Last Year</th>";
        echo "<th colspan='12' id='title' class='table-dark'>This Year (".$year.")</th>";

        if(isset($_SESSION['user_permis']["25"])) echo "<th rowspan='2' id='title' class='table-dark'>GP %</th>";

        echo "<th rowspan='2' id='title' class='table-dark'>Total<br>(".$year.")</th>";
        echo "<th rowspan='2' id='title' class='table-dark'>Promedio<br>(".$year.")</th>";
      echo "</tr>";

      echo "<tr>";
        echo "<th id='title' class='table-dark' class='sum'>".$last_2year."</th>";
        echo "<th id='title' class='table-dark'>".$last_year."</th>";
        echo "<th id='title' class='table-dark'>Promedio <br>(".$last_year.")</th>";
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
          echo "<td>".$row["county"]."</td>";
          echo "<td id='number'>".format_number($row["si_qty_last_2year"],$amount_format,$comma_digit)."</td>"; $total_last_2year+=$row["si_qty_last_2year"]; //$total_per_line+=$row["si_qty_last_2year"];
          echo "<td id='number'>".format_number($row["si_qty_last_year"],$amount_format,$comma_digit)."</td>"; $total_last_year+=$row["si_qty_last_year"]; //$total_per_line+=$row["si_qty_last_year"];

          echo "<td id='number'>".format_number($row["si_qty_last_year"]/12,$amount_format,2)."</td>";

          $counting_month = 0;
          $month_now2 = date("n");
          foreach($months as $row2){
              echo "<td id='number'>".format_number($row["now_".$year."_".$row2],$amount_format,$comma_digit)."</td>"; $total_per_line+=$row["now_".$year."_".$row2];
              $total["total_".$year."_".$row2]+=$row["now_".$year."_".$row2];

              if($counting_month < $month_now2) $counting_month++;
          }

          if(isset($_SESSION['user_permis']["25"])){
              if($row["gp_percent"] == 0) echo "<td class='table-secondary' id='number'>".format_number($row["gp_percent"],$amount_format,$comma_digit)."</td>";
              else echo "<td class='table-secondary' id='number'>".($row["gp_percent"]-$gp_minus)."</td>";
          }

          echo "<td class='table-secondary' id='number'>".format_number($total_per_line,$amount_format,$comma_digit)."</td>";
          echo "<td class='table-secondary' id='number'>".format_number($total_per_line/$counting_month,$amount_format,2)."</td>";
        echo "</tr>";

        $no++;
      }

      echo "</tbody>";

      /*echo "<tfoot>";

      // total
      $total_all = 0;
      echo "<tr class='table-secondary'>";
        echo "<td></td><td></td><td></td><td></td><td>Total</td>";
        echo "<td id='number' class='Int'>".format_number($total_last_2year,$amount_format,$comma_digit)."</td>"; //$total_all+=$total_last_2year;
        echo "<td id='number'>".format_number($total_last_year,$amount_format,$comma_digit)."</td>"; //$total_all+=$total_last_year;
        echo "<td>-</td>";

        foreach($months as $row2){
            echo "<td id='number'>".format_number($total["total_".$year."_".$row2],$amount_format,$comma_digit)."</td>";
            $total_all+=$total["total_".$year."_".$row2];
        }

        echo "<td id='number'></td>";
        echo "<td id='number'>".format_number($total_all,$amount_format,$comma_digit)."</td>";
        echo "<td id='number'></td>";
      echo "</tr>";
      //--

      echo "</tfoot>";*/


  ?>
</table>

<script>
// product review
/*var table2excel3 = new Table2Excel();
document.getElementById('btn_export_xlsx_customer').addEventListener('click', function() {
  alert("Your converted to Excel, check your DOWNLOAD folder");
  table2excel3.export(document.querySelector('#tbl_report_custreview'),"Customer");
});*/
//---


</script>
