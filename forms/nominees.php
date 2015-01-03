<br/><br/><div>
	<form action="" method="POST" class="basic-grey" id="admin_nomnee_form">
		<input type="hidden" name="admin_nominees" value="1" id="admin_nominees"/>
        <input type="hidden" name="admin_nominee_id" value="1" id="admin_nominee_id"/>

<?php
    $election= $this->getLatestActiveElection();
    if($election!==false){
        $nominations=$this->getNominations($election->id);
        echo "<h1>Nominees<span>Nominees for: ".$election->name."</span></h1>";
        echo "<table class='customTable'>";
        foreach($nominations as $nominee){
            echo "<tr>";
            $nomineeData=get_userdata($nominee->user_id);
            echo "<td>".get_avatar( $nominee->user_id, 32 )."</td>";
            echo "<td>$nomineeData->first_name $nomineeData->last_name</td>";
            echo "<td>$nominee->title</td>";
            if($nominee->status=="0"){
                echo "<td>pending</td>";
                echo "<td><button class='button' onclick='admin_acceptNominee(".$nominee->id.")'>Accept</button></td>";
            }else{
                echo "<td>Accepted</td>";
                echo "<td><button class='button' onclick='admin_rejectNominee(".$nominee->id.")'>Reject</button></td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    }
?> 
</form></div>
<script type='text/javascript'>
    function admin_acceptNominee(id){
        //jQuery(document).ready(function(){
            jQuery("#admin_nominees").val("accept");
            jQuery("#admin_nominee_id").val(id);
            jQuery("#admin_nomnee_form").submit();
        //});
    };

    function admin_rejectNominee(id){
        //jQuery(document).ready(function(){
            jQuery("#admin_nominees").val("reject");
            jQuery("#admin_nominee_id").val(id);
            jQuery("#admin_nomnee_form").submit();
        //});
    };

</script>