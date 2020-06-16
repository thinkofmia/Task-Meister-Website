<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_review
 * 
 * 
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Review Model
 * 
 * @since   0.0.1
 */
class ReviewModelReview extends JModelAdmin
{
    /**
     * Method to get a table object, load it if necessary
     * 
     * @param   string  $type   The table name (optional)
     * @param   string  $prefix The class prefix (optional)
     * @param   string  $config Configuration array for model (optional)
     * 
     * @return  JTable  A JTable oject
     * 
     * @since   1.6
     */
    public function getTable($type = 'Review', $prefix = 'ReviewTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    /**
     * Method to get the record form
     * 
     * @param   array   $data       Data for the form
     * @param   boolean $loadData   True if the form is to load its own data (default), false if not
     * 
     * @return  mixed   A JForm object on success, false on failure
     * 
     * @since   1.6
     */
    public function getForm($data = array(), $loadData = true)
    {
        // Get the form
        $form = $this->loadForm(
            'com_review.review',
            'review',
            array(
                'control' => 'jform',
                'load_data' => $loadData
            )
        );

        if(empty($form))
        {
            return false;
        }

        return $form;
    }

    /**
     * Method to get the data that should be injected in the form
     * 
     * @return  mixed   The data for the form
     * 
     * @since   1.6
     */
    protected function loadFormData()
    {
        // Check the session for previously entered form data
        $data = JFactory::getApplication()->getUserState(
            'com_review.edit.review.data',
            array()
        );

        if(empty($data))
        {
            $data = $this->getItem();
        }

        return $data;
    }
}