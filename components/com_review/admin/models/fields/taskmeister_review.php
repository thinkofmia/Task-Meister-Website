<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_taskmeister_review
 * 
 * 
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

JFormHelper::loadFieldClass('list');

/**
 * Taskmeister_Review Form Field class for the Taskmeister_Review component
 * 
 * @since   0.0.1
 */
class JFormFieldTaskmeister_Review extends JFormFieldList
{
    /**
     * The field type
     * 
     * @var string
     */
    protected $type = 'Taskmeister_Review';

    /**
     * Method to get a list of options for a list input
     * 
     * @return  array   An array of JHtml options
     */
    protected function getOptions()
    {
        // Get a db connection
        $db     = JFactory::getDbo();

        // Create a new query object
        $query  = $db->getQuery(true);
        $query
            ->select($db->quoteName(array('id','ease','ease_rating','effectiveness','effectiveness_rating')))
            ->from($db->quoteName('taskmeister_reviews'));
        $db->setQuery((string) $query);
        $reviews = $db->loadObjectList();
        $options = array();

        if($reviews)
        {
            foreach($reviews as $review)
            {
                $options[] = JHtml::_('select.option', $review->id, $review->ease);
            }
        }

        $options = array_merge(parent::getOptions(), $options);

        return $options;
    }
}