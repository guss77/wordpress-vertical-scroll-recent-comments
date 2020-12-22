<?php
/*
Plugin Name: Vertical scroll recent comments
Description: Vertical scroll recent comments wordpress plugin will scroll the recent post comment vertically (bottom to top) in the widget.
Author: Gopi Ramasamy
Author URI: http://www.gopiplus.com/work/2010/07/18/vertical-scroll-recent-comments/
Plugin URI: http://www.gopiplus.com/work/2010/07/18/vertical-scroll-recent-comments/
Version: 11.9
Tags: Vertical, scroll, recent, comments, comment, widget
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
vsrc means Vertical scroll recent comments
Text Domain: vertical-scroll-recent-comments
Domain Path: /languages
*/

function vsrc() 
{
	global $wpdb;
	?>
    <style type="text/css">
	.vsrc-regimag img { 
	float: left ;
	border: 1px solid #CCCCCC ;
	vertical-align:bottom; 
	padding: 3px ;
	margin-right: 2px;
	};
    </style>
    <?php
	$num_user = get_option('vsrc_select_num_user');
	$dis_num_user = get_option('vsrc_dis_num_user');
	$dis_num_height = get_option('vsrc_dis_num_height');
	$vsrc_select_character = get_option('vsrc_select_character');
	
	$vsrc_speed = get_option('vsrc_speed');
	$vsrc_waitseconds = get_option('vsrc_waitseconds');
	if(!is_numeric($vsrc_speed)) { $vsrc_speed = 2; }
	if(!is_numeric($vsrc_waitseconds)) { $vsrc_waitseconds = 2; }
	
	if(!is_numeric($num_user))
	{
		$num_user = 5;
	} 
	if(!is_numeric($dis_num_height))
	{
		$dis_num_height = 30;
	}
	if(!is_numeric($dis_num_user))
	{
		$dis_num_user = 5;
	}
	if(!is_numeric($vsrc_select_character))
	{
		$vsrc_select_character = 75;
	}

	$vsrc_data = $wpdb->get_results("SELECT * from $wpdb->comments WHERE comment_approved= '1' and comment_type<>'pingback' ORDER BY comment_date DESC LIMIT 0, $num_user");

	$vsrc_html = "";
	$post_link = "";
	$avatar = "";
	$vsrc_x = "";
	if ( ! empty($vsrc_data) ) 
	{
		$vsrc_count = 0;

		foreach ( $vsrc_data as $vsrc_data ) 
		{
			$vsrc_post_title = $vsrc_data->comment_content 	;
			$vsrc_post_title = strip_tags($vsrc_post_title);
			$vsrc_post_title = preg_replace("/[\n\t\r]/"," ",$vsrc_post_title);
			$vsrc_comment_author = $vsrc_data->comment_author 	;
			$vsrc_post_title = substr($vsrc_post_title, 0, $vsrc_select_character);
			$avatar = get_avatar( $vsrc_data->comment_author_email, 30 );
			$dis_height = $dis_num_height."px";
			$vsrc_html = $vsrc_html . "<div class='vsrc_div' style='height:$dis_height;padding:2px 0px 2px 0px;'>"; 
			if(get_option('vsrc_dis_image_or_name') == "NAME" )
			{
				$vsrc_html = $vsrc_html . "<span>$vsrc_comment_author: </span>";
				$vsrc_js_html = "<span>$vsrc_comment_author: </span>";
			}
			elseif(get_option('vsrc_dis_image_or_name') == "IMAGE")
			{
				$vsrc_html = $vsrc_html . "<span class='vsrc-regimag'>$avatar</span>";
				$avatar = esc_sql($avatar);
				$vsrc_js_html = "<span class=\'vsrc-regimag\'>$avatar</span>";
			}
			$vsrc_html = $vsrc_html . "<span>$vsrc_post_title...</span>";
			$vsrc_html = $vsrc_html . "</div>";
			$vsrc_post_title = esc_sql(trim($vsrc_post_title));
			$post_link    = get_permalink($vsrc_data->comment_post_ID);
			$comment_link = $post_link ."#comment-$vsrc_data->comment_ID";
			$vsrc_post_title = "<a href=\'$comment_link\'>$vsrc_post_title ...</a>";
			$vsrc_x = $vsrc_x . "vsrc_array[$vsrc_count] = '<div class=\'vsrc_div\' style=\'height:$dis_height;padding:2px 0px 2px 0px;\'>$vsrc_js_html<span>$vsrc_post_title</span></div>'; ";	
			$vsrc_count++;
		}

		$dis_num_height = $dis_num_height + 4;
		if($vsrc_count >= $dis_num_user)
		{
			$vsrc_count = $dis_num_user;
			$vsrc_height = ($dis_num_height * $dis_num_user);
		}
		else
		{
			$vsrc_count = $vsrc_count;
			$vsrc_height = ($vsrc_count*$dis_num_height);
		}
		$vsrc_height1 = $dis_num_height."px";
		?>	
		<div style="padding-top:8px;padding-bottom:8px;">
			<div style="text-align:left;vertical-align:middle;text-decoration: none;overflow: hidden; position: relative; margin-left: 1px; height: <?php echo $vsrc_height1; ?>;" id="vsrc_Holder">
				<?php echo $vsrc_html; ?>
			</div>
		</div>
		<script type="text/javascript" src="<?php echo plugins_url(); ?>/vertical-scroll-recent-comments/vertical-scroll-recent-comments.js"></script>
		<script type="text/javascript">
		var vsrc_array	= new Array();
		var vsrc_obj	= '';
		var vsrc_scrollPos 	= '';
		var vsrc_numScrolls	= '';
		var vsrc_heightOfElm = '<?php echo $dis_num_height; ?>';
		var vsrc_numberOfElm = '<?php echo $vsrc_count; ?>';
		var vsrc_speed 		= '<?php echo $vsrc_speed; ?>';
        var vsrc_waitseconds = '<?php echo $vsrc_waitseconds; ?>';
		var vsrc_scrollOn 	= 'true';
		function vsrc_createscroll() 
		{
			<?php echo $vsrc_x; ?>
			vsrc_obj = document.getElementById('vsrc_Holder');
			vsrc_obj.style.height = (vsrc_numberOfElm * vsrc_heightOfElm) + 'px';
			vsrc_content();
		}
		</script>
		<script type="text/javascript">
		vsrc_createscroll();
		</script>
		<?php
	}
	else
	{
		echo "<div style='padding-bottom:5px;padding-top:5px;'>No data available!</div>";
	}
}

function vsrc_install() 
{
	add_option('vsrc_title', "Recent Comments");
	add_option('vsrc_select_num_user', "10");
	add_option('vsrc_dis_num_user', "5");
	add_option('vsrc_dis_num_height', "60");
	add_option('vsrc_dis_image_or_name', "NAME");
	add_option('vsrc_select_character', "50");
	add_option('vsrc_speed', "2");
	add_option('vsrc_waitseconds', "2");
}

function vsrc_control() 
{
	echo '<p><b>';
	_e('Vertical scroll recent comments', 'vertical-scroll-recent-comments');
	echo '.</b> ';
	_e('Check official website for more information', 'vertical-scroll-recent-comments');
	?> <a target="_blank" href="http://www.gopiplus.com/work/2010/07/18/vertical-scroll-recent-post/"><?php _e('click here', 'vertical-scroll-recent-comments'); ?></a></p><?php
}

function vsrc_admin_options()
{
	?>
	<div class="wrap">
	  <div class="form-wrap">
		<div id="icon-edit" class="icon32"></div>
		<h2><?php _e('Vertical scroll recent comments' , 'vertical-scroll-recent-comments'); ?></h2>
		<?php	
		$display_name 			= "";
		$display_avator 		= "";
		$display_none 			= "";
		$vsrc_title 			= get_option('vsrc_title');
		$vsrc_select_num_user 	= get_option('vsrc_select_num_user');
		$vsrc_dis_num_user 		= get_option('vsrc_dis_num_user');
		$vsrc_dis_num_height 	= get_option('vsrc_dis_num_height');
		$vsrc_dis_image_or_name = get_option('vsrc_dis_image_or_name');
		$vsrc_select_character 	= get_option('vsrc_select_character');
		
		$vsrc_speed = get_option('vsrc_speed');
		$vsrc_waitseconds = get_option('vsrc_waitseconds');
		
		if (isset($_POST['vsrc_form_submit']) && $_POST['vsrc_form_submit'] == 'yes')
		{
			check_admin_referer('vsrc_form_setting');
			
			$vsrc_title 			= stripslashes(sanitize_text_field($_POST['vsrc_title']));
			$vsrc_select_num_user 	= intval($_POST['vsrc_select_num_user']);
			$vsrc_dis_num_user 		= intval($_POST['vsrc_dis_num_user']);
			$vsrc_dis_num_height 	= intval($_POST['vsrc_dis_num_height']);
			$vsrc_dis_image_or_name = stripslashes(sanitize_text_field($_POST['name_ava']));
			$vsrc_select_character 	= intval($_POST['vsrc_select_character']);
			$vsrc_speed 			= intval($_POST['vsrc_speed']);
			$vsrc_waitseconds 		= intval($_POST['vsrc_waitseconds']);
			
			update_option('vsrc_title', $vsrc_title );
			update_option('vsrc_select_num_user', $vsrc_select_num_user );
			update_option('vsrc_dis_num_user', $vsrc_dis_num_user );
			update_option('vsrc_dis_num_height', $vsrc_dis_num_height );
			update_option('vsrc_dis_image_or_name', $vsrc_dis_image_or_name );
			update_option('vsrc_select_character', $vsrc_select_character );
			
			update_option('vsrc_speed', $vsrc_speed );
			update_option('vsrc_waitseconds', $vsrc_waitseconds );
			?>
			<div class="updated fade">
				<p><strong><?php _e('Details successfully updated.' , 'vertical-scroll-recent-comments'); ?></strong></p>
			</div>
			<?php
		}
		if($vsrc_dis_image_or_name == "NAME")
		{
			$display_name = 'checked="checked"';
		}
		elseif($vsrc_dis_image_or_name == "IMAGE")
		{
			$display_avator = 'checked="checked"';
		}
		else
		{
			$display_none = 'checked="checked"';
		}
		?>
		<form name="vsrc_form" method="post" action="">
		    <h3><?php _e('Widget setting' , 'vertical-scroll-recent-comments'); ?></h3>
		
			<label for="tag-width"><strong><?php _e('Widget title' , 'vertical-scroll-recent-comments'); ?></strong></label>
			<input name="vsrc_title" type="text" value="<?php echo $vsrc_title; ?>"  id="vsrc_title" size="50" maxlength="150">
			<p><?php _e('Please enter your widget title.' , 'vertical-scroll-recent-comments'); ?></p>
			
			<label for="tag-width"><strong><?php _e('Height' , 'vertical-scroll-recent-comments'); ?></strong></label>
			<input name="vsrc_dis_num_height" type="text" value="<?php echo $vsrc_dis_num_height; ?>"  id="vsrc_dis_num_height" maxlength="4">
			<p><?php _e('Please enter your height. If any overlap in the scroll at front end, <br />You should arrange this height (increase/decrease this height).' , 'vertical-scroll-recent-comments'); ?> (Example: 10)</p>
			
			<label for="tag-width"><strong><?php _e('Display count' , 'vertical-scroll-recent-comments'); ?></strong></label>
			<input name="vsrc_dis_num_user" type="text" value="<?php echo $vsrc_dis_num_user; ?>"  id="vsrc_dis_num_user" maxlength="2">
			<p><?php _e('Please enter your display count. Display number of comments at the same time in scroll.' , 'vertical-scroll-recent-comments'); ?> (Example: 5)</p>
			
			<label for="tag-width"><strong><?php _e('Scroll comment count' , 'vertical-scroll-recent-comments'); ?></strong></label>
			<input name="vsrc_select_num_user" type="text" value="<?php echo $vsrc_select_num_user; ?>"  id="vsrc_select_num_user" maxlength="3">
			<p><?php _e('Please enter your scroll comment count. Enter max number of comments to scroll.' , 'vertical-scroll-recent-comments'); ?> (Example: 10)</p>
			
			<label for="tag-width"><strong><?php _e('Comments length' , 'vertical-scroll-recent-comments'); ?></strong></label>
			<input name="vsrc_select_character" type="text" value="<?php echo $vsrc_select_character; ?>"  id="vsrc_select_character" maxlength="3">
			<p><?php _e('Please enter number of comment characters you like to display in the scroll.' , 'vertical-scroll-recent-comments'); ?> (Example: 50)</p>
			
			<label for="tag-width"><strong><?php _e('Display Options' , 'vertical-scroll-recent-comments'); ?></strong></label>
			<?php _e('Display name :' , 'vertical-scroll-recent-comments'); ?> <input name="name_ava" id="name_ava" type="radio" value="NAME" <?php echo $display_name; ?> /> &nbsp;  &nbsp; 
			<?php _e('Display Avator :' , 'vertical-scroll-recent-comments'); ?> <input name="name_ava" id="name_ava" type="radio" value="IMAGE" <?php echo $display_avator; ?> /> &nbsp;  &nbsp; 
			<?php _e('None :' , 'vertical-scroll-recent-comments'); ?> <input name="name_ava" id="name_ava" type="radio" value="NONE" <?php echo $display_none; ?> />
			<p><?php _e('Please select your disply option.' , 'vertical-scroll-recent-comments'); ?></p>
			
			<label for="vsrc_speed"><strong><?php _e('Scrolling speed', 'vertical-scroll-recent-comments'); ?></strong></label>
			<?php _e( 'Slow', 'vertical-scroll-recent-comments' ); ?> 
			<input name="vsrc_speed" type="range" value="<?php echo $vsrc_speed; ?>"  id="vsrc_speed" min="1" max="10" /> 
			<?php _e( 'Fast', 'vertical-scroll-recent-comments' ); ?> 
			<p><?php _e('Select how fast you want the to scroll the items.', 'vertical-scroll-recent-comments'); ?></p>
			
			<label for="vsrc_waitseconds"><strong><?php _e( 'Seconds to wait', 'vertical-scroll-recent-comments' ); ?></strong></label>
			<input name="vsrc_waitseconds" type="text" value="<?php echo $vsrc_waitseconds; ?>" id="vsrc_waitseconds" maxlength="4" />
			<p><?php _e( 'How many seconds you want the wait to scroll', 'vertical-scroll-recent-comments' ); ?> (<?php _e( 'Example', 'vertical-scroll-recent-comments' ); ?>: 5)</p>
			
			<p class="submit">
				<input name="vsrc_submit" id="vsrc_submit" class="button" value="<?php _e('Submit' , 'vertical-scroll-recent-comments'); ?>" type="submit" />&nbsp;
				<a class="button" target="_blank" href="http://www.gopiplus.com/work/2010/07/18/translucent-image-slideshow-gallery/"><?php _e('Help' , 'vertical-scroll-recent-comments'); ?></a>
			</p>
			<input type="hidden" name="vsrc_form_submit" value="yes"/>
			<?php wp_nonce_field('vsrc_form_setting'); ?>
		</form>
		</div>
		<h3><?php _e('Plugin configuration option' , 'vertical-scroll-recent-comments'); ?></h3>
		<ol>
			<li><?php _e('Add directly in to the theme using PHP code.' , 'vertical-scroll-recent-comments'); ?></li>
			<li><?php _e('Drag and drop the widget to your sidebar.' , 'vertical-scroll-recent-comments'); ?></li>
		</ol>
	  <p class="description"><?php _e('Check official website for more information' , 'vertical-scroll-recent-comments'); ?> 
	  <a target="_blank" href="http://www.gopiplus.com/work/2010/07/18/vertical-scroll-recent-comments/"><?php _e('click here' , 'vertical-scroll-recent-comments'); ?></a></p>
	</div>
	<?php
}

function vsrc_widget($args) 
{
	extract($args);
	echo $before_widget . $before_title;
	echo get_option('vsrc_title');
	echo $after_title;
	vsrc();
	echo $after_widget;
}

function vsrc_init()
{
	if(function_exists('wp_register_sidebar_widget')) 
	{
		wp_register_sidebar_widget('vertical-scroll-recent-comments', __('Vertical scroll recent comments', 'vertical-scroll-recent-comments'), 'vsrc_widget');
	}
	
	if(function_exists('wp_register_widget_control')) 
	{
		wp_register_widget_control('vertical-scroll-recent-comments', 
					array( __('Vertical scroll recent comments', 'vertical-scroll-recent-comments'), 'widgets'), 'vsrc_control');
	} 
}

function vsrc_add_to_menu()
{
	add_options_page( __('Vertical scroll recent comments', 'vertical-scroll-recent-comments'), 
			__('Vertical scroll recent comments', 'vertical-scroll-recent-comments'), 'manage_options', __FILE__, 'vsrc_admin_options' );
}

if (is_admin())
{
	add_action('admin_menu', 'vsrc_add_to_menu');
}

function vsrc_deactivation()
{
	// No action required.
}

function vsrc_textdomain()
{
	  load_plugin_textdomain( 'vertical-scroll-recent-comments', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

add_action('plugins_loaded', 'vsrc_textdomain');
add_action("plugins_loaded", "vsrc_init");
register_activation_hook(__FILE__, 'vsrc_install');
register_deactivation_hook(__FILE__, 'vsrc_deactivation');
?>