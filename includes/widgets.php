<?php

//upcoming meetings widget
class TSML_Widget_Upcoming extends WP_Widget {

	//constructor
	public function __construct() {
		parent::__construct('tsml_widget_upcoming', __('Upcoming Meetings', '12-step-meeting-list'),
			array(
				'description' => __('Display a table of upcoming meetings.', '12-step-meeting-list'),
			)
		);
	}

	//front-end display of widget
	public function widget($args, $instance) {
		$table = tsml_next_meetings($instance);
		if (empty($table)) return false;
		if (empty($instance['title'])) $instance['title'] = '';
		if (!empty($instance['css'])) {
			echo '<style type="text/css">
				.tsml-widget-upcoming {
					background-color: transparent;
					padding: 0;
				}
				.tsml-widget-upcoming table thead { display: none; }
				.tsml-widget-upcoming table {
					border: 0;
					margin-bottom: 13px;
				}
				.tsml-widget-upcoming table tbody tr { 
					display: block;
					padding: 6px 6px 6px 80px;
					position: relative;
					border-bottom: 1px solid rgba(0, 0, 0, 0.1);
				}
				.tsml-widget-upcoming table tbody td { 
					display: block;
					border: 0;
				}
				.tsml-widget-upcoming table tbody td a { 
					font-weight: 700;
				}
				.tsml-widget-upcoming table tbody td a small { 
					color: #999;
					margin-left: 4px;
				}
				.tsml-widget-upcoming table tbody td a small:before { 
					content: " / ";
					margin-right: 3px;
				}
				.tsml-widget-upcoming table tbody td.time { 
					position: absolute;
					left: 0;
				}
				.tsml-widget-upcoming p a {
					font-size: 15px;
					font-weight: 700;
				}
				.widgets-meetings-top .tsml-widget-upcoming {
					margin: 0 0 15px;
				}
			</style>';
		}
		
		//don't know how to set this properly
		$args['before_widget'] = str_replace(' class="', ' class="tsml-widget-upcoming ', $args['before_widget']);
		
		echo $args['before_widget'];
		if (!empty($instance['title'])) {
			echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
		}
		echo $table;
		$link = get_post_type_archive_link('tsml_meeting');
		$link .= ((strpos($link, '?') === false) ? '?' : '&') . 'tsml-time=upcoming';
		echo '<p><a href="' . $link . '">' . __('View More…', '12-step-meeting-list') . '</a></p>';
		echo $args['after_widget'];
	}

	//backend form
	public function form($instance) {
		$title = !empty($instance['title']) ? $instance['title'] : __('Upcoming Meetings', '12-step-meeting-list');
		$count = !empty($instance['count']) ? $instance['count'] : 5;
		?>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title'))?>"><?php _e('Title:', '12-step-meeting-list')?></label> 
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title'))?>" name="<?php echo esc_attr($this->get_field_name('title'))?>" type="text" value="<?php echo esc_attr($title)?>">
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('count'))?>"><?php _e('Show:', '12-step-meeting-list')?></label> 
			<select class="widefat" id="<?php echo esc_attr($this->get_field_id('title'))?>" name="<?php echo esc_attr($this->get_field_name('count'))?>">
				<?php for ($i = 1; $i < 11; $i++) {?>
					<option value="<?php echo $i?>"<?php selected($i, esc_attr($count))?>><?php echo $i?></option>
				<?php }?>
			</select>
		</p>
		<p>
			<input id="<?php echo esc_attr($this->get_field_id('css'))?>" name="<?php echo esc_attr($this->get_field_name('css'))?>" type="checkbox" <?php checked(!empty($instance['css']))?>>
			<label for="<?php echo esc_attr($this->get_field_id('css'))?>"><?php _e('Style with CSS?', '12-step-meeting-list')?></label> 
		</p>
		<?php 
	}

	//sanitize widget form values as they are saved
	public function update($new_instance, $old_instance) {
		$instance = array();
		$instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
		$instance['count'] = (!empty($new_instance['count'])) ? intval($new_instance['count']) : 5;
		$instance['css'] = !empty($new_instance['css']);
		return $instance;
	}
}

//app store links widget
class TSML_Widget_App_Store extends WP_Widget {

	//constructor
	public function __construct() {
		parent::__construct('tsml_widget_app_store', 
			__('App Store', '12-step-meeting-list'),
			array(
				'description' => __('Display links to the Meeting Guide app in the Apple and Android app stores.', '12-step-meeting-list'),
			)
		);
	}

	//backend form
	public function form($instance) {
		$title = empty($instance['title']) ? '' : $instance['title'];
		?>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title'))?>"><?php _e('Title (optional):', '12-step-meeting-list')?></label> 
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title'))?>" name="<?php echo esc_attr($this->get_field_name('title'))?>" type="text" value="<?php echo esc_attr($title)?>">
		</p>
		<p>
			<input id="<?php echo esc_attr($this->get_field_id('css'))?>" name="<?php echo esc_attr($this->get_field_name('css'))?>" type="checkbox" <?php checked(!empty($instance['css']))?>>
			<label for="<?php echo esc_attr($this->get_field_id('css'))?>"><?php _e('Style with CSS?', '12-step-meeting-list')?></label> 
		</p>
		<?php 
	}

	//sanitize widget form values as they are saved
	public function update($new_instance, $old_instance) {
		$instance = array();
		$instance['title'] = empty($new_instance['title']) ? '' : strip_tags($new_instance['title']);
		$instance['css'] = !empty($new_instance['css']);
		return $instance;
	}

	//front-end display of widget
	public function widget($args, $instance) {
		if (empty($instance['title'])) $instance['title'] = '';
		
		//don't know how to set this properly
		$args['before_widget'] = str_replace(' class="', ' class="tsml-widget-app-store ', $args['before_widget']);
		
		echo $args['before_widget'];
		if (!empty($instance['title'])) {
			echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
		}
		
		if (!empty($instance['css'])) {
			echo '<style type="text/css">
			.tsml-widget-app-store {
				background-color: transparent;
				padding: 0;
			}
			.tsml-widget-app-store nav {
				overflow: auto;
				padding: 0;
				margin: 0 -7.5px;
			}
			.tsml-widget-app-store a {
				display: inline-block;
				width: 50%;
				box-sizing: border-box;
				padding: 0 7.5px;
				float: left;
			}
			.tsml-widget-app-store img {
				width: 100%;
				height: auto;
			}
			#tsml .meetings-widgets h3 {
				margin: 0 0 15px;
				border-bottom: 1px solid #ddd;
				padding: 0 0 10px;
				text-align: center;
			}
			#tsml .meetings-widgets-top .tsml-widget-app-store {
				margin: 0 0 15px;
			}
			#tsml .meetings-widgets-bottom .tsml-widget-app-store {
				margin: 30px 0;
			}
			</style>';
		}

		echo '
			<nav>
				<a href="https://itunes.apple.com/us/app/meeting-guide/id1042822181" target="_blank">
					<img src="' . plugins_url('assets/img/apple.svg', __DIR__) . '" alt="App Store" width="113.13" height="38.2">
				</a>
				<a href="https://play.google.com/store/apps/details?id=org.meetingguide.app" target="_blank">
					<img src="' . plugins_url('assets/img/google.svg', __DIR__) . '" alt="Google Play" width="113.13" height="38.2">
				</a>
			</nav>
		';
			
		echo $args['after_widget'];
	}

}

//closest meetings widget
class TSML_Widget_Closest extends WP_Widget {

	//constructor
	public function __construct() {
		parent::__construct('tsml_widget_closest', __('Closest Meetings', '12-step-meeting-list'),
			array(
				'description' => __('Display a table of closest meetings.', '12-step-meeting-list'),
			)
		);
	}
	

    
	//front-end display of widget 
	public function widget($args, $instance) {
		//$table = display_upcoming_meetings($instance);
	    $table = do_shortcode('tsml_closest');
		if (empty($table)) return do_shortcode('tsml_closest');
		if (empty($instance['title'])) $instance['title'] = '';
		if (!empty($instance['css'])) {
			echo '<style type="text/css">
			    #dataxhr th.time, #dataxhr th..name, #dataxhr th..distance { background-color: #fff; }
				.tsml-widget-closest {
					background-color: transparent;
					padding: 0;
				}
				.tsml-widget-closest table thead { display: none; }
				.tsml-widget-closest table#dataxhr {
					border: 0;
					max-width: 262px;
					margin-bottom: 13px;
				}
				.tsml-widget-closest table tbody tr { 
					display: block;
					padding: 6px 6px 6px 80px;
					position: relative;
					border-bottom: 1px solid rgba(0, 0, 0, 0.1);
				}
				.tsml-widget-closest table tbody td { 
					display: block;
					border: 0;
				}
				.tsml-widget-closest table tbody td a { 
					font-weight: 700;
				}
				.tsml-widget-closest table tbody td a small { 
					color: #999;
					margin-left: 4px;
				}
				.tsml-widget-closest table tbody td a small:before { 
					content: " / ";
					margin-right: 3px;
				}
				.tsml-widget-closest table tbody td.time { 
					position: absolute;
					left: 0;
				}
				.tsml-widget-closest p a {
					font-size: 15px;
					font-weight: 700;
				} 
				.widgets-meetings-top .tsml-widget-closest {
					margin: 0 0 15px;
				}
			</style>'; 
		}
		
		//don't know how to set this properly
		$args['before_widget'] = str_replace(' class="', ' class="tsml-widget-closest ', $args['before_widget']);
		
		echo $args['before_widget'];
		if (!empty($instance['title'])) {
			echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
		}
		//echo $table;
		$link = get_post_type_archive_link('tsml_meeting');
		$link .= ((strpos($link, '?') === false) ? '?' : '&') . 'tsml-mode=me';
		$link = "/meetings?tsml-mode=me";
		echo '<select id="geolocate" onchange="closestmeetings();"><option value="today">today</option><option value="1">Monday</option><option value="2">Tuesday</option><option value="3">Wednesday</option><option value="4">Thursday</option><option value="5">Friday</option><option value="6">Saturday</option><option value="0">Sunday</option></select><table class="tsml_closestmeetings table table-striped" id="dataxhr"></table><p style="margin: 20px auto; width: 100%; text-align: center;" id="closest"><a href="/meetings/?tsml-day=any&tsml-distance=5&tsml-mode=me&tsml-view=map" style="margin: 0 auto 36px auto;">all meetings near me</a></p><br><br>';
		echo $args['after_widget'];
		
	    }

	//backend form
	public function form($instance) {
		$title = !empty($instance['title']) ? $instance['title'] : __('Closest Meetings', '12-step-meeting-list');
		$count = !empty($instance['count']) ? $instance['count'] : 4;
		?>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title'))?>"><?php _e('Title:', '12-step-meeting-list')?></label> 
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title'))?>" name="<?php echo esc_attr($this->get_field_name('title'))?>" type="text" value="<?php echo esc_attr($title)?>">
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('count'))?>"><?php _e('Show:', '12-step-meeting-list')?></label> 
			<select class="widefat" id="<?php echo esc_attr($this->get_field_id('title'))?>" name="<?php echo esc_attr($this->get_field_name('count'))?>">
				<?php for ($i = 4; $i < 5; $i++) {?>
					<option value="<?php echo $i?>"<?php selected($i, esc_attr($count))?>><?php echo $i?></option>
				<?php }?>
			</select>
		</p>
		<p>
			<input id="<?php echo esc_attr($this->get_field_id('css'))?>" name="<?php echo esc_attr($this->get_field_name('css'))?>" type="checkbox" <?php checked(!empty($instance['css']))?>>
			<label for="<?php echo esc_attr($this->get_field_id('css'))?>"><?php _e('Style with CSS?', '12-step-meeting-list')?></label> 
		</p>
		<?php 
	}

	//sanitize widget form values as they are saved
	public function update($new_instance, $old_instance) {
		$instance = array();
		$instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : ''; 
		$instance['count'] = (!empty($new_instance['count'])) ? intval($new_instance['count']) : 5;
		$instance['css'] = !empty($new_instance['css']);
		return $instance;
	}
}

add_action( 'wp_enqueue_scripts', 'closest_enqueue_scripts' );
function closest_enqueue_scripts() {
	wp_enqueue_script( 'closestmeetings', '/wp-content/plugins/12-step-meeting-list/assets/js/closestmeetings.min.js', array('jquery'), '1.0', true );
	wp_localize_script( 'closestmeetings', 'displayclosestmeetings', array(
		'ajax_url' => admin_url( 'admin-ajax.php' )
	));
}

// register widgets
function tsml_widget() {
    register_widget('TSML_Widget_Upcoming');
    register_widget('TSML_Widget_App_Store');
    register_widget('TSML_Widget_Closest');
}
add_action('widgets_init', 'tsml_widget');
