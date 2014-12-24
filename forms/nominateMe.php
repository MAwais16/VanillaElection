<br/>
<br/>
<form action="" method="POST" class="basic-grey">
	<input type="hidden" name="normal_nominate" value="1"/>
	


<?php

global $wpdb;
$table_name = $wpdb->prefix . "ve_elections";
$result = $wpdb->get_results("SELECT * FROM $table_name where is_active=1 order by id DESC limit 1");
foreach ($result as $row) {
?>
	<h1><?php echo $row->name;?>
        <span>Nominate Yourself</span>
    </h1>
<?php

    $seats = explode(",", $row->seats);
    echo '<input type="hidden" name="eid" value="'.$row->id.'"/>';
    
    echo "<label>
        <span>Choose Seat:</span>";
    echo "<select name='dd_seat'>";

    foreach($seats as $seat){
    	$seat=trim($seat);
    	echo "<option value='$seat'>$seat</option>";
    }
    echo "</select></label>";
}?>

	<label>
        <span>&nbsp;</span> 
        <input type="submit" value="Nominate Me" class='button'/> 
	</label>  
</form>
	
