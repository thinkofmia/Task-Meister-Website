<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_taskmeister_review
 * 
 * @copyright   (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

 // No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * TaskmeisterReview Model
 * 
 * @since   0.0.1
 */
class TaskmeisterReviewModelTaskmeisterReview extends JModelItem
{
    /**
     * @var array   reviews
     */
    protected $reviews;

    /**
     * Method to get a table object, load it if necessary
     * 
     * @param   string  $type   The table name (optional)
     * @param   string  $prefix The class prefix (optional)
     * @param   array   $config Configuraton array for model (optional)
     * 
     * @return  JTable  A JTable object
     * 
     * @since 1.6
     */
    public function getTable($type = 'TaskmeisterReview', $prefix = 'TaskmeisterReviewTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    /**
     * Get the review
     * 
     * @param   integer $id Review id
     * 
     * @return  string      Fetched string from Table for relevant id
     */
    public function getMsg($id = 1)
    {
        if(!is_array($this->reviews))
        {
            $this->reviews = array();
        }

        if(!isset($this->reviews[$id]))
        {
            // Request the selected id
            $input = JFactory::getApplication()->input;
            $id = $input->get('id', 1, 'int');

            // get a TableTaskmeisterReview instance
            $table = $this->getTable();

            // Load the message
            $table->load($id);

            // Assign the review
            $this->reviews[$id] = $table->ease;
        }

        return $this->reviews[$id];
    }
}