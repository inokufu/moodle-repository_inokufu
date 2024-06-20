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

require_once ($CFG->libdir . '/externallib.php');

class inokufu
{
    private $default_lang = 'fr';
    private $supported_langs;
    private $used_lang;

    public function __construct($used_lang)
    {
        $this->supported_langs = array($this->default_lang, 'en');

        if (in_array($used_lang, $this->supported_langs, true)) {
            $this->used_lang = $used_lang;
        } else {
            $this->used_lang = $this->default_lang;
        }
    }

    /**
     * Call Inokufu Services and retrieve tha needed API LO data
     *
     * @param string $func_name External function's name
     * @param array $params Parameters for the external function
     * @return array Retrieved data
     */
    private function call_external_api($func_name, $params = null)
    {
        $params = $params ? $params : array();
        $result = external_api::call_external_function(
            $func_name,
            $params,
            null    // To use the current context
        );

        if (isset($result['error']) && !$result['error'] && isset($result['data'])) {
            // No errors, return the data
            return $result['data'];

        } else if (isset($result['exception'])) {
            // Moodle error
            $exception_details = $result['exception'];
            $exception_msg = $exception_details->message . ' - ';
            $exception_msg .= $exception_details->debuginfo . ' - ';
            throw new Exception($exception_msg);
        } else {
            // Unsupported error
            throw new Exception('Unsupported error, please contact Inokufu\'s support.' . json_encode($result));
        }
    }

    /**
     * Get a property with given dotted key
     *
     * @param mixed $object Base object
     * @param string $dotted_path Dotted path
     * @return mixed The value obtained if found, else null
     */
    private function get_dotted_property($object, $dotted_path)
    {
        $parts = explode('.', $dotted_path);
        $value = $object;
        foreach ($parts as $part) {
            if (is_object($value) && property_exists($value, $part)) {
                $value = $value->{$part};
            } else if (is_array($value) && array_key_exists($part, $value)) {
                $value = $value[$part];
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
    private function add_select_item($value, $value_key, $dotted_label_key)
    {
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
    public function format_json_for_list_display($json, $value_key, $dotted_label_key)
    {
        $ret = array();
        $json = isset($json) && !empty($json) ? $json : array();

        $ret[] = $this->add_select_item('', '', '');
        foreach ($json as $key => $value) {
            $ret[] = $this->add_select_item($value, $value_key, $dotted_label_key);
        }
        return $ret;
    }

    /**
     * Format LO type list as a 'select'
     */
    public function get_types_formatted()
    {
        $json = self::call_external_api('local_inokufu_get_type');
        return $this->format_json_for_list_display($json, 'id_type', 'name.' . $this->used_lang);
    }

    /**
     * Format LO provider list as a 'select'
     */
    public function get_providers_formatted()
    {
        $json = self::call_external_api('local_inokufu_get_provider');
        return $this->format_json_for_list_display($json, 'id_provider', 'name');
    }

    /**
     * Format LO lang list as a 'select'
     */
    public function get_langs_formatted()
    {
        $json = self::call_external_api('local_inokufu_get_lang');
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
        $page,
        $query,
        $lang,
        $provider,
        $type,
        $is_chargeable
    ) {
        $query = urlencode($query); // special char support
        $query_args = array(
            'query' => $query,
            'lang' => $lang,
            'provider' => $provider,
            'type' => $type,
            'limit' => 12,
            'page' => $page,
        );
        // If chargeable content not included, send 'free' = true
        if (!$is_chargeable) {
            $query_args['free'] = true;
        }

        // Get data
        $results = self::call_external_api('local_inokufu_get_search', array('params' => $query_args));

        $list = [];
        $timestamp = time();
        foreach ($results as $key => $value) {
            $details_url = self::call_external_api('local_inokufu_get_host') . 'lo/' . $value['id'];
            $list[] = array(
                'title' => $value['title'],
                'description' => $value['description'],
                'thumbnail' => $value['picture']['full_width'],
                'thumbnail_width' => 150,
                'thumbnail_height' => 100,
                'source' => $details_url,
                'url' => $details_url,
                'date' => $timestamp,   // 'datemodified' & 'datecreated' have automatically the same value
                'author' => $value['provider'],
            );
        }
        return $list;
    }
}