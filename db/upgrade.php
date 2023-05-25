<?php

function xmldb_local_quizhelp_upgrade($oldversion)
{
    global $DB;
    $dbman = $DB->get_manager();
    if ($oldversion < 2023052501) {

        // Define field userid to be added to local_testplugin_messages.
        $table = new xmldb_table('local_quizhelp_resources');
        // <FIELD NAME="is_link" TYPE="int" LENGTH="10" NOTNULL="true" SEQUENCE="false"/>

        $field = new xmldb_field('is_link', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, 0, 'is_link');

        // Conditionally launch add field userid.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Testplugin savepoint reached.
        upgrade_plugin_savepoint(true, 2023052501, 'local', 'quizhelp');
    }

    return true;
}