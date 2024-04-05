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
            title : 'TPM-salesyear'
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
      <th>total <?php echo $var_lastyear?></th>

      <th>Ene <?php echo $var_lastyear?></th>
      <th>Ene <?php echo $var_year?></th>
      <th>Dif 01</th>
      <th>Feb <?php echo $var_lastyear?></th>
      <th>Feb <?php echo $var_year?></th>
      <th>Dif 02</th>
      <th>Mar <?php echo $var_lastyear?></th>
      <th>Mar <?php echo $var_year?></th>
      <th>Dif 03</th>
      <th>Abr <?php echo $var_lastyear?></th>
      <th>Abr <?php echo $var_year?></th>
      <th>Dif 04</th>
      <th>May <?php echo $var_lastyear?></th>
      <th>May <?php echo $var_year?></th>
      <th>Dif 05</th>
      <th>Jun <?php echo $var_lastyear?></th>
      <th>Jun <?php echo $var_year?></th>
      <th>Dif 06</th>
      <th>Jul <?php echo $var_lastyear?></th>
      <th>Jul <?php echo $var_year?></th>
      <th>Dif 07</th>
      <th>Ago <?php echo $var_lastyear?></th>
      <th>Ago <?php echo $var_year?></th>
      <th>Dif 08</th>
      <th>Sep <?php echo $var_lastyear?></th>
      <th>Sep <?php echo $var_year?></th>
      <th>Dif 09</th>
      <th>Oct <?php echo $var_lastyear?></th>
      <th>Oct <?php echo $var_year?></th>
      <th>Dif 10</th>
      <th>Nov <?php echo $var_lastyear?></th>
      <th>Nov <?php echo $var_year?></th>
      <th>Dif 11</th>
      <th>Dic <?php echo $var_lastyear?></th>
      <th>Dic <?php echo $var_year?></th>
      <th>Dif 12</th>
      <th>total <?php echo $var_year?></th>
      <th>Dif</th>
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
            echo "<td>".$row["total_".$var_lastyear]."</td>";
            echo "<td>".$row["ene_".$var_year]."</td>";
            echo "<td>".$row["ene_".$var_lastyear]."</td>";
            if ($row["diference01"] > 0)  {echo "<td>".$row["diference01"]."</td>"; }else echo "<td class='text-danger'>".$row["diference01"]."</td>";
            echo "<td>".$row["feb_".$var_year]."</td>";
            echo "<td>".$row["feb_".$var_lastyear]."</td>";
            if ($row["diference02"] > 0)  {echo "<td>".$row["diference02"]."</td>"; }else echo "<td class='text-danger'>".$row["diference02"]."</td>";
            echo "<td>".$row["mar_".$var_year]."</td>";
            echo "<td>".$row["mar_".$var_lastyear]."</td>";
            if ($row["diference03"] > 0)  {echo "<td>".$row["diference03"]."</td>"; }else echo "<td class='text-danger'>".$row["diference03"]."</td>";
            echo "<td>".$row["abr_".$var_year]."</td>";
            echo "<td>".$row["abr_".$var_lastyear]."</td>";
            if ($row["diference04"] > 0)  {echo "<td>".$row["diference04"]."</td>"; }else echo "<td class='text-danger'>".$row["diference04"]."</td>";
            echo "<td>".$row["may_".$var_year]."</td>";
            echo "<td>".$row["may_".$var_lastyear]."</td>";
            if ($row["diference05"] > 0)  {echo "<td>".$row["diference05"]."</td>"; }else echo "<td class='text-danger'>".$row["diference05"]."</td>";
            echo "<td>".$row["jun_".$var_year]."</td>";
            echo "<td>".$row["jun_".$var_lastyear]."</td>";
            if ($row["diference06"] > 0)  {echo "<td>".$row["diference06"]."</td>"; }else echo "<td class='text-danger'>".$row["diference06"]."</td>";
            echo "<td>".$row["jul_".$var_year]."</td>";
            echo "<td>".$row["jul_".$var_lastyear]."</td>";
            if ($row["diference07"] > 0)  {echo "<td>".$row["diference07"]."</td>"; }else echo "<td class='text-danger'>".$row["diference07"]."</td>";
            echo "<td>".$row["ago_".$var_year]."</td>";
            echo "<td>".$row["ago_".$var_lastyear]."</td>";
            if ($row["diference08"] > 0)  {echo "<td>".$row["diference08"]."</td>"; }else echo "<td class='text-danger'>".$row["diference08"]."</td>";
            echo "<td>".$row["sep_".$var_year]."</td>";
            echo "<td>".$row["sep_".$var_lastyear]."</td>";
            if ($row["diference09"] > 0)  {echo "<td>".$row["diference09"]."</td>"; }else echo "<td class='text-danger'>".$row["diference09"]."</td>";
            echo "<td>".$row["oct_".$var_year]."</td>";
            echo "<td>".$row["oct_".$var_lastyear]."</td>";
            if ($row["diference10"] > 0)  {echo "<td>".$row["diference10"]."</td>"; }else echo "<td class='text-danger'>".$row["diference10"]."</td>";
            echo "<td>".$row["nov_".$var_year]."</td>";
            echo "<td>".$row["nov_".$var_lastyear]."</td>";
            if ($row["diference11"] > 0)  {echo "<td>".$row["diference11"]."</td>"; }else echo "<td class='text-danger'>".$row["diference11"]."</td>";
            echo "<td>".$row["dic_".$var_year]."</td>";
            echo "<td>".$row["dic_".$var_lastyear]."</td>";
            if ($row["diference12"] > 0)  {echo "<td>".$row["diference12"]."</td>"; }else echo "<td class='text-danger'>".$row["diference12"]."</td>";
            echo "<td>".$row["total_".$var_year]."</td>";
            echo "<td>".$row["diference_year"]."</td>";
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