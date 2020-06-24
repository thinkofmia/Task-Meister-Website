<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_helloworld
 * 
 * @copyright   (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

 // No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * HelloWorlds Controller
 * 
 * @since   0.0.1
 */
class HelloWorldControllerHelloWorlds extends JControllerAdmin
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
    public function getModel($name = 'HelloWorld', $prefix = 'HelloWorldModel', $config = array('ignore_request' => true))
    {
        $model = parent::getModel($name, $prefx, $config);

        return $model;
    }
}