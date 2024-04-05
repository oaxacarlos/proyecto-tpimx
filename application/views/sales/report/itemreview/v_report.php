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

<button class="btn btn-success btn-sm"  id="btn_export_xlsx_itemreview" style="margin-bottom:20px;">EXCEL</button>

<table class="table table-bordered table-striped table-sm" id="tbl_report_itemreview">
  <thead>
    <tr>
      <th id="title">Item Code</th>
      <th id="title">Item Name</th>
      <th id="title">Item Cat</th>
      <th id="title">Annual Sales Qty <?php echo $last_4year; ?></th>
      <th id="title">Annual Sales Qty <?php echo $last_3year; ?></th>
      <th id="title">No.Cust Buy <?php echo $last_3year; ?></th>
      <th id="title">Annual Sales Qty <?php echo $last_2year; ?></th>
      <th id="title">No.Cust Buy <?php echo $last_2year; ?></th>
      <th id="title">Annual Sales Qty <?php echo $last_year; ?></th>
      <th id="title">No.Cust Buy <?php echo $last_year; ?></th>
      <th id="title">Annual Sales Qty <?php echo $this_year; ?></th>
      <th id="title">No.Cust Buy <?php echo $this_year; ?></th>
      <th id="title">COGS <?php echo $this_year; ?></th>
      <th id="title">GP Percent <?php echo $this_year; ?></th>
      <th id="title">GP Percent <?php echo $last_year; ?></th>
      <th id="title">AVG Sell Price <?php echo $last_year; ?></th>
    </tr>
  </thead>
  <tbody>
    <?php
      foreach($var_data as $row){
          echo "<tr>";
            echo "<td>".$row["code"]."</td>";
            echo "<td>".$row["name"]."</td>";
            echo "<td>".$row["item_category_codee"]."</td>";
            echo "<td id='number'>".format_number($row["qty_".$last_4year],1,0)."</td>";
            echo "<td id='number'>".format_number($row["qty_".$last_3year],1,0)."</td>";
            echo "<td id='number'>".format_number($row["cust_buy_".$last_3year],1,0)."</td>";
            echo "<td id='number'>".format_number($row["qty_".$last_2year],1,0)."</td>";
            echo "<td id='number'>".format_number($row["cust_buy_".$last_2year],1,0)."</td>";
            echo "<td id='number'>".format_number($row["qty_".$last_year],1,0)."</td>";
            echo "<td id='number'>".format_number($row["cust_buy_".$last_year],1,0)."</td>";
            echo "<td id='number'>".format_number($row["qty_".$this_year],1,0)."</td>";
            echo "<td id='number'>".format_number($row["cust_buy_".$this_year],1,0)."</td>";
            echo "<td id='number'>".format_number($row["cogs"],1,2)."</td>";
            echo "<td id='number'>".format_number($row["gp_percent_".$this_year],1,2)."</td>";
            echo "<td id='number'>".format_number($row["gp_percent_".$last_year],1,2)."</td>";
            echo "<td id='number'>".format_number($row["avg_sell_price"],1,2)."</td>";
          echo "</tr>";
      }
    ?>
  </tbody>
</table>

<script>
// product review
var table2excel3 = new Table2Excel();
document.getElementById('btn_export_xlsx_itemreview').addEventListener('click', function() {
  alert("Your converted to Excel, check your DOWNLOAD folder");
  setTimeout(table2excel3.export(document.querySelector('#tbl_report_itemreview'),"ItemReview-"),1000);
});
//---
</script>
