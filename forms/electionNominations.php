<br/>
<br/>
<?php
$elec = $this->getLatestActiveElection();
if ($elec !== false) {
    
    //echo "<h1 style='margin-left:auto;margin-right:auto;text-align:center;'>Election Nominations</h1>";
    $electionSeats = $this->getSeats($elec->seats);
    foreach ($electionSeats as $seat) {
?>
<div class="basic-grey">
	<h2><?php echo $seat->title; ?>
        <span>Approved nominees for seat of <?php echo $seat->title; ?> in <?php echo $elec->name; ?></span>
    </h2>
<?php
	$result=$this->getElectionNominations($elec->id,$seat->id);
	if($result===false){
		echo "<p>Unfortunately! there are no approved nominations so far.</p>";
	}else{
		echo "<div class='nominee'>";
		foreach($result as $nomination){
			echo "<div class='card'>";

			echo "<div>".get_avatar( $nomination->user_id, 128 )."</div>";
			$nomineeData=get_userdata($nomination->user_id);
			echo "<div class='name'>".$nomineeData->first_name." ".$nomineeData->last_name ."</div>";
			echo "<div class='username'> user:".$nomineeData->user_login."</div>";
			echo "</div>";
		}
		echo "<div style='clear:left;'></div>";
		echo "</div>";
	}

?>
</div>

<?php

    }//foreach seats
    ?>
<br/>
<form class="basic-grey">
	<h2>Your Nomination
        <span></span>
    </h2>
    	<?php
    	$myNomination =$this->getMyNomination($elec->id);
		if($myNomination!==false){

			echo '<label><span>Seat Title: '.$myNomination[0]->title.'</label>';
			echo '<label><span>Status: ';
			if($myNomination[0]->status=="0"){
				echo "Pending... (waiting for approval)";
			}else{
				echo "Approved!";
			}
			echo "</label>";
			echo "<label><span></span><button class='button'>Cancel Nomination</button></label>";
			
		}else{
			echo "none";
		}
		
		?>
</form>


<?php
} //if

?>