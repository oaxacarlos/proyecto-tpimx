<script>
$(document).ready(function() {
    $('#tbl_report_salescontribution').DataTable({
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

<div class="containter-fluid">
    <button class="btn btn-success btn-sm"  id="btn_export_xlsx_salescontribution">EXCEL</button>
    <button class='btn btn-primary btn-sm' id="copy_button_salescontribution" style='margin-left:20px;'>Copy ALL</button>
</div>

<table class="table table-bordered table-striped table-sm" id="tbl_report_salescontribution" style="margin-top:10px;">
  <?php

    // calculate total sales amount
    $total_qty_this_year = 0;
    foreach($var_report as $row){
      foreach($months as $row2){
            $total_qty_this_year+=$row["now_".$year."_".$row2];
      }
    }
    //--

    // calculate total sales amount
    $total_sales_amount_this_year = 0;
    $total_cost_amount_this_year = 0;
    foreach($var_report as $row){
      $total_sales_amount_this_year+=$row["line_amount_this_year"];
      $total_cost_amount_this_year+=$row["line_cost_amount_this_year"];
    }
    //--

    // calculate % sales contribution
    foreach($var_report as $index => $row){
        $total_qty_item_this_year = 0;
        foreach($months as $row2){
              $total_qty_item_this_year+=$row["now_".$year."_".$row2];
        }

        $sls_contrib = percentage($total_qty_item_this_year, $total_qty_this_year);
        $var_report[$index]["sls_contrib"] = $sls_contrib;
        $var_report[$index]["qty_this_year"] = $total_qty_item_this_year;
    }
    //---

    array_multisort(array_column($var_report, 'qty_this_year'), SORT_DESC, SORT_NATURAL|SORT_FLAG_CASE, $var_report); // sort

    // put color
    $contrib_max = 80;
    $contrib_run = 0;

    foreach($var_report as $index => $row){
        $contrib_run += $row["sls_contrib"];
        if($contrib_run <= 80){
            $var_report[$index]["color"] = "1";
        }
        else{
            $var_report[$index]["color"] = "0";
        }
    }
    //---

    echo "<thead>";
      echo "<tr>";
        echo "<td colspan='2'>Year</td>";
        echo "<td>".$year."</td>";
        echo "<td colspan='14'></td>";
      echo "</tr>";
    echo "</thead>";

    echo "<thead>";
      echo "<tr><td colspan='17'></td></tr>";
    echo "</thead>";

    echo "<thead>";
      echo "<tr>";
        echo "<th rowspan='2' id='title' class='table-dark'>No</th>";
        echo "<th rowspan='2' id='title' class='table-dark'>Item No</th>";
        echo "<th rowspan='2' id='title' class='table-dark'>Type of Product</th>";
        echo "<th id='title' class='table-dark'>Cust Buy </th>";
        echo "<th id='title' class='table-dark'>Cust Buy </th>";
        echo "<th id='title' class='table-dark'>Last 2 Year</th>";
        echo "<th id='title' class='table-dark'>Last Year</th>";
        echo "<th colspan='12' id='title' class='table-dark'>This Year (".$year.")</th>";
        echo "<th rowspan='2' id='title' class='table-dark'>Total<br>(".$year.")</th>";
        //echo "<th rowspan='2' id='title' class='table-dark'>Total Sales Amount<br>(".$year.")</th>";
        echo "<th rowspan='2' id='title' class='table-dark'>% GP<br>(".$year.")</th>";
        echo "<th rowspan='2' id='title' class='table-dark'>% Sales Contribution<br>(".$year.")</th>";
      echo "</tr>";

      echo "<tr>";
        echo "<th id='title' class='table-dark'>".$last_2year."</th>";
        echo "<th id='title' class='table-dark'>".$last_year."</th>";
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
      $total_cust_buy_this_year = 0;
      $total_cust_buy_last_year = 0;
      foreach($months as $row2){ $total["total_".$year."_".$row2]=0;}
      //---

      $no=1;
      foreach($var_report as $row){
        $total_per_line = 0;

        if($row["color"] == 1) $color = "table-warning";
        else $color = "";

        echo "<tr class='".$color."'>";
          echo "<td>".$no."</td>";
          echo "<td>".$row["item_no"]."</td>";
          echo "<td>".$row["item_category_code"]."</td>";

          echo "<td id='number'>".format_number($row["cust_buy_last_year"],$amount_format,$comma_digit)."</td>";
          $total_cust_buy_last_year+=$row["cust_buy_last_year"];

          echo "<td id='number'>".format_number($row["cust_buy_this_year"],$amount_format,$comma_digit)."</td>";
          $total_cust_buy_this_year+=$row["cust_buy_this_year"];

          echo "<td id='number'>".format_number($row["si_qty_last_2year"],$amount_format,$comma_digit)."</td>"; $total_last_2year+=$row["si_qty_last_2year"]; //$total_per_line+=$row["si_qty_last_2year"];
          echo "<td id='number'>".format_number($row["si_qty_last_year"],$amount_format,$comma_digit)."</td>"; $total_last_year+=$row["si_qty_last_year"]; //$total_per_line+=$row["si_qty_last_year"];

          foreach($months as $row2){
              echo "<td id='number'>".format_number($row["now_".$year."_".$row2],$amount_format,$comma_digit)."</td>"; $total_per_line+=$row["now_".$year."_".$row2];
              $total["total_".$year."_".$row2]+=$row["now_".$year."_".$row2];
          }

          echo "<td class='table-secondary' id='number'>".format_number($total_per_line,$amount_format,$comma_digit)."</td>";
          //echo "<td class='table-secondary' id='number'>".format_number($row["line_amount_this_year"],1,2)."</td>";

          echo "<td class='table-secondary' id='number'>".convert_number3($row["gp_percent"])."</td>";

          $sls_contrib = $row["sls_contrib"];
          $total_sls_contrib+=$sls_contrib;

          echo "<td class='table-secondary' id='number'>".convert_number3($sls_contrib)."</td>";
          $total_sls_contrib+=$row["sales_contri_percent"];
        echo "</tr>";

        $no++;
      }

      // total
      $total_all = 0;
      echo "<tr class='table-secondary'>";
        echo "<td colspan='3'>Total</td>";
        echo "<td colspan='2'></td>";
        echo "<td id='number'>".format_number($total_last_2year,$amount_format,$comma_digit)."</td>"; //$total_all+=$total_last_2year;
        echo "<td id='number'>".format_number($total_last_year,$amount_format,$comma_digit)."</td>"; //$total_all+=$total_last_year;
        //echo "<td id='number'>".format_number($total_cust_buy_last_year,$amount_format,$comma_digit)."</td>"; //$total_all+=$total_last_2year;
        //echo "<td id='number'>".format_number($total_cust_buy_this_year,$amount_format,$comma_digit)."</td>"; //$total_all+=$total_last_year;



        foreach($months as $row2){
            echo "<td id='number'>".format_number($total["total_".$year."_".$row2],$amount_format,$comma_digit)."</td>";
            $total_all+=$total["total_".$year."_".$row2];
        }

        echo "<td id='number'>".format_number($total_all,$amount_format,$comma_digit)."</td>";
        echo "<td id='number'>".format_number($total_sales_amount_this_year,0,2)."</td>";
        echo "<td id='number'>".percentage($total_sales_amount_this_year-$total_cost_amount_this_year, $total_sales_amount_this_year)."</td>";
        echo "<td id='number'>".round($total_sls_contrib,2)."</td>";
      echo "</tr>";
      //--

    echo "</tbody>";

  ?>
</table>

<script>
// product review
var table2excel3 = new Table2Excel();
document.getElementById('btn_export_xlsx_salescontribution').addEventListener('click', function() {
  alert("Your converted to Excel, check your DOWNLOAD folder");
  setTimeout(table2excel3.export(document.querySelector('#tbl_report_salescontribution'),"Contribution8020-"),1000);
});
//---
</script>
