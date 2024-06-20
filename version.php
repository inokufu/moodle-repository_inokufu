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
 * @package    repository
 * @subpackage inokufu
 * @author     Inokufu, 2023
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$plugin->version = 2024061700;
$plugin->requires = 2022112802.00; // 4.1.2 (Build: 20230313)
$plugin->component = 'repository_inokufu';
$plugin->maturity = MATURITY_STABLE;
$plugin->release = '1.1.0';
$plugin->supported = [410, 420];

$plugin->dependencies = [
    'local_inokufu' => 2024061700  // Needs local services plugin to be useful
];