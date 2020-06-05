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
    
    public static function getText($params)//Function to get custom text from Joomla interface
    {
        return $params->get('customtext');
    }
    public static function getHeader($params)//Function to get custom header from Joomla interface
    {
        return $params->get('customheader');
    }
    function findTags(){//Get list of available
        /*
            Function: to get all the available tags being currently used 
            Call our recommender plugin to invoke function getTagList()
            Requires no parameters
        */
        JPluginHelper::importPlugin('taskmeister','tm_recommender');
        $dispatcher = JDispatcher::getInstance();
        $results = $dispatcher->trigger('getTagList', array());//Results returned is in a form of an array
        
        return $results[0] ;//Return the first index of the resulting array
    }
    function displayTags($list){
        /*
            Function: To display the list of tags in a string - Normally for debugging
            Parameter $list: An array of tags
        */
        foreach ($list as $key => $value){//For loop to display contents of the array
            echo $key." used ".$value." times!<br>";
        }
        return true;//Return that the func has successfully carried out
    }
    function displayTags_String($list){
        /*
            Function: Does the same as displayTags() above
        */
        foreach ($list as $key => $value){
            echo $key." used ".$value." times!<br>";
        }
        return true;
    }
    function saveLists($list1,$list2,$list3){
        /*
            Function: Save the user's preference lists into the database
            Parameter $list1: List of tags that the user preferred
            Parameter $list2: List of tags that the user do not prefer
            Parameter $list3: List of tags that the user may try
        */
        //Call our recommender to invoke function saveUserPreference(), requires all three parameters
        JPluginHelper::importPlugin('taskmeister','tm_recommender');
        $dispatcher = JDispatcher::getInstance();
        $results = $dispatcher->trigger('saveUserPreference', array($list1,$list2,$list3));//Returns results in the form of an array
        
        return $results[0];//Return first index of results, which is mostly a boolean to show if the func completes
    }
    function getPreferenceLists($userid, $db){
        if ($userid != 0 ){//If user is not a guest
            //Get external user table (custom table) To find out list of liked, deployed and disliked articles
            $query = $db->getQuery(true);
            $query->select($db->quoteName(array('es_userid','es_userpreference')))
            ->from($db->quoteName('#__customtables_table_userstats'))
            ->where($db->quoteName('es_userid') . ' = ' . $userid);
            $db->setQuery($query);
            $results_ext = $db->loadAssocList();
            //Save information into a list
            foreach ($results_ext as $row){
                if ($row['es_userid']==$userid){//Just to be sure if user id is same
                    $preferencelist = json_decode($row['es_userpreference']);
                }
            }
            return $preferencelist;
        }
        
    }
}