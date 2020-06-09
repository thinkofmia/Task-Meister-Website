<?php
/**
 * Helper class for Choose Class! module
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
class ModChooseClassHelper
{
    /**
     * Retrieves the function
     *
     * @param   array  $params An object containing the module parameters
     *
     * @access public
     */    
    
    public static function getText($params)//Function to get custom text from Joomla interface
    {
        return $params->get('customtext');
    }
    public static function getHeader($params)//Function to get custom header from Joomla interface
    {
        return $params->get('customheader');
    }
    function findTeachers(){//Get list of available teachers
        /*
            Function: to get all the available teachers being currently used 
            Call our recommender plugin to invoke function getTagList()
            Requires no parameters
        */
        JPluginHelper::importPlugin('taskmeister','tm_recommender');
        $dispatcher = JDispatcher::getInstance();
        $results = $dispatcher->trigger('getTeachersList', array());//Results returned is in a form of an array
        
        return $results[0] ;//Return the first index of the resulting array
    }
    function saveLists($list){
        /*
            Function: Save the user's teacher lists into the database
            Parameter $list: List of teachers that the user is under
        */
        //Call our recommender to invoke function saveOurTeachers(), requires a list of teachers
        JPluginHelper::importPlugin('taskmeister','tm_recommender');
        $dispatcher = JDispatcher::getInstance();
        $results = $dispatcher->trigger('saveOurTeachers', array($list));//Returns results in the form of an array
        return $results[0];//Return first index of results, which is mostly a boolean to show if the func completes
    }
    function getYourTeachers($userid, $db){
        if ($userid != 0 ){//If user is not a guest
            //Get external teacher table (custom table)
            $query = $db->getQuery(true);
            $query->select($db->quoteName(array('es_teacherid','es_students')))
            ->from($db->quoteName('#__customtables_table_teacherstats'));
            $db->setQuery($query);
            $results_ext = $db->loadAssocList();
            //Save information into a list
            $yourTeachers = array();
            foreach ($results_ext as $row){
                if (in_array(intval($userid), json_decode($row['es_students']))){//If student exists in teacher's class
                    array_push($yourTeachers, $row['es_teacherid']);
                }
            }
            return $yourTeachers;
        }
    }
}