<?php

/**
 * Class FeedToPosts_Serializer
 */
class FeedToPosts_Controller
{
    public function FeedToPosts_controllerInit()
    {
        add_action('admin_post', array( $this, 'FeedToPosts_save' ));
    }

    /**
     * Validates the incoming nonce value, verifies the current user has
     * permission to save the value from the options page and saves the
     * option to the database.
     */
    public function FeedToPosts_save()
    {

        // First, validate the nonce and verify the user as permission to save.
        if (! ($this->FeedToPosts_hasValidNonce() && current_user_can('manage_options'))) {
            echo 'You can\'t access this page';
        }

        // If the above are valid, sanitize and save the option.
        if (wp_unslash($_POST['FeedToPosts_feed']) !== null  &&  wp_unslash($_POST['FeedToPosts_user']) !== null  && wp_unslash($_POST['FeedToPosts_status']) !==  null &&  wp_unslash($_POST['FeedToPosts_category']) !== null) {
            update_option('FeedToPosts_feed', $_POST['FeedToPosts_feed']);
            update_option('FeedToPosts_user', $_POST['FeedToPosts_user']);
            update_option('FeedToPosts_category', $_POST['FeedToPosts_category']);
            update_option('FeedToPosts_status', $_POST['FeedToPosts_status']);

            $this->FeedToPosts_Feed();
            $this->FeedToPosts_redirect();
        }
    }


    /**
     * Get data and post it
     * @return bool
     */
    public function FeedToPosts_Feed()
    {
        if (empty(get_option('FeedToPosts_feed'))) {
            FeedToPosts_notices_addError('Please provide a JSON feed');
            return false;
        } else {
            // get feed and json decode
            $FeedToPosts_getFeed = @file_get_contents(get_option('FeedToPosts_feed'));
            if ($FeedToPosts_getFeed === false) {
                FeedToPosts_notices_addError('Can\'t access to this feed');
                return false;
            } else {
                $FeedToPosts_json = json_decode($FeedToPosts_getFeed, true);
                $FeedToPosts_postIt = [];
                $countExists = 0;
                $countGenerated = 0;
                // parse posts items
                foreach ($FeedToPosts_json['items'] as $FeedToPosts_item) {
                    if (!array_key_exists('title', $FeedToPosts_item) || !array_key_exists('pubdate', $FeedToPosts_item) || !array_key_exists('description', $FeedToPosts_item)) {
                        FeedToPosts_notices_addError('Invalid JSON');
                        return false;
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
                        $countExists++;
                    } else {
                        wp_insert_post($FeedToPosts_postIt);
                        $countGenerated++;
                    }
                }
                if($countExists > 0)
                {
                    FeedToPosts_notices_addWarning($countExists . ' posts already exists (Empty the trash if already delete it) !');
                }
                if($countGenerated > 0){
                    FeedToPosts_notices_addSuccess($countGenerated . ' posts generated');
                }
            }
        }
    }

    /**
     * Determines if the nonce variable associated with the options page is set
     * and is valid.
     *
     * @access private
     *
     * @return boolean False if the field isn't set or the nonce value is invalid otherwise, true.
     */
    private function FeedToPosts_hasValidNonce()
    {

        // If the field isn't even in the $_POST, then it's invalid.
        if (! isset($_POST['FeedToPosts_nonce']) && ! isset($_POST['FeedToPosts_user']) && ! isset($_POST['FeedToPosts_status']) && ! isset($_POST['FeedToPosts_category'])) { // Input var okay.
            return false;
        }

        $field  =  wp_unslash($_POST['FeedToPosts_nonce']);
        $action = 'FeedToPosts_saveFeed';
        return wp_verify_nonce($field, $action);
    }

    /**
     * Redirect to the page from which we came
     */
    private function FeedToPosts_redirect()
    {
        // Input var okay.
        if (! isset($_POST['_wp_http_referer'])) {
            $_POST['_wp_http_referer'] = wp_login_url();
        }

        $url = sanitize_text_field(
            wp_unslash($_POST['_wp_http_referer'])
        );
        // Finally, redirect back to the admin page.
        wp_safe_redirect(urldecode($url));
        exit;
    }
}
