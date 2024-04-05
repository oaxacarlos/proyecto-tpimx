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
					<a href="#">New Document in WMS</a>
					<?php  if($start_datetime == 0){
							echo "<p>No Data</p>";
					}
					else{
						$starting_time = $start_datetime;
					?>
					<a href="#" class="float-right"><?php echo $start_datetime; ?></a>
					<p>This time was when User pull the data from Navision</p>
					<?php } ?>
				</li>
				<li>
					<a href="#">RECEIVED DOCUMENT(S)</a>
					<a href="#" class="float-right"></a>
					<p>
						<?php
							if($received == 0){
									echo "<p>No Data</p>";
							}
							else{
								foreach($received as $row){
										echo "<p>";
										echo "<span>".$row['doc1']."</span>";
										echo "<span class='float-right'>".$row["created_datetime"]."</span>";
										echo "</p>";
										$ending_time = $row["created_datetime"];
								}
							}
						?>
					</p>
				</li>
				<li>
					<a href="#">RECEIVED VERIFIED DOCUMENT(S)</a>
					<a href="#" class="float-right"></a>
					<p>
						<?php
							if($received_verified == 0){
									echo "<p>No Data</p>";
							}
							else{
								foreach($received_verified as $row){
										echo "<p>";
										echo "<span>".$row['doc1']."</span>";
										echo "<span class='float-right'>".$row["created_datetime"]."</span>";
										echo "</p>";
										$ending_time = $row["created_datetime"];
								}
							}
						?>
					</p>
				</li>
				<li>
					<a href="#">GENERATING SN DOCUMENT(S)</a>
					<a href="#" class="float-right"></a>
					<p>
						<?php
							if($gen_sn == 0){
									echo "<p>No Data</p>";
							}
							else{
								foreach($gen_sn as $row){
										echo "<p>";
										echo "<span>".$row['doc1']."</span>";
										echo "<span class='float-right'>".$row["created_datetime"]."</span>";
										echo "</p>";
										$ending_time = $row["created_datetime"];
								}
							}
						?>
					</p>
				</li>
				<li>
					<a href="#">PUT AWAY(S)</a>
					<a href="#" class="float-right"></a>
					<p>
						<?php
							if($put_away == 0){
									echo "<p>No Data</p>";
							}
							else{
								foreach($put_away as $row){
										echo "<p>";
										echo "<span>".$row['doc1']."-".$row['doc2']."</span>";
										echo "<span class='float-right'>".$row["created_datetime"]."</span>";
										echo "</p>";
										$ending_time = $row["created_datetime"];
								}
							}
						?>
					</p>
				</li>
				<li>
					<a href="#">PUT AWAY FINISHED(S)</a>
					<a href="#" class="float-right"></a>
					<p>
						<?php
							if($put_away_finished == 0){
									echo "<p>No Data</p>";
							}
							else{
								foreach($put_away_finished as $row){
										echo "<p>";
										echo "<span>".$row['doc1']."</span>";
										echo "<span class='float-right'>".$row["created_datetime"]."</span>";
										echo "</p>";
										$ending_time = $row["created_datetime"];
								}
							}
						?>
					</p>
				</li>
				<li>
					<a href="#">WHS Receipt Release</a>
					<?php
						if($release == 0){
								echo "<p>No Data</p>";
						}
						else{
							$ending_time = $release["created_datetime"];
					?>
					<a href="#" class="float-right"><?php echo $release["created_datetime"]; ?></a>
					<p>This time, the document was released..</p>
					<?php } ?>
				</li>
				<li>
					<a href="#">WHS Receipt Submitted to Navision</a>
					<?php
						if($submitnav == 0){
								echo "<p>No Data</p>";
						}
						else{
							$ending_time = $submitnav["created_datetime"];
					?>
					<a href="#" class="float-right"><?php echo $submitnav["created_datetime"]; ?></a>
					<p>This time, the document was submitted to Navision</p>
						<?php } ?>
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
