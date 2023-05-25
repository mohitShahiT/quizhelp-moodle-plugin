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
 * @copyright  
 * @license    ////////////////////////////http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


require_once('../../config.php');
require_once('../../mod/quiz/attemptlib.php');
require_once('../../mod/quiz/accessmanager.php');
require_once('../../lib/questionlib.php');
require_once('../../mod/quiz/classes/question/bank/qbank_helper.php');
require_once('../../mod/quiz/classes/external.php');
require_once($CFG->dirroot . '/local/quizhelp/lib.php');
use mod_quiz\question\bank\qbank_helper;

$courseid = required_param('id', PARAM_INT);
$quizid = required_param('quizid', PARAM_INT);
$attemptid = optional_param('attemptid',0, PARAM_INT);
$course = $DB->get_record('course', array('id'=>$courseid), '*', MUST_EXIST);
$context = context_course::instance($courseid);

$quiz = get_coursemodule_from_id('quiz', $quizid, $courseid); //course quiz module cm 
$quizModule = context_module::instance($quizid);

$quizObj = quiz::create($quiz->instance, $USER->id);
$quizObj->preload_questions();
$quizObj->load_questions();
$questions = $quizObj->get_questions();

// Must pass login
require_login($course);


// print_object($questionArray);
// die;

$PAGE->set_title($course->fullname . ': ' . get_string('pluginname', 'local_quizhelp'));
$PAGE->set_heading($course->fullname . ': ' . get_string('pluginname', 'local_quizhelp'));
$PAGE->set_url(new moodle_url('/local/quizhelp/index.php', ['id' => $courseid]));
$PAGE->set_context($context);
$PAGE->set_pagelayout('incourse');
$PAGE->set_pagetype('course-view-' . $course->format);  // To get the blocks exactly like the course.

//getting role of user to render the right template for each user
$roles = get_user_roles($context, $USER->id, true);
$role = key($roles);
$rolename = $roles[$role]->shortname;


$questionArray = [];
foreach($questions as $qstn){
    $editUrl = "addresoruce.php?id={$courseid}&quizid={$quizid}&qid={$qstn->id}";
    $resourceObjs = $DB->get_records('local_quizhelp_resources',['questionid'=>$qstn->id]);
    $resources = [];
    foreach($resourceObjs as $res){
        array_push($resources, ['resource'=>$res->resources, 'isLink'=>$res->is_link]);
    }
    array_push($questionArray, array('question_text'=>strip_tags($qstn->questiontext),'resources'=>$resources, 'edit_link'=>$editUrl));
}

// print_object($questionArray);
// die;

if($attemptid){

    $review = mod_quiz_external::get_attempt_review($attemptid);
    $newQuestionArray = [];
    for($i = 0; $i<sizeof($questionArray); $i++){
        if((float)$review['questions'][$i]['mark']<1){
            array_push($newQuestionArray, array('question_text'=>$questionArray[$i]['question_text'],'resources'=>$questionArray[$i]['resources']));
        }
    }
    $questionArray = $newQuestionArray;
}


$templateContext = ['questions'=>$questionArray];

// $resources 

echo $OUTPUT->header();

// echo print_object($questionArray);
echo $OUTPUT->render_from_template('local_quizhelp/editresources', $templateContext);
// if($rolename == "editingteacher" || $rolename == "teacher" ){
//     echo $OUTPUT->render_from_template('local_quizhelp/editresources', $editContext);
// }
// else{
//     echo $OUTPUT->render_from_template('local_quizhelp/viewresources', $viewContext);
// }

echo $OUTPUT->footer();