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
 * Review Table class
 * 
 * @since   0.0.1
 */
class ReviewTableReview extends JTable
{
    /**
     * Constructor
     * 
     * @param   JDatabaseDriver &$db    A database connector object
     */
    function __construct(&$db, $table = 'reviews', $key = 'id')
    {
        // Set internal variables.
        $this->_tbl = $table;
    
        // Set the key to be an array.
        if (is_string($key))
        {
            $key = array($key);
        }
        elseif (is_object($key))
        {
            $key = (array) $key;
        }

        $this->_tbl_keys = $key;

        if (count($key) == 1)
        {
            $this->_autoincrement = true;
        }
        else
        {
            $this->_autoincrement = false;
        }

        // Set the singular table key for backwards compatibility.
        $this->_tbl_key = $this->getKeyName();

        $this->_db = $db;

        // Initialise the table properties.
        $fields = $this->getFields();

        // ignore generated columns
        unset($fields['auid']);
        unset($fields['overall_rating']);

        if ($fields)
        {
            foreach ($fields as $name => $v)
            {
                // Add the field if it is not already present.
                if (!property_exists($this, $name))
                {
                    $this->$name = null;
                }
            }
        }

        // If we are tracking assets, make sure an access field exists and initially set the default.
        if (property_exists($this, 'asset_id'))
        {
            $this->_trackAssets = true;
        }

        // If the access property exists, set the default.
        if (property_exists($this, 'access'))
        {
            $this->access = (int) \JFactory::getConfig()->get('access');
        }

        // Implement \JObservableInterface:
        // Create observer updater and attaches all observers interested by $this class:
        $this->_observers = new \JObserverUpdater($this);
        \JObserverMapper::attachAllObservers($this);
    }
}