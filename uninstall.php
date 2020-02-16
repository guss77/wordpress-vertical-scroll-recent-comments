<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

delete_option('vsrc_title');
delete_option('vsrc_select_num_user');
delete_option('vsrc_dis_num_user');
delete_option('vsrc_dis_num_height');
delete_option('vsrc_dis_image_or_name');
delete_option('vsrc_select_character');
delete_option('vsrc_speed');
delete_option('vsrc_waitseconds');
 
// for site options in Multisite
delete_site_option('vsrc_title');
delete_site_option('vsrc_select_num_user');
delete_site_option('vsrc_dis_num_user');
delete_site_option('vsrc_dis_num_height');
delete_site_option('vsrc_dis_image_or_name');
delete_site_option('vsrc_select_character');
delete_site_option('vsrc_speed');
delete_site_option('vsrc_waitseconds');