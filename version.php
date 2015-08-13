<?php
/**
 * @package   turnitintool
 * @copyright 2012 Turnitin
 */

if(!isset($module)){
	$module = new stdClass();
}
$module->version  = 2015030303;  // The current module version (Date: YYYYMMDDXX)
$module->component = 'mod_turnitintool';
$module->maturity  = MATURITY_STABLE;
$module->cron     = 1800;        // Period for cron to check this module (secs)
//$module->requires = 2007101509;  // 1.9+
//$module->requires = 2010112400;  // 2.0+

if (!isset($plugin)) {
	$plugin = new StdClass();
}

$plugin->version  = 2015030303;  // The current module version (Date: YYYYMMDDXX)
$plugin->component = 'mod_turnitintool';
$plugin->maturity  = MATURITY_STABLE;
$plugin->cron     = 1800;        // Period for cron to check this module (secs)
//$plugin->requires = 2007101509;  // 1.9+
//$plugin->requires = 2010112400;  // 2.0+

/* ?> */
