<?php
/**
 * @package     Joomla.Administrator
 * @subpackage    com_taskmeister_review
 * 
 * 
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * HTML View class for the TaskmeisterReview Component
 * 
 * @since   0.0.1
 */
class TaskmeisterReviewViewTaskmeisterReview extends JViewLegacy
{
    /**
     * Display the Taskmeister Review view
     * 
     * @param   string  $tpl    The name of the template file to parse; automatically searches through the template paths
     * 
     * @return  void
     */
    function display($tpl = null)
    {
        // Assign data to the view
        $this->msg = $this->get('Msg');

        // Check for errors
        if(count($errors = $this->get('Errorrs')))
        {
            JLog::add(implode('<br />', $errors), JLog::WARNING, 'jerror');

            return false;
        }

        // Display the view
        parent::display($tpl);
    }
}