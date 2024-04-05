
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

  /*th { position: sticky; top: 0; }*/

</style>

<div class="containter-fluid">
  <?php if(isset($_SESSION['user_permis']["7"])){ ?>
    <button class="btn btn-success btn-sm" onclick=f_convert_excel_sales_item_cat_report()>EXCEL</button>
  <?php } ?>

  <?php if(isset($_SESSION['user_permis']["8"])){ ?>
    <button class='btn btn-primary btn-sm' id="copy_button_item_cat_report" style='margin-left:20px;'>Copy ALL</button>
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
$total_all_this_year = 0;

// total per cat
foreach($var_report as $row){
    $summary_cat[$row["item_category_code"]][$last_year] = 0;
    $summary_cat[$row["item_category_code"]][$last_2year] = 0;
    $summary_cat[$row["item_category_code"]][$si_last_year_this_month] = 0;

    foreach($months as $row2){
        $summary_cat[$row["item_category_code"]][$year.$row2] = 0;
        $summary_cat[$row["item_category_code"]]["total_item_cat_this_year"] = 0;
    }
}
//---

//---

// calculate
unset($summary_amount);
unset($summary_cost);
unset($summary);
foreach($var_report as $row){

  $total_sales_amount_this_year+=$row["line_amount_this_year"];
  $total_cost_amount_this_year+=$row["line_cost_amount_this_year"];
  $summary_amount[$row["item_category_code"]] = $summary_amount[$row["item_category_code"]] + $row["line_amount_this_year"];
  $summary_cost[$row["item_category_code"]] = $summary_cost[$row["item_category_code"]] + $row["line_cost_amount_this_year"];

  // calculate per category
  $summary_cat[$row["item_category_code"]][$last_year] += $row["si_qty_last_year"];
  $summary_cat[$row["item_category_code"]][$last_2year] += $row["si_qty_last_2year"];
  $summary_cat[$row["item_category_code"]]["si_last_year_this_month"] += $row["si_last_year_this_month"];

  foreach($months as $row2){
      $summary_cat[$row["item_category_code"]][$year.$row2] += $row["now_".$year."_".$row2];
      $summary_cat[$row["item_category_code"]]["total_item_cat_this_year"] += $row["now_".$year."_".$row2];
      $total_all_this_year += $row["now_".$year."_".$row2];
  }
  //---
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

<table class="table table-bordered table-striped table-sm" id="tbl_report_item_cat" style="margin-top:10px;" onselectstart="return false">
  <?php
    echo "<thead>";
      echo "<tr>";
        echo "<th rowspan='2' id='title' class='table-dark'>No</th>";
        echo "<th rowspan='2' id='title' class='table-dark'>Category</th>";
        echo "<th rowspan='2' id='title' class='table-dark'>Type</th>";
        echo "<th id='title' class='table-dark'>Last 2 Year</th>";
        echo "<th id='title' class='table-dark'>Last Year</th>";
        echo "<th colspan='12' id='title' class='table-dark'>This Year (".$year.")</th>";
        echo "<th rowspan='2' id='title' class='table-dark'>%<br>(".$year.")</th>";
        echo "<th rowspan='2' id='title' class='table-dark'>Total<br>(".$year.")</th>";
        echo "<th rowspan='2' id='title' class='table-dark'>YTD<br>".$month_name_last_year."<br>(".$last_year.")</th>";

        //if(isset($_SESSION['user_permis']["26"])) echo "<th rowspan='2' id='title' class='table-dark'>% GP<br>(".$year.")</th>";
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
      $item_cat_temp = "";
      foreach($var_report as $row){
        $total_per_line = 0;

        if($first_character == "1"){
          if($item_cat_temp == ""){
              $item_cat_temp = $row["item_category_code"];
          }
          else{
              if($item_cat_temp != $row["item_category_code"]){
                  echo "<tr class='table-info'>";
                    echo "<td></td>";
                    echo "<td>".$item_cat_temp."</td>";
                    echo "<td>TOTAL</td>";
                    echo "<td id='number'>".format_number($summary_cat[$item_cat_temp][$last_2year],0,2)."</td>";
                    echo "<td id='number'>".format_number($summary_cat[$item_cat_temp][$last_year],0,2)."</td>";
                    foreach($months as $row2){
                      echo "<td id='number'>".format_number($summary_cat[$item_cat_temp][$year.$row2],0,2)."</td>";
                    }

                    echo "<td id='number'>".percentage($summary_cat[$item_cat_temp]["total_item_cat_this_year"],$total_all_this_year)."</td>";
                    echo "<td id='number'>".format_number($summary_cat[$item_cat_temp]["total_item_cat_this_year"],0,2)."</td>";
                    echo "<td id='number'>".format_number($summary_cat[$item_cat_temp]["si_last_year_this_month"],0,2)."</td>";

                  echo "</tr>";

                  $item_cat_temp = $row["item_category_code"];
              }
          }
        }

        echo "<tr>";
          echo "<td>".$no."</td>";
          echo "<td>".$row["item_category_code"]."</td>";
          echo "<td>".$row["item_desc"]."</td>";
          echo "<td id='number'>".format_number($row["si_qty_last_2year"],$amount_format,$comma_digit)."</td>"; $total_last_2year+=$row["si_qty_last_2year"]; //$total_per_line+=$row["si_qty_last_2year"];
          echo "<td id='number'>".format_number($row["si_qty_last_year"],$amount_format,$comma_digit)."</td>"; $total_last_year+=$row["si_qty_last_year"]; //$total_per_line+=$row["si_qty_last_year"];

          foreach($months as $row2){
              echo "<td id='number'>".format_number($row["now_".$year."_".$row2],$amount_format,$comma_digit)."</td>"; $total_per_line+=$row["now_".$year."_".$row2];
              $total["total_".$year."_".$row2]+=$row["now_".$year."_".$row2];
          }

          //if($total_per_line < $row["si_last_year_this_month"]) $total_per_line_style="danger";
          //else $total_per_line_style="secondary";

          echo "<td id='number'>".percentage($total_per_line,$total_all_this_year)."</td>";
          echo "<td class='table-".$total_per_line_style."' id='number'>".format_number($total_per_line,$amount_format,$comma_digit)."</td>";

          echo "<td class='table-secondary' id='number'>".format_number($row["si_last_year_this_month"],$amount_format,$comma_digit)."</td>";
          $total_last_year_this_month+=$row["si_last_year_this_month"];

          /*if(isset($_SESSION['user_permis']["26"])){
            if($row["gp_percent"] == 0) echo "<td id='number'>-</td>";
            else{
               echo "<td id='number'>".format_number($row["gp_percent"]-$minus_gp, 1, 2)."</td>";
               //echo "<td id='number'>-</td>";
               $gp_total += $row["gp_percent"]-$minus_gp;
             }
          }*/

        echo "</tr>";

        $no++;
      }

      if($first_character == "1"){
        // last category
        echo "<tr class='table-info'>";
          echo "<td></td>";
          echo "<td>".$item_cat_temp."</td>";
          echo "<td>TOTAL</td>";
          echo "<td id='number'>".format_number($summary_cat[$item_cat_temp][$last_2year],0,2)."</td>";
          echo "<td id='number'>".format_number($summary_cat[$item_cat_temp][$last_year],0,2)."</td>";
          foreach($months as $row2){
            echo "<td id='number'>".format_number($summary_cat[$item_cat_temp][$year.$row2],0,2)."</td>";
          }

          echo "<td id='number'>".percentage($summary_cat[$item_cat_temp]["total_item_cat_this_year"],$total_all_this_year)."</td>";
          echo "<td id='number'>".format_number($summary_cat[$item_cat_temp]["total_item_cat_this_year"],0,2)."</td>";
          echo "<td id='number'>".format_number($summary_cat[$item_cat_temp]["si_last_year_this_month"],0,2)."</td>";

        echo "</tr>";
        //--
      }

      // total
      $total_all = 0;
      echo "<tr class='table-secondary' style='font-weight:bold;'>";
        echo "<td colspan='3'>Total ALL</td>";
        echo "<td id='number'>".format_number($total_last_2year,$amount_format,$comma_digit)."</td>"; //$total_all+=$total_last_2year;
        echo "<td id='number'>".format_number($total_last_year,$amount_format,$comma_digit)."</td>"; //$total_all+=$total_last_year;

        foreach($months as $row2){
            echo "<td id='number'>".format_number($total["total_".$year."_".$row2],$amount_format,$comma_digit)."</td>";
            $total_all+=$total["total_".$year."_".$row2];
        }

        echo "<td></td>";
        echo "<td id='number'>".format_number($total_all,$amount_format,$comma_digit)."</td>";
        echo "<td id='number'>".format_number($total_last_year_this_month,$amount_format,$comma_digit)."</td>";

        /*if(isset($_SESSION['user_permis']["26"])){
          echo "<td id='number'>".format_number($gp_total/100,1,2)."</td>";
        }*/

      echo "</tr>";
      //--

    echo "</tbody>";

  ?>
</table>
