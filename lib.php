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
 * This file contains the moodle hooks for the assign module.
 *
 * It delegates most functions to the assignment class.
 *
 * @package   mod_assign
 * @copyright 2012 NetSpot {@link http://www.netspot.com.au}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();
function local_quizhelp_extend_navigation_course($navigation, $course, $context) {
    $url = new moodle_url('/local/quizhelp/index.php', array('id' => $course->id));
    $node = navigation_node::create(
        'Quiz Help',
        $url,
        navigation_node::NODETYPE_LEAF,
        'local_quizhelp',
        'local_quizhelp',
        new pix_icon('icon', '', 'local_quizhelp')
    );
    $navigation->add_node($node);
}