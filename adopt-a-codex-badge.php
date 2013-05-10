<?php
/*
Plugin Name: Adopt A Codex Page Badge
Plugin URI: http://ramiabraham.com/adopt-a-codex-badge
Description: Shows your involvement in the Adopt A Codex initiative!
Version: 1.1
Author: Rami Abraham, Siobhan P McKeown, Drew Jaynes
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

	public $adopt_a_codex_username;
	public $adopted_codex_page;

	function Adopt_A_Codex_Badge() {
		load_plugin_textdomain( 'adopt_a_codex_badge', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

		$widget_ops = array( 'classname' => 'Adopt_A_Codex_Badge', 'description' => __( 'Shows your involvement in the WordPress Codex Adopt-A-Page initiative.', 'adopt_a_codex_badge' ) );
		$this->WP_Widget( 'Adopt_A_Codex_Badge', 'WordPress Codex Adopt-A-Page', $widget_ops );
	}
 
	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'adopt_a_codex_username' => '' ) );
		$this->adopt_a_codex_username = $instance['adopt_a_codex_username'];
		$instance = wp_parse_args( (array) $instance, array( 'adopted_codex_page' => '' ) );
		$this->adopted_codex_page = $instance['adopted_codex_page'];
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'adopt_a_codex_username' ); ?>"><?php _e( 'Your Codex or Wordpress.org username:', 'adopt_a_codex_badge' ); ?>
				<input class="widefat" id="<?php echo $this->get_field_id( 'adopt_a_codex_username' ); ?>" name="<?php echo $this->get_field_name( 'adopt_a_codex_username' ); ?>" type="text" value="<?php echo esc_attr( $this->adopt_a_codex_username ); ?>" />
			</label>
					<br /><br />
			<label for="<?php echo $this->get_field_id( 'adopted_codex_page' ); ?>"><?php _e( 'The page you are working on:', 'adopt_a_codex_badge' ); ?>
				<input class="widefat" id="<?php echo $this->get_field_id( 'adopted_codex_page' ); ?>" name="<?php echo $this->get_field_name( 'adopted_codex_page' ); ?>" type="text" value="<?php echo $this->adopted_codex_page; ?>" />
			</label>
			
		</p>
		<?php
	}
 
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['adopt_a_codex_username'] = $new_instance['adopt_a_codex_username'];
		$instance['adopted_codex_page'] = $new_instance['adopted_codex_page'];
		return $instance;
	}
 
	function widget( $args, $instance ) {
		extract( $args, EXTR_SKIP );
 
		echo $before_widget;
		
		$this->adopt_a_codex_username = empty( $instance['adopt_a_codex_username'] ) ? ' ' : apply_filters( 'widget_adopt_a_codex_username', $instance['adopt_a_codex_username'] );
		$this->adopted_codex_page = empty( $instance['adopted_codex_page'] ) ? ' ' : apply_filters( 'widget_adopted_codex_page', $instance['adopted_codex_page'] );
		if ( ! empty( $this->adopt_a_codex_username ) && ! empty( $this->adopted_codex_page ) ) {
		
			// Adopt A codex badge image wrapper. Pretty simple but it does the job.
			?>
			<div class="adopt-a-codex-badge-background">
				<h2><a href="<?php echo esc_url( 'http://profiles.wordpress.org/' . $this->adopt_a_codex_username ); ?>"><?php echo $this->adopt_a_codex_username; ?></a></h2>
			</div>
				<br />
				<div class="adopted-codex-page-link">
					<a href="<?php echo esc_url( 'http://codex.wordpress.org/' . $this->adopted_codex_page ); ?>">
						<?php _e( 'My Adopted Page', 'adopt_a_codex_badge' ); ?>
					</a>
				</div>

			<?php include_once( ABSPATH . WPINC . '/feed.php' ); ?>
				<div class="adopt-a-codex-log-container"><h3><?php
			printf( __( '%s\'s recent activity:', 'adopt_a_codex_badge' ), $this->adopt_a_codex_username );
				?></h3><?php
			$rss = fetch_feed( esc_url( sprintf( 'http://codex.wordpress.org/index.php?title=Special:Contributions&feed=rss&target=%s', $this->adopt_a_codex_username ) ) );
			$maxitems = $rss->get_item_quantity( 5 );
			$rss_items = $rss->get_items( 0, $maxitems );
			?>
					<ul id="adopt-a-codex-log">
				<?php if ( 0 == $maxitems ) 
					echo '<li>' . __( 'I haven\'t had a chance to get started yet.', 'adopt_a_codex_badge' ) . '</li>';
		    	else
		    		// Loop through Codex feed items and display each item as a hyperlink.
	    			foreach ( $rss_items as $item ) :
						?>
						<li>
							<a href="<?php echo esc_url( $item->get_permalink() ); ?>" title="<?php echo esc_attr( $item->get_date( 'j F Y l g:i a' ) ); ?>"><?php echo $item->get_title(); ?></a>
						</li>
					<?php endforeach; ?>
					<ul>
				</div><!-- #adopt-a-codex-log -->
			<?php
		}
	}

} // Adopt_A_Codex_Badge
} // exists check

add_action( 'widgets_init', create_function( '', 'return register_widget( "Adopt_A_Codex_Badge" );' ) );

function adopt_a_codex_badge_style() { 
	// Register the style for the codex badge background
	wp_register_style( 'adopt_a_codex_badge_style', 
		plugins_url( 'style.css', __FILE__ ),
		array(), 
		'20130420', 
		'all' );

	wp_enqueue_style( 'adopt_a_codex_badge_style' );
}
add_action( 'wp_enqueue_scripts', 'adopt_a_codex_badge_style' );
