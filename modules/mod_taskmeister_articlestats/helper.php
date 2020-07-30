<?php
/**
 * Helper class for the Article Statistics module
 * 
 * @package    Joomla.Tutorials
 * @subpackage Modules
 * @link http://docs.joomla.org/J3.x:Creating_a_simple_module/Developing_a_Basic_Module
 * @license        GNU/GPL, see LICENSE.php
 * mod_helloworld is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */
use Joomla\CMS\Factory;

class modArticleStats
{
    /**
     * Retrieves the hello message
     *
     * @param   array  $params An object containing the module parameters
     *
     * @access public
     */    
    public static function getText($params) //Get text input from the Joomla's interface
    {
        return $params->get('customtext');
    }
    public static function getHeader($params)//Get header input from the Joomla's interface
    {
        return $params->get('customheader');
    }
    /**
     * getName()
     * Function: Get the user's name from the factory
     * Parameter: $id refers to the target user id
     */
    function getName($id)
    {
        $user = Factory::getUser($id);
        return $user->name;
    }
}