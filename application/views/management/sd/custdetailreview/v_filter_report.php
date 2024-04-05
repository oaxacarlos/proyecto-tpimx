<script>
$(document).ready(function() {
    $('#tbl_report_custdetailreview_filter').DataTable({
      "paging": false,
      "ordering": false,
    });
});
//---

//---
function f_convert_excel_filter(){
  var table2excel = new Table2Excel();
  alert("Your converted to Excel, check your DOWNLOAD folder");
  setTimeout(table2excel.export(document.querySelector('#tbl_report_custdetailreview_filter'),"CustomerDetailReviewFilter"),1000);
}
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

  th { position: sticky; top: 0; }

</style>

<div class="containter-fluid">
    <button class="btn btn-success btn-sm"  id="btn_export_xlsx_custdetailreview_filter" onclick=f_convert_excel_filter()>EXCEL</button>
    <button class='btn btn-primary btn-sm' id="copy_button_custdetailreview_filter" style='margin-left:20px;'>Copy ALL</button>
</div>

<table class="table table-bordered table-striped table-sm" id="tbl_report_custdetailreview_filter" style="margin-top:10px;">
  <?php

    // calculate total sales amount
    $total_sales_amount_this_year = 0;
    $total_cost_amount_this_year = 0;
    foreach($var_report as $row){
      $total_sales_amount_this_year+=$row["line_amount_this_year"];
      $total_cost_amount_this_year+=$row["line_cost_amount_this_year"];
    }
    //--

    // calculate total sales amount
    $total_sales_amount_last_year = 0;
    $total_cost_amount_last_year = 0;
    foreach($var_report as $row){
      $total_sales_amount_last_year+=$row["line_amount_last_year"];
      $total_cost_amount_last_year+=$row["line_cost_amount_last_year"];
    }
    //--

    echo "<thead>";
      echo "<tr>";
        echo "<th rowspan='2' id='title' class='table-dark'>No</th>";
        echo "<th rowspan='2' id='title' class='table-dark'>Cust No</th>";
        echo "<th rowspan='2' id='title' class='table-dark'>Cust Name</th>";
        echo "<th rowspan='2' id='title' class='table-dark'>Sales Name</th>";
        echo "<th rowspan='2' id='title' class='table-dark'>County</th>";
        echo "<th rowspan='2' id='title' class='table-dark'>Item Cat</th>";
        echo "<th rowspan='2' id='title' class='table-dark'>Item Name</th>";
        echo "<th id='title' class='table-dark'>Last 2 Year</th>";
        echo "<th id='title' class='table-dark'>Last Year</th>";
        echo "<th colspan='12' id='title' class='table-dark'>This Year (".$year.")</th>";
        echo "<th rowspan='2' id='title' class='table-dark'>Total<br>(".$year.")</th>";
        echo "<th rowspan='2' id='title' class='table-dark'>GP %<br>(".$year.")</th>";
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
          echo "<td>".$row["cust_name"]."</td>";
          echo "<td>".$row["slsname"]."</td>";
          echo "<td>".$row["county"]."</td>";
          echo "<td>".$row["item_category_codee"]."</td>";
          echo "<td>".$row["item_name"]."</td>";

          echo "<td id='number'>".format_number($row["si_last_2year"],$amount_format,$comma_digit)."</td>";
          $total_last_2year+=$row["si_last_2year"]; //$total_per_line+=$row["si_qty_last_2year"];

          echo "<td id='number'>".format_number($row["si_last_year"],$amount_format,$comma_digit)."</td>";
          $total_last_year+=$row["si_last_year"]; //$total_per_line+=$row["si_qty_last_year"];

          $sls_contrib_last_year = percentage($row["line_amount_last_year"], $total_sales_amount_last_year);

          foreach($months as $row2){
              echo "<td id='number'>".format_number($row["now_".$year."_".$row2],$amount_format,$comma_digit)."</td>"; $total_per_line+=$row["now_".$year."_".$row2];
              $total["total_".$year."_".$row2]+=$row["now_".$year."_".$row2];
          }

          echo "<td class='table-secondary' id='number'>".format_number($row["total_".$year],$amount_format,$comma_digit)."</td>";
          echo "<td class='table-secondary' id='number'>".convert_number3($row["gp_percent"])."</td>";
        echo "</tr>";

        $no++;
      }

      // total
      $total_all = 0;
      echo "<tr class='table-secondary'>";
        echo "<td colspan='7'>Total</td>";
        echo "<td id='number'>".format_number($total_last_2year,$amount_format,$comma_digit)."</td>"; //$total_all+=$total_last_2year;
        echo "<td id='number'>".format_number($total_last_year,$amount_format,$comma_digit)."</td>"; //$total_all+=$total_last_year;
        foreach($months as $row2){
            echo "<td id='number'>".format_number($total["total_".$year."_".$row2],$amount_format,$comma_digit)."</td>";
            $total_all+=$total["total_".$year."_".$row2];
        }

        echo "<td id='number'>".format_number($total_all,$amount_format,$comma_digit)."</td>";
        echo "<td id='number'>-</td>";
      echo "</tr>";
      //--

    echo "</tbody>";

  ?>
</table>
