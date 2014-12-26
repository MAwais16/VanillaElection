<br/>
<?php
	$elec=$this->getActiveElection();
	if($elec!==false){
		$electionSeats=$this->getSeats($elec->seats);
		var_dump($electionSeats);
	}


?>