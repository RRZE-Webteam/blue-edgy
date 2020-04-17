<?php

/**
 * Text widget with border-bottom and aligncenter
 */
class rrze_Widget_Text extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'widget_text', 'description' => __('Normaler Text oder HTML.', 'blue-edgy' ));
		$control_ops = array('width' => 400, 'height' => 350);
		parent::__construct('text', __('Text', 'blue-edgy' ), $widget_ops, $control_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
                $align = ! empty( $instance['align'] ) ? ' aligncenter' : '';
		$bottom = ! empty( $instance['bottom'] ) ? 'rrze_textwidget' : 'textwidget';
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$text = apply_filters( 'widget_text', empty( $instance['text'] ) ? '' : $instance['text'], $instance );
		echo $before_widget;

                                
		if ( !empty( $title ) ) { echo $before_title . $title . $after_title; } ?>
			<div class="<?php echo $bottom . $align; ?>"><?php echo !empty( $instance['filter'] ) ? wpautop( $text ) : $text; ?></div>
		<?php
		echo $after_widget;

	}

	function update( $new_instance, $old_instance ) {
            
		
                $instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		if ( current_user_can('unfiltered_html') )
			$instance['text'] =  $new_instance['text'];
		else
			$instance['text'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['text']) ) ); // wp_filter_post_kses() expects slashed
                $instance['align'] = isset($new_instance['align']);
		$instance['bottom'] = isset($new_instance['bottom']);
		$instance['filter'] = isset($new_instance['filter']);

		return $instance;

	}

	function form( $instance ) {
            
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '', 'align' => '', 'bottom' => '') );
		$title = strip_tags($instance['title']);
		$text = esc_textarea($instance['text']);
                $align = $instance['align'] ? 'checked="checked"' : '';
		$bottom = $instance['bottom'] ? 'checked="checked"' : '';

?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Titel:', 'blue-edgy' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

		<textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea>
                <p>
			<input class="checkbox" type="checkbox" <?php echo $align; ?> id="<?php echo $this->get_field_id('align'); ?>" name="<?php echo $this->get_field_name('align'); ?>" /> <label for="<?php echo $this->get_field_id('align'); ?>"><?php _e('Inhalt mittig ausrichten', 'blue-edgy'); ?></label>
                <br/>
			<input class="checkbox" type="checkbox" <?php echo $bottom; ?> id="<?php echo $this->get_field_id('bottom'); ?>" name="<?php echo $this->get_field_name('bottom'); ?>" /> <label for="<?php echo $this->get_field_id('bottom'); ?>"><?php _e('Fußlinie nicht anzeigen', 'blue-edgy'); ?></label>
		
                </p>

		<p><input id="<?php echo $this->get_field_id('filter'); ?>" name="<?php echo $this->get_field_name('filter'); ?>" type="checkbox" <?php checked(isset($instance['filter']) ? $instance['filter'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('filter'); ?>"><?php _e('Absätze automatisch hinzufügen', 'blue-edgy' ); ?></label></p>
<?php
	}
};
