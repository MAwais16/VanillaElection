<form action="" method="POST">
	<input type="hidden" name="post_listElection" value="1"/>
<?php
global $wpdb;
$table_name = $wpdb->prefix . "ve_elections";
$result = $wpdb->get_results("SELECT * FROM $table_name");
?>

<table>
	<!-- <thead>
		<tr>
			<th>id</th>
			<th>name</th>
			<th>seats</th>
			<th>creation date</th>
			<th>isAcitve</th>
		</tr>
	</thead> -->

<?php
foreach ($result as $row) {
    echo "<tr>";
    echo "<td>" . $row->id . "<td>" . "<td>" . $row->name . "<td>" . "<td>" . $row->seats . "<td>" . "<td>" . $row->time . "<td>";
    if ($row->is_active == 0) {
        echo "<td><input type='submit' value='Activate' onclick='alert(123);return false;' class='button'/><td>";
    }
    echo "<td><input type='submit' value='Delete' onclick='alert(123); return true;' class='button' style='border-color:#e74e4e;'/></td>";
    echo "</tr>";
}
?>

</table>
</form>