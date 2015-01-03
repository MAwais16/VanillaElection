<br/>
<form class="basic-grey" action="" method="post">
	<input type="hidden" name="normal_nominate" value="delete"/>

	<h2>Your Nomination
        <span></span>
    </h2>
    	<?php

    	echo '<input type="hidden" name="eid" value="'.$elec->id.'"/>';
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
			echo "<label><span></span><input type='submit' class='button redBorder' value='Cancel My Nomination' /></label>";
		}else{
			echo "none";
		}

		?>
</form>