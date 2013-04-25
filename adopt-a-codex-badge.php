<?php
/*
Plugin Name: Adopt A Codex Badge
Plugin URI: http://ramiabraham.com/adopt-a-codex-badge
Description: Shows your involvement in the Adopt A Codex initiative!
Version: 1.1
Author: Rami Abraham, Siobhan P McKeown
Author URI: http://make.wordpress.org/docs
Text Domain: adopt-a-codex-badge-widget
Domain Path: /lang/
Network: false
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html


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

if ( ! class_exists( 'Adopt_A_Codex_Badge' ) ) {
class Adopt_A_Codex_Badge extends WP_Widget {

	function Adopt_A_Codex_Badge() {
		$widget_ops = array('classname' => 'Adopt_A_Codex_Badge', 'description' => 'Shows your involvement in the WordPress Adopt-A-Codex initiative.' );
		$this->WP_Widget( 'Adopt_A_Codex_Badge', 'Adopt A Codex Badge', $widget_ops );
	}
 
	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'adopt_a_codex_username' => '' ) );
		$adopt_a_codex_username = $instance['adopt_a_codex_username'];
		?>
		<p>
			<label for="<?php echo $this->get_field_id('adopt_a_codex_username'); ?>"><?php _e( 'Your Codex or Wordpress.org username:' ); ?>
				<input class="widefat" id="<?php echo $this->get_field_id('adopt_a_codex_username'); ?>" name="<?php echo $this->get_field_name('adopt_a_codex_username'); ?>" type="text" value="<?php echo attribute_escape($adopt_a_codex_username); ?>" />
			</label>
		</p>
		<?php
	}
 
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['adopt_a_codex_username'] = $new_instance['adopt_a_codex_username'];
		return $instance;
	}
 
	function widget( $args, $instance ) {
		extract( $args, EXTR_SKIP );
 
		echo $before_widget;
		
		$adopt_a_codex_username = empty( $instance['adopt_a_codex_username'] ) ? ' ' : apply_filters( 'widget_adopt_a_codex_username', $instance['adopt_a_codex_username'] );
		if ( ! empty( $adopt_a_codex_username ) );
		
		// Adopt A codex badge image wrapper. Pretty simple but it does the job.
		?>
		<div class="adopt-a-codex-badge-background">
			<h2><a href="<?php esc_url( printf( 'http://codex.wordpress.org/User:%s', $adopt_a_codex_username ) ); ?>"><?php echo $adopt_a_codex_username; ?></a></h2>
		</div><br />
		<?php include_once(ABSPATH.WPINC.'/feed.php'); ?>
		<br />
		<?php
		$rss = fetch_feed( esc_url( sprintf( 'http://codex.wordpress.org/index.php?title=Special:Contributions&feed=rss&target=%s', $adopt_a_codex_username ) ) );
		echo $adopt_a_codex_username;
		echo'\'s recent activity:';
		$maxitems = $rss->get_item_quantity( 5 );
		$rss_items = $rss->get_items( 0, $maxitems );
		?>
		<ul id="adopt-a-codex-log">
			<?php if ( 0 == $maxitems ) 
				echo '<li>' . __( 'I haven\'t had a chance to get started yet.' ) . '</li>';
	    	else
	    		// Loop through Codex feed items and display each item as a hyperlink.
    			foreach ( $rss_items as $item ) :
					?>
					<li>
						<a href="<?php echo $item->get_permalink(); ?>" title="<?php echo esc_attr( $item->get_date( 'j F Y l g:i a' ) ); ?>"><?php echo $item->get_title(); ?></a>
					</li>
				<?php endforeach; ?>
		<ul><!-- #adopt-a-codex-log -->
		<?php
	}

} // Adopt_A_Codex_Badge
} // exists check

add_action( 'widgets_init', create_function( '', 'return register_widget( "Adopt_A_Codex_Badge" );' ) );

	function adopt_a_codex_badge_style() { 
	// Register the style for the codex badge background
	wp_register_style( 'adopt_a_codex_badge_style', 
		esc_url( sprintf( '%s/adopt-a-codex-badge/style.css', plugins_url() ) ),
		array(), 
		'20130420', 
		'all' );

	wp_enqueue_style( 'adopt_a_codex_badge_style' );
}
add_action('wp_enqueue_scripts', 'adopt_a_codex_badge_style');
