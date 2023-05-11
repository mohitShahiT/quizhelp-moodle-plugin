<?php

defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir.'/formslib.php');

class quiz_selection_form extends moodleform {
    // Add elements to form.
    public function definition() {
        // A reference to the form is stored in $this->form.
        // A common convention is to store it in a variable, such as `$mform`.
        $mform = $this->_form;

        $mform->addElement('select', 'quiz', $this->_customdata['canEdit']?'Select a Quiz to edit resources':'Select a Quiz to view resources', $this->_customdata['quiz_options']);
        //$this->_customdata['quiz_options']

        $mform->addElement('hidden', 'id', $this->_customdata['id']);
        // $mform->setType('id', PARAM_INT);

        $this->add_action_buttons(true, 'Show Resources');

    }

    // Custom validation should be added here.
    function validation($data, $files) {
        return [];
    }
}
