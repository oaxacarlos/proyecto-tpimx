
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
  <?php if(isset($_SESSION['user_permis']["7"])){ ?>
    <button class="btn btn-success btn-sm" onclick=f_convert_excel_sales_report()>EXCEL</button>
  <?php } ?>

  <?php if(isset($_SESSION['user_permis']["8"])){ ?>
    <button class='btn btn-primary btn-sm' id="copy_button_report" style='margin-left:20px;'>Copy ALL</button>
  <?php } ?>
</div>

<?php

// initial
$total_last_year = 0;
$total_last_2year = 0;
$total_last_year_this_month = 0;
$total_sales_amount_this_year = 0;
$total_cost_amount_this_year = 0;
foreach($months as $row2){ $total["total_".$year."_".$row2]=0;}

if(isset($_SESSION['user_permis']["29"])) $minus_gp = 0;
else $minus_gp = 25;

$gp_total = 0;
//---

// calculate
unset($summary_amount);
unset($summary_cost);
unset($summary);
foreach($var_report as $row){

  $total_sales_amount_this_year+=$row["line_amount_this_year"];
  $total_cost_amount_this_year+=$row["line_cost_amount_this_year"];
  $summary_amount[$row["brand_desc"]] = $summary_amount[$row["brand_desc"]] + $row["line_amount_this_year"];
  $summary_cost[$row["brand_desc"]] = $summary_cost[$row["brand_desc"]] + $row["line_cost_amount_this_year"];
}

$gp_total = percentage($total_sales_amount_this_year-$total_cost_amount_this_year, $total_sales_amount_this_year);

foreach($summary_amount as $key=>$value){
    if($summary_cost[$key] == 0) $summary_cost[$key] = 0;
    else $summary[$key] = ($summary_amount[$key]-$summary_cost[$key])/$summary_amount[$key]*100;
}

if(isset($_SESSION['user_permis']["29"])){

}
else{
    $gp_total = $gp_total-$minus_gp ;
    foreach($summary as $key=>$value){
        $summary[$key] = $summary[$key]-$minus_gp;
    }
}
//--

?>

<table class="table table-bordered table-striped table-sm" id="tbl_report" style="margin-top:10px;">
  <?php
    echo "<thead>";
      echo "<tr'>";
        echo "<td colspan='2'>Customer No 1</td>";
        echo "<td>".$cust_code."</td>";
        echo "<td colspan='14'></td>";
      echo "</tr>";
      echo "<tr>";
        echo "<td colspan='2'>Customer Name</td>";
        echo "<td colspan='3'>".$cust_name."</td>";
        echo "<td colspan='12'></td>";
      echo "</tr>";
      echo "<tr>";
        echo "<td colspan='2'>Year</td>";
        echo "<td>".$year."</td>";
        echo "<td colspan='14'></td>";
      echo "</tr>";

    if(isset($_SESSION['user_permis']["26"])){
        foreach($summary as $key=>$value){
          echo "<tr>";
            echo "<td colspan='2'>% GP - ".$key."</td>";
            echo "<td>".format_number($value,1,2)." %</td>";
            echo "<td colspan='14'></td>";
          echo "</tr>";
        }

        echo "<tr>";
          echo "<td colspan='2'>% GP - TOTAL</td>";
          echo "<td>".format_number($gp_total,1,2)." %</td>";
          echo "<td colspan='14'></td>";
        echo "</tr>";
    }

    echo "</thead>";

    echo "<thead>";
      echo "<tr><td colspan='17'></td></tr>";
    echo "</thead>";

    echo "<thead>";
      echo "<tr>";
        echo "<th rowspan='2' id='title' class='table-dark'>No</th>";
        echo "<th rowspan='2' id='title' class='table-dark'>Category</th>";
        echo "<th rowspan='2' id='title' class='table-dark'>Description</th>";
        echo "<th rowspan='2' id='title' class='table-dark'>Item No</th>";
        echo "<th id='title' class='table-dark'>Last 2 Year</th>";
        echo "<th id='title' class='table-dark'>Last Year</th>";
        echo "<th colspan='12' id='title' class='table-dark'>This Year (".$year.")</th>";
        echo "<th rowspan='2' id='title' class='table-dark'>Total<br>(".$year.")</th>";
        echo "<th rowspan='2' id='title' class='table-dark'>Total<br>".$month_name_last_year."<br>(".$last_year.")</th>";

        if(isset($_SESSION['user_permis']["26"])) echo "<th rowspan='2' id='title' class='table-dark'>% GP<br>(".$year.")</th>";
      echo "</tr>";

      echo "<tr>";
        echo "<th id='title' class='table-dark'>".$last_2year."</th>";
        echo "<th id='title' class='table-dark'>".$last_year."</th>";
        foreach($months as $row){ echo "<th id='title' class='table-dark'>".$row."</th>"; }
      echo "</tr>";
    echo "</thead>";

    echo "<tbody>";

      $no=1;
      $gp_total = 0;
      foreach($var_report as $row){
        $total_per_line = 0;

        echo "<tr>";
          echo "<td>".$no."</td>";
          echo "<td>".$row["brand_desc"]."</td>";
          echo "<td>".$row["item_desc"]."</td>";
          echo "<td>".$row["item_no"]."</td>";
          echo "<td id='number'>".format_number($row["si_qty_last_2year"],$amount_format,$comma_digit)."</td>"; $total_last_2year+=$row["si_qty_last_2year"]; //$total_per_line+=$row["si_qty_last_2year"];
          echo "<td id='number'>".format_number($row["si_qty_last_year"],$amount_format,$comma_digit)."</td>"; $total_last_year+=$row["si_qty_last_year"]; //$total_per_line+=$row["si_qty_last_year"];

          foreach($months as $row2){
              echo "<td id='number'>".format_number($row["now_".$year."_".$row2],$amount_format,$comma_digit)."</td>"; $total_per_line+=$row["now_".$year."_".$row2];
              $total["total_".$year."_".$row2]+=$row["now_".$year."_".$row2];
          }

          if($total_per_line < $row["si_last_year_this_month"]) $total_per_line_style="danger";
          else $total_per_line_style="secondary";

          echo "<td class='table-".$total_per_line_style."' id='number'>".format_number($total_per_line,$amount_format,$comma_digit)."</td>";

          echo "<td class='table-secondary' id='number'>".format_number($row["si_last_year_this_month"],$amount_format,$comma_digit)."</td>";
          $total_last_year_this_month+=$row["si_last_year_this_month"];

          if(isset($_SESSION['user_permis']["26"])){
            if($row["gp_percent"] == 0) echo "<td id='number'>-</td>";
            else{
               echo "<td id='number'>".format_number($row["gp_percent"]-$minus_gp, 1, 2)."</td>";
               //echo "<td id='number'>-</td>";
               $gp_total += $row["gp_percent"]-$minus_gp;
             }
          }

        echo "</tr>";

        $no++;
      }

      // total
      $total_all = 0;
      echo "<tr class='table-secondary'>";
        echo "<td colspan='4'>Total</td>";
        echo "<td id='number'>".format_number($total_last_2year,$amount_format,$comma_digit)."</td>"; //$total_all+=$total_last_2year;
        echo "<td id='number'>".format_number($total_last_year,$amount_format,$comma_digit)."</td>"; //$total_all+=$total_last_year;

        foreach($months as $row2){
            echo "<td id='number'>".format_number($total["total_".$year."_".$row2],$amount_format,$comma_digit)."</td>";
            $total_all+=$total["total_".$year."_".$row2];
        }

        echo "<td id='number'>".format_number($total_all,$amount_format,$comma_digit)."</td>";
        echo "<td id='number'>".format_number($total_last_year_this_month,$amount_format,$comma_digit)."</td>";

        if(isset($_SESSION['user_permis']["26"])){
          echo "<td id='number'>".format_number($gp_total/100,1,2)."</td>";
        }

      echo "</tr>";
      //--

    echo "</tbody>";

  ?>
</table>

<script>
//sales report
function f_convert_excel_sales_report(){
    var table2excel = new Table2Excel();
    alert("Your converted to Excel, check your DOWNLOAD folder");
    table2excel.export(document.querySelector('#tbl_report'),"CustSlsReport");

}
//--

// copy clipboard
var clipboard3 = new ClipboardJS('#copy_button_report', {
  target: function() {
    return document.querySelector('#tbl_report');
  }
});
</script>
