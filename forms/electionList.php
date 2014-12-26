
<br/>
<form action="" method="POST" id="formElectionList" class="basic-grey">
	<h1>Election List
        <span></span>
    </h1>
	<input type="hidden" name="post_listElection" id="post_listElection" value="1"/>
	<input type="hidden" name="id" id="id" value="-1"/>
<?php
global $wpdb;
$table_name = $wpdb->prefix . "ve_elections";

$result = $wpdb->get_results("SELECT * FROM $table_name");
?>

<table class="customTable">
	<thead>
		<tr>
			<th>id</th>
			<th>name</th>
			<th>seats</th>
			<th>created</th>
			<th>act/deact</th>
			<th>delete</th>
		</tr>
	</thead>

<?php
foreach ($result as $row) {
    echo "<tr>";
    echo "<td>" . $row->id . "</td>" . "<td>" . $row->name . "</td>" . "<td>" . $row->seats . "</td>" . "<td class='cTime'>" . $row->time . "</td>";
    if ($row->is_active == 0) {
        echo "<td><input type='button' value='Activate' onclick='activate($row->id);' class='button'/></td>";
    }else{
    	echo "<td><input type='button' value='Deactivate' onclick='deactivate($row->id);' class='button'/></td>";
    }
    echo "<td><input type='button' value='Delete' onclick='del($row->id);' class='button' style='border-color:#e74e4e;'/></td>";
    echo "</tr>";
}
?>
</table>
</form>

<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery(".cTime").each(function(index){
			jQuery(this).text(moment(jQuery(this).text()).fromNow());
		});
	});
	
	function activate(id){
		jQuery(document).ready(function(){
    		jQuery("#post_listElection").val("activate");
    		jQuery("#id").val(id);
    		jQuery("#formElectionList").submit();
		});

	};

	function del(id){
		jQuery(document).ready(function(){
    		if(confirm("Are you sure you want to delete this Election and related data?")){
    			jQuery("#post_listElection").val("del");
    			jQuery("#id").val(id);
    			jQuery("#formElectionList").submit();
    		}
		});	
	};

	function deactivate(id){
		jQuery(document).ready(function(){
    		jQuery("#post_listElection").val("deactivate");
    		jQuery("#id").val(id);
    		jQuery("#formElectionList").submit();
		});	
	};

</script>