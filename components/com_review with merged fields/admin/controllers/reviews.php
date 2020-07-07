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
 * Reviews Controller
 * 
 * @since   0.0.1
 */
class ReviewControllerReviews extends JControllerAdmin
{
    /**
     * Proxy for getModel
     * 
     * @param   string  $name   The model name (optional)
     * @param   string  $prefix The class prefix (optional)
     * @param   array   $config Configuration array for model (optional)
     * 
     * @return  object  The model
     * 
     * @since   1.6
     */
    public function getModel($name = 'Review', $prefix = 'ReviewModel', $config = array('ignore_request' => true))
    {
        $model = parent::getModel($name, $prefix, $config);

        return $model;
    }
}