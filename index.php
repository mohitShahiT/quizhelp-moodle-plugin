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
 * Main interface to Moodle PHP code check
 *
 * @package    local_quizhelp
 * @copyright  2023
 * @license    ////////////////////////////http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


use core\plugininfo\local;

require_once('../../config.php');
require_once($CFG->dirroot . '/local/quizhelp/lib.php');


$courseid = required_param('id', PARAM_INT);
$course = $DB->get_record('course', array('id'=>$courseid), '*', MUST_EXIST);
$context = context_course::instance($courseid);

// Must pass login
require_login($course);



$PAGE->set_title($course->fullname . ': ' . get_string('pluginname', 'local_quizhelp'));
$PAGE->set_heading($course->fullname . ': ' . get_string('pluginname', 'local_quizhelp'));
$PAGE->set_url(new moodle_url('/local/quizhelp/index.php', ['id' => $courseid]));
$PAGE->set_context($context);
$PAGE->set_pagelayout('incourse');
$PAGE->set_pagetype('course-view-' . $course->format);  // To get the blocks exactly like the course.

redirect(new moodle_url('/local/quizhelp/edit.php', array('id'=>$courseid)));
redirect("edit.php?id=$course->id");

// core_course_category::page_setup();
// Set the competency frameworks node active in the settings navigation block.
// if ($competencyframeworksnode = $PAGE->settingsnav->find('quizhelp', navigation_node::TYPE_SETTING)) {
//     $competencyframeworksnode->make_active();
// }


echo $OUTPUT->header();

echo var_dump($course);
echo 'HELLO';

echo $OUTPUT->footer();



