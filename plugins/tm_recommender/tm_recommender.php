<?php
// no direct access
defined( '_JEXEC' ) or die;

class plgTaskMeisterTM_recommender extends JPlugin
{
	/**
	 * Load the language file on instantiation. Note this is only available in Joomla 3.1 and higher.
	 * If you want to support 3.0 series you must override the constructor
	 *
	 * @var    boolean
	 * @since  3.1
	 */
	protected $autoloadLanguage = true;

	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	 function calculateMetrics($number)
	 {
		/*
		 * Plugin code goes here.
		 * You can access database and application objects and parameters via $this->db,
		 * $this->app and $this->params respectively
		 */
        return "Wonderful ".($number*2);
    }
    
    function welcomeText($number){
        
        return "Welcome user: Working Now";
    }
}
?>