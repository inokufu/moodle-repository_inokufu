<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Inokufu Client Class
 * Communication between Moodle and Search API
 *
 * @author  Inokufu, 2023
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 */

define('INOKUFU_GATEWAY_URL', 'https://api.gateway.inokufu.com');
define('INOKUFU_LO_ENDPOINT', INOKUFU_GATEWAY_URL . '/learningobject/v3/');

class inokufu {

    private $default_lang = 'fr';
    private $supported_langs;
    private $used_lang;
    private $api_key;

    public function __construct($api_key, $used_lang) {
        $this->api_key = $api_key;
        $this->supported_langs = array($this->default_lang, 'en');

        if (in_array($used_lang, $this->supported_langs, true)) {
            $this->used_lang = $used_lang;
        } else {
            $this->used_lang = $this->default_lang;
        }
    }

    /** Utils */

    /**
     * API Call to a given endpoint
     *
     * @param string $base_endpoint Endpoint, without query arguments
     * @param string $get_args Query arguments, starting with a '?'
     * @param array $headers Array of headers
     * @return array Results found
     */
    private function api_call($base_endpoint, $get_args = '', $headers = array()) {
        $curl = curl_init();
        $headers[] = 'x-api-key: ' . $this->api_key;
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_URL, $base_endpoint . $get_args);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $json = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($httpcode == 200 && isset($json) && !empty($json)) {
            $results = json_decode($json);
            return $results;
        } else {
            throw new Exception('Inokufu API LO error', $httpcode);
        }
    }

    /**
     * Format query args based on an associative array (key/value)
     *
     * @param array $args
     * @return string Formatted query args
     */
    public function format_get_args($args) {
        $formatted_args = array();
        if (!empty($args)) {
            foreach ($args as $key => $value) {
                if ($value != '') {
                    array_push($formatted_args, $key . '=' . $value);
                }
            }
        }
        $ret = '?' . join('&', $formatted_args);
        return $ret;
    }

    /**
     * Get a property with given dotted key
     *
     * @param mixed $object Base object
     * @param string $dotted_path Dotted path
     * @return mixed The value obtained if found, else null
     */
    private function get_dotted_property($object, $dotted_path) {
        $parts = explode('.', $dotted_path);
        $value = $object;
        foreach ($parts as $part) {
            if (is_object($value) && property_exists($value, $part)) {
                $value = $value->{$part};
            } else {
                return null;
            }
        }
        return $value;
    }

    /**
     * Create a 'select' item (an 'option' tag) for Moodle
     *
     * @param mixed $value Base value with data
     * @param string $value_key Dotted key to the value of the option
     * @param string $dotted_label_key Dotted key to the label of the option
     * @return array Array representation of the 'option' created
     */
    private function add_select_item($value, $value_key, $dotted_label_key) {
        return array(
            'value' => $this->get_dotted_property($value, $value_key),
            'label' => $this->get_dotted_property($value, $dotted_label_key),
        );
    }

    /**
     * Format a JSON to be displayed as a 'select' tag with 'options' on Moodle, with one empty field at the beginning
     *
     * @param mixed $json Base object with data
     * @param string $value_key Dotted key to the value of the option
     * @param string $dotted_label_key Dotted key to the label of the option
     * @return array Array representation of the 'select' created
     */
    public function format_json_for_list_display($json, $value_key, $dotted_label_key) {
        $ret = array();
        $ret[] = $this->add_select_item('', '', '');
        foreach ($json as $key => $value) {
            $ret[] = $this->add_select_item($value, $value_key, $dotted_label_key);
        }
        return $ret;
    }

    /** Calls */

    /**
     * Format LO type list as a 'select'
     */
    public function get_types_formatted() {
        $json = $this->api_call(INOKUFU_LO_ENDPOINT . 'type');
        return $this->format_json_for_list_display($json, 'id_type', 'name.' . $this->used_lang);
    }

    /**
     * Format LO provider list as a 'select'
     */
    public function get_providers_formatted() {
        $json = $this->api_call(INOKUFU_LO_ENDPOINT . 'provider');
        return $this->format_json_for_list_display($json, 'id_provider', 'name');
    }

    /**
     * Format LO lang list as a 'select'
     */
    public function get_langs_formatted() {
        $json = $this->api_call(INOKUFU_LO_ENDPOINT . 'lang');
        return $this->format_json_for_list_display($json, 'id_lang', 'name.' . $this->used_lang);
    }

    /**
     * Get a list of LOs based on the filters provided,
     * the following arguments are passed as query arguments as specified
     * in the API LO V3 documentation
     *
     * @param string $page - Page number
     * @param string $query - Search text query
     * @param string $lang - Language to search LOs in
     * @param string $provider - Provider of the LOs
     * @param string $type - Type of the LOs
     * @param bool $is_chargeable - Opposite of 'is_free'
     * @return array Array of LOs found
     */
    public function get_learning_objects(
        $page, $query, $lang, $provider, $type, $is_chargeable
    ) {
        $query = urlencode($query); // special char support
        $query_args_raw = array(
            'query' => $query,
            'lang' => $lang,
            'provider' => $provider,
            'type' => $type,
            'limit' => 12,
            'page' => $page,
        );
        // If chargeable content not included, send 'free' = true
        if (!$is_chargeable) {
            $query_args_raw['free'] = true;
        }
        
        $results = $this->api_call(INOKUFU_LO_ENDPOINT . 'search' , $this->format_get_args($query_args_raw));
        
        $list = [];
        $timestamp = time();
        foreach ($results as $key => $value) {
            $preview_content = INOKUFU_LO_ENDPOINT . 'lo/' . $value->id . '/preview';
            $list[] = array(
                'title' => $value->title,
                'description' => $value->description,
                'thumbnail' => $value->picture->full_width,
                'thumbnail_width' => 150,
                'thumbnail_height' => 100,
                'source' => $preview_content,
                'url' => $preview_content,
                'date' => $timestamp,   // 'datemodified' & 'datecreated' have automatically the same value
                'author' => $value->provider,
            );
        }
        return $list;
    }

}