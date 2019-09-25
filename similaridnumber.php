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
 * Initially developped for :
 * Université de Cergy-Pontoise
 * 33, boulevard du Port
 * 95011 Cergy-Pontoise cedex
 * FRANCE
 *
 * Tool box of scripts and pages for aministrators
 *
 * @package   local_toolbox
 * @copyright 2019 Laurent Guillet <laurent.guillet@u-cergy.fr>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * File : similaridnumber.php
 * Version file
 */

require_once('../../config.php');
require_login();

if (is_siteadmin()) {

    global $DB;

    $sqllistidnumbers = "SELECT DISTINCT idnumber FROM {user} WHERE auth LIKE 'cas' AND suspended = 0 AND deleted = 0";
    $listidnumbers = $DB->get_records_sql($sqllistidnumbers);

    print_object ($listidnumbers);
}
