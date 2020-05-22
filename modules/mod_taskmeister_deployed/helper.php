<?php
/**
 * Helper class for Taskmeister Deployed module
 * 
 * @package    Joomla.Tutorials
 * @subpackage Modules
 * @link http://docs.joomla.org/J3.x:Creating_a_simple_module/Developing_a_Basic_Module
 * @license        GNU/GPL, see LICENSE.php
 * mod_helloworld is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */
class modTMDeployed
{
    /**
     * Retrieves the hello message
     *
     * @param   array  $params An object containing the module parameters
     *
     * @access public
     */    
    public static function getText($params)
    {
        /**
         * Function: Get Text
         * $params refers to the Joomla input parameters
         */
        //Return the text input in the Joomla interface
        return $params->get('customtext');
    }
    public static function getHeader($params)
    {
        /**
         * Function: Get Header
         * $params refers to the Joomla input parameters
         */
        //Return the header input in the Joomla interface
        return $params->get('customheader');
    }
    public static function loginFirst(){
        /**
         * Function: Gives alert msg to login first
         * No parameters required
         */
        return 'Login first!!!'; 
    }

}