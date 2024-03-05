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
 * Strings for component 'repository_inokufu', language 'en', branch 'MOODLE_40_STABLE'
 *
 * @package   repository_inokufu
 * @author    Inokufu, 2023
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
require_once(__DIR__ . '/../../constants.php');

$string[CONFIG_PLUGIN] = 'Configuration for Inokufu Repository';
$string[PLUGIN_NAME] = 'Inokufu Search';
$string[PLUGIN_NAME_HELP] = 'Better data for better learning! Find the good resource at the good moment with Inokufu Search repository plugin.';
$string[PLUGIN_VIEW] = 'View the Inokufu Search repository';

$string[CONST_QUERY] = 'Search for a Learning Object (in French by default):<br>Try typing something like "How to become a carpenter"';
$string[CONST_TYPE] = 'Type:';
$string[CONST_PROVIDER] = 'Provider:';
$string[CONST_LANG] = 'Language:';
$string[CONST_NOT_FREE] = 'Include chargeable content:';
$string[CONST_API_KEY] = 'API Key';
$string[CONST_KEY_INFORMATION] = 'Get your own <a href="https://gateway.inokufu.com/">API Key here</a>.';

$string[CONST_OR] = 'OR';
$string[CONST_DEFAULT_LANG] = '- Default lang -';
$string[CONST_ALL] = '- All -';

$string[CACHE_API_LO_NAME] = 'Cache for LO API';

$string[ERROR_TOO_MANY_REQUESTS] = 'You have reached the maximum query limit per minute for our service. Please try again later or contact the administrator if you want to change your current quota plan.';
$string[ERROR_QUERY_FAILED] = 'An error has occurred during your API request. If the problem persists, please contact your administrator.';
