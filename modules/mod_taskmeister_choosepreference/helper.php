<?php
/**
 * Helper class for Choose Preference! module
 * 
 * @package    Joomla.Tutorials
 * @subpackage Modules
 * @link http://docs.joomla.org/J3.x:Creating_a_simple_module/Developing_a_Basic_Module
 * @license        GNU/GPL, see LICENSE.php
 * This module is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */
class ModChoosePreferenceHelper
{
    /**
     * Retrieves the function
     *
     * @param   array  $params An object containing the module parameters
     *
     * @access public
     */    

    public static function getText($params)
    {
        return $params->get('customtext');
    }
    public static function getHeader($params)
    {
        return $params->get('customheader');
    }
    function findTags(){//Get list of available
        //Call our recommender
        JPluginHelper::importPlugin('taskmeister','tm_recommender');
        $dispatcher = JDispatcher::getInstance();
        $results = $dispatcher->trigger('getTagList', array());
        //Return string
        return $results[0] ;
    }
    function displayTags($list){//Display tags from list_str
        foreach ($list as $key => $value){
            echo $key." used ".$value." times!<br>";
        }
        return true;
    }
    function displayTags_String($list){//Display tags from list_str
        foreach ($list as $key => $value){
            echo $key." used ".$value." times!<br>";
        }
        return true;
    }
}