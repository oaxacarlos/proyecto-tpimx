<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      New Delivery
</div>

<div class="container-fluid">
  <div class="list-group-item text-right">
  		<a href="<?php echo base_url(); ?>download/NewDelivery-template.csv" class="btn btn-md btn-danger">Download New Delivery Template</a>
  </div>

  <div class="list-group-item">
			<p>Upload your file in here - the file extensions is ".csv"</p>
			<input type="file" name="fileToUpload" id="fileToUpload" accept=".csv"><br><br>
			<button id="btn_upload" class="btn btn-md btn-primary">UPLOAD</div>
	</div>
</div>

<div class="container-fluid" style="margin-top:20px;">
  <div class="list-group-item">
  		Result
  </div>
  <div class="list-group-item">
    <div class="row" id="report_result">Check the Result/Error in here after upload</div>
  </div>
</div>

<?php echo loading_body_full(); ?>

<script>

  $("#btn_upload").click(function(){

      $("#loading_text").text("Uploading your file, Please wait...");
      $('#loading_body').show();

      setTimeout(function () {
        var file_data = $('#fileToUpload').prop('files')[0];
        var form_data = new FormData();
        form_data.append('file', file_data);
        $.ajax({
          url       : "<?php echo base_url();?>index.php/operacion/delivery/newdelivery/upload_file",
          type: "POST",
          data: form_data,
          contentType: false,
          cache: false,
          processData:false,
          success: function(data){
            var responsedata = $.parseJSON(data);

            if(responsedata.status){
                $("#loading_text").text("Your file successfully uploaded, We are checking the data, please wait...");

                setTimeout(function () {
                  var attachment = responsedata.filename;
                  $.ajax({
                    url       : "<?php echo base_url();?>index.php/operacion/delivery/newdelivery/upload_file_checking",
                    type      : 'post',
                    dataType  : 'html',
                    data      : {attachment:attachment},
                    success   :  function(respons){
                        $('#report_result').fadeIn("5000");
                        $("#report_result").html(respons);
                        $('#loading_body').hide();
                    }
                  })
              }, 1000);
            }
          }
        })
      }, 1000);
  })
</script>
