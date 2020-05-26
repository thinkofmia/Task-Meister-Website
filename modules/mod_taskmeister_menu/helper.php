<?php
/**
 * Helper class for Custom Menu module
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
class ModCustomMenuHelper
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
         * Function: Get Text from interface
         * Parameter $params: Parameters/Inputs from the Joomla interface
         */
        return $params->get('customtext');
    }
    public static function getHeader($params)
    {
        /**
         * Function: Get Header from interface
         * Parameter $params: Parameters/Inputs from the Joomla interface
         */
        return $params->get('customheader');
    }
    public static function getWebsite($params)
    {
        /**
         * Function: Get Website from interface
         * Parameter $params: Parameters/Inputs from the Joomla interface
         */
        if ($params->get('website')) return $params->get('website');
        else return "taskmeisterx";
    }
    public static function getCustomLabel($params,$labelNo)
    {
        /**
         * Function: Get Custom Label 1 from interface
         * Parameter $params: Parameters/Inputs from the Joomla interface
         * Parameter $labelNo: Refers to the label number of the custom label
         */
        switch($labelNo){//Based on the label number, return the right inputs
            case 5:
                return $params->get('customLabel5');
            case 4:
                return $params->get('customLabel4');
            case 3:
                return $params->get('customLabel3');
            case 2:
                return $params->get('customLabel2');
            default:
                return $params->get('customLabel1');
        }
    }
    public static function getCustomAlias($params,$labelNo)
    {
        /**
         * Function: Get Custom Alias 1 from interface
         * Parameter $params: Parameters/Inputs from the Joomla interface
         * Parameter $labelNo: Refers to the label number of the custom alias
         */
        switch($labelNo){//Based on the label number, return the right inputs
            case 5:
                return $params->get('customAlias5');
            case 4:
                return $params->get('customAlias4');
            case 3:
                return $params->get('customAlias3');
            case 2:
                return $params->get('customAlias2');
            default:
                return $params->get('customAlias1');
        }
    }
}