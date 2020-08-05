<?php
/**
 * Helper class for Choose Class module
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
    /*
        findStudents()
        Function: to get the list of all the available students
        Parameters: $teacherList refers to the list of teachers, $db refers to the database
    */
    function findStudents($teacherList, $db){
        //Query the database for all the users
        $query = $db->getQuery(true);
        $query->select($db->quoteName(array('id','name')))//Gets the user id and user name
            ->from($db->quoteName('#__users'));//From the user table
        $db->setQuery($query);
        $results_ext = $db->loadAssocList();//Saves the results as $results_ext
        //Initialize the student list as an array
        $studentList = array();
        //Loop for each user in the results
        foreach($results_ext as $row){
            if (array_key_exists($row['id'], $teacherList)){
                //Its a teacher, ignore
            }
            else {//Else if it is a student to list
                $studentList[$row['id']] = $row['name'];//Add the student id and name into the list
            }
        }
        return $studentList ;//Returns the list of students

    }
    /*
        findTeachers()
        Function: find all the available teachers in the database
        Parameters: None
    */
    function findTeachers(){
        //Call our recommender engine to find the teachers
        JPluginHelper::importPlugin('taskmeister','tm_recommender');
        $dispatcher = JDispatcher::getInstance();
        $results = $dispatcher->trigger('getTeachersList', array());//Results returned is in a form of an array
        
        return $results[0] ;//Return the list of teachers
    }
    /*
        saveListOfStudents()
        Function: Save the teacher's class into the database
        Parameters: $list refers to the teacher's class/list of students the teacher has selected
    */
    function saveListOfStudents($list){
        //Call our recommender to invoke function saveOurTeachers(), requires a list of teachers
        JPluginHelper::importPlugin('taskmeister','tm_recommender');
        $dispatcher = JDispatcher::getInstance();
        $results = $dispatcher->trigger('saveOurStudents', array($list));//Returns results in the form of an array
        return $results[0];//Return a confirmation boolean if succeeded         
    }
    /*
        saveListOfTeachers()
        Function: Save the student's list of teachers into the database
        Parameters: $list refers to the list of teachers the student has selected
    */
    function saveListOfTeachers($list){
        //Call our recommender to invoke function saveOurTeachers(), requires a list of teachers
        JPluginHelper::importPlugin('taskmeister','tm_recommender');
        $dispatcher = JDispatcher::getInstance();
        $results = $dispatcher->trigger('saveOurTeachers', array($list));//Returns results in the form of an array
        return $results[0];//Return a confirmation boolean if succeeded   
    }
    /*
        getYourTeachers()
        Function: get all of the student's current teachers in the database
        Parameters: $userid refers to the student id, while $db refers to the database
    */
    function getYourTeachers($userid, $db){
        if ($userid != 0 ){//If user is not a guest/has logined
            //Query the database for the custom teacher statistics
            $query = $db->getQuery(true);
            $query->select($db->quoteName(array('es_teacherid','es_students')))//Gets the teacher id and their class (list of students)
            ->from($db->quoteName('#__customtables_table_teacherstats'));//From the custom teacher statistics table
            $db->setQuery($query);
            $results_ext = $db->loadAssocList();//Save results as $results_ext
            //Initialize your teachers array
            $yourTeachers = array();
            //Loop for each teacher found
            foreach ($results_ext as $row){
                //If teacher has the student id in their class
                if (in_array(intval($userid), json_decode($row['es_students']))){
                    array_push($yourTeachers, $row['es_teacherid']);//Add the teacher id into your teachers array
                }
            }
            return $yourTeachers;//Returns the list of teachers you have
        }
    }
    /*
        getYourStudents()
        Function: gets the teacher's current class from the database
        Parameters: $userid refers to the student id, while $db refers to the database
    */
    function getYourStudents($userid, $db){
        if ($userid != 0 ){//If user is not a guest/has logined
            //Query the database for the custom teacher's statistics
            $query = $db->getQuery(true);
            $query->select($db->quoteName(array('es_teacherid','es_students')))//Gets the teacher id and their list of students
            ->from($db->quoteName('#__customtables_table_teacherstats'));//From the custom teachers statistics table
            $db->setQuery($query);
            $results_ext = $db->loadAssocList();//Saves the results as $results_ext
            //Initialize your class array
            $yourStudents = array();
            //Loop for each teacher found
            foreach ($results_ext as $row){
                //If teacher matches the user id
                if ($row['es_teacherid']==$userid){
                    //Gets the current class and convert it into an array
                    $yourStudents = json_decode($row['es_students']);
                }
            }
            return $yourStudents;//Returns the current class
        }
    }
}