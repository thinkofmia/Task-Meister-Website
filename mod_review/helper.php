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

// Contains the helper class which does the actual work in retrieving the information to be displayed
class ModReviewHelper
{
    /**
     * Method to get testimonials from the database for the currently viewed article
     * 
     * @return  string  testimonials    An array of testimonial objects sorted by their rating
     */
    public static function getTestimonials()
    {
        // Get a db connection
        $db = JFactory::getDbo();
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

        // Reset the query using the newly populated query object
        $db->setQuery($query);

        // Load the results as a list of stdClass objects
        $results = $db->loadObjectList();

        return $results;
    }
}