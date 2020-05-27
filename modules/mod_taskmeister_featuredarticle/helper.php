<?php
/**
 * Helper class for Featured Article module
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
class ModFeaturedArticleHelper
{
    /**
     * Retrieves the hello message
     *
     * @param   array  $params An object containing the module parameters
     *
     * @access public
     */    
    public static function getText($params)
    {
        return $params->get('customtext');
    }
    public static function getHeader($params)
    {
        return $params->get('customheader');
    }
    public static function getVideo($params)
    {
        return $params->get('videolink');
    }
    function getArticle($articleId){
        $db =& JFactory::getDbo();
        //Query
        $query = $db->getQuery(true);
        $query->select($db->quoteName(array('*')))
            ->from($db->quoteName('#__content'))
            ->where($db->quoteName('id') . ' = ' . intval($articleId));
        $db->setQuery($query);
        $results = $db->loadAssocList();
        foreach($results as $row){
            $fullArticle = $row;
        }
        if (!$fullArticle) $fullArticle = "No article found. ";
        return $fullArticle;
    }
}