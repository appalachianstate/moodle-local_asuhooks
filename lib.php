<?php

	defined('MOODLE_INTERNAL') || die();
	
	require_once("$CFG->dirroot/lib/navigationlib.php");
	
/**
     * Hook to insert a link in settings navigation menu block
     *
     * @param settings_navigation $navigation
     * @param course_context      $context
     * @return void
     */
    function local_asuhooks_extends_settings_navigation(settings_navigation $navigation, $context) {
		global $COURSE;
		
        // If not in a course context, then leave
        if ($context == null || $context->contextlevel != CONTEXT_COURSE) {
            return;
        }
        
        // When on front page there is 'frontpagesettings' node, other
        // courses will have 'courseadmin' node
        if (null == ($courseadmin_node = $navigation->get('courseadmin'))) {
            // Keeps us off the front page
            return;
        }

        // If user doesn't have role capability, leave
        if (!has_capability("block/simple_restore:canrestore", $context)) {
        	return;
        }
        
        if (null == ($restoreadmin_node = $courseadmin_node->get('restore'))) {
        	return;
        }
        
		// Remove link to default Moodle restore for users other than admin
        if (!is_siteadmin()) {
        	$restoreadmin_node->action = null;
        } 
        
        // Add links to simple restore node in courseadmin nav
        // Import into current course
        $restoreadmin_node->add(
            get_string('IMPORT_MENU_LONG', 'local_asuhooks'),
            new moodle_url('/blocks/simple_restore/list.php', array(
                    'id' => $COURSE->id,
                    'restore_to' => 1)),
            navigation_node::TYPE_SETTING,
            get_string('IMPORT_MENU_SHORT', 'local_asuhooks'),
        	null, new pix_icon('i/import', 'import'));
        
        
        // Add link to simple restore node in courseadmin nav
        // Overwrite into current course
        $restoreadmin_node->add(
        	get_string('OVERWRITE_MENU_LONG', 'local_asuhooks'),
        	new moodle_url('/blocks/simple_restore/list.php', array(
        			'id' => $COURSE->id,
        			'restore_to' => 0)),
        	navigation_node::TYPE_SETTING,
        	get_string('OVERWRITE_MENU_SHORT', 'local_asuhooks'),
        	null, new pix_icon('i/backup', 'overwrite'));
    
    }

    
    