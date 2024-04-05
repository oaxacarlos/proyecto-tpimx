<style>
  tr{
    font-size: 12px;
  }

</style>

<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      GoTo Transfer Bin Process
</div>

<div class="container">

<div class="container" style="margin-bottom:20px;">
  <input type="text" value="<?php echo $doc_no; ?>" id="h_doc_no" class="form-control" readonly>
</div>

<?php

function gen_template($loc,$zone,$area,$rack,$bin,$item,$qty,$uom,$desc,$loc_to,$zone_to,$area_to,$rack_to,$bin_to,$line,$counter,$doc_no, $pick_datetime, $putaway_datetime, $var_pick_all, $var_put_all){
    $from = $loc."-".$zone."-".$area."-".$rack."-".$bin;
    $to = $loc_to."-".$zone_to."-".$area_to."-".$rack_to."-".$bin_to;

    $result ="";
    $result.= "<div class='card' style='margin-bottom:20px;'>";
      $result.="<div class='card-header text-right' style='font-size:12px; font-weight:bold;'>";
        $result.="<span class='badge badge-warning' style='font-size:15px;'>".$from."</span> | <span class='badge badge-info' style='font-size:15px;'>".$to."</span>";
      $result.="</div>";

      $result.="<div class='card-body'>";
        $result.="<table class='table-bordered table-striped' width='100%'>";
          $result.="<tr>";
            $result.="<th>Item</th>";
            $result.="<th>Desc</th>";
            $result.="<th>Qty</th>";
            $result.="<th>Uom</th>";

            // pick all
            $result.="<th style='width:50px;'>PICK ALL<br>";

            if($var_pick_all == 0){
                $result.="<button class='btn btn-warning btn-sm' onclick=f_pick_all('".$doc_no."','".count($item)."') id='btn_pick_all'><i class='bi-chevron-double-up' id='icn_pick_all'></i><i class='bi-check2' style='display:none' id='icn_pickdone_all'></i></button>";
            }
            else{
                $result.="<button class='btn btn-warning btn-sm' disabled id='btn_pick_all'><i class='bi-check2'></i></button>";
            }
            $result.="<br><br></th>";
            //--

            // put all
            $result.="<th style='width:50px;'>PUT ALL<br>";
            if($var_put_all == 0){
                $result.="<button class='btn btn-info btn-sm' onclick=f_put_all('".$doc_no."','".count($item)."') id='btn_put_all'><i class='bi-chevron-double-up' id='icn_put_all'></i><i class='bi-check2' style='display:none' id='icn_putdone_all'></i></button>";
            }
            else{
                $result.="<button class='btn btn-info btn-sm' disabled id='btn_put_all'><i class='bi-check2'></i></button>";
            }

            $result.="<br><br></th>";
            //---

          $result.="</tr>";

          for($i=0;$i<count($item);$i++){
              $result.="<tr>";
                $result.="<td>".$item[$i]."</td>";
                $result.="<td>".$desc[$i]."</td>";
                $result.="<td>".$qty[$i]."</td>";
                $result.="<td>".$uom[$i]."</td>";

                // pick button
                if($pick_datetime[$i] != "")
                    $result.="<td><button class='btn btn-warning btn-sm' disabled id='btn_pick_".$line[$i]."'><i class='bi-check2'></i></button></td>";
                else
                    $result.="<td><button class='btn btn-warning btn-sm' onclick=f_pick('".$doc_no."','".$line[$i]."',".$line[$i].") id='btn_pick_".$line[$i]."'><i class='bi-chevron-double-up' id='icn_pick_".$line[$i]."'></i><i class='bi-check2' style='display:none' id='icn_pickdone_".$line[$i]."'></i></button></td>";

                // put button
                if($pick_datetime[$i] == ""){
                    $result.="<td><button class='btn btn-info btn-sm' onclick=f_put('".$doc_no."','".$line[$i]."',".$line[$i].") id='btn_put_".$line[$i]."' disabled><i class='bi-chevron-double-down' id='icn_put_".$line[$i]."'></i><i class='bi-check2' style='display:none' id='icn_putdone_".$line[$i]."'></i></button></td>";
                }
                else{
                  if($putaway_datetime[$i] != "")
                      $result.="<td><button class='btn btn-info btn-sm' id='btn_put_".$line[$i]."' disabled><i class='bi-check2'></i></button></td>";
                  else
                      $result.="<td><button class='btn btn-info btn-sm' onclick=f_put('".$doc_no."','".$line[$i]."',".$line[$i].") id='btn_put_".$line[$i]."'><i class='bi-chevron-double-down' id='icn_put_".$line[$i]."'></i><i class='bi-check2' style='display:none' id='icn_putdone_".$line[$i]."'></i></button></td>";
                }

              $result.="</tr>";
          }

        $result.="</table>";
      $result.="</div>";
    $result.="</div>";

    return $result;
}
//---

$i=0;
$loc=""; $zone=""; $area=""; $rack=""; $bin="";
$loc_to=""; $zone_to=""; $area_to=""; $rack_to=""; $bin_to="";
unset($item); unset($qty); unset($uom); unset($desc); unset($line);

unset($all_data);

foreach($var_transferbin_d as $row){
    if($loc=="" && $zone=="" && $area=="" && $rack=="" && $bin==""){
        $loc = $row["location_code_from"];
        $zone = $row["zone_code_from"];
        $area = $row["area_code_from"];
        $rack = $row["rack_code_from"];
        $bin = $row["bin_code_from"];
        $loc_to = $row["location_code_to"];
        $zone_to = $row["zone_code_to"];
        $area_to = $row["area_code_to"];
        $rack_to = $row["rack_code_to"];
        $bin_to = $row["bin_code_to"];
        $item[] = $row["item_code"];
        $qty[] = $row["qty"];
        $uom[] = $row["uom"];
        $desc[] = $row["description"];
        $line[] = $row["line_no"];
        $pick_datetime[] = $row["pick_datetime"];
        $putaway_datetime[] = $row["putaway_datetime"];
    }
    else{

        if($loc==$row["location_code_from"] && $zone==$row["zone_code_from"] && $area==$row["area_code_from"]
          && $rack==$row["rack_code_from"] && $bin==$row["bin_code_from"]
        ){
            $item[] = $row["item_code"];
            $qty[] = $row["qty"];
            $uom[] = $row["uom"];
            $desc[] = $row["description"];
            $line[] = $row["line_no"];
            $pick_datetime[] = $row["pick_datetime"];
            $putaway_datetime[] = $row["putaway_datetime"];
        }
        else{

            echo gen_template($loc,$zone,$area,$rack,$bin,$item,$qty,$uom,$desc,$loc_to,$zone_to,$area_to,$rack_to,$bin_to,$line,$i,$doc_no, $pick_datetime, $putaway_datetime,$var_pick_all, $var_put_all);
            $i++;
            unset($item); unset($qty); unset($uom); unset($desc); unset($line);
            $loc = $row["location_code_from"];
            $zone = $row["zone_code_from"];
            $area = $row["area_code_from"];
            $rack = $row["rack_code_from"];
            $bin = $row["bin_code_from"];
            $loc_to = $row["location_code_to"];
            $zone_to = $row["zone_code_to"];
            $area_to = $row["area_code_to"];
            $rack_to = $row["rack_code_to"];
            $bin_to = $row["bin_code_to"];
            $item[] = $row["item_code"];
            $qty[] = $row["qty"];
            $uom[] = $row["uom"];
            $desc[] = $row["description"];
            $line[] = $row["line_no"];
            $pick_datetime[] = $row["pick_datetime"];
            $putaway_datetime[] = $row["putaway_datetime"];
        }
    }


}

if(count($var_transferbin_d) > 0){
    echo gen_template($loc,$zone,$area,$rack,$bin,$item,$qty,$uom,$desc,$loc_to,$zone_to,$area_to,$rack_to,$bin_to,$line,$i,$doc_no, $pick_datetime, $putaway_datetime, $var_pick_all, $var_put_all);
}

?>

</div>

<script>

function f_pick(doc_no, line, i){
    $.ajax({
        url  : "<?php echo base_url();?>index.php/wms/inbound/transferbin/pick",
        type : "post",
        dataType  : 'json',
        data : {doc_no:doc_no, line:line},
        success: function(data){
            if(data.status == 1){
                $("#icn_pick_"+i).hide();
                $("#icn_pickdone_"+i).show();
                $("#btn_pick_"+i).attr("disabled", "disabled");
                $("#btn_put_"+i).removeAttr("disabled");
            }
        }
    })
}
//---

function f_put(doc_no, line, i){
  $.ajax({
      url  : "<?php echo base_url();?>index.php/wms/inbound/transferbin/put",
      type : "post",
      dataType  : 'json',
      data : {doc_no:doc_no, line:line},
      success: function(data){
          if(data.status == 1){
              $("#icn_put_"+i).hide();
              $("#icn_putdone_"+i).show();
              $("#btn_put_"+i).attr("disabled", "disabled");
              check_if_all_has_pick_put(doc_no);
          }
      }
  })
}
//---

function check_if_all_has_pick_put(doc_no){
    $.ajax({
        url  : "<?php echo base_url();?>index.php/wms/inbound/transferbin/check_all_item_has_pick_put",
        type : "post",
        dataType  : 'json',
        data : {doc_no:doc_no},
        success: function(data){
            if(data == "1"){
                change_transfer_bin_status(doc_no);
            }
        }
    })
}
//---

function change_transfer_bin_status(doc_no){
  status = 2;
  $.ajax({
      url  : "<?php echo base_url();?>index.php/wms/inbound/transferbin/update_status",
      type : "post",
      dataType  : 'json',
      data : {doc_no:doc_no,status:status},
      success: function(data){
          if(data.status = 1){
            swal({
               title: data.msg,
               type: "success", confirmButtonText: "OK",
            }).then(function(){
              setTimeout(function(){
                  window.location.href = "<?php echo base_url();?>index.php/wms/inbound/transferbin/goto";
              },100)
            });
          }
      }
  })
}
//--

function f_pick_all(doc_no, total_line){
    $.ajax({
        url  : "<?php echo base_url();?>index.php/wms/inbound/transferbin/pick_all",
        type : "post",
        dataType  : 'json',
        data : {doc_no:doc_no},
        success: function(data){
            if(data.status == 1){
                for(i=0;i<=total_line;i++){
                  $("#icn_pick_"+i).hide();
                  $("#icn_pickdone_"+i).show();
                  $("#btn_pick_"+i).attr("disabled", "disabled");
                  $("#btn_put_"+i).removeAttr("disabled");
                }

                $("#icn_pick_all").hide();
                $("#icn_pickdone_all").show();
                $("#btn_pick_all").attr("disabled", "disabled");
                $("#btn_put_all").removeAttr("disabled");
            }
        }
    })
}
//--

function f_put_all(doc_no, total_line){
    $.ajax({
        url  : "<?php echo base_url();?>index.php/wms/inbound/transferbin/put_all",
        type : "post",
        dataType  : 'json',
        data : {doc_no:doc_no},
        success: function(data){
            if(data.status == 1){
                for(i=0;i<=total_line;i++){
                  $("#icn_put_"+i).hide();
                  $("#icn_putdone_"+i).show();
                  $("#btn_put_"+i).attr("disabled", "disabled");
                }

                $("#icn_put_all").hide();
                $("#icn_putdone_all").show();
                $("#btn_put_all").attr("disabled", "disabled");
                check_if_all_has_pick_put(doc_no);
            }
        }
    })
}
//--


</script>
