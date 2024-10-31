<?php
/*
  Plugin Name: Scheduled Post List
  Plugin URI: http://wordpress.org/extend/plugins/scheduled-post-list
  Description: to show the list of author and title from your scheduled posts, in table style
  Version: 1.1
  Author: TaijiMark
  Author URI: http://www.laobanit.com/about
*/

/* 
  Copyright 2012,  TaijiMark  (email : taijimark@laobanit.com)

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

class taijimark001 extends WP_Widget {

    function __construct() {
        $widget_ops = array(
            'classname' => 'taijimark001',
            'description' => 'Show scheduled post list to inform your readers for upcoming topics.');
        $control_ops = array(
            'id_base' => 'taijimark001');
        $this->WP_Widget('taijimark001', '###Scheduled Post List', $widget_ops, $control_ops);
    }

    function widget($args, $instance) {
        
        // http://www.php.net/manual/en/function.extract.php
        extract($args);
        /* http://codex.wordpress.org/Widgets_API
           Here are the default values: 
          'name'          => sprintf( __( 'Sidebar %d' ), $i ),
          'id'            => "sidebar-$i",
          'description'   => '',
          'class'         => '',
          'before_widget' => '<li id="%1$s" class="widget %2$s">',
          'after_widget'  => "</li>n",
          'before_title'  => '<h2 class="widgettitle">',
          'after_title'   => "</h2>n",
         */
                
        $title = apply_filters('widget_title', $instance['title']); // widget title
        $post_cnt = $instance['post_cnt']; // number of schedued posts to list
        $no_found_msg = $instance['no_found_msg']; // message when no found

        if ($title) {
            echo $before_title . $title . $after_title;
        }

        //http://codex.wordpress.org/Function_Reference/WP_Query
        $qry = new WP_Query(array(
                    'posts_per_page' => $post_cnt,
                    'post_status' => 'future',
                    'order' => 'ASC',
                    'orderby' => 'date', 'ignore_sticky_posts' => '1'));

        if ($qry->have_posts()) {
            $k = 0;
            echo " <table class='tm001'>";
            echo "<tr><th></th><th>Author</th><th>Post Subject</th></tr> ";
            while ($qry->have_posts()) {
                $qry->the_post();
                $k++;
                echo "<tr><th>$k</th><td> ";
                the_author();
                echo "</td><td> ";
                the_title();
                echo "</td></tr>";
            }
            echo " </table>";
        } else {
            echo $no_found_msg;
        }
        echo $after_widget;
    }

    function update($new_instance, $old_instance) {
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['post_cnt'] = strip_tags($new_instance['post_cnt']);
        $instance['no_found_msg'] = $new_instance['no_found_msg'];
        return $instance;
    }

    function form($instance) {
        $defaults = array(
            'title' => 'Scheduled Post List',
            'post_cnt' => 3,
            'no_found_msg' => '( No posts found! )');
        $instance = wp_parse_args((array) $instance, $defaults);
        ?>
        <label for="<?php echo $this->get_field_id('title'); ?>">Title of this widget:</label>
        <input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>'" type="text" value="<?php echo $instance['title']; ?>" />
        <label for="<?php echo $this->get_field_id('post_cnt'); ?>"><?php _e('Number of posts to show:'); ?></label>
        <input id="<?php echo $this->get_field_id('post_cnt'); ?>" name="<?php echo $this->get_field_name('post_cnt'); ?>" type="text" value="<?php echo $instance['post_cnt']; ?>" />
        <label for="<?php echo $this->get_field_id('no_found_msg'); ?>"><?php _e('Message when no posts found:'); ?></label>
        <input id="<?php echo $this->get_field_id('no_found_msg'); ?>" name="<?php echo $this->get_field_name('no_found_msg'); ?>" type="text" value="<?php echo $instance['no_found_msg']; ?>" />
        <?php
    }
}

// http://www.php.net/manual/en/function.create-function.php
add_action('widgets_init', create_function('', "register_widget('taijimark001');"));

function taijimark001_css() {
    echo "
	<style type='text/css'>
            table.tm001 {
                margin: 0em 1em 1em 0.5em;
                background: #F0F0F0;
                border-collapse: collapse;
            }
            table.tm001 th,table.widget td {
                border: 1px #C0C0C0 solid;
                padding: 0.2em;
            }
            table.tm001 th {
                background: #D0D0D0;
                text-align: left;
            }
        </style>
        "
    ;
}

// http://codex.wordpress.org/Plugin_API/Action_Reference/wp_head
// The wp_head action hook is triggered within the <head></head> section of the user's template by the wp_head() function.
add_action('wp_head', 'taijimark001_css');
?>