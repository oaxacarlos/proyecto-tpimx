<style>
ul.timeline {
    list-style-type: none;
    position: relative;
}
ul.timeline:before {
    content: ' ';
    background: #d4d9df;
    display: inline-block;
    position: absolute;
    left: 29px;
    width: 2px;
    height: 100%;
    z-index: 400;
}
ul.timeline > li {
    margin: 20px 0;
    padding-left: 20px;
}
ul.timeline > li:before {
    content: ' ';
    background: white;
    display: inline-block;
    position: absolute;
    border-radius: 50%;
    border: 3px solid #22c0e8;
    left: 20px;
    width: 20px;
    height: 20px;
    z-index: 400;
}
</style>

	<?php $starting_time=0; $ending_time=0; ?>

	<div class="container mt-5 mb-5">
	<div class="row">
		<div class="col-md-6 offset-md-3">
			<h4>Timeline - ( <?php echo $doc_no; ?> )</h4>
			<ul class="timeline">
				<li>
          <i class="bi bi-folder-plus"></i>
					<a href="#">New Document in WMS</a>
					<?php  if($start_datetime == 0){
							echo "<p>No Data</p>";
					}
					else{
						$starting_time = $start_datetime;
					?>
					<a href="#" class="float-right" style="<?php echo $font_color; ?>"><?php echo $start_datetime; ?></a>
					<p>This time was when User pull the data from Navision<br> <?php echo "(".$start_user.")" ?></p>
					<?php } ?>
				</li>

        <li>
          <i class="bi bi-clipboard-plus"></i>
          <a href="#">CREATED PICKING DOCUMENT(S)</a>
					<a href="#" class="float-right"><?php echo $create_picking["created_datetime"]; ?></a>
          <?php
            if($create_picking == 0){
                echo "<p>No Data</p>";
            }
            else{
                echo "<br>(".$create_picking["name"].")";
            }
          ?>
        </li>

				<li>
          <i class="bi bi-cart-plus"></i>
					<a href="#">PICKING DOCUMENT(S)</a>
					<a href="#" class="float-right"></a>
					<p>
						<?php
							if($picking == 0){
									echo "<p>No Data</p>";
							}
							else{
								foreach($picking as $row){
										echo "<p>";
										echo "<span>".$row['doc1']."</span><br><span style='font-size:10px;'>".$row["name"]."</span>";
										echo "<span class='float-right'>".$row["created_datetime"]."</span>";
										echo "</p>";
										if($row["created_datetime"] > $ending_time) $ending_time = $row["created_datetime"];
								}
							}
						?>
					</p>
				</li>
				<li>
          <i class="bi bi-cart-check"></i>
					<a href="#">PICKING FINISHED DOCUMENT(S)</a>
					<a href="#" class="float-right"></a>
					<p>
						<?php
							if($picking_finished == 0){
									echo "<p>No Data</p>";
							}
							else{
								foreach($picking_finished as $row){
										echo "<p>";
										echo "<span>".$row['doc1']."<br>(".$row["name"].")</span>";
										echo "<span class='float-right'>".$row["created_datetime"]."</span>";
										echo "</p>";
										if($row["created_datetime"] > $ending_time) $ending_time = $row["created_datetime"];
								}
							}
						?>
					</p>
				</li>
        <li>
          <i class="bi bi-upc-scan"></i>
          <a href="#">QC</a>
          <?php
            if($qc == 0){
                echo "<p>No Data</p>";
            }
            else{
              if($row["created_datetime"] > $ending_time) $ending_time = $qc["created_datetime"];
          ?>
          <a href="#" class="float-right"><?php echo $qc["created_datetime"]; ?></a>
          <p>(<?php echo $qc["name"]; ?>)</p>
          <p>This time, the document has been QC</p>
          <?php } ?>
        </li>
        <li>
          <i class="bi bi-send"></i>
					<a href="#">WHS Shipment Submitted to Navision</a>
					<?php
						if($submitnav == 0){
								echo "<p>No Data</p>";
						}
						else{
							if($submitnav["created_datetime"] > $ending_time) $ending_time = $submitnav["created_datetime"];
					?>
					<a href="#" class="float-right"><?php echo $submitnav["created_datetime"]; ?></a>
          <p>(<?php echo $submitnav["name"]; ?>)</p>
					<p>This time, the document was submitted to Navision</p>
						<?php } ?>
				</li>
				<li>
          <i class="bi bi-box"></i>
					<a href="#">PACKING(S)</a>
					<a href="#" class="float-right"></a>
					<p>
						<?php
							if($packing == 0){
									echo "<p>No Data</p>";
							}
							else{
								foreach($packing as $row){
										echo "<p>";
										echo "<span>".$row['doc1']."<br>(".$row['name'].")</span>";
										echo "<span class='float-right'>".$row["created_datetime"]."</span>";
										echo "</p>";
										if($row["created_datetime"] > $ending_time) $ending_time = $row["created_datetime"];
								}
							}
						?>
					</p>
				</li>
        <li>
          <i class="bi bi-receipt"></i>
					<a href="#">INVOICE(S)</a>
					<a href="#" class="float-right"></a>
					<p>
						<?php
							if($invoice == 0){
									echo "<p>No Data</p>";
							}
							else{
								foreach($invoice as $row){
										echo "<p>";
										echo "<span>".$row['invoice_no']."</span>";
										echo "<span class='float-right'>-</span>";
										echo "</p>";
								}
							}
						?>
					</p>
				</li>

				<div>
					Total Consumed time :
					<?php
							if($starting_time!=0 && $ending_time!=0){
								$data = calculate_time($starting_time, $ending_time);
								//echo $data['hours'].":".$data['minutes'].":".$data['seconds'];
                echo $data;
							}
							else{ echo "No Data"; }
					?>
				</div>
			</ul>
		</div>
	</div>
</div>
