<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    <form method="post" action="<?php echo esc_html(admin_url('admin-post.php')); ?>">
        <div id="container">
            <h2>Feed</h2>
            <div class="intro">
            <p>This plugin allows you to generate posts through a JSON feed.
                Note that the JSON stream must have the same format as below and the datetime must respect RFC822.</p>
            </div>
            <div class="code">
                <code>
                    <pre>
{
   "items": [
      {
         "pubdate": "Thu, 21 Dec 2000 16:01:07 +0000",
         "description": "Your content (html is ok)1",
         "title": "Title1"
      },
      {
         "pubdate": "Thu, 21 Dec 2000 16:01:07 +0000",
         "description": "Your content (html is ok)2",
         "title": "Title2"
      }
   ]
}
                        </pre>
                </code>
            </div>
            <div class="options">
                <p>
                    <label>What feed would you like to generate as wp posts?</label>
                    <br/>
                    <input type="text" name="FluxToPosts_feed" style="width:100%;"
                           value="<?php
                            $values = $this->deserializer->FeedToPosts_getValue('FeedToPosts_option_key');
                            if (isset($values['feed'])) {
                                echo esc_attr($values['feed']);
                            }
                            ?>"
                    />
                </p>
                <p>
                    <label>Author</label>
                    <select id="FluxToPosts_user" name="FluxToPosts_user">
                        <?php

                        foreach (get_users() as $user) {
                            echo '<option value=' . esc_html($user->ID) . '>' . esc_html($user->user_nicename) . '</option>';
                        }
                        ?>
                    </select>
                </p>
                <p>
                    <label>Post status</label>
                    <select id="FluxToPosts_status" name="FluxToPosts_status">
                        <option value="publish">Publish</option>
                        <option value="future">Future</option>
                        <option value="draft">Draft</option>
                        <option value="pending">Pending</option>
                        <option value="private">Private</option>
                        <option value="trash">Trash</option>
                    </select>
                </p>
                <p>
                    <label>Category</label>
                    <select id="FluxToPosts_category" name="FluxToPosts_category">
                        <?php

                        foreach (get_categories(array('hide_empty' => 0)) as $category) {
                            echo '<option value=' . esc_html($category->cat_ID) . '>' . esc_html($category->name) . '</option>';
                        }
                        ?>
                    </select>
                </p>
            </div>
            <?php
            wp_nonce_field('FeedToPosts_saveFeed', 'FeedToPosts_nonce');
            submit_button('Generate posts', 'primary', 'FeedToPosts_submit');
            ?>
    </form>

    <div class="last-import">
        <?php

        $args = ['numberposts' => 5, 'order' => 'DESC'];
        $recent_posts = wp_get_recent_posts($args);

        if (!empty($recent_posts)) {
            echo "<h2>Last posts</h2>";
            foreach ($recent_posts as $recent_post) {
                $link = get_permalink($recent_post["ID"]);
                $title = $recent_post['post_title'];
                $date = $recent_post['post_date'];
                $authorID = $recent_post['post_author'];
                foreach (get_users() as $user) {
                    if ($authorID == $user->ID) {
                        $author = $user->user_nicename;
                        $authorUrl = site_url() . '?author=' . $user->ID;
                    }
                }
                echo "<p><a href='$link' >" . $title . "</a> at " . $date . " by <a href='$authorUrl'>" . $author . "</a> with " . $recent_post['post_status'] . " status</p>";
            }
        } else {
            echo '<p>No posts</p>';
        }
        ?>
    </div>
</div>