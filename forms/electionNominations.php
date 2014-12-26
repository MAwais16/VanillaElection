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
        <span>Below are the nominations for seat of <?php echo $seat->title; ?> in <?php echo $elec->name; ?></span>
    </h2>
<?php
	$result=$this->getElectionNominations($elec->id,$seat->id);
	if($result===false){
		echo "<p>Unfortunately! there are no approved nominations so far.</p>";
	}else{
		foreach($result as $nomination){
			echo "<li>".$nomination->user_id."</li>";
		}
	}

?>
</div>

<?php

    }//foreach seats
    
} //if

?>