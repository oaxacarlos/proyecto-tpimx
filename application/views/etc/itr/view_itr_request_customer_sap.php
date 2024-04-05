<script>
$(document).ready(function() {
    $('#DataTableCustSap').DataTable();
} );

</script>

<table id="DataTableCustSap" class="table table-striped table-sm" style="width:100%">
  <thead>
      <tr>
        <th>CustCode</th>
        <th>CustName</th>
        <th>Region</th>
        <th>Action</th>
      </tr>
  </thead>
  <tbody>
    <?php
      $i = 1;
      foreach($v_list_cust_sap as $row){
          echo "<tr>";
          echo "<td>".$row['custno']."</td>";
          echo "<td>".$row['custname']."</td>";
          echo "<td>".$row['region']."</td>";

          $custname = str_replace(" ","|",$row['custname']);
          echo "<td><button class='btn btn-sm btn-primary' onclick=choose_cust_sap('".$row['custno']."','".$custname."')>select</td>";

          echo "</tr>";
          $i++;
      }
    ?>
  </tbody>
</table>

<script>
  function choose_cust_sap(custno,custname){
      var custname1 = custname.split("|");
      var new_custname = "";

      for(i=0; i < custname1.length; i++)
      { new_custname = new_custname + custname1[i] + " "; }

      document.getElementById('itr_customer').value = custno+"-"+new_custname;
      $('#myModalCustSap').modal('hide');
      $("#itr_customer").prop('disabled', true);
  }
</script>
