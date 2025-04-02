<?php
function mytheme_render_release_filter_block($attributes) {
    $filter = $attributes['filter'] ?? 'all';

    ob_start();
    ?>
    <div class="release-filter-block">
        <p>Selected Filter: <?php echo esc_html($filter); ?></p>
    </div>
    <?php
    return ob_get_clean();
}

// Register the block type and use server-side rendering
function register_releasefilter_block() {
    register_block_type(__DIR__, [
        'render_callback' => 'mytheme_render_release_filter_block',
    ]);
}
add_action('init', 'register_releasefilter_block');
?>