<?php

/**
 * Class FeedToPosts_Serializer
 */
class FeedToPosts_Serializer
{
    public function FeedToPosts_init()
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
        if (null !== wp_unslash($_POST['FluxToPosts_feed']) && null !== wp_unslash($_POST['FluxToPosts_user']) && null !== wp_unslash($_POST['FluxToPosts_status']) && null !== wp_unslash($_POST['FluxToPosts_category'])) {
            $data = [
            'feed' => sanitize_text_field($_POST['FluxToPosts_feed']),
            'user' => sanitize_text_field($_POST['FluxToPosts_user']),
            'status' => sanitize_text_field($_POST['FluxToPosts_status']),
            'category' => sanitize_text_field($_POST['FluxToPosts_category'])
            ];

            update_option('FeedToPosts_option_key', $data, 'yes');
        }
        $this->FeedToPosts_redirect();
    }

    /**
     * Determines if the nonce variable associated with the options page is set
     * and is valid.
     *
     * @access private
     *
     * @return boolean False if the field isn't set or the nonce value is invalid otherwise, true.
     */
    public function FeedToPosts_hasValidNonce()
    {

        // If the field isn't even in the $_POST, then it's invalid.
        if (! isset($_POST['FeedToPosts_nonce']) && ! isset($_POST['FluxToPosts_user']) && ! isset($_POST['FluxToPosts_status']) && ! isset($_POST['FluxToPosts_category'])) { // Input var okay.
            return false;
        }

        $fields  = [
            wp_unslash($_POST['FeedToPosts_nonce']),
            wp_unslash($_POST['FluxToPosts_user']),
            wp_unslash($_POST['FluxToPosts_status']),
            wp_unslash($_POST['FluxToPosts_category']),
        ];
        $action = 'FeedToPosts_saveFeed';
        foreach ($fields as $field) {
            return wp_verify_nonce($field, $action);
        }
    }

    /**
     * Redirect to the page from which we came
     */
    public function FeedToPosts_redirect()
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
