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
        // Order it by ease_rating and then effectiveness_rating
        $query
            ->select($db->quoteName(array(
                'uid',
                'ease_rating', 'ease',
                'effectiveness_rating', 'effectiveness')))
            ->from($db->quoteName('reviews'))
            ->where($db->quoteName('aid') . ' = ' . $db->quote(JRequest::getVar('id')))
            ->order($db->quoteName('ease_rating') . ' DESC', $db->quoteName('effectiveness_rating' . ' DESC'));
        
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

        // Load the results as a list of stdClass objects
        $results = $db->loadObjectList();

        return $results;
    }
    
    /**
     * Method to save a user review of an article to the 'reviews' table
     * 
     * @param   String  &$msg   A variable containing a string for the user, reflecting the success/failure of saving the review
     */
    public static function saveReview(&$msg)
    {
        // Check if user is submitting a POST request for form submission
        //  process the POST request if so
        $app = Factory::getApplication();
        $input = $app->input;

        if(strtoupper($input->getMethod()) === 'POST')
        {
            // check for cross-site request forgery
            if(!(JSession::checkToken()))
            {
                return;
            }

            $review = (object) array('aid' => 0,
            'uid' => 0,
            'ease_rating' => '',
            'ease' => '',
            'effectiveness_rating' => '',
            'effectiveness' => '',
            );

            $review->aid                    = $input->getInt('id');
            $review->uid                    = Factory::getUser()->id;
            $review->ease_rating            = $input->getInt('ease_rating');
            $review->ease                   = $input->getString('ease');
            $review->effectiveness_rating   = $input->getInt('effectiveness_rating'); 
            $review->effectiveness          = $input->getString('effectiveness');

            // Get a db connection
            $db = Factory::getDbo();

            // Attempt to save review to database
            if($db->insertObject('reviews', $review))
            {
                $msg = 'MOD_REVIEW_SUBMIT_SUCCESS';
            }
            else
            {
                $msg = 'MOD_REVIEW_SUBMIT_FAILURE';
            }
        }
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
        if($review)
        {
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
     * Method to get module configuration information from the backend to determine whether the user's review is published by default
     */
    public static function getConfig($params)
    {
        //echo $params->get('sampleField');
        //var_dump(JAccess::getUsersByGroup(8));

        // logical OR all permissions for the user's current groups to determine publishing default 
    }
}