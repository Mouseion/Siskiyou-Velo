<h1 class="categories">
	<span>Categories</span>
</h1>
        
<ul class="rightNav">

<?php 

//Connect to database
$connection = mysql_connect("localhost","gray8110","8aU_V{^{,RJC");
if (!$connection) {
	die ("Database connection failed: " . mysql_error());
}

$db_select = mysql_select_db("gray8110_etna",$connection);

if (!db_select) {
	die("Database selection failed: " . mysql_error());
} 
?>

<?php
$result = mysql_query("SELECT DISTINCT category FROM posts WHERE category != '' ORDER BY category ASC", $connection);

if (!result) {
   die("Database query failed: " . mysql_error());
}

while ($row = mysql_fetch_array($result)) {

$category = $row["category"];
$fullURL = 'category.php?id=' . $category;

echo 
	'
	<li><a href="' . $fullURL . '">' . $category . '</a></li>';

}
?>

<?php
//Close connection
mysql_close($connection);

?>

</ul>
        
<div class="navBottom">
</div>