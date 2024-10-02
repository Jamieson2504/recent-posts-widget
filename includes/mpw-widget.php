<?php 

// Define the custom widget class
class Latest_Posts_Widget extends WP_Widget {
    
    // Constructor: Define the widget's name, description, etc.
    public function __construct() {
        parent::__construct(
            'latest_posts_widget', // Base ID
            __('Latest Posts Widget Hey', 'text_domain'), // Name
            array('description' => __('A widget that displays the latest posts', 'text_domain')) // Arguments
        );
    }

    // Output the content of the widget on the front-end
    public function widget($args, $instance) {
        echo $args['before_widget'];

        // Widget title, if set
        if (!empty($instance['title'])) {
            // Make sure the title can be localized
            $title = apply_filters('widget_title', $instance['title']);
            echo $args['before_title'] .  esc_html__( $title, 'my-posts-widget' ) . $args['after_title'];
        }

        // Default Taxonomy Args (Can be changed to a custom taxonomy)
        $cat_args =  array(
            'taxonomy'   => 'category',
            'hide_empty' => true,
        );
        // Allow developers to modify the arguments
        $cat_args = apply_filters('my_plugin_cat_args', $cat_args);

        // Get categories
        $cat_terms = get_terms($cat_args);
        
        // Default Tag Args (Can be changed to a custom tag)
        $tag_args =  array(
            'taxonomy'   => 'post_tag',
            'hide_empty' => true,
        );
        
        // Allow developers to modify the arguments
        $tag_args = apply_filters('my_plugin_tag_args', $tag_args);
        
        // Get tags
        $tag_terms = get_terms($tag_args);
        

        // Set default arguments for WP_Query
        $query_args = array(
            'post_type'      => 'post',
            'posts_per_page' => 3,
            'post_status'    => 'publish',
            'orderby' => 'date',
            'order' => 'DESC',
            'post__not_in' => array(get_the_ID()),
        );

        // Allow developers to modify the WP_Query arguments
        $query_args = apply_filters('my_plugin_query_args', $query_args);

        $post_type_selected = $query_args['post_type'];

        // Run WP_Query with the filtered arguments
        $query = new WP_Query($query_args);

        if ($query->have_posts()) {
            // Taxonomy filter
            if($cat_terms) {
                echo '<div class="filter filter--cat"><div class="filter__inner" id="cat-filter">';
                foreach($cat_terms as $cat) {
                    echo '<div class="filter__item">';
                    echo '<input type="checkbox" id="' . $cat->slug . '" name="cat" value="' . $cat->name . '">';
                    echo '<label for="' . $cat->slug . '">' . $cat->name . '</label>';
                    echo '</div>';
                }
                 echo '</div></div>';
            }

            // Tag filter
            if($tag_terms) {
                echo '<div class="filter filter--tag"><div class="filter__inner" id="tag-filter">';
                foreach($tag_terms as $cat) {
                    echo '<div class="filter__item">';
                    echo '<input type="checkbox" id="' . $cat->slug . '" name="tag" value="' . $cat->name . '">';
                    echo '<label for="' . $cat->slug . '">' . $cat->name . '</label>';
                    echo '</div>';
                }
                 echo '</div></div>';
            }

            // List of found posts
            echo '<ul data-post="' . get_the_ID() . '" data-posttype="' . $post_type_selected . '">';
            while ($query->have_posts()) {
                $query->the_post();
                echo '<li><a class="widget-post-title" href="' . get_permalink() . '">' . get_the_title() . '</a><p class="widget-post-excerpt">' . get_the_excerpt() . '</p></li>';
            }
            echo '</ul>';
        } else {
            echo 'No posts found.';
        }


        // Reset post data
        wp_reset_postdata();

        echo $args['after_widget'];
    }

    // Back-end widget form (for title input)
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Recent Posts', 'text_domain');
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title:', 'text_domain'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <?php
    }

    // Save widget form values when updated
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        return $instance;
    }
}

// Register the widget
function register_latest_posts_widget() {
    register_widget('Latest_Posts_Widget');
}
add_action('widgets_init', 'register_latest_posts_widget');

// Our AJAX functions
require plugin_dir_path( __FILE__ ) . '/mpw-ajax.php';