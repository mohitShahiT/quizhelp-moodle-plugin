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

require_once('../../config.php');
require_once('addresource_form.php');
$courseid = required_param('id', PARAM_INT);
$quesitonid = required_param('qid', PARAM_INT);
$quizid = optional_param('quizid', 0, PARAM_INT);
$course = $DB->get_record('course', ['id' => $courseid]);
$context = context_course::instance($course->id);

require_login($course);



if (!$course) {
    throw new \moodle_exception('invalidcourseid');
}

$PAGE->set_title($course->fullname . ': ' . get_string('pluginname', 'local_quizhelp'));
$PAGE->set_heading($course->fullname . ': ' . get_string('pluginname', 'local_quizhelp'));
$PAGE->set_url(new moodle_url('/local/quizhelp/index.php', ['id' => $courseid]));
$PAGE->set_context($context);
$PAGE->set_pagelayout('incourse');
$PAGE->set_pagetype('course-view-' . $course->format);  // To get the blocks exactly like the course.


$question = $DB->get_record('question', ['id'=>$quesitonid]);
$optionObjs = $DB->get_records('question_answers', ['question'=>$quesitonid]);
// print_object($question);
// print_object($options);
// die;
$form = new add_resource_form(null,['id'=>$courseid, 'qid'=>$quesitonid, 'quizid'=>$quizid]);
$formHTML = $form->render();
$optionsText = [];
foreach($optionObjs as $opt){
    array_push($optionsText, ['option'=>strip_tags($opt->answer), 'fraction'=>(int)$opt->fraction]);
}


if($form->is_cancelled()){
    redirect(new moodle_url('/local/quizhelp/view.php', array('id' => $courseid, 'quizid'=>$quizid)));
}
else if($formData = $form->get_data()){
    $record = new stdClass;
    if(filter_var($formData->resource, FILTER_VALIDATE_URL)){
        $record->is_link = 1;
    }
    else{
        $record->is_link = 0;
    }
    $record->resources = $formData->resource;
    $record->timecreated = time();
    $record->questionid = $quesitonid;
    $record->quizid = $quizid;
    $record->courseid = $courseid;
    $DB->insert_record('local_quizhelp_resources', $record);
    redirect(new moodle_url('/local/quizhelp/view.php', array('id' => $courseid, 'quizid'=>$quizid)));
}

echo $OUTPUT->header();

// echo print_object($questionArray);
    
    echo $OUTPUT->render_from_template('local_quizhelp/addresource', ['form'=>$formHTML, 'question'=>strip_tags($question->questiontext), 'options'=>$optionsText]);

echo $OUTPUT->footer();