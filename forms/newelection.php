<br/><br/><div>
	<form action="" method="POST" class="basic-grey">
		<input type="hidden" name="post_newElection" value="1"/>
    <h1>New Election
        <span>Please fill all the texts in the fields.</span>
    </h1>
    <label>
        <span>Election Title :</span>
        <input id="name" type="text" name="name" placeholder="Election Title" />
    </label>

    <label>
        <span>Election Seats:</span>
        <?php
        $result=$this->getAllSeats(); //admin.php
        if($result){
            echo "<select name='seats[]' multiple='multiple'>";
            foreach ($result as $seat) {
                echo "<option value='".$seat->id."'>".$seat->title."</option>";
            }
            echo "</select>";
        }
        ?>
        <!-- <input id="seats" type="text" name="seats" title="comma seperated" placeholder="comma seperated" value="President,Vice President,General Secretary,Finance Secretary,Information Secretary,Internation Relation Secretary"/> -->
    </label>
    
     <label>
        <span>&nbsp;</span> 
        <input type="submit" value="Save" class='button'/> 
    </label>    
</form>

</div>