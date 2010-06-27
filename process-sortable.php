<?php

$root = dirname(dirname(dirname(dirname(__FILE__))));
require_once($root.'/wp-load.php');

global $wpdb;

foreach ($_GET['listItem'] as $position => $item) {
	$postquery = "UPDATE $wpdb->posts SET menu_order='$position' WHERE ID='$item'";	
	$wpdb->query($postquery);	    
}

?>