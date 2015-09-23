<?php
/**
 * @package   turnitintool
 * @copyright 2015 Turnitin
 *
 */
//moodleform is defined in formslib.php
require_once("$CFG->libdir/formslib.php");

class submit_assignment extends moodleform {
    //Add elements to form
    public function definition() {
        global $CFG, $USER, $COURSE, $cm, $turnitintool;

        $cm = $this->_customdata['cm'];
        $turnitintool = $this->_customdata['turnitintool'];
        $optional_params = $this->_customdata['optional_params'];
        $cansubmit = $this->_customdata['cansubmit'];

        if (has_capability('mod/turnitintool:grade', turnitintool_get_context('MODULE', $cm->id))) {
            $submissions=turnitintool_get_records_select('turnitintool_submissions','turnitintoolid='.$turnitintool->id);
        } else {
            $submissions=turnitintool_get_records_select('turnitintool_submissions','userid='.$USER->id.' AND turnitintoolid='.$turnitintool->id);
        }

        $output_js='<script language="javascript" type="text/javascript">'.PHP_EOL;
        $output_js.='var stringsArray = new Array("'.get_string('addsubmission','turnitintool').'","'
                .get_string('resubmit','turnitintool').'","'
                .get_string('resubmission','turnitintool').'","'
                .get_string('resubmissionnotenabled','turnitintool').'","'
                .get_string('anonenabled','turnitintool').'");'.PHP_EOL;
        $output_js.='var submissionArray = new Array();'.PHP_EOL;

        if ($turnitintool->allowlate==1) {
            $parts=turnitintool_get_records_select('turnitintool_parts',
                    "turnitintoolid='".$turnitintool->id."' AND deleted=0 AND dtstart < '".time()."'", null,
                    'dtstart,dtdue,dtpost,id');
        } else {
            $parts=turnitintool_get_records_select('turnitintool_parts',
                    "turnitintoolid='".$turnitintool->id."' AND deleted=0 AND dtstart < '".time()."' AND dtdue > '".time()."'",
                    NULL,'dtstart,dtdue,dtpost,id');
        }

        if (is_array($submissions)) {
            $i=0;
            foreach ($submissions as $submission) {
                $lockresubmission=0;
                if (isset($parts[$submission->submission_part]) AND $parts[$submission->submission_part]->dtdue < time()) {
                    $lockresubmission=1;
                }
                $output_js.='submissionArray['.$i.'] = new Array("'.$submission->userid.'","'.$submission->submission_part.'","'.$submission->submission_title.'","'.$submission->submission_unanon.'",'.$lockresubmission.');'.PHP_EOL;
                $submittedparts[]=$submission->submission_part;
                $i++;
            }
        }
        $output_js.='</script>'.PHP_EOL;
        echo $output_js;

        $mform = $this->_form;

        // Display file upload error if need be.
        if (isset($_SESSION["notice"]["type"]) AND $_SESSION["notice"]["type"] == "error") {
            $mform->addElement('html', '<div id="upload_error" class="felement ftext error"><span id="upload_error_text" class="error">'.$_SESSION["notice"]["message"].'</span></div>');
            unset($_SESSION["notice"]["type"]);
            unset($_SESSION["notice"]["message"]);
        }

        if (!empty($parts)) {
            // Upload type.
            switch ($turnitintool->type) {
                case 0:
                    $options = array("1" => "File Upload", "2" => "Text Submission");
                    $mform->addElement('select', 'submissiontype', get_string('selectoption', 'turnitintool'), $options, array("class" => "formnarrow"));
                    $mform->addHelpButton('submissiontype', 'submissiontype', 'turnitintool');
                    break;
                case 1:
                case 2:
                    $mform->addElement('hidden', 'submissiontype', $turnitintool->type);
                    $mform->setType('submissiontype', PARAM_INT);
            }

            $istutor = turnitintool_istutor($USER->email);
            $userid = $USER->id;

            // User id if applicable.
            if ($istutor) {
                $mform->addElement('hidden', 'studentsname', $USER->id);
                $mform->setType('studentsname', PARAM_INT);
            }

            $context = turnitintool_get_context('MODULE', $cm->id);

            $submissiontitle=optional_param('submissiontitle','',PARAM_CLEAN);
            $disableform=false;
            if (has_capability('mod/turnitintool:grade', turnitintool_get_context('MODULE', $cm->id))) {
                $utype="tutor";

                // If tutor submitting on behalf of student
                if (count($cansubmit)>0) {
                    $module_group = turnitintool_module_group( $cm );
                    $studentusers = array_keys( get_users_by_capability($context,'mod/turnitintool:submit','u.id','','','',$module_group,'',false) );

                    // Append course users.
                    $courseusers = array();

                    $selected = "";
                    foreach ($cansubmit as $courseuser) {
                        // Filter Guest users, admins and grader users
                        if (in_array( $courseuser->id, $studentusers ) ) {

                            if (!is_null($optional_params->userid) AND $optional_params->userid==$courseuser->id) {
                                $selected=$courseuser->id;
                            }
                            $courseusers[$courseuser->id] = $courseuser->lastname.
                                    ', '.$courseuser->firstname;
                        }
                    }
                    $select = $mform->addElement('select', 'userid', get_string('studentsname', 'turnitintool'), $courseusers, array("class" => "formnarrow", "onchange" => "updateSubForm(submissionArray,stringsArray,this.form,".$turnitintool->reportgenspeed.")"));
                    $mform->addHelpButton('userid', 'studentsname', 'turnitintool');
                    if ($selected != "") {
                        $select->setSelected($selected);
                    }

                    if (empty($courseusers)) {
                        $mform->addElement('static', 'allsubmissionsmade', get_string('allsubmissionsmade', 'turnitintool'));
                    }
                } else {
                    $mform->addElement('static', 'noenrolledstudents', get_string('noenrolledstudents', 'turnitintool'));
                }
            } else {
                // If student submitting
                $utype="student";

                $mform->addElement('hidden', 'userid', $USER->id);
                $mform->setType('userid', PARAM_INT);
            }

            if (!$disableform) {
                // Submission Title.
                $mform->addElement('text', 'submissiontitle', get_string('submissiontitle', 'turnitintool'), array("class" => "formwide"));
                $mform->setType('submissiontitle', PARAM_TEXT);
                $mform->addHelpButton('submissiontitle', 'submissiontitle', 'turnitintool');
                $mform->addRule('submissiontitle', get_string('submissiontitleerror', 'turnitintool'), 'required', '', 'client');

                // Handle assignment parts.
                if (count($parts)>1) {
                    foreach ($parts as $part) {
                        $options_parts[$part->id] = $part->partname;
                    }
                    $mform->addElement('select', 'submissionpart', get_string('submissionpart', 'turnitintool'), $options_parts, array("class" => "formnarrow", "onchange" => "updateSubForm(submissionArray,stringsArray,this.form,".$turnitintool->reportgenspeed.",'".$utype."')"));
                    $mform->addHelpButton('submissionpart', 'submissionpart', 'turnitintool');
                }
                else {
                    foreach ($parts as $part) {
                        $mform->addElement('hidden', 'submissionpart', $part->id);
                        $mform->setType('submissionpart', PARAM_INT);
                        break;
                    }
                }

                // File input.
                $maxbytessite = ($CFG->maxbytes == 0 || $CFG->maxbytes > TURNITINTOOL_MAX_FILE_UPLOAD_SIZE) ?
                            TURNITINTOOL_MAX_FILE_UPLOAD_SIZE : $CFG->maxbytes;
                $maxbytescourse = ($COURSE->maxbytes == 0 || $COURSE->maxbytes > TURNITINTOOL_MAX_FILE_UPLOAD_SIZE) ?
                            TURNITINTOOL_MAX_FILE_UPLOAD_SIZE : $COURSE->maxbytes;

                $maxfilesize = get_user_max_upload_file_size(context_module::instance($cm->id),
                                                $maxbytessite,
                                                $maxbytescourse,
                                                $turnitintool->maxfilesize);
                $maxfilesize = ($maxfilesize <= 0) ? TURNITINTOOL_MAX_FILE_UPLOAD_SIZE : $maxfilesize;
                $turnitintoolfileuploadoptions = array('maxbytes' => $maxfilesize,
                                            'subdirs' => false, 'maxfiles' => 1,
                                            'accepted_types' => array('.doc', '.docx', '.rtf', '.txt', '.pdf', '.htm',
                                                                        '.html', '.odt', '.eps', '.ps', '.wpd', '.hwp',
                                                                        '.ppt', '.pptx', '.ppsx', '.pps'));

                $mform->addElement('filemanager', 'submissionfile', get_string('filetosubmit', 'turnitintool'),
                                        null, $turnitintoolfileuploadoptions);
                $mform->addHelpButton('submissionfile', 'filetosubmit', 'turnitintool');

                // Text input input.
                $mform->addElement('textarea', 'submissiontext', get_string('texttosubmit', 'turnitintool'), array("class" => "submissionText"));
                $mform->addHelpButton('submissiontext', 'texttosubmit', 'turnitintool');

                $checked='';
                if (!is_null($optional_params->agreement)) {
                    $checked=' checked';
                }

                if (has_capability('mod/turnitintool:grade', $context) OR empty($CFG->turnitin_agreement)) {
                    $mform->addElement('hidden', 'agreement', '1');
                    $mform->setType('agreement', PARAM_INT);
                } else {
                    $mform->addElement('checkbox', 'agreement', '', $CFG->turnitin_agreement);
                    $mform->setDefault('agreement', '1', true);
                }

                $mform->addElement('submit', 'submitbutton', get_string('addsubmission', 'turnitintool'));
            }
        }
    }
    //Custom validation should be added here
    function validation($data, $files) {
        return array();
    }
}

/* ?> */
