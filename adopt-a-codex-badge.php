<?php
/*
Plugin Name: Adopt A Codex Badge
Plugin URI: http://ramiabraham.com/adopt-a-codex-badge
Description: Shows your involvement in the WordPress Adopt A Codex initiative!
Version: 1.0
Author: Rami Abraham, idea by Siobhan
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

 
 
class AdoptACodexBadge extends WP_Widget
{
  function AdoptACodexBadge()
  {
    $widget_ops = array('classname' => 'AdoptACodexBadge', 'description' => 'Shows your awesome involvement in the WordPress Adopt-A-Codex initiative.' );
    $this->WP_Widget('AdoptACodexBadge', 'Adopt A Codex Badge', $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'adopt_a_codex_username' => '' ) );
    $adopt_a_codex_username = $instance['adopt_a_codex_username'];
?>
  <p><label for="<?php echo $this->get_field_id('adopt_a_codex_username'); ?>">Your Codex/Wordpress.org username: <input class="widefat" id="<?php echo $this->get_field_id('adopt_a_codex_username'); ?>" name="<?php echo $this->get_field_name('adopt_a_codex_username'); ?>" type="text" value="<?php echo attribute_escape($adopt_a_codex_username); ?>" /></label></p>
<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['adopt_a_codex_username'] = $new_instance['adopt_a_codex_username'];
    return $instance;
  }
 
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);
 
    echo $before_widget;
    $adopt_a_codex_username = empty($instance['adopt_a_codex_username']) ? ' ' : apply_filters('widget_adopt_a_codex_username', $instance['adopt_a_codex_username']);
    if (!empty($adopt_a_codex_username));
      
    
    // Adopt A Codex Badge image wrapper. Pretty simple but it does the job.
    echo '<a href="http://codex.wordpress.org/User:';
    echo $adopt_a_codex_username;
    echo '">';
    echo '<img title="Click here to see my WordPress Codex profile" alt="link to WordPress Adopt-A-Codex Initiative" src="' . plugins_url( '/adopt-a-codex-badge.png' , __FILE__ ) . '" >';
    echo '</a>';
    echo '<br />';
    // Should probably move the include to the top, and disable on admin screens
    include_once(ABSPATH.WPINC.'/feed.php');
    echo '<br />';
    $rss = fetch_feed('http://codex.wordpress.org/index.php?title=Special:Contributions&feed=rss&target=' . $adopt_a_codex_username);
    echo $adopt_a_codex_username;
    echo'\'s recent activity:';
    $maxitems = $rss->get_item_quantity(5);
    $rss_items = $rss->get_items(0, $maxitems);
    echo '<ul>';
    	if ($maxitems == 0) echo '<li>I haven\'t had a chance to get started yet.</li>';
	    	else
	    		// Loop through Codex feed items and display each item as an electronic internets hyperlink.
	    			foreach ( $rss_items as $item ) :
	    			echo '<li>';
	    			echo '<a href="';
	    			echo $item->get_permalink();
	    			echo '" title="';
	    			echo 'Posted '.$item->get_date('j F Y | g:i a');
	    			echo '">';
	    			echo $item->get_title();
	    			echo '</li>';
	    			endforeach;
	    			echo '</ul>';   			
  }
 
}
add_action( 'widgets_init', create_function('', 'return register_widget("AdoptACodexBadge");') );?>