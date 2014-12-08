<?php
/*
Plugin Name: Under Construction
Plugin URI: https://github.com/hyptx/hyp-under-construction
Description: Automatic Under Construction Messages for new pages
Version: 1.0
Author: Adam J Nowak
Author URI: http://hyperspatial.com
License: GPL2
*/

/*
Copyright 2010  Adam J Nowak  (email : adam@hyperspatial.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

function uc_construction_placeholder($content){
	global $post;
	$uc_exclude = get_option('uc_exclude');
	$uc_exclude_array = explode(',',$uc_exclude);
	foreach($uc_exclude_array as $exclude){
		if($post->ID == $exclude) return;
	}
	if(!$content && is_page()) $content = get_option('uc_content');
	return $content;
}
add_filter('the_content','uc_construction_placeholder');

function uc_create_admin_menu(){
	add_submenu_page('edit.php?post_type=page','UC Settings','UC&nbsp;Settings',3,'uc-settings','uc_admin_page','/graphics/icon.png');
	add_action('admin_init','register_uc_options');
}
add_action('admin_menu','uc_create_admin_menu');
	
function register_uc_options(){
	$input_field_names = array(
	'uc_content',
	'uc_exclude'
	);
	foreach($input_field_names as $field_name){ register_setting('uc_options',$field_name);	}
}

//Admin Page
function uc_admin_page(){
	$uc_content = get_option('uc_content');
	$uc_exclude = get_option('uc_exclude');
	$default_uc_content = '<div style="margin-top:50px; text-align:center;"><h2>Under Construction</h2><img src="' . WP_PLUGIN_URL . '/under-construction/construction.png"/><p>Sorry, but this page is under construction</p></div>';
	if(get_option('uc_content') == ''){ update_option('uc_content', $default_uc_content); }
	?>
	<style type="text/css">
		.help-text{font-size:11px; font-style:italic;}
		.example{color:#669;}
		form {margin-top:30px;}
		h3{border-bottom:1px solid #000; width:620px; color:#000; padding-bottom:3px;}
		label{font-weight:bold;}
		input.button-primary{margin-top:20px;}
		.html-textarea{width:620px; height:300px; font-family:"Courier New", Courier, monospace; color:#333;}
		form p{margin-bottom:30px;}
	</style>

	<div class="wrap">
	  <h2>UC Settings Page</h2>
	
  <!-- Main Form -->
  
  <form method="post" action="options.php">
    <?php settings_fields('uc_options'); ?>    
    <p>
    	<label>Under Construction Message:</label><br />
        <textarea name="uc_content" class="html-textarea"><?php echo $uc_content ?></textarea><br />
        <span class="help-text">Enter HTML code for your default message</span>
    </p>
    <label>Exclude Pages:</label><br />
    <input name="uc_exclude" type="text" value="<?php echo $uc_exclude ?>" size="70" /><br />
    <span class="help-text">Enter a comma separated list of page ID numbers to exclude from this plugin</span>
    <p>
    	<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
    </p>
  </form>
</div><!--/.wrap-->
<?php } ?>
