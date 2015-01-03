<br/>
<br/>
<form class="basic-grey" action="" method="post" id="normal_castvote_form">
	<input type="hidden" value="castvote" name="normal_castvote" id="normal_castvote"/>
	<input type="hidden" value="" name="normal_seatId" id="normal_seatId"/>
	<input type="hidden" value="" name="normal_electionId" id="normal_electionId"/>
	<input type="hidden" value="" name="normal_nominationId" id="normal_nominationId"/>
<?php
$elec = $this->getLatestActiveElection();
if ($elec !== false) {
    $electionSeats = $this->getSeats($elec->seats);
    foreach ($electionSeats as $seat) {
?>
	<h2><?php
        echo $seat->title; ?>
        <span>Approved nominees for seat of <?php
        echo $seat->title; ?> in <?php
        echo $elec->name; ?></span>
    </h2>
<?php
        $result = $this->getElectionNominations($elec->id, $seat->id);
        if ($result === false) {
            echo "<p>Unfortunately! there are no approved nominations so far.</p>";
        } else {
            echo "<div class='nominee'>";
            foreach ($result as $nomination) {
                echo "<div class='card'>";
                echo "<div>" . get_avatar($nomination->user_id, 128) . "</div>";
                $nomineeData = get_userdata($nomination->user_id);
                echo "<div class='name'>" . $nomineeData->first_name . " " . $nomineeData->last_name . "</div>";
                echo "<div class='username'> user:" . $nomineeData->user_login . "</div>";
                if ($elec->is_active == 2) {
                    echo "<button class='button' onclick='castVote($seat->id,$elec->id,$nomination->id);'> Vote </button>";
                }
                echo "</div>";
            }
            echo "<div style='clear:both;'></div>";
            echo "</div>";
        }
        echo "<hr style='padding:5px;'/>";
    }//foreach seats
}//if
else {
    echo "no election";
}
?>
</form>

<script type="text/javascript">
    function castVote(seatId,electionId,nominationId){
        jQuery("#normal_castvote").val("castvote");
        jQuery("#normal_seatId").val(seatId);
        jQuery("#normal_electionId").val(electionId);
        jQuery("#normal_nominationId").val(nominationId);
        jQuery("#normal_castvote_form").submit();
    };
</script>