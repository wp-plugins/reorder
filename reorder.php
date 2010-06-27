<?php
/*
Plugin Name: Reorder
Plugin URI: http://benjitastic.com/reorder.zip
Description: Enables simple drag and drop reordering of all custom post types.
Author: Ben Kennedy
Version: 1.0
Author URI: http://benjitastic.com
	
*/

// Build the admin UI
function reorder_ui(){

	//get the label of the current post_type
  	foreach (get_post_types('','objects') as $post_type ) {
  		if($post_type->name == $_GET['post_type'])
			$pt = $post_type->label;
		elseif(!$_GET['post_type'])
			$pt = 'Posts';
  	}
  	
	?> 
	<div class="wrap">
		<div id="icon-edit" class="icon32"><br /></div>
			<h2>Reorder <?php echo $pt ?></h2>
			<p id="order-loading">Click, drag and drop to reorder.<span></span></p>
			<table class="widefat post fixed">
				<thead>
					<tr><th>Title</th><th width="80">Author</th><th width="158">Date</th><th width="60">ID</th></tr>
				</thead>	
				<tr>
					<td style="padding: 0" colspan="4">
						<ul id="order-posts-list">

						<?php
							//get posts
							$posts = get_posts('post_type='.$_GET['post_type'].'&orderby=menu_order&order=ASC&numberposts=-1&depth=1&post_parent=0');
							foreach($posts as $p) {								
								
								echo '<li id="listItem_'.$p->ID.'">
									<table class="order-inner">
									<tr>
									<td width="22" class="drag"><img src="'.WP_PLUGIN_URL.'/reorder/drag-handle.png" alt="" /></td>
									<td>																		
									<strong>'.$p->post_title.'</strong>
									</td>
									<td width="83">'.get_userdata($p->post_author)->display_name.'</td>
									<td width="162">'.mysql2date('l, j F, Y', $p->post_date).'</td>		
									<td width="62">'.$p->ID.' | <a class="order-edit" href="'.get_bloginfo('url').'/wp-admin/post.php?post='.$p->ID.'&action=edit">Edit</a></td>	
									</tr>
									</table>
								</li>';
							}
						?>
						</ul>
					</td>
				</tr>
				<tfoot>
					<tr><th>Title</th><th>Author</th><th>Date</th><th>ID</th></tr>
				</tfoot>
			</table>	
		<form action="process-sortable.php" method="post" name="sortables"><input type="hidden" name="test-log" id="test-log" /> </form>
	</div>
<?php }


// Add CSS
function reorder_head(){	
	echo '<link type="text/css" rel="stylesheet" href="' . WP_PLUGIN_URL.'/reorder/reorder.css" />' . "\n";
}

// Add Javascript
function reorder_script() {	
	wp_enqueue_script('jquery-ui-core');            
	wp_enqueue_script('jquery-ui-sortable');	
	wp_enqueue_script('reorderScript', WP_PLUGIN_URL . '/reorder/reorder.js');	
}

// Add to admin menus
function reorder_menu(){
	global $minlevel, $wp_version;	
		
	//add menu to Posts
	//add_submenu_page('edit.php', 'Order Posts', 'Reorder', $minlevel,  __FILE__, 'postMash_main'); 
		
	//exclude this plugin from the following post_types
	$excludedPostTypes = array('attachment', 'revision', 'page', 'nav_menu_item');
	
	//add menu to each post_type
foreach(get_post_types('','names') as $r) {
	if(!in_array($r, $excludedPostTypes)) {
		add_submenu_page('edit.php?post_type='.$r.'', "Reorder", "Reorder", $minlevel,  $r, 'reorder_ui');
	}
}	
}

add_action('admin_print_scripts', 'reorder_script');
add_action('admin_head', 'reorder_head');
add_action('admin_menu', 'reorder_menu');

?>