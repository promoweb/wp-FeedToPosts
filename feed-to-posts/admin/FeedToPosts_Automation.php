<?php

class FeedToPosts_Automation
{
    public function FeedToPosts_automationInit()
    {
        add_action('rest_api_init', function () {
            register_rest_route('FeedToPosts/v1/', '/feedtoposts', array(
                'methods' => 'GET',
                'callback' => array($this, 'FeedToPosts_onCall'),
            ));
        });
    }

    public function FeedToPosts_onCall()
    {
        {
        if (empty(get_option('FeedToPosts_feed'))) {
            return new WP_Error('400', 'Please provide a JSON feed');
        } else {
            // get feed and json decode
            $FeedToPosts_getFeed = @file_get_contents(get_option('FeedToPosts_feed'));
            if ($FeedToPosts_getFeed === false) {
                return new WP_Error('400', 'Can\'t access to this feed');
            } else {
                $FeedToPosts_json = json_decode($FeedToPosts_getFeed, true);
                $FeedToPosts_postIt = [];
                // parse posts items
                foreach ($FeedToPosts_json['items'] as $FeedToPosts_item) {
                    if (!array_key_exists('title', $FeedToPosts_item) || !array_key_exists('pubdate', $FeedToPosts_item) || !array_key_exists('description', $FeedToPosts_item)) {
                        return new WP_Error('400', 'Invalid JSON');
                    }
                    // convert date D-d-M H:i:s O to Y-m-d H:i:s
                    $FeedToPosts_dateFeed = $FeedToPosts_item['pubdate'];
                    $FeedToPosts_convertedDate = date("Y-m-d H:i:s", strtotime($FeedToPosts_dateFeed));

                    $FeedToPosts_postIt['post_title'] = $FeedToPosts_item['title'];
                    $FeedToPosts_postIt['post_content'] = $FeedToPosts_item['description'];
                    $FeedToPosts_postIt['post_status'] = get_option('FeedToPosts_status');
                    $FeedToPosts_postIt['post_author'] = intval(get_option('FeedToPosts_user'));
                    $FeedToPosts_postIt['post_date_gmt'] = $FeedToPosts_convertedDate;
                    $FeedToPosts_postIt['post_category'] = [intval(get_option('FeedToPosts_category'))];

                    if (post_exists($FeedToPosts_item['title'])) {
                        return new WP_Error('400', 'Posts already exists (Empty the trash if already delete it) !');
                    } else {
                        wp_insert_post($FeedToPosts_postIt);
                    }
                }
                $args = ['numberposts' => 5, 'order' => 'DESC'];
                $recent_posts = wp_get_recent_posts($args);
                return wp_send_json_success(['last_import' => $recent_posts], '201');
            }
        }
        }
    }
}
