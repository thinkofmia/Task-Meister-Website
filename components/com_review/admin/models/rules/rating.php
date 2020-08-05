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
 * Form Rule class for the Joomla Framework.
 */
class JFormRuleRating extends JFormRule
{
	public function test(SimpleXMLElement $element, $value, $group = null, JRegistry $input = null, JForm $form = null)
    {
		if($value > 0 && $value <= 10 && filter_var($value, FILTER_VALIDATE_INT))
		{
			return true;
		}
        return false;
    }
}