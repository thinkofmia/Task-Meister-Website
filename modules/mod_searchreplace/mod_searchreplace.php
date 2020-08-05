<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  mod_searchreplace
 * @license     GNU/GPL, see LICENSE.php
 * mod_searchreplace is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
**/

// no direct access
defined('_JEXEC') or die;

// Main entry point of the module, to perform any necessary initialisation routines
//  call helper routines to collect data, and include the template which will display the module output

// include the syndicate functions only once
require_once __DIR__.'/helper.php';

//$pattern = '/(<article .*)margin:(?:\s*)auto;/';

//$pattern = '/lightnessfaq/';
//$replacement = 'darkhivefaq';

//$pattern = ['/color:(\s)?#333333;/', '/background-color:(\s)?#fafafa;/'];
//$replacement = '/* \0 */';
$pattern = '/background-color:(\s)?white;/';
$replacement = '/* \0 */';

ModSearchReplaceHelper::getMatches($pattern, $replacement);

require JModuleHelper::getLayoutPath('mod_searchreplace');