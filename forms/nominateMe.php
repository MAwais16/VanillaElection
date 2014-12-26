<br/>
<br/>
<form action="" method="POST" class="basic-grey">
	<input type="hidden" name="normal_nominate" value="1"/>

<?php

$elec=$this->getActiveElection();
    if($elec!==false){
        echo "<h1>".$elec->name."<span>Nominate Yourself</span></h1>";

        $electionSeats=$this->getSeats($elec->seats);
        
        echo '<input type="hidden" name="eid" value="'.$elec->id.'"/>';
        echo "<label>
            <span>Choose Seat:</span>";
        echo "<select name='dd_seat'>";

        foreach($electionSeats as $seat){
            $seatTitle=trim($seat->title);
            $seatId=$seat->id;
            echo "<option value='$seatId'>$seatTitle</option>";
        }

        echo "</select></label>";
    }

?>
	<label>
        <span>&nbsp;</span> 
        <input type="submit" value="Nominate Me" class='button'/> 
	</label>  
</form>