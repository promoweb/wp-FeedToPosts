<?php


class FeedToPosts_Page
{
    /**
     * FeedToPosts_Page constructor.
     * @param $deserializer
     */
    public function __construct($deserializer)
    {
        $this->deserializer = $deserializer;
    }

    /**
     * render settings view
     */
    public function render()
    {
        include_once('views/settings.php');
    }
}
