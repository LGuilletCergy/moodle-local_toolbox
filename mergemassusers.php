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
 * File : mergemassusers.php
 * Merge lots of users with similar idnumbers
 */

require_once('../../config.php');
require_once("$CFG->dirroot/admin/tool/mergeusers/lib/mergeusertool.php");

require_login();
require_capability('tool/mergeusers:mergeusers', context_system::instance());

if (is_siteadmin()) {



    $sqllistidnumbersobject = "SELECT DISTINCT idnumber FROM {user} WHERE idnumber NOT LIKE '' AND auth LIKE 'cas' "
            . "AND suspended = 0 AND deleted = 0";
    $listidnumbersobject = $DB->get_records_sql($sqllistidnumbersobject);

    foreach ($listidnumbersobject as $idnumberobject) {

        $emailstudent = "@etu.u-cergy.fr";

        $sqlcountlistusers = "SELECT COUNT(id) FROM {user} WHERE idnumber LIKE $idnumberobject->idnumber "
                . "AND auth LIKE 'cas' AND suspended = 0 AND deleted = 0 AND email LIKE '%$emailstudent%'";

        if ($DB->count_records_sql($sqlcountlistusers) > 1) {

            // A ce stade, j'ai un étudiant et j'explore ces multiples comptes.

            $sqllistusers = "SELECT * FROM {user} WHERE idnumber LIKE $idnumberobject->idnumber "
                . "AND auth LIKE 'cas' AND suspended = 0 AND deleted = 0 AND email LIKE '%$emailstudent%'";

            $listusers = $DB->get_records_sql($sqllistusers);

            foreach ($listusers as $user) {

                if (substr($user, 0, 2) == "e-") {

                    $touser = $user;
                } else {

                    $fromuser = $user;
                }
            }

            $mut = new MergeUserTool();
            $mut->merge($touser->id, $fromuser->id);
            $SESSION->mut = NULL;

            print_object($touser);
            print_object($fromuser);

            exit;
        }
    }
}