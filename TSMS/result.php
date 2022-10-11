<?php
$Text = urldecode($_REQUEST['cluster']);
$Mixed = json_decode($Text);
print_r( $Mixed);
print "<p>$Mixed->phone</p>";
?>
