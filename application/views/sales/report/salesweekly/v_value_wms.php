<style>
  td#number{
    text-align: right;
  }
</style>

<div class="container text-right">
  <button class="btn btn-info text-right" onclick=gen_report_salesnational_view_total_value_wms()>refresh</button>
</div>
<table class='table table-bordered table-striped' style='margin-top:20px;'>
  <tr>
    <th>Total WMS Value</th>
    <td id='number'><?php echo number_format($var_detail["total_wms_value"],2) ?></td>
  </tr>
</table>
