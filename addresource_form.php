<?php

defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir.'/formslib.php');

class add_resource_form extends moodleform {
    // Add elements to form.
    public function definition() {
        // A reference to the form is stored in $this->form.
        // A common convention is to store it in a variable, such as `$mform`.
        $mform = $this->_form;

        $mform->addElement('text', 'resource', 'Resource'); // Add elements to your form.
        $mform->setType('resource', PARAM_TEXT); // Set type of element.
        $mform->addElement('hidden', 'id', $this->_customdata['id']);
        $mform->addElement('hidden', 'qid', $this->_customdata['qid']);
        $mform->addElement('hidden', 'quizid', $this->_customdata['quizid']);
        
        $mform->addRule('resource', 'resource is required', 'required', null, 'client');
        $this->add_action_buttons(true, 'Add Resource');

        
    }

    // Custom validation should be added here.
    function validation($data, $files) {
        return [];
    }
}