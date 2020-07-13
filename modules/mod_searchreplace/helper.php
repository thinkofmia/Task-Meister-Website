<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  mod_searchreplace
 * @license     GNU/GPL, see LICENSE.php
 * mod_searchreplace is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
**/

// no direct access
defined('_JEXEC') or die;

use Joomla\CMS\Factory;

class ModSearchReplaceHelper
{
    /**
     * Method to get the matches of $pattern
     */
    public static function getMatches($pattern)
    {
        $app = Factory::getApplication();
        $app->enqueueMessage('Debugging search and replace');

        $db = Factory::getDbo();

        // setup query for introtext and fulltext of articles
        $query = $db->getQuery(true)
            ->select('*')
            ->from($db->qn('#__content'));
        $db->setQuery($query);

        $results = $db->loadObjectList();

        foreach ($results as $result) {
            $result->fulltext = preg_replace_callback($pattern,
                function($matches) {
                    return $matches[1];
                },
                $result->fulltext);
            $result->introtext = preg_replace_callback($pattern,
                function($matches) {
                    return $matches[1];
                },
                $result->introtext);
        }
        //var_dump($results);
        foreach($results as $result)
            $db->updateObject('#__content', $result, 'id');
        //var_dump($db->getTableCreate('#__content'));
    }
}