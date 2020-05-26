<?php
/**
 * Helper class for Taskmeister Likes module
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
class modTMLikes
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
         * Function: Get custom input text from Joomla interface
         * Parameter $params: parameter from the joomla input box
         */
        return $params->get('customtext');//Return the value of id customtext in the joomla input box
    }
    public static function getHeader($params)
    {
        /**
         * Function: Get custom input header from Joomla interface
         * Parameter $params: parameter from the joomla input box
         */
        return $params->get('customheader');//Return the value of id customheader in the joomla input box
    }
    public static function giveThumbsUp(){
        /**
         * Function: Give a thumbs up string
         * Requires no parameters
         */
        return 'You gave a thumbs up!'; 
    }
    public static function giveThumbsDown(){
        /**
         * Function: Give a thumbs down string
         * Requires no parameters
         */
        return 'You gave a thumbs down!'; 
    }
    public static function loginFirst(){
        /**
         * Function: Give a login warning string
         * Requires no parameters
         */
        return 'Login first!!!'; 
    }

}