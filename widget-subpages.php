<?php

/*
Plugin Name: Sub Pages Widget
Plugin URI: http://www.wearepixel8.com
Description: A widget that will display a list of sub pages and/or siblings relative to the currently viewed page.
Version: 1.0.0
Author: We Are Pixel8
Author URI: http://www.wearepixel8.com
License:
	Copyright 2013 We Are Pixel8 <hello@wearepixel8.com>
	
	This program is free software; you can redistribute it and/or modify it under
	the terms of the GNU General Public License, version 2, as published by the Free
	Software Foundation.
	
	This program is distributed in the hope that it will be useful, but WITHOUT ANY
	WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
	PARTICULAR PURPOSE. See the GNU General Public License for more details.
	
	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software Foundation, Inc.,
	51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
*/

/*----------------------------------------------------------------------------*/
/* Has Children
/*----------------------------------------------------------------------------*/

/**
 * Has Children
 *
 * A helper function to determine if the currently viewed page has sub pages.
 *
 * @param $id Post ID
 *
 * @package Sub Pages Widget
 * @version 1.0.0
 * @since 1.0.0
 * @author Erik Ford for We Are Pixel8 <@notdivisible>
 *
 */

function wap8_has_children( $id ) {
	
	$children = get_pages( 'child_of=' . $id );
	
	if ( count( $children ) > 0 ) {
		return '1';
	}
	
}

/*----------------------------------------------------------------------------*/
/* Has Siblings
/*----------------------------------------------------------------------------*/

/**
 * Has Siblings
 *
 * A helper function to determine if the currently viewed page has siblings.
 *
 * @package Sub Pages Widget
 * @version 1.0.0
 * @since 1.0.0
 * @author Erik Ford for We Are Pixel8 <@notdivisible>
 *
 */

function wap8_has_siblings() {
	
	global $post;
	
	if ( $post->post_parent ) {
		
		$siblings = get_pages( 'child_of=' . $post->post_parent );
		
		if ( count( $siblings ) > 0 ) {
			return '1';
		}
	
	}
	
}

/*----------------------------------------------------------------------------*/
/* Sub Pages Widget
/*----------------------------------------------------------------------------*/
 
add_action( 'widgets_init', 'wap8_subpages_widget', 10 );

/**
 * Sub Pages Widget
 *
 * Register the Sub Pages widget.
 *
 * @package Sub Pages Widget
 * @version 1.0.0
 * @since 1.0.0
 * @author Erik Ford for We Are Pixel8 <@notdivisible>
 *
 */

function wap8_subpages_widget() {
	register_widget( 'wap8_Subpages_Widget' );
}

/*----------------------------------------------------------------------------*/
/* Extend WP_Widget by adding This Widget Class
/*----------------------------------------------------------------------------*/

class wap8_Subpages_Widget extends WP_Widget {

	// widget setup
	function wap8_Subpages_Widget() {
		$widget_ops = array(
			'classname'   => 'wap8-subpages-widget',
			'description' => __( 'Display a list of sub pages relative to the currently viewed page.', 'wap8theme-i18n' )
			);
			
		$this->WP_Widget( 'wap8-subpages-widget', __( 'Sub Pages', 'wap8theme-i18n' ), $widget_ops );	
	}
	
	// widget output
	function widget( $args, $instance ) {
		
		global $post;
		
		if ( is_page() && wap8_has_children( $post->ID ) == '1' || wap8_has_siblings( $post->ID ) == '1' ) {
		
			extract( $args );
			
			echo $before_widget;
			
			if ( $post->post_parent ) {
				
				$args = array(
					'sort_column' => 'menu_order',
					'title_li'    => '',
					'child_of'    => $post->post_parent,
					'echo'        => 1
				);
			
			} else {
				
				$args = array(
					'sort_column' => 'menu_order',
					'title_li'    => '',
					'child_of'    => $post->ID,
					'echo'        => 1
				);
				
			}
			
			echo "<ul>\n";
			wp_list_pages( $args );
			echo "</ul>\n";
			
			echo $after_widget;
				
		}
		
	}
	
	// Update widget
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		return $instance;
	}
	
	// Widget form
	function form( $instance ) {
		
		$instance = wp_parse_args( ( array ) $instance );
		
		?>
		
		<p><?php _e( 'This widget has no options and will only appear on pages that have sub pages.', 'wap8theme-i18n' ); ?></p>
		
		<?php
		
	}		
	
}