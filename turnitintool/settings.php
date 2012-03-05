<?php
/**
 * @package   turnitintool
 * @copyright 2010 iParadigms LLC
 */

require_once($CFG->dirroot.'/mod/turnitintool/lib.php');
require_once($CFG->dirroot.'/mod/turnitintool/version.php');

global $RESOURCE_WINDOW_OPTIONS;

$toplinks = '<div><a href="'.$CFG->wwwroot.'/mod/turnitintool/extras.php">'.get_string("connecttest", "turnitintool").'</a> | <a href="'.$CFG->wwwroot.'/mod/turnitintool/extras.php?do=viewreport">'.get_string("showusage", "turnitintool").'</a> | <a href="'.$CFG->wwwroot.'/mod/turnitintool/extras.php?do=savereport">'.get_string("saveusage", "turnitintool").'</a> | <a href="'.$CFG->wwwroot.'/mod/turnitintool/extras.php?do=commslog">'.get_string("logs").'</a> | <a href="'.$CFG->wwwroot.'/mod/turnitintool/extras.php?do=unlinkusers">'.get_string("unlinkusers", "turnitintool").'</a> - ('.get_string('moduleversion','turnitintool').': '.$module->version.')</div>';

$settings->add(new admin_setting_heading('turnitin_header', '', $toplinks));

$settings->add(new admin_setting_configtext('turnitin_account_id', get_string("turnitinaccountid", "turnitintool"),
                   get_string("turnitinaccountid_desc", "turnitintool"),''));

$settings->add(new admin_setting_configpasswordunmask('turnitin_secretkey', get_string("turnitinsecretkey", "turnitintool"),
                   get_string("turnitinsecretkey_desc", "turnitintool"),''));

$settings->add(new admin_setting_configtext('turnitin_apiurl', get_string("turnitinapiurl", "turnitintool"),
                   get_string("turnitinapiurl_desc", "turnitintool"),''));

$options = array(0 => get_string('no', 'turnitintool'),
                    1 => get_string('yes', 'turnitintool'),
                 );
$settings->add(new admin_setting_configselect('turnitin_usegrademark', get_string('turnitinusegrademark', 'turnitintool'),
                   get_string('turnitinusegrademark_desc', 'turnitintool'), 0, $options));

$settings->add(new admin_setting_configselect('turnitin_useerater', get_string('turnitinuseerater', 'turnitintool'),
                    get_string('turnitinuseerater_desc', 'turnitintool'), 0, $options));

$settings->add(new admin_setting_configselect('turnitin_userepository', get_string('turnitinuserepository', 'turnitintool'),
                   get_string('turnitinuserepository_desc', 'turnitintool'), 0, $options));

$settings->add(new admin_setting_configselect('turnitin_useanon', get_string('turnitinuseanon', 'turnitintool'),
                   get_string('turnitinuseanon_desc', 'turnitintool'), 0, $options));

if (!isset($CFG->turnitin_agreement)) {
    $CFG->turnitin_agreement=get_string('turnitintoolagreement_default','turnitintool');
}

$settings->add(new admin_setting_configtextarea('turnitin_agreement', get_string('turnitintoolagreement', 'turnitintool'),
                   get_string('turnitintoolagreement_desc', 'turnitintool'), ''));

$settings->add(new admin_setting_configselect('turnitin_studentemail', get_string('turnitinstudentemail', 'turnitintool'),
                   get_string('turnitinstudentemail_desc', 'turnitintool'), 1, $options));

$settings->add(new admin_setting_configselect('turnitin_tutoremail', get_string('turnitintutoremail', 'turnitintool'),
                   get_string('turnitintutoremail_desc', 'turnitintool'), 1, $options));

$settings->add(new admin_setting_configselect('turnitin_receiptemail', get_string('turnitinreceiptemail', 'turnitintool'),
                   get_string('turnitinreceiptemail_desc', 'turnitintool'), 1, $options));

$settings->add(new admin_setting_configselect('turnitin_enablediagnostic', get_string('turnitindiagnostic', 'turnitintool'),
                   get_string('turnitindiagnostic_desc', 'turnitintool'), 0, $options));

$settings->add(new admin_setting_configtext('turnitin_proxyurl', get_string("proxyurl", "turnitintool"),
                   get_string("proxyurl_desc", "turnitintool"),''));

$settings->add(new admin_setting_configtext('turnitin_proxyport', get_string("proxyport", "turnitintool"),
                   get_string("proxyport_desc", "turnitintool"),''));

$settings->add(new admin_setting_configtext('turnitin_proxyuser', get_string("proxyuser", "turnitintool"),
                   get_string("proxyuser_desc", "turnitintool"),''));

$settings->add(new admin_setting_configpasswordunmask('turnitin_proxypassword', get_string("proxypassword", "turnitintool"),
                   get_string("proxypassword_desc", "turnitintool"),''));

// Following are default values for new instance

$settings->add(new admin_setting_heading('turnitin_defaults', get_string('defaults','turnitintool'),
                   get_string('defaults_desc', 'turnitintool')));

$settings->add(new admin_setting_configselect('turnitin_default_type', get_string('type','turnitintool'),
                   '', 1, turnitintool_filetype_array())); 
                  
$settings->add(new admin_setting_configselect('turnitin_default_numparts', get_string('numberofparts','turnitintool'),
                   '', 1, array(1=>1,2=>2,3=>3,4=>4,5=>5))); 
                  
$options = array();
$scales = get_scales_menu();
foreach ($scales as $value => $scale) {
    $options[-$value] = $scale;
}
for ($i=100; $i>=1; $i--) {
    $options[$i] = $i;
}
$settings->add(new admin_setting_configselect('turnitin_default_grade', get_string('overallgrade','turnitintool'),
                   '', 100, $options)); 
unset( $options );

$ynoptions = array(0 => get_string('no', 'turnitintool'),
                   1 => get_string('yes', 'turnitintool'),
                 );

$settings->add(new admin_setting_configselect('turnitin_default_anon', get_string('anon','turnitintool'),
                   '', 0, $ynoptions ));

$settings->add(new admin_setting_configselect('turnitin_default_studentreports', get_string('studentreports','turnitintool'),
                   '', 0, $ynoptions ));

$settings->add(new admin_setting_configselect('turnitin_default_allowlate', get_string('allowlate','turnitintool'),
                   '', 0, $ynoptions ));

$genoptions = array( 0 => get_string('genimmediately1','turnitintool'), 1 => get_string('genimmediately2','turnitintool'), 2 => get_string('genduedate','turnitintool'));
$settings->add(new admin_setting_configselect('turnitin_default_reportgenspeed', get_string('reportgenspeed','turnitintool'),
                   '', 0, $genoptions ));

$suboptions = array( 0 => get_string('norepository','turnitintool'), 1 => get_string('standardrepository','turnitintool'));
$settings->add(new admin_setting_configselect('turnitin_default_submitpapersto', get_string('submitpapersto','turnitintool'),
                   '', 1, $suboptions ));

$settings->add(new admin_setting_configselect('turnitin_default_spapercheck', get_string('spapercheck','turnitintool'),
                   '', 1, $ynoptions ));

$settings->add(new admin_setting_configselect('turnitin_default_internetcheck', get_string('internetcheck','turnitintool'),
                   '', 1, $ynoptions ));

$settings->add(new admin_setting_configselect('turnitin_default_journalcheck', get_string('journalcheck','turnitintool'),
                   '', 1, $ynoptions ));

$settings->add(new admin_setting_configselect('turnitin_default_excludebiblio', get_string('excludebiblio','turnitintool'),
                   '', 0, $ynoptions ));

$settings->add(new admin_setting_configselect('turnitin_default_excludequoted', get_string('excludequoted','turnitintool'),
                   '', 0, $ynoptions ));
				   
/* ?> */
