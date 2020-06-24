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

    /**
	 * Method to save the form data.
	 * 	Slightly modified from the Joomla source code to ignore generated columns
	 *
	 * @param   array  $data  The form data.
	 *
	 * @return  boolean  True on success, False on error.
	 *
	 * @since   1.6
	 */
	public function save($data)
	{
		$dispatcher = \JEventDispatcher::getInstance();
		$table      = $this->getTable();
		$context    = $this->option . '.' . $this->name;
        $app        = \JFactory::getApplication();
        
		if (!empty($data['tags']) && $data['tags'][0] != '')
		{
			$table->newTags = $data['tags'];
		}

		$key = $table->getKeyName();
		$pk = (!empty($data[$key])) ? $data[$key] : (int) $this->getState($this->getName() . '.id');
		$isNew = true;

		// Include the plugins for the save events.
		\JPluginHelper::importPlugin($this->events_map['save']);

		// Allow an exception to be thrown.
		try
		{

			// Load the row if saving an existing record.
			if ($pk > 0)
			{
				$table->load($pk);
				$isNew = false;

				// ignore generated columns
				unset($table->auid);
				unset($table->overall_rating);
			}
			// Create the value for the `created` column for a new record
			else
			{
				$table->created = date('Y-m-d');
			}
			// Create the value for the `updated` column for the record
			$table->updated = date('Y-m-d');

			// debug
			// $app->enqueueMessage(var_export($table, true));

			// Bind the data.
			if (!$table->bind($data))
			{
				$this->setError($table->getError());

				return false;
			}

			// Prepare the row for saving
			$this->prepareTable($table);

			// Check the data.
			if (!$table->check())
			{
				$this->setError($table->getError());

				return false;
			}

			// Trigger the before save event.
			$result = $dispatcher->trigger($this->event_before_save, array($context, $table, $isNew, $data));

			if (in_array(false, $result, true))
			{
				$this->setError($table->getError());

				return false;
			}

			// Store the data.
			if (!$table->store())
			{
				$this->setError($table->getError());

				return false;
			}

			// Clean the cache.
			$this->cleanCache();

			// Trigger the after save event.
			$dispatcher->trigger($this->event_after_save, array($context, $table, $isNew, $data));
		}
		catch (\Exception $e)
		{
			$this->setError($e->getMessage());

			return false;
		}

		if (isset($table->$key))
		{
			$this->setState($this->getName() . '.id', $table->$key);
		}

		$this->setState($this->getName() . '.new', $isNew);

		if ($this->associationsContext && \JLanguageAssociations::isEnabled() && !empty($data['associations']))
		{
			$associations = $data['associations'];

			// Unset any invalid associations
			$associations = ArrayHelper::toInteger($associations);

			// Unset any invalid associations
			foreach ($associations as $tag => $id)
			{
				if (!$id)
				{
					unset($associations[$tag]);
				}
			}

			// Show a warning if the item isn't assigned to a language but we have associations.
			if ($associations && $table->language === '*')
			{
				$app->enqueueMessage(
					\JText::_(strtoupper($this->option) . '_ERROR_ALL_LANGUAGE_ASSOCIATED'),
					'warning'
				);
			}

			// Get associationskey for edited item
			$db    = $this->getDbo();
			$query = $db->getQuery(true)
				->select($db->qn('key'))
				->from($db->qn('#__associations'))
				->where($db->qn('context') . ' = ' . $db->quote($this->associationsContext))
				->where($db->qn('id') . ' = ' . (int) $table->$key);
			$db->setQuery($query);
			$old_key = $db->loadResult();

			// Deleting old associations for the associated items
			$query = $db->getQuery(true)
				->delete($db->qn('#__associations'))
				->where($db->qn('context') . ' = ' . $db->quote($this->associationsContext));

			if ($associations)
			{
				$query->where('(' . $db->qn('id') . ' IN (' . implode(',', $associations) . ') OR '
					. $db->qn('key') . ' = ' . $db->q($old_key) . ')');
			}
			else
			{
				$query->where($db->qn('key') . ' = ' . $db->q($old_key));
			}

			$db->setQuery($query);
			$db->execute();

			// Adding self to the association
			if ($table->language !== '*')
			{
				$associations[$table->language] = (int) $table->$key;
			}

			if (count($associations) > 1)
			{
				// Adding new association for these items
				$key   = md5(json_encode($associations));
				$query = $db->getQuery(true)
					->insert('#__associations');

				foreach ($associations as $id)
				{
					$query->values(((int) $id) . ',' . $db->quote($this->associationsContext) . ',' . $db->quote($key));
				}

				$db->setQuery($query);
				$db->execute();
			}
		}

		if ($app->input->get('task') == 'editAssociations')
		{
			return $this->redirectToAssociations($data);
		}

		return true;
	}
}