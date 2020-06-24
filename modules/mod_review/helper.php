<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_review
 * @license     GNU/GPL, see LICENSE.php
 * mod_review is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
**/

// no direct access
defined('_JEXEC') or die;

use Joomla\CMS\Factory;

// Contains the helper class which does the actual work in retrieving the information to be displayed
class ModReviewHelper
{
    /**
     * Method to get testimonials from the database for the currently viewed article
     * 
     * @return  array   $testimonials    An array of testimonial objects sorted by their rating
     */
    public static function getTestimonials($uid = null)
    {
        // Get a db connection
        $db = Factory::getDbo();
        // Create a new JDatabaseQuery object
        $query = $db->getQuery(true);

        // Select all records from the 'reviews' table where article ID is the one of the article being viewed
        // Order it by column overall_rating (automatically generated column from db)
        //   overall_rating: CEILING((`ease_rating` + `effectiveness_rating`)/2)
        $query
            ->select('*')
            ->from($db->quoteName('reviews'))
            ->where($db->quoteName('aid') . ' = ' . $db->quote(JRequest::getVar('id')))
            ->order($db->quoteName('overall_rating') . ' DESC');
        
        // Additionally filter to get the specified testimonial if $uid is passed
        // Else only get published reviews for display
        if($uid)
        {
            $query->where($db->quoteName('uid') . ' = ' . $uid);
        }
        else
        {
            $query->where($db->quoteName('published') . ' = 1');
        }

        // Reset the query using the newly populated query object
        $db->setQuery($query);

        // Load the results as a list/array of stdClass objects
        $results = $db->loadObjectList();

        return $results;
    }
    
    /**
     * Method to save a user review of an article to the 'reviews' table
     * 
     * @param   Object  $params     An object containing the parameters set in the backend interface for the module
     */
    public static function saveReview($params)
    {
        // Check if user is submitting a POST request for form submission
        //  process the POST request if so
        $app = Factory::getApplication();
        $input = $app->input;

        $uri = JUri::getInstance();

        if(strtoupper($input->getMethod()) === 'POST')
        {
            // check for cross-site request forgery
            if(!(JSession::checkToken()))
            {
                return;
            }

            $review = (object) array(
                'aid' => 0,
                'uid' => 0,
                'summary' => '',
                'ease_rating' => '',
                'ease' => '',
                'effectiveness_rating' => '',
                'effectiveness' => '',
            );

            $review->aid                    = $input->getInt('id');
            $review->uid                    = (int) Factory::getUser()->id;
            $review->summary                = $input->getString('summary');
            $review->ease_rating            = $input->getInt('ease_rating');
            $review->ease                   = $input->getString('ease');
            $review->effectiveness_rating   = $input->getInt('effectiveness_rating'); 
            $review->effectiveness          = $input->getString('effectiveness');

            // Get a db connection
            $db = Factory::getDbo();

            // Check if review by the user for the article does not exist, insert if so
            if(is_null($prev = self::getTestimonials($review->uid)[0]))
            {
                $review->created = date('Y-m-d');
                $review->updated = date('Y-m-d');

                // Set the default publish value for a new review
                $review->published = (int) self::publishDefault($params);

                // Attempt to insert new review to database
                if($db->insertObject('reviews', $review))
                {
                    $msg = 'submit_success';
                }
                else
                {
                    $msg = 'submit_failure';
                }
            }
            // Update db record otherwise
            else
            {
                $review->updated = date('Y-m-d');
                
                // get the first and only record's id for the updateObject method
                $review->id = $prev->id;
                if($db->updateObject('reviews', $review, 'id'))
                {
                    $msg = 'edit_success';
                }
                else
                {
                    $msg = 'edit_failure';
                }
            }
            
            // Perform a redirect to reload the page
            $uri->setVar('submit_status', $msg);
            $uri->setFragment('review-form');
            $app->redirect($uri->render());
        }
    }

    /**
     * Method to display a status message reflecting the success/failure of review submission
     * 
     * @return  String          A message reflecting the success/failure of the form submission
     */
    public static function displayStatus()
    {
        $uri = JUri::getInstance();
        // get value of GET parameter 'submit_status'
        $status = $uri->getVar('submit_status', '');

        switch ($status) {
            case 'submit_success':
                $msg = 'MOD_REVIEW_SUBMIT_SUCCESS';
                break;
            case 'submit_failure':
                $msg = 'MOD_REVIEW_SUBMIT_FAILURE';
                break;
            case 'edit_success':
                $msg = 'MOD_REVIEW_EDIT_SUCCESS';
                break;
            case 'edit_failure':
                $msg = 'MOD_REVIEW_EDIT_FAILURE';
                break;
        }

        return JText::_($msg);
    }

    /**
     * Method to translate a user id to their corresponding display name
     * 
     * @param   integer $uid    An integer representation of their Joomla user ID
     * 
     * @return  string  $name   The user's display name
     */
    public static function getName($uid)
    {
        return Factory::getUser($uid)->name;
    }

    /**
     * Method to check whether the user has deployed the article they are currently viewing
     *  Custom table definition:
     *      Table name: $db->quoteName('#__customtables_table_userstats')
     *      Columns:    es_userid, es_name, es_email, es_userpreference, es_pageliked, es_pagedisliked, es_pagedeployed
     * 
     * @return  boolean true if the user has deployed the article, false otherwise
     */
    public static function hasDeployed()
    {
        // Get the user id and article id
        $uid = Factory::getUser()->id;
        $aid = Factory::getApplication()->input->getInt('id');

        $db = Factory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select($db->quoteName('es_pagedeployed'))
            ->from($db->quoteName('#__customtables_table_userstats'))
            ->where('es_userid = ' . $uid);

        // Reset the query using the newly populated query object
        $db->setQuery($query);

        // Load the result as a stdClass object
        $result = $db->loadObject();

        // Convert json string to data
        $deployed = json_decode($result->es_pagedeployed);

        if(in_array($aid, $deployed))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Method to set the review form to the previously submitted review so the user can edit their review
     * 
     * @param   Form    $form   The Joomla form object
     * 
     * @return  boolean true if the user had a prior review to populate the current form field, false otherwise
     */
    public static function setForm($form)
    {
        // Get user id
        $uid = Factory::getUser()->id;

        // Get prior user submitted review 
        $review = self::getTestimonials($uid)[0];

        // Set the various field values 
        if(!is_null($review))
        {
            $form->setValue('summary', null, $review->summary);
            $form->setValue('ease_rating', null, $review->ease_rating);
            $form->setValue('ease', null, $review->ease);
            $form->setValue('effectiveness_rating', null, $review->effectiveness_rating);
            $form->setValue('effectiveness', null, $review->effectiveness);

            return true;
        }
        else 
        {
            return false;
        }
    }

    /**
     * Method to format date for printing, converts dates like "2020-05-03" to "03 May" including the year if it's earlier
     * 
     * @param   String  $date   date in a string with the format YYYY-MM-DD
     * 
     * @return  String  $r      date in a string with the format "DD Month" or "DD Month YYYY" if the year is earlier
     */
    public static function fmtDate($date)
    {
        $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July',
                   'August', 'September', 'October', 'November', 'December'];
        $date = explode('-', $date);
        $r = $date[2] . ' ' . $months[(int) $date[1] - 1];
        
        if($date[0] !== date('Y'))
        {
            $r .= ' ' . $date[0];
        }
        
        return $r;
    }

    /**
     * Method to get module configuration information from the backend to determine whether the user's review is published by default
     * 
     * @return  Boolean $publish    A boolean representing whether the review should be published by default
     */
    public static function publishDefault($params)
    {
        // An array of user group names mapped to their group id
        $group_names = array(
            2 => 'registered',
            3 => 'author',
            4 => 'editor',
            5 => 'publisher',
            6 => 'manager',
            7 => 'administrator',
            8 => 'super_user',
            10 => 'new_user',
            11 => 'teacher',
            12 => 'hod'
        );

        $group_ids = Factory::getUser()->groups;

        $publish = false;

        // Iterate through all groups the user belongs to and override to publish by default if any of the user's groups are set as such
        foreach($group_ids as $group_id)
        {
            $publish = $publish || (int) $params->get($group_names[$group_id]);
        }

        return $publish;
    }
}