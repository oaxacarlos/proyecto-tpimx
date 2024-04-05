

<table class="table table-bordered table-sm">
  <tbody>
    <?php

      function check_is_date_format($date){
          $date_temp = explode("-",$date);
          $year   = $date_temp[0];
          $month  = $date_temp[1];
          $day    = $date_temp[2];

          return checkdate((int)$month, (int)$day, (int)$year);
          return 1;
      }
      //---

      function check_destino($destino, $var_city){
          $status_temp = 0;
          foreach($var_city as $row){
              if($destino == $row["name"]){
                 $status_temp = 1;
                 break;
               }
          }

          if($status_temp == 1) return true;
          else return false;
      }
      //--

      function check_estado($estado, $var_state){
          $status_temp = 0;
          foreach($var_state as $row){
              if($estado == $row["name"]){
                 $status_temp = 1;
                 break;
               }
          }

          if($status_temp == 1) return true;
          else return false;
      }
      //---

      function check_vendor($vendor, $var_vendor){
          $status_temp = 0;
          foreach($var_vendor as $row){
              if($vendor == $row["vendor_code"]){
                 $status_temp = 1;
                 break;
               }
          }

          if($status_temp == 1) return true;
          else return false;
      }
      //---

      function check_domicili($domicili, $var_domicili){
          $status_temp = 0;
          foreach($var_domicili as $row){
              if($domicili == $row["name"]){
                 $status_temp = 1;
                 break;
               }
          }

          if($status_temp == 1) return true;
          else return false;
      }
      //---

      function check_payment_terms($payment, $var_payment_terms){
          $status_temp = 0;
          foreach($var_payment_terms as $row){
              if($payment == $row["name"]){
                 $status_temp = 1;
                 break;
               }
          }

          if($status_temp == 1) return true;
          else return false;
      }
      //---

      function check_delv_status($delv, $var_delv_status){
          $status_temp = 0;
          foreach($var_delv_status as $row){
              if($delv == $row["name"]){
                 $status_temp = 1;
                 break;
               }
          }

          if($status_temp == 1) return true;
          else return false;
      }
      //---

      function check_trackingno_exist($tracking_no, $data_tracking_no){
          $check = 1; $delv_no = "";
          foreach($data_tracking_no as $row){
              if($row["tracking_no"] == $tracking_no && $row["no_exist"] == 0){
                  $delv_no = $row["delv_no"];
                  $check = 0;
                  break;
              }
          }

        if($check == 0){
            $result["status"] = 0;
            $result["delv_no"] = $delv_no;
        }
        else{
            $result["status"] = 1;
            $result["delv_no"] = "";
        }

        return $result;
      }
      //---

      //---
      $i=0;
      unset($tracking_no);
      $first = 1;
      $status_all = 1;
      foreach($tables as $row){
          if($i == 0){
            echo "<tr style='font-size:12px;' class='table-secondary'>";
              echo "<th>ROW</th>";
              echo "<th>SENDING DATE</th>";
              echo "<th>DESTINO</th>";
              echo "<th>ESTADO</th>";
              echo "<th>CHOFER</th>";
              echo "<th>VENDOR</th>";
              echo "<th>TRACKING NO</th>";
              echo "<th>DOMICILI</th>";
              echo "<th>PAYMENT TERMS</th>";
              echo "<th>REMARK HEADER (OPTIONAL)</th>";
              echo "<th>CAJA (OPTIONAL)</th>";
              echo "<th>PALLET (OPTIONAL)</th>";
              echo "<th>DELIVERY STATUS</th>";
              echo "<th>SUBTOTAL</th>";
              echo "<th>TOTAL</th>";
              echo "<th>DOC NO</th>";
              echo "<th>DOC DATE</th>";
              echo "<th>SO REF</th>";
              echo "<th>CUST NO</th>";
              echo "<th>TOTAL</th>";
              echo "<th>REMARKS</th>";
              echo "<th>STATUS</th>";
              echo "<th>ERROR MESSAGE</th>";
            echo "</tr>";
          }
          else{
            $status = 1; $error = "";

            // check if data no sort by tracking no
            if($first == 1){
              $tracking_no[] = $row[5];
              $tracking_no_temp = $row[5];
              $table_color = "";
            }
            else{
              if($row[5] == $tracking_no_temp){
                $tracking_no_temp = $row[5];
                $tracking_no[] = $row[5];
              }
              else{
                $status_temp = 1;
                foreach($tracking_no as $row_tracking_no){
                  if($row[5] == $row_tracking_no){
                    $status_temp = 0; break;
                  }
                }

                if($status_temp == 0){
                  $status = 0;
                  $error = "You have already this TRACKING NO before";
                  $tracking_no_temp = $row[5];
                  $tracking_no[] = $row[5];
                }
                else{
                  $tracking_no_temp = $row[5];
                  $tracking_no[] = $row[5];
                  if($table_color == "info") $table_color = "";
                  else $table_color = "info";
                }
              }
            }
            //--

            echo "<tr class='table-".$table_color."' style='font-size:12px;'>";
              echo "<td>".$i."</td>";
              echo "<td id='tbl_sending_date_".$i."'>".$row[0]."</td>";
              echo "<td id='tbl_destino_".$i."'>".$row[1]."</td>";
              echo "<td id='tbl_estado_".$i."'>".$row[2]."</td>";
              echo "<td id='tbl_chofer_".$i."'>".$row[3]."</td>";
              echo "<td id='tbl_vendor_".$i."'>".$row[4]."</td>";
              echo "<td id='tbl_tracking_no_".$i."'>".$row[5]."</td>";
              echo "<td id='tbl_domicili_".$i."'>".$row[6]."</td>";
              echo "<td id='tbl_payment_terms_".$i."'>".$row[7]."</td>";
              echo "<td id='tbl_remark_header_".$i."'>".$row[8]."</td>";
              echo "<td id='tbl_caja_".$i."'>".$row[9]."</td>";
              echo "<td id='tbl_pallet_".$i."'>".$row[10]."</td>";
              echo "<td id='tbl_delivery_status_".$i."'>".$row[11]."</td>";
              echo "<td id='tbl_subtotal_header_".$i."'>".$row[12]."</td>";
              echo "<td id='tbl_total_header_".$i."'>".$row[13]."</td>";
              echo "<td id='tbl_doc_no_".$i."'>".$row[14]."</td>";
              echo "<td id='tbl_doc_date_".$i."'>".$row[15]."</td>";
              echo "<td id='tbl_so_ref_".$i."'>".$row[16]."</td>";
              echo "<td id='tbl_cust_no_".$i."'>".$row[17]."</td>";
              echo "<td id='tbl_total_detail_".$i."'>".$row[18]."</td>";
              echo "<td id='tbl_remarks_detail_".$i."'>".$row[19]."</td>";

              // check if tracking_no already exist
              if($status == 1){
                  $result_check_tracking_no = check_trackingno_exist($row[5], $var_tracking_no_check);
                  if($result_check_tracking_no["status"] == 0){
                    $status = 0; $error = "Tracking No already exist on the system with DELV NO = ".$result_check_tracking_no["delv_no"];
                  }
              }
              //---

              if($status == 1){
                if(!check_is_date_format($row[0])){
                  $status = 0; $error = "Format SENDING DATE is not correct";
                }
              }

              if($status == 1){
                  if(!check_destino($row[1], $var_city)){
                      $status = 0; $error = "DESTINO is not on the DATABASE";
                  }
              }

              if($status == 1){
                  if(!check_estado($row[2], $var_state)){
                      $status = 0; $error = "ESTADO is not on the DATABASE";
                  }
              }

              if($status == 1){
                  if(!check_vendor($row[4], $var_vendor)){
                      $status = 0; $error = "VENDOR is not on the DATABASE";
                  }
              }

              if($status == 1){
                  if(!check_domicili($row[6], $var_domicili)){
                      $status = 0; $error = "DOMICILI is not on the DATABASE";
                  }
              }

              if($status == 1){
                  if(!check_payment_terms($row[7], $var_payment_terms)){
                      $status = 0; $error = "PAYMENT TERMS is not on the DATABASE";
                  }
              }

              if($status == 1){
                  if($row[9] != ""){
                    if(!is_numeric($row[9])){
                      $status = 0; $error = "CAJA must NUMERIC";
                    }
                  }
              }

              if($status == 1){
                  if($row[10] != ""){
                    if(!is_numeric($row[10])){
                      $status = 0; $error = "PALLET must NUMERIC";
                    }
                  }
              }

              if($status == 1){
                  if(!check_delv_status($row[11], $var_delv_status)){
                      $status = 0; $error = "DELIVERY STATUS is not on the DATABASE";
                  }
              }

              if($status == 1){
                  if((float)$row[12] < 0){
                        $status = 0; $error = "SUBTOTAL(14) is not allow < 0";
                  }
              }

              if($status == 1){
                  if((float)$row[13] < 0){
                        $status = 0; $error = "SUBTOTAL(15) is not allow < 0";
                  }
              }

              if($status == 1){
                  if(!check_is_date_format($row[15])){
                      $status = 0; $error = "Format DOC DATE is not correct";
                  }
              }

              if($status == 1){
                  if($row[17] != "1190027" and $row[17] !="1190033"){
                      $status = 0; $error = "CUST NO is not correct";
                  }
              }

              if($status == 1){
                  if((float)$row[18] < 0){
                        $status = 0; $error = "TOTAL(20) is not allow < 0";
                  }
              }


              // end status
              if($status == 1) echo "<td><i class='bi bi-check2' style='font-size:20px; color:green;'></i></td>";
              else{
                 echo "<td><i class='bi bi-x-lg' style='font-size:20px; color:red;'></i></td>";
                 $status_all = 0;
              }

              echo "<td>".$error."</td>";

            echo "</tr>";

            $first++;
          }

          $i++;
      }
    ?>
  </tbody>
</table>

<?php
  if($status_all == 1) $disabled_status_all = "";
  else $disabled_status_all = "disabled";

  echo "<input type='hidden' id='total_row' value='".count($tables)."'>";
?>

<div class="container-fluid text-right">
  <button class="btn btn-success" id="btn_process" <?php echo $disabled_status_all; ?> >PROCESS</button>
</div>

<script>

  $("#btn_process").click(function(){
      var total_row = parseInt($("#total_row").val());

      var sending_date = [];
      var destino = [];
      var estado = [];
      var chofer = [];
      var vendor = [];
      var tracking_no = [];
      var domicili = [];
      var payment_terms = [];
      var remark_header = [];
      var caja = [];
      var pallet = [];
      var delivery_status = [];
      var subtotal_header = [];
      var total_header = [];
      var doc_no = [];
      var doc_date = [];
      var so_ref = [];
      var cust_no = [];
      var total_detail = [];
      var remarks_detail = [];
      var count = 0;

      for(i=1;i<total_row;i++){
        sending_date[count] = $("#tbl_sending_date_"+i).text();
        destino[count] = $("#tbl_destino_"+i).text();
        estado[count] = $("#tbl_estado_"+i).text();
        chofer[count] = $("#tbl_chofer_"+i).text();
        vendor[count] = $("#tbl_vendor_"+i).text();
        tracking_no[count] = $("#tbl_tracking_no_"+i).text();
        domicili[count] = $("#tbl_domicili_"+i).text();
        payment_terms[count] = $("#tbl_payment_terms_"+i).text();
        remark_header[count] = $("#tbl_remark_header_"+i).text();
        caja[count] = $("#tbl_caja_"+i).text();
        pallet[count] = $("#tbl_pallet_"+i).text();
        delivery_status[count] = $("#tbl_delivery_status_"+i).text();
        subtotal_header[count] = $("#tbl_subtotal_header_"+i).text();
        total_header[count] = $("#tbl_total_header_"+i).text();
        doc_no[count] = $("#tbl_doc_no_"+i).text();
        doc_date[count] = $("#tbl_doc_date_"+i).text();
        so_ref[count] = $("#tbl_so_ref_"+i).text();
        cust_no[count] = $("#tbl_cust_no_"+i).text();
        total_detail[count] = $("#tbl_total_detail_"+i).text();
        remarks_detail[count] = $("#tbl_remarks_detail_"+i).text();
        count++;
      }

      // is everything ok, proceed
      swal({
        title: "Are you sure ?",
        html: "Proceed this Delivery",
        type: "question",
        showCancelButton: true,
        confirmButtonText: "Yes",
        showLoaderOnConfirm: true,
        closeOnConfirm: false
      }).then(function (result) {
          if(result.value){

              $("#loading_text").text("Creating New Delivery Document, Please wait...");
              $('#loading_body').show();

              $.ajax({
                  url  : "<?php echo base_url();?>index.php/operacion/delivery/newdelivery/upload_file_process",
                  type : "post",
                  dataType  : 'html',
                  data : {sending_date:JSON.stringify(sending_date), destino:JSON.stringify(destino), estado:JSON.stringify(estado), chofer:JSON.stringify(chofer), vendor:JSON.stringify(vendor), tracking_no:JSON.stringify(tracking_no), domicili:JSON.stringify(domicili), payment_terms:JSON.stringify(payment_terms), remark_header:JSON.stringify(remark_header), caja:JSON.stringify(caja), pallet:JSON.stringify(pallet), delivery_status:JSON.stringify(delivery_status), subtotal_header:JSON.stringify(subtotal_header), total_header:JSON.stringify(total_header), doc_no:JSON.stringify(doc_no), doc_date:JSON.stringify(doc_date), so_ref:JSON.stringify(so_ref), cust_no:JSON.stringify(cust_no), total_detail:JSON.stringify(total_detail), remarks_detail:JSON.stringify(remarks_detail) },
                  success: function(data){
                      var responsedata = $.parseJSON(data);

                      if(responsedata.status == 1){
                            swal({
                               title: responsedata.msg,
                               type: "success", confirmButtonText: "OK",
                            }).then(function(){
                              setTimeout(function(){
                                $('#loading_body').hide();
                                location.reload();
                              },100)
                            });
                      }
                      else if(responsedata.status == 0){
                          Swal('Error!',responsedata.msg,'error');
                          $('#loading_body').hide();
                      }
                  }
              })

          }
      })
  })

</script>
