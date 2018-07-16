<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    <form method="post" action="<?php echo esc_html(admin_url('admin-post.php')); ?>">
        <div id="container">
            <h2>Flux</h2>

            <div class="options">
                <p>
                    <label>What flux would you like to generate as wp posts?</label>
                    <br />
                    <input type="text" name="flux" style="width:100%;"
                           value="<?php echo esc_attr($this->deserializer->get_value('data')); ?>"
                    />
                </p>
            </div>
            <?php
            wp_nonce_field('save-flux', 'custom-flux');
            submit_button('Generate posts');
            ?>
    </form>
    <div class="last-import">
    <?php

    $args = ['numberposts' => 5];
    $recent_posts = wp_get_recent_posts($args);

    if (!empty($recent_posts)) {
        echo "<h2>Last posts imported</h2>";
        foreach ($recent_posts as $recent_post) {
            echo "<b><p>" . $recent_post['post_title'] . "</b> at " . $recent_post['post_date'] . "</p>";
        }
    } else {
        echo '<p>Nothing to import</p>';
    }
    ?>
    </div>
</div>