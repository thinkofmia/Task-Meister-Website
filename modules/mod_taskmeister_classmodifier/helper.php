<?php
/**
 * Helper class for Class Modifier module
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
class modClassModifier
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
        return $params->get('customtext');
    }
    public static function getHeader($params)
    {
        return $params->get('customheader');
    }
    function checkTable($choice){
        if ($choice=="choice_yes") return true;
        else return false;
    }
    function saveSelection($data){
        /*
            Function: Save the teacher's selection into the database
            Parameter $data: Data form from the post request 
        */
        //Call our recommender to invoke function saveOurTeachers(), requires a list of teachers
        JPluginHelper::importPlugin('taskmeister','tm_recommender');
        $dispatcher = JDispatcher::getInstance();
        $results = $dispatcher->trigger('saveClassModifiers', array($data));//Returns results in the form of an array
        return $results[0];//Return first index of results, which is mostly a boolean to show if the func completes

    }
}