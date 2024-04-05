<script>
$(document).ready(function() {
    $('#tbl_report_producttype').DataTable({
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
  The parameter of this report is only Year, no Customer.
</div>

<div class="containter-fluid">
  <?php if(isset($_SESSION['user_permis']["5"])){ ?>
    <button class="btn btn-success btn-sm"  id="btn_export_xlsx_producttype">EXCEL</button>
  <?php } ?>

  <?php if(isset($_SESSION['user_permis']["6"])){ ?>
    <button class='btn btn-primary btn-sm' id="copy_button_producttype" style='margin-left:20px;'>Copy ALL</button>
  <?php } ?>
</div>

<table class="table table-bordered table-striped table-sm" id="tbl_report_producttype" style="margin-top:10px;">
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
      echo "<tr><td colspan='17'></td></tr>";
    echo "</thead>";

    echo "<thead>";
      echo "<tr>";
        echo "<th rowspan='2' id='title' class='table-dark' width='10px'></th>";
        echo "<th rowspan='2' id='title' class='table-dark'>No</th>";
        echo "<th rowspan='2' id='title' class='table-dark'>Item No</th>";
        echo "<th rowspan='2' id='title' class='table-dark'>Type of Product</th>";
        echo "<th id='title' class='table-dark'>Last 2 Year</th>";
        echo "<th id='title' class='table-dark'>Last Year</th>";
        echo "<th colspan='12' id='title' class='table-dark'>This Year (".$year.")</th>";
        echo "<th rowspan='2' id='title' class='table-dark'>Total<br>(".$year.")</th>";
        echo "<th rowspan='2' id='title' class='table-dark'>GP %<br>(".$year.")</th>";
        echo "<th rowspan='2' id='title' class='table-dark'>Total Sales Amount<br>(".$year.")</th>";
        echo "<th rowspan='2' id='title' class='table-dark'>% Sales Contribution<br>(".$year.")</th>";
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
      foreach($var_report_item as $row_item){
          $total_per_line_item = 0;
          echo "<tr>";
          echo "<td><button class='btn btn-primary btn-xs' data-toggle='collapse' href='#detail_".$row_item["item_no"]."'>+</button></td>";
          echo "<td>".$no."</td>";
          echo "<td>".$row_item["item_no"]."</td>";
          echo "<td>".$row_item["item_category_code"]."</td>";
          echo "<td id='number'>".format_number($row_item["si_qty_last_2year"],$amount_format,$comma_digit)."</td>"; $total_last_2year+=$row_item["si_qty_last_2year"]; //$total_per_line+=$row["si_qty_last_2year"];
          echo "<td id='number'>".format_number($row_item["si_qty_last_year"],$amount_format,$comma_digit)."</td>"; $total_last_year+=$row_item["si_qty_last_year"]; //$total_per_line+=$row["si_qty_last_year"];

          foreach($months as $row2){
              echo "<td id='number'>".format_number($row_item["now_".$year."_".$row2],$amount_format,$comma_digit)."</td>"; $total_per_line+=$row_item["now_".$year."_".$row2];
              $total["total_".$year."_".$row2]+=$row_item["now_".$year."_".$row2];
          }

          echo "<td class='table-secondary' id='number'>".format_number($total_per_line,$amount_format,$comma_digit)."</td>";
          echo "<td class='table-secondary' id='number'>".$row_item["gp_percent"]."</td>";
          echo "<td class='table-secondary' id='number'>".format_number($row_item["line_amount_this_year"],1,2)."</td>";

          $sls_contrib = percentage($row_item["line_amount_this_year"], $total_sales_amount_this_year);
          $total_sls_contrib+=$sls_contrib;

          echo "<td class='table-secondary' id='number'>".$sls_contrib."</td>";

          echo "</tr>";

          // print detail
          /*foreach($var_report_cust[$row_item["item_no"]] as $row_cust){
            echo "<tr>";
              echo "<td></td><td></td>";
              echo "<td colspan='2'>".$row_cust["customer"]."</td>";
              echo "<td id='number'>".format_number($row_cust["si_qty_last_2year"],$amount_format,$comma_digit)."</td>"; $total_last_2year+=$row_cust["si_qty_last_2year"]; //$total_per_line+=$row["si_qty_last_2year"];
              echo "<td id='number'>".format_number($row_cust["si_qty_last_year"],$amount_format,$comma_digit)."</td>"; $total_last_year+=$row_cust["si_qty_last_year"]; //$total_per_line+=$row["si_qty_last_year"];

              foreach($months as $row2){
                  echo "<td id='number'>".format_number($row_cust["now_".$year."_".$row2],$amount_format,$comma_digit)."</td>"; $total_per_line+=$row_cust["now_".$year."_".$row2];
                  $total["total_".$year."_".$row2]+=$row_cust["now_".$year."_".$row2];
              }

              echo "<td class='table-secondary' id='number'>".format_number($total_per_line,$amount_format,$comma_digit)."</td>";
              echo "<td class='table-secondary' id='number'>".$row_cust["gp_percent"]."</td>";
              echo "<td class='table-secondary' id='number'>".format_number($row_cust["line_amount_this_year"],1,2)."</td>";

              $sls_contrib = percentage($row_cust["line_amount_this_year"], $total_sales_amount_this_year);
              $total_sls_contrib+=$sls_contrib;

              echo "<td class='table-secondary' id='number'>".$sls_contrib."</td>";

            echo "</tr>";
          }*/
      }



    echo "</tbody>";

  ?>
</table>

<script>
// product review
var table2excel3 = new Table2Excel();
document.getElementById('btn_export_xlsx_producttype').addEventListener('click', function() {
  alert("Your converted to Excel, check your DOWNLOAD folder");
  setTimeout(table2excel3.export(document.querySelector('#tbl_report_producttype'),"ProductType-"),1000);
});
//---
</script>
