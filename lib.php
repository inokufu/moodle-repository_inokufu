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
 * This plugin is used to access inokufu resources
 *
 * @since Moodle 2.0
 * @package    repository_inokufu
 * @author     Inokufu, 2023
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once($CFG->dirroot . '/repository/lib.php');
require_once(__DIR__ . '/../../config.php');

require_once(__DIR__ . '/inokufu.php');
require_once(__DIR__ . '/constants.php');

/**
 * repository_inokufu class
 *
 * @since Moodle 2.0
 * @package    repository_inokufu
 * @author     Inokufu, 2023
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class repository_inokufu extends repository {

    /**
     * ID for using the Inokufu Search API.
     * @var mixed
     */
    private $api_key;

    /**
     * Inokufu client
     * @var inokufu
     */
    private $client = null;

    /**
     * Inokufu plugin constructor
     * @param int $repositoryid
     * @param object $context
     * @param array $options
     */
    public function __construct($repositoryid, $context = SYSCONTEXTID, $options = array()) {
        parent::__construct($repositoryid, $context, $options);
        global $USER;

        $this->api_key = $this->get_option(CONST_API_KEY);
        $this->client = new inokufu($this->api_key, $USER->lang);

        // Without an API Key, don't show this repo to users as its useless without it.
        if (empty($this->api_key)) {
            $this->disabled = true;
        }
    }

    /**
     * Get api_key from config table.
     *
     * @param string $config
     * @return mixed
     */
    public function get_option($config = '') {
        if ($config === CONST_API_KEY) {
            return trim(get_config('inokufu', CONST_API_KEY));
        } else {
            $options[CONST_API_KEY] = trim(get_config('inokufu', CONST_API_KEY));
        }
        return parent::get_option($config);
    }

    /**
     * Save api_key in config table.
     * @param array $options
     * @return boolean
     */
    public function set_option($options = array()) {
        if (!empty($options[CONST_API_KEY])) {
            set_config(CONST_API_KEY, trim($options[CONST_API_KEY]), 'inokufu');
        }
        unset($options[CONST_API_KEY]);
        return parent::set_option($options);
    }

    /**
     * Create a generic field according to what Moodle asks for
     *
     * @param string $type
     * @param string $id
     * @param string $name
     * @param string $label_string_name
     * @return stdClass
     */
    private function create_field($type, $id, $name, $label_string_name) {
        $field = new stdClass();
        $field->type = $type;
        $field->id = $id;
        $field->name = $name;
        $field->label = get_string($label_string_name, REPO_NAME) . '&nbsp;&nbsp;';
        return $field;
    }

    /**
     * Create a 'select' field according to what Moodle asks for
     *
     * @param string $id
     * @param string $label_string_name
     * @param string $options
     * @return stdClass
     */
    private function create_select_field($id, $label_string_name, $options) {
        $field = $this->create_field('select', $id, $id, $label_string_name);
        $field->options = $options;
        return $field;
    }

    /**
     * Add plugin settings input to Moodle form.
     * @param object $mform
     * @param string $classname
     */
    public static function type_config_form($mform, $classname = 'repository') {
        parent::type_config_form($mform, $classname);

        $api_key = get_config('inokufu', CONST_API_KEY);

        if (empty($api_key)) {
            $api_key = '';
        }

        $mform->addElement('password', CONST_API_KEY, get_string(CONST_API_KEY, REPO_NAME), array('value' => $api_key, 'size' => '40'));
        $mform->setType(CONST_API_KEY, PARAM_RAW_TRIMMED);
        $mform->addRule(CONST_API_KEY, get_string('required'), 'required', null, 'client');
        $mform->setDefault(CONST_API_KEY, 'default');

        $mform->addElement('static', null, '',  get_string(CONST_KEY_INFORMATION, REPO_NAME));
    }

    /**
     * Search form
     * @return array
     * @throws coding_exception
     */
    public function print_login() {
        try {
            $cache = cache::make(REPO_NAME, 'api_lo_cache');

            $cached_data = $cache->get(CONST_TYPE);
            if ($cached_data !== false) {
                $options_types = json_decode($cached_data);
            } else {
                $options_types = $this->client->get_types_formatted();
                $options_types[0]['label'] = get_string(CONST_ALL, REPO_NAME);
                $cache->set(CONST_TYPE, json_encode($options_types));
            }

            $cached_data = $cache->get(CONST_PROVIDER);
            if ($cached_data !== false) {
                $options_providers = json_decode($cached_data);
            } else {
                $options_providers = $this->client->get_providers_formatted();
                $options_providers[0]['label'] = get_string(CONST_ALL, REPO_NAME);
                $cache->set(CONST_PROVIDER, json_encode($options_providers));
            }

            $cached_data = $cache->get(CONST_LANG);
            if ($cached_data !== false) {
                $options_langs = json_decode($cached_data);
            } else {
                $options_langs = $this->client->get_langs_formatted();
                $options_langs[0]['label'] = get_string(CONST_DEFAULT_LANG, REPO_NAME);
                $cache->set(CONST_LANG, json_encode($options_langs));
            }

        } catch (Exception $e) {
            $this->print_api_lo_error_message($e);
        }

        // TODO: change the picture
        $logo = '<a href="https://www.inokufu.com/" target="_new">
            <img src="https://www.inokufu.com/wp-content/uploads/2019/02/logo_inokufu_vector_full-black.png" alt="Inokufu" style="display: block; margin: auto; width: 50%;">
        </a><br>';
        
        $search = $this->create_field('text', 'inokufu_search', 's', CONST_QUERY);
        $search->label = $logo . $search->label;

        $types = $this->create_select_field('inokufu_type', CONST_TYPE, $options_types);
        $providers = $this->create_select_field('inokufu_provider', CONST_PROVIDER, $options_providers);
        $providers->label = get_string(CONST_OR, REPO_NAME) . '<br><br>' . $providers->label;

        $langs = $this->create_select_field('inokufu_lang', CONST_LANG, $options_langs);
        $is_chargeable = $this->create_field('checkbox', 'inokufu_not_free', 'inokufu_not_free', CONST_NOT_FREE);

        $ret = array();
        $ret['login'] = array($search, 
            $types, $providers, $langs, $is_chargeable,
        );
        $ret['login_btn_label'] = get_string('search');
        $ret['login_btn_action'] = 'search';
        $ret['allowcaching'] = true; // Indicates that login form can be cached in filepicker.js.

        return $ret;
    }

    /**
     * Retrieve and cache a field's value to be used when scrolling down on the page 
     * (for the pagination)
     *
     * @param string $value_name
     * @param string $field_name
     * @param string $original_value If used, put a base value in the field
     * @return mixed
     */
    private function get_and_save_cached_value($value_name, $field_name, $original_value = null) {
        global $SESSION;

        // Get value as parameter
        if (!is_null($original_value)) {
            $this->{$value_name} = $original_value;
        }
        // Get value as field form value
        if (empty($this->{$value_name})) {
            $this->{$value_name} = optional_param($field_name, '', PARAM_TEXT);
        }
        $sess_value = 'inokufu_'.$this->id . '_' . $value_name;
        // If empty and cached, retrieve session stored value
        if (empty($this->{$value_name}) && optional_param('page', '', PARAM_RAW)) {
            // This is the request of another page for the last search, retrieve the cached keyword.
            if (isset($SESSION->{$sess_value})) {
                $this->{$value_name} = $SESSION->{$sess_value};
            }
            // If not empty, save it
        } else if (!empty($this->{$value_name})) {
            $SESSION->{$sess_value} = $this->{$value_name};
        } else {
            $SESSION->{$sess_value} = '';
        }
        return $this->{$value_name};
    }

    /**
     * Search function
     *
     * @param $searchtext
     * @param $page
     * @return array
     * @throws coding_exception
     * @throws moodle_exception
     */
    public function search($searchtext, $page = 0) {
        $ret = array();
        $ret['page'] = (int)$page;
        if ($ret['page'] < 1) {
            $ret['page'] = 1;
        }

        $query = $this->get_and_save_cached_value('query', 's', $searchtext);
        $lang = $this->get_and_save_cached_value('lang', 'inokufu_lang');
        $provider = $this->get_and_save_cached_value('provider', 'inokufu_provider');
        $type = $this->get_and_save_cached_value('type', 'inokufu_type');
        $is_chargeable = $this->get_and_save_cached_value('is_chargeable', 'inokufu_not_free', array_key_exists('inokufu_not_free', $_POST));

        // see get_listing documentation
        $ret['nologin'] = true;
        $ret['norefresh'] = true;
        $ret['nosearch'] = true;

        try {
            $ret['list'] = $this->client->get_learning_objects($ret['page'] -1, $query, 
                $lang, $provider, $type, $is_chargeable
            );
        } catch (Exception $e) {
            $this->print_api_lo_error_message($e);
        }

        if (!empty($ret['list'])) {
            $ret['pages'] = -1; // means we don't know exactly how many pages there are but we can always jump to the next page
        } else if ($ret['page'] > 1) {
            $ret['pages'] = $ret['page']; // no images available on this page, this is the last page
        } else {
            $ret['pages'] = 0; // no paging
        }
        return $ret;
    }

    /**
     * Centralized error printing for API LO calls.
     *
     * @param Exception $exception
     */
    private function print_api_lo_error_message($exception){
        $error_message = $exception->getMessage();
        $error_code = $exception->getCode();
        if ($error_code == 429) {
            print_error(ERROR_TOO_MANY_REQUESTS, REPO_NAME, '', null, $error_message);
        } else {
            print_error(ERROR_QUERY_FAILED, REPO_NAME, '', null, $error_message);
        }

    }

    /**
     * Names of the plugin settings
     * @return array
     */
    public static function get_type_option_names() {
        return array(CONST_API_KEY, PLUGIN_NAME);
    }

    /**
     * Bypass first 'get_listing' screen with no content displayed
     * @return boolean
     */
    public function check_login() {
        return false;
    }

    /**
     * Inokufu plugin doesn't support global search
     *
     * @return false
     */
    public function global_search() {
        return false;
    }

    /**
     * Inokufu plugin only return external links
     * @return int
     */
    public function supported_returntypes() {
        return FILE_EXTERNAL;
    }

    /**
     * Return the source information
     *
     * @param stdClass $url
     * @return string|null
     */
    public function get_file_source_info($url) {
        return $url;
    }

    /**
     * Is this repository accessing private data?
     * @return bool
     */
    public function contains_private_data() {
        return false;
    }

}