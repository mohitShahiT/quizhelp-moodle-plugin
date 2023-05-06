<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Form for taking which quiz to add resoruces to or to show resources.
 *
 * @package     local_quizhelp
 * @copyright   2023
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use function PHPSTORM_META\type;

require_once('../../config.php');
require_once('edit_form.php');
$courseid = required_param('id', PARAM_INT);
$course = $DB->get_record('course', ['id' => $courseid]);
$context = context_course::instance($course->id);

require_login($course);



if (!$course) {
    throw new \moodle_exception('invalidcourseid');
}

$PAGE->set_title($course->fullname . ': ' . get_string('pluginname', 'local_quizhelp'));
$PAGE->set_heading($course->fullname . ': ' . get_string('pluginname', 'local_quizhelp'));
$PAGE->set_pagelayout('incourse');
$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/quizhelp/edit.php', ['id' => $courseid]));
$PAGE->set_pagetype('course-view-' . $course->format);  // To get the blocks exactly like the course.


$quizes = get_coursemodules_in_course('quiz', $courseid);

$quizOptions = array();
$quizOptionIndex = 0;
foreach($quizes as $quiz){
    if(!$quiz->deletioninprogress){
        $quiz->optionIndex = $quizOptionIndex++;
        array_push($quizOptions, $quiz->name);
    }
}
$roles = get_user_roles($context, $USER->id, true);
$role = key($roles);
$rolename = $roles[$role]->shortname;
if($rolename == "editingteacher" || $rolename == "teacher" ){
    $form = new quiz_selection_form(null, array('quiz_options'=>$quizOptions, 'canEdit'=>true, 'id'=>$courseid));
}
else{
    $form = new quiz_selection_form(null, array('quiz_options'=>$quizOptions, 'canEdit'=>false, 'id'=>$courseid));
}



if($form->is_cancelled()){
    redirect(new moodle_url('/course/view.php', array('id' => $courseid)));
}
else if($formData = $form->get_data()){
    // var_dump($courseid);
    foreach($quizes as $quiz){
        if($quiz->optionIndex == (int)$formData->quiz){
            redirect(new moodle_url('/local/quizhelp/view.php', array('id'=>$courseid, 'quizid'=>$quiz->id)));
        }
    }
    
}

echo $OUTPUT->header();


if(!empty($quizOptions)){
    $form->display();
}
else{
    core\notification::add('No quiz in the course.', core\output\notification::NOTIFY_WARNING);
}

echo $OUTPUT->footer();


