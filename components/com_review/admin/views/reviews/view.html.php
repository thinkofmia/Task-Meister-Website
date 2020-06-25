<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_review
 * 
 * @copyright   (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

 // No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Reviews view
 * 
 * @since   0.0.1
 */
class ReviewViewReviews extends JViewLegacy
{
    /**
     * Display the Reviews view
     * 
     * @param   string  $tpl    The name of the template file to parse; automatically searches through the template paths
     * 
     * @return  void
     */
    function display($tpl = null)
    {
        // Get application
        $app = JFactory::getApplication();
        $context = "review.list.admin.review";

        // Get data from the model
        $this->items            = $this->get('Items');
        $this->pagination       = $this->get('Pagination');
        $this->state            = $this->get('State');
        $this->filter_order     = $app->getUserStateFromRequest($context.'filter_order', 'filter_order', 'AID', 'cmd');
        $this->filter_order_Dir = $app->getUserStateFromRequest($context.'filter_order_Dir', 'filter_order_Dir', 'asc', 'cmd');
        $this->filterForm       = $this->get('FilterForm');
        $this->activeFilters    = $this->get('ActiveFilters');

        // Check for errors
        if(count($errors = $this->get('Errors')))
        {
            JError::raiseError(500, implode('<br />', $errors));

            return false;
        }

        // Set the toolbar and number of found items
        $this->addToolBar();

        // Display the template
        parent::display($tpl);

        // Set the document
        $this->setDocument();
    }

    /**
     * Add the page title and toolbar
     * 
     * @return  void
     * 
     * @since   1.6
     */
    protected function addToolBar()
    {
        $title = JText::_('COM_REVIEW_MANAGER_REVIEWS');

        if($this->pagination->total)
        {
            $title .= " <span style='font-size: 0.5cm; vertical-align: middle;'>(" . $this->pagination->total . ")</span>";
        }


        JToolbarHelper::title($title, 'review');
        JToolbarHelper::addNew('review.add');
        JToolbarHelper::editList('review.edit');
        JToolbarHelper::deleteList('', 'reviews.delete');
        JToolbarHelper::publishList('reviews.publish');
        JToolbarHelper::unpublishList('reviews.unpublish');
    }
    /**
     * Method to set up the document properties
     * 
     * @return  void
     */
    protected function setDocument()
    {
        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_REVIEW_ADMINISTRATION'));
    }
}