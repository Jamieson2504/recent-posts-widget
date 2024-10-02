<?php 


// Hook for logged-in users
add_action('wp_ajax_get_custom_posts', 'get_custom_posts_callback');

// Hook for guests (not logged-in users)
add_action('wp_ajax_nopriv_get_custom_posts', 'get_custom_posts_callback');

function get_custom_posts_callback() {
    // Check if nonce is set and valid (for security)
    if ( ! isset($_POST['nonce']) || ! wp_verify_nonce($_POST['nonce'], 'ajax_nonce') ) {
        wp_send_json_error('Invalid nonce');
        return;
    }
    
    $post_type = isset($_POST['post_type']) ? sanitize_text_field($_POST['post_type']) : 'post';
    $post_not_in = isset($_POST['post__not__in']) ? sanitize_text_field($_POST['post__not__in']) : '';
    $cats = isset($_POST['cats']) ? sanitize_text_field($_POST['cats']) : '';
    $tags = isset($_POST['tags']) ? sanitize_text_field($_POST['tags']) : '';


    // Initial query
    $args = array(
        'post_type' => $post_type,
        'posts_per_page' => 3,
        'orderby' => 'date',
        'order' => 'DESC',
    );

    $tax_query = array();

    // Add category query if categories are selected
    if (!empty($cats)) {
        $tax_query[] = array(
            'taxonomy' => 'category',
            'field'    => 'name',
            'terms'    => explode(',', $cats),
            'operator' => 'IN',
        );
    }

    // Add tag query if tags are selected
    if (!empty($tags)) {
        $tax_query[] = array(
            'taxonomy' => 'post_tag',
            'field'    => 'name',
            'terms'    => explode(',', $tags),
            'operator' => 'IN',
        );
    }

    if (!empty($tax_query)) {
        $args['tax_query'] = $tax_query;
    }

    if(!empty($post_not_in)) {
        $args['post__not_in'] = array($post_not_in);
    }

    
    $query = new WP_Query($args);

    if ($query->have_posts()) {
        $postsHTML = '';

        while ($query->have_posts()) {
            $query->the_post();
            $postsHTML .= '<li><a class="widget-post-title" href="' . get_permalink() . '">' . get_the_title() . '</a><p class="widget-post-excerpt">' . wp_trim_words( get_the_excerpt(), 16 , '...' ) . '</p></li>';
        }
        wp_reset_postdata(); // Always reset post data after custom query

        echo $postsHTML;
    } else {
       echo "<p>No posts found</p>";
    }
    
    wp_die(); // Required to end the AJAX request properly
}
