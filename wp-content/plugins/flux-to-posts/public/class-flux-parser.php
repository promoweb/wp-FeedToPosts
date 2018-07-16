<?php


class Flux_Parser
{
    /**
     * A reference to the class for retrieving our option values.
     *
     * @access private
     * @var    Deserializer
     */
    private $deserializer;

    /**
     * Initializes the class by setting a reference to the incoming deserializer.
     *
     * @param Deserializer $deserializer Retrieves a value from the database.
     */
    public function __construct($serializer, $deserializer)
    {
        $this->serializer = $serializer;
        $this->deserializer = $deserializer;
    }

    public function postFromFlux()
    {
        if ($this->serializer->has_valid_nonce()) {
            // get flux and json decode
            $json = json_decode(file_get_contents($this->deserializer->get_value('data')), true);
            // init post array
            $postIt = [];
            // parse posts items
            foreach ($json['items'] as $item) {
                if (post_exists($item['title'])) {
                    return;
                } else {
                    // convert date D-d-M H:i:s O to Y-m-d H:i:s
                    $dateFlux = $item['pubdate'];
                    $convertDate = date("Y-m-d H:i:s", strtotime($dateFlux));

                    $postIt['post_title'] = $item['title'];
                    $postIt['post_content'] = $item['description'];
                    $postIt['post_status'] = 'publish';
                    $postIt['post_author'] = 1;
                    $postIt['post_date_gmt'] = $convertDate;
                    $postIt['post_category'] = array(0);
                    // post in db
                    wp_insert_post($postIt);
                }
            }
        }
    }
}
