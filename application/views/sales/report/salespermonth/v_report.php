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

<script>
$(document).ready(function() {

    $('#DataTable').DataTable({
      dom: 'Bfrtip',
     
        buttons: [
          {
            extend: 'excel',
            title : 'TPM-salesperyear'
          }
        ],
        
    });


});
</script>


<table class="table table-bordered table-striped table-sm" id="DataTable">
  <thead>
    <tr>
      <th>No.</th>
      <th>Category</th>
      <th>Description</th>
      <th>Item No</th>
      <th>Total <?php echo $var_lastyear."-".$var_month ?></th>
      <th>Total <?php echo $var_year."-".$var_month ?></th>
      <th>Diference</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $line_no = 1;
      foreach($var_report as $row){
          echo "<tr>";
            echo "<td>".$line_no."</td>";
            echo "<td>".$row["category"]."</td>";
            echo "<td>".$row["description"]."</td>";
            echo "<td>".$row["item_no"]."</td>";
            echo "<td>".$row["month_1"]."</td>";
            echo "<td>".$row["month_2"]."</td>";
            if ($row["diference"] > 0)  {echo "<td>".$row["diference"]."</td>"; }else echo "<td class='text-danger'>".$row["diference"]."</td>";
    echo "</tr>";
    $line_no++;
      }
    ?>

<script>
    function f_convert_excel_sales_report(){
    var table2excel = new Table2Excel();
    alert("Your converted to Excel, check your DOWNLOAD folder");
    table2excel.export(document.querySelector('#DataTable'),"CustSlsReport");

}

</script>