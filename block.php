<?php
function mytheme_render_release_filter_block($attributes) {
    // Get the selected filter from the URL (default to 'all' if not set)
    $filter = $_GET['release_filter'] ?? 'all';

    ob_start();
    ?>
    <form method="GET" class="release-filter-form">
        <select name="release_filter" class="release-filter-dropdown">
            <option value="all" <?php selected($filter, 'all'); ?>>All</option>
            <option value="album" <?php selected($filter, 'album'); ?>>Album</option>
            <option value="ep" <?php selected($filter, 'ep'); ?>>EP</option>
            <option value="single" <?php selected($filter, 'single'); ?>>Single</option>
        </select>
        <button type="submit">Filter</button>
    </form>
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