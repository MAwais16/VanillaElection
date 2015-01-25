<br/>
<br/>
<form action="" method="POST" class="basic-grey" id="normal_nominate">
<input type="hidden" name="normal_nominate" value="add"/>
<?php

$elec=$this->getLatestActiveElection();
    if($elec!==false && $elec->is_active==1){
        echo "<h1>".$elec->name."<span>You can nominate yourself for one post only.</span></h1>";
        
        $electionSeats=$this->getSeats($elec->seats);
        global $wpdb;
        echo "q=".$wpdb->last_query;
        
        

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
        ?>

    <label>
        <span>&nbsp;</span> 
        <input type="submit" value="Nominate Me" class='button'/> 
    </label> 

<?php
    }else{
        echo "<div>No active election in nomination stage</div>";
    }

?> 
</form>
