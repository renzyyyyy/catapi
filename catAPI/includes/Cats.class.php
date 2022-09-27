<?php

/**
 * Cats
 * @author Regina
 */
class Cats
{
    private $api_key;
    private const api_url = 'https://api.thecatapi.com/v1';
    private const RESULTS_PER_PAGE = 8;

    function __construct()
    {
        $this->api_key = getenv_docker('CATAPI_KEY', 'default-key');
    }

    /**
     * Get list of cat breeds from API
     */
    function get_breeds()
    {
        return $this->call_api(self::api_url . '/breeds');
    }

    /**
     * Get cat images and metadata based on 
     * @param breed breed_id
     * @param page starts with 0
     */
    function get_cats($breed, $page)
    {
        if (!empty($breed)) {
            $args = array(
                'limit' => self::RESULTS_PER_PAGE,
                'order' => 'ASC',
                'breed_id' => $breed,
            );

            if (!empty($page)) {
                $args['page'] = $page;
            }
            $url = add_query_arg($args, self::api_url . '/images/search');
            return $this->call_api($url);
        }

        return false;
    }

    /**
     * Get single cat metadata
     * @param id single cat ID 
     */
    function get_cat($id)
    {
        if (!empty($id)) {

            $url = self::api_url . '/images/' . $id;
            return $this->call_api($url);
        }
    }
    /**
     * Uses HTTP API: WP_Http_Curl class
     */
    private function call_api($url)
    {
        $curl = new Wp_Http_Curl();
        $args =  array(
            'method'      => 'GET',
            'timeout'     => 20,
            'redirection' => 5,
            'headers'     => array('x-api-key' => $this->api_key),
        );

        $url = strip_tags(stripslashes(filter_var($url, FILTER_VALIDATE_URL)));
        $result = $curl->request($url, $args);

        if (is_wp_error($result)) {
            $msg = $result->get_error_message();
            return false;
        }
        $pagination = array(
            'total' => $result['headers']['pagination-count'],
            'page' => $result['headers']['pagination-page'],
            'limit' => $result['headers']['pagination-limit'],
        );
        $pagination['current'] = ($pagination['page'] + 1) * $pagination['limit'];
        return array($pagination, json_decode($result['body'], true));
    }
}
