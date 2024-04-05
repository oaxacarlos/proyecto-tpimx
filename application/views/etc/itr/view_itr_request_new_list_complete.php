<style>
  tr{
      font-size: 12px;
  }
</style>

<table id="result" class="table table-bordered table-striped table-sm">
  <thead>
    <tr class="thead-dark">
      <th>No</th>
      <th>Status</th>
      <th>DateTime</th>
      <th>ITR Number</th>
      <th>Email</th>
      <th>Customer</th>
      <th>DataDetail</th>
      <th>Document</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
  <?php

  $target_file = "./assets/itrfiles/";

  if($v_list_itr_new_complete == 0){
    echo "<tr><td colspan='9'>No Data Available</td></tr>";
  }
  else{
    $i=1;
    foreach($v_list_itr_new_complete as $row){

        // cek detail data
        if($row['count_detail'] > 0){
          $color_count = "badge-success";
          $text_count = "Succees";
        }
        else{
          $color_count = "badge-danger";
          $text_count = "Failed";
        }
        //-------------

        // check file
        $filename = $target_file.$row['attachment'];
        $file_exist = 0;
        if(file_exists($filename)) {
            $file_exist = 1;
        }

        if($file_exist == 1){
          $file_color = "badge-success";
          $file_text = "Succees";
        }
        else{
          $file_color = "badge-danger";
          $file_text = "Failed";
        }
        //--------------------

        // status---
        if($row['itr_status'] == 'ITRST004') $color_status = "danger";
        else if($row['itr_status'] == 'ITRST001') $color_status = "info";
        else if($row['itr_status'] == 'ITRST002') $color_status = "primary";
        else if($row['itr_status'] == 'ITRST003') $color_status = "success";
        else $color_status = "";
        //----------

        echo "<tr>";
          echo "<td>".$i."</td>";
          echo "<td><span class='badge badge-".$color_status."'>".$row['itr_status_name']."</span></td>";
          echo "<td>".$row['itr_h_created_datetime']."</td>";
          echo "<td>".$row['itr_h_code']."</td>";
          echo "<td>".$row['email_user']."</td>";
          echo "<td>".$row['customer_text']."</td>";
          echo "<td><span class='badge ".$color_count."' style='font-size:14px;'>".$text_count."</span></td>";
          echo "<td><span class='badge ".$file_color."' style='font-size:14px;'>".$file_text."</span></td>";
          echo "<td><button type='button' onclick=f_itr_report_detail('".$row['itr_h_code']."')>view detail</button></td>";
        echo "</tr>";
        $i++;
    }
  }

  ?>
  </tbody>
</table>

<div class="modal" id="myModalItrDetail">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">ITR Detail</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="modal_itr_detail">
      </div>
    </div>
  </div>
</div>


<script>
function f_itr_report_detail(itr_code){
    data = {'itr_code':itr_code}

    $('#modal_itr_detail').html('Loading, Please wait...');
    //open the modal with selected parameter attached
    $('#modal_itr_detail').load(
        "<?php echo base_url();?>index.php/itr_report/show_itr_detail",
        data,                                                  // data
        function(responseText, textStatus, XMLHttpRequest) { } // complete callback
    );

    $('#myModalItrDetail').modal();
}

//-----------
</script>
