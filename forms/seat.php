<br/>
<br/>
<form action="" method="POST" class="basic-grey">
	<input type="hidden" name="admin_seat" value="addseat"/>

	<h1>Seats
        <span>Add new seats for elections</span>
    </h1>
    <label>
        <span>Seat title</span>
        <input id="seat" type="text" name="title" placeholder="Seat Title" />
    </label>
    <label>
        <span>&nbsp;</span> 
        <input type="submit" value="Add" class='button'/> 
    </label>    
	
</form>

<form action="" method="POST" class="basic-grey" id="deletForm">
<h1>
        <span>Saved Seats</span>
    </h1>

	<input type="hidden" name="admin_seat" value="delete"/>
	<input type="hidden" name="admin_seat_id" value="0" id="admin_seat_id"/>
<table class="customTable">
	<?php
		//$result=$this->getAllSeats(); //admin.php
		$result=\WP_ve_seat::find('all');
		if($result){
			foreach ($result as $seat) {
				echo "<tr>";
				echo "<td>".$seat->id."</td>";
				echo "<td>".$seat->title."</td>";
				echo "<td><button onClick='deleteSeat(".$seat->id.");' class='button'>Delete</td>";
				echo "</tr>";
			}
			
		}
	?>
</table>
</form>

<script type="text/javascript">
function deleteSeat(id){
	jQuery(document).ready(function(){

		jQuery("#admin_seat_id").val(id);
		jQuery('#deletForm').submit();


	});
} 
	
</script>