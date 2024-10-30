<?php
/**
 * Plugin Name: Mobli Image Gallery Widget
 * Plugin URI: http://mobdmin.yampolsky.org
 * Description: Show your latest Mobli images in a widget. Images are linked to Mobli.com, where all your Mobli images are visible.
 * Version: 1.0.1
 * Author: Tal Yampolsky
 * Author URI: http://yampolsky.org 
 */

/** 
 * @since 0.1
 */
add_action( 'widgets_init', 'mobli_gallery_load_widgets' );

/**
 * @since 0.1
 */
function mobli_gallery_load_widgets() {
	register_widget( 'Mobli_Image_Gallery_Widget' );
}

/**
 * @since 0.1
 */
class Mobli_Image_Gallery_Widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function Mobli_Image_Gallery_Widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'gallery', 'description' => __('Show your latest Mobli images in a widget.', 'gallery') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'mobli-gallery' );

		/* Create the widget. */
		$this->WP_Widget( 'mobli-gallery', __('Mobli Image Gallery Widget', 'gallery'), $widget_ops, $control_ops );
	}

	/**
	 * Display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );
		
		$title = apply_filters('widget_title', $instance['title'] );
		$user_name = $instance['user_name'];
		$images = $instance['images'];
		$image_width = $instance['image_width'];
		
		echo $before_widget;

		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $title )
			echo $before_title . $title . $after_title;

		/* Display name from widget settings if one was input. */		
		if ( $user_name && $images)
			printf( '<p>' . __(file_get_the_contents('http://mobdmin.yampolsky.org/wp-widget.php?u='.$user_name.'&n='.$images.'&w='.$image_width), 'gallery') . '</p>', $user_name );
			
		echo $after_widget;
	}

	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['user_name'] = strip_tags( $new_instance['user_name'] );		
		$instance['images'] = strip_tags($new_instance['images']);
		$instance['image_width'] = strip_tags($new_instance['image_width']);

		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.	 
	 */
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 'title' => __('My Mobli Stream', 'gallery'), 'user_name' => __('talyampolsky', 'gallery'), 'images' => '6', 'image_width' => __('70', 'gallery'));
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'hybrid'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>

		<!-- User Name: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'user_name' ); ?>"><?php _e('User Name:', 'gallery'); ?></label>
			<input id="<?php echo $this->get_field_id( 'user_name' ); ?>" name="<?php echo $this->get_field_name( 'user_name' ); ?>" value="<?php echo $instance['user_name']; ?>" style="width:100%;" />
		</p>
		
		<!-- Image Width: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'image_width' ); ?>"><?php _e('Image Maximum Width (px):', 'gallery'); ?></label>
			<input id="<?php echo $this->get_field_id( 'image_width' ); ?>" name="<?php echo $this->get_field_name( 'image_width' ); ?>" value="<?php echo $instance['image_width']; ?>" style="width:100%;" />
		</p>		

		<!-- Images: Select Box -->
		<p>
			<label for="<?php echo $this->get_field_id( 'images' ); ?>"><?php _e('Number of Images:', 'gallery'); ?></label> 
			<select id="<?php echo $this->get_field_id( 'images' ); ?>" name="<?php echo $this->get_field_name( 'images' ); ?>" class="widefat" style="width:100%;">
				<option <?php if ( '1' == $instance['images'] ) echo 'selected="selected"'; ?>>1</option>
				<option <?php if ( '2' == $instance['images'] ) echo 'selected="selected"'; ?>>2</option>
				<option <?php if ( '3' == $instance['images'] ) echo 'selected="selected"'; ?>>3</option>
				<option <?php if ( '4' == $instance['images'] ) echo 'selected="selected"'; ?>>4</option>
				<option <?php if ( '5' == $instance['images'] ) echo 'selected="selected"'; ?>>5</option>
				<option <?php if ( '6' == $instance['images'] ) echo 'selected="selected"'; ?>>6</option>
				<option <?php if ( '7' == $instance['images'] ) echo 'selected="selected"'; ?>>7</option>
				<option <?php if ( '8' == $instance['images'] ) echo 'selected="selected"'; ?>>8</option>
				<option <?php if ( '9' == $instance['images'] ) echo 'selected="selected"'; ?>>9</option>
				<option <?php if ( '10' == $instance['images'] ) echo 'selected="selected"'; ?>>10</option>				
			</select>
		</p>

	<?php
	}
}

function file_get_the_contents($url) 
{	
	$ch = curl_init();  
		
	$timeout = 0; // set to zero for no timeout  
	curl_setopt ($ch, CURLOPT_USERAGENT, 'Mobdmin (http://mobdmin.yampolsky.org)');
	curl_setopt ($ch, CURLOPT_URL, $url);  
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);  
	curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);  	

	$file_contents = curl_exec($ch);  
	
	curl_close($ch); 
	
	return $file_contents;
}
?>