<script>
$(document).ready(function() {
    $('#tbl_report').DataTable({
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
  <?php if(isset($_SESSION['user_permis']["1"])){ ?>
    <button class="btn btn-success btn-sm"  id="btn_export_xlsx">EXCEL</button>
  <?php } ?>

  <?php if(isset($_SESSION['user_permis']["2"])){ ?>
    <button class='btn btn-primary btn-sm' id="copy_button_report" style='margin-left:20px;'>Copy ALL</button>
  <?php } ?>
</div>

<table class="table table-bordered table-striped table-sm" id="tbl_report" style="margin-top:10px;">
  <?php
    echo "<thead>";
      echo "<tr'>";
        echo "<td colspan='2'>Customer No</td>";
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
    echo "</thead>";

    echo "<thead>";
      echo "<tr><td colspan='17'></td></tr>";
    echo "</thead>";

    echo "<thead>";
      echo "<tr>";
        echo "<th rowspan='2' id='title' class='table-dark'>No</th>";
        echo "<th rowspan='2' id='title' class='table-dark'>Item No</th>";
        echo "<th id='title' class='table-dark'>Last 2 Year</th>";
        echo "<th id='title' class='table-dark'>Last Year</th>";
        echo "<th colspan='12' id='title' class='table-dark'>This Year (".$year.")</th>";
        echo "<th rowspan='2' id='title' class='table-dark'>Total<br>(".$year.")</th>";
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
      foreach($months as $row2){ $total["total_".$year."_".$row2]=0;}
      //---

      $no=1;
      foreach($var_report as $row){
        $total_per_line = 0;

        echo "<tr>";
          echo "<td>".$no."</td>";
          echo "<td>".$row["item_no"]."</td>";
          echo "<td id='number'>".format_number($row["si_qty_last_2year"],$amount_format,$comma_digit)."</td>"; $total_last_2year+=$row["si_qty_last_2year"]; //$total_per_line+=$row["si_qty_last_2year"];
          echo "<td id='number'>".format_number($row["si_qty_last_year"],$amount_format,$comma_digit)."</td>"; $total_last_year+=$row["si_qty_last_year"]; //$total_per_line+=$row["si_qty_last_year"];

          foreach($months as $row2){
              echo "<td id='number'>".format_number($row["now_".$year."_".$row2],$amount_format,$comma_digit)."</td>"; $total_per_line+=$row["now_".$year."_".$row2];
              $total["total_".$year."_".$row2]+=$row["now_".$year."_".$row2];
          }

          echo "<td class='table-secondary' id='number'>".format_number($total_per_line,$amount_format,$comma_digit)."</td>";
        echo "</tr>";

        $no++;
      }

      // total
      $total_all = 0;
      echo "<tr class='table-secondary'>";
        echo "<td colspan='2'>Total</td>";
        echo "<td id='number'>".format_number($total_last_2year,$amount_format,$comma_digit)."</td>"; //$total_all+=$total_last_2year;
        echo "<td id='number'>".format_number($total_last_year,$amount_format,$comma_digit)."</td>"; //$total_all+=$total_last_year;

        foreach($months as $row2){
            echo "<td id='number'>".format_number($total["total_".$year."_".$row2],$amount_format,$comma_digit)."</td>";
            $total_all+=$total["total_".$year."_".$row2];
        }

        echo "<td id='number'>".format_number($total_all,$amount_format,$comma_digit)."</td>";
      echo "</tr>";
      //--

    echo "</tbody>";

  ?>
</table>

<script>
//sales report
var table2excel = new Table2Excel();
document.getElementById('btn_export_xlsx').addEventListener('click', function() {
  alert("Your converted to Excel, check your DOWNLOAD folder");
  var cust_code = $("#inp_cust_code").val();
  setTimeout(table2excel.export(document.querySelector('#tbl_report'),"CustSlsReport-"+cust_code),1000);
});
//--
</script>
