<?php

class FeedToPosts_Notices
{
    const NOTICES_OPTION_KEY = 'FeedToPosts_notices';

    public static function FeedToPosts_init()
    {
        add_action('admin_notices', [__CLASS__, 'FeedToPosts_displayNotices']);
    }
    /**
    * Checks for any stored notices and outputs them.
    */
    public static function FeedToPosts_displayNotices()
    {
        $notices = self::FeedToPosts_getNotices();
        if (empty($notices)) {
            return;
        }
        foreach ($notices as $type => $messages) {
            foreach ($messages as $message) {
                printf(
                    '<div class="notice notice-%1$s is-dismissible">
    <p>%2$s</p>
</div>',
                    $type,
                    $message
                );
            }
        }
        self::FeedToPosts_updateNotices([]);
    }
    /**
    * Retrieves any stored notices.
    *
    * @return array|void
    */
    private static function FeedToPosts_getNotices()
    {
        $notices = get_option(self::NOTICES_OPTION_KEY, []);
        return $notices;
    }
    /**
    * Update the stored notices in the options table with a new array.
    *
    * @param array $notices
    */
    private static function FeedToPosts_updateNotices(array $notices)
    {
        update_option(self::NOTICES_OPTION_KEY, $notices);
    }

    /**
     * Add notice
     *
     * @param $message
     * @param string $type
     */
    private static function FeedToPosts_addNotice($message, $type = 'success')
    {
        $notices = self::FeedToPosts_getNotices();
        $notices[$type][] = $message;
        self::FeedToPosts_updateNotices($notices);
    }
    /**
    * Success notice
    *
    * @param $message
    */
    public static function FeedToPosts_addSuccess($message)
    {
        self::FeedToPosts_addNotice($message, 'success');
    }

    /**
     * Error notice
     *
     * @param $message
     */
    public static function FeedToPosts_addError($message)
    {
        self::FeedToPosts_addNotice($message, 'error');
    }
}
FeedToPosts_Notices::FeedToPosts_init();
function FeedToPosts_notices_addSuccess($message)
{
    FeedToPosts_Notices::FeedToPosts_addSuccess($message);
}
function FeedToPosts_notices_addError($message)
{
    FeedToPosts_Notices::FeedToPosts_addError($message);
}
