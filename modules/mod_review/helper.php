<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_review
 * @license     GNU/GPL, see LICENSE.php
 * mod_review is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
**/

// no direct access
defined('_JEXEC') or die;

use Joomla\CMS\Factory;

// Contains the helper class which does the actual work in retrieving the information to be displayed
class ModReviewHelper
{
    /**
     * Method to get testimonials from the database for the currently viewed article
     * 
     * @return  array   $testimonials    An array of testimonial objects sorted by their rating
     */
    public static function getTestimonials($uid = null)
    {
        // Get a db connection
        $db = Factory::getDbo();
        // Create a new JDatabaseQuery object
        $query = $db->getQuery(true);

        // Select all records from the 'reviews' table where article ID is the one of the article being viewed
        // Order it by column rating
        $query
            ->select('*')
            ->from($db->quoteName('reviews'))
            ->where($db->quoteName('aid') . ' = ' . $db->quote(JRequest::getVar('id')))
            ->order($db->quoteName('rating') . ' DESC');
        
        // Additionally filter to get the specified testimonial if $uid is passed
        // Else only get published reviews for display
        if($uid)
        {
            $query->where($db->quoteName('uid') . ' = ' . $uid);
        }
        else
        {
            $query->where($db->quoteName('published') . ' = 1');
        }

        // Reset the query using the newly populated query object
        $db->setQuery($query);

        // Load the results as a list/array of stdClass objects
        $results = $db->loadObjectList();

        return $results;
    }
    
    /**
     * Method to save a user review of an article to the 'reviews' table
     * 
     * @param   Object  $params     An object containing the parameters set in the backend interface for the module
     */
    public static function saveReview($params)
    {
        // Check if user is submitting a POST request for form submission
        //  process the POST request if so
        $app = Factory::getApplication();
        $input = $app->input;

        $uri = JUri::getInstance();

        if(strtoupper($input->getMethod()) === 'POST')
        {
            // check for cross-site request forgery
            if(!(JSession::checkToken()))
            {
                return;
            }

            $review = (object) array(
                'aid' => 0,
                'uid' => 0,
                'rating' => '',
                'review' => ''
            );

            $review->aid                    = $input->getInt('id');
            $review->uid                    = (int) Factory::getUser()->id;
            $review->rating                 = $input->getInt('rating');
            $review->review                 = $input->getString('review');

            // Get a db connection
            $db = Factory::getDbo();

            // Check if review by the user for the article does not exist, insert if so
            if(is_null($prev = self::getTestimonials($review->uid)[0]))
            {
                $review->created = date('Y-m-d');
                $review->updated = date('Y-m-d');

                // Set the default publish value for a new review
                $review->published = (int) self::publishDefault($params);

                // Attempt to insert new review to database
                if($db->insertObject('reviews', $review))
                {
                    $msg = 'submit_success';
                    self::updateRecommendationRecordsDB($review->uid, $review->aid, "submitted a review for");
                }
                else
                {
                    $msg = 'submit_failure';
                }
                // notify admin after review is uploaded if it's not published by default
                if(!$review->published)
                    self::notifyAdmin($review);
            }
            // Update db record otherwise
            else
            {
                $review->updated = date('Y-m-d');
                
                // get the first and only record's id for the updateObject method
                $review->id = $prev->id;
                if($db->updateObject('reviews', $review, 'id'))
                {
                    $msg = 'edit_success';
                    self::updateRecommendationRecordsDB($review->uid, $review->aid, "updated their review for");
                }
                else
                {
                    $msg = 'edit_failure';
                }
            }
            
            // Perform a redirect to reload the page
            $uri->setVar('submit_status', $msg);
            $uri->setFragment('review-form');
            $app->redirect($uri->render());
        }
    }

    /**
     * Method to send an email notifying the administrators of an unpublished review for approval
     *  An array of user group names mapped to their group id
     *   $group_names = array(
     *       2 => 'registered',
     *       3 => 'author',
     *       4 => 'editor',
     *       5 => 'publisher',
     *       6 => 'manager',
     *       7 => 'administrator',
     *       8 => 'super_user',
     *       10 => 'new_user',
     *       11 => 'teacher',
     *       12 => 'hod'
     *   );
     */
    public static function notifyAdmin($review = array())
    {
        // get current user
        $user = Factory::getUser();
        
        // get current article
        $article_id = Factory::getApplication()->input->getInt('id');

        // query db for article title
        $db = Factory::getDbo();
        $query = $db->getQuery(true)
            ->select('title')
            ->from('#__content')
            ->where('id = ' . $article_id);
        $db->setQuery($query);

        $article_title = $db->loadObject()->title;

        // get mailer object
        $mailer = Factory::getMailer();

        // set sender according to site email
        $config = Factory::getConfig();
        $sender = array( 
            $config->get( 'mailfrom' ),
            $config->get( 'fromname' ) 
        );
        $mailer->setSender($sender);

        // set the recipient(s)
        $access = new JAccess;
        $list = array();
        $admins = $access->getUsersByGroup(7);
        foreach ($admins as $admin) {
            array_push($list, Factory::getUser($admin)->email);
        }
        $mailer->addBcc($list);

        // create mail
        $subject = "New review by %s for %s at %s";
        $subject = sprintf($subject, $user->name, $article_title, JUri::base());
        $body = "Hello administrator,\n\nA student '%s', username '%s', user ID '%d', ";
        $body .= "has submitted a new review for article %d: '%s'\n";
        $body .= "You may publish/moderate/delete the review at " . JUri::base() . "administrator/index.php?option=com_review ";
        $body .= "after logging in.\n\n";
        $body = sprintf($body, $user->name, $user->username, $user->id, $article_id, $article_title);

        if(empty($review))
            $body .= "This is either a test or an empty review has been submitted somehow.";
        else {
            $body .= "Preview of review:\n";
            $body .= "Rating: " . $review->rating . "\n" . $review->review . "\n\n";
        }
        $mailer->setSubject($subject);
        $mailer->setBody($body);

        // send the mail
        $sent = $mailer->Send();
        if($sent === true)
            echo("Mail sent!");
        else
            echo("Failed to send.");
    }

    /**
     * Method to display a status message reflecting the success/failure of review submission
     * 
     * @return  String          A message reflecting the success/failure of the form submission
     */
    public static function displayStatus()
    {
        $uri = JUri::getInstance();
        // get value of GET parameter 'submit_status'
        $status = $uri->getVar('submit_status', '');

        switch ($status) {
            case 'submit_success':
                $msg = 'MOD_REVIEW_SUBMIT_SUCCESS';
                break;
            case 'submit_failure':
                $msg = 'MOD_REVIEW_SUBMIT_FAILURE';
                break;
            case 'edit_success':
                $msg = 'MOD_REVIEW_EDIT_SUCCESS';
                break;
            case 'edit_failure':
                $msg = 'MOD_REVIEW_EDIT_FAILURE';
                break;
        }

        return JText::_($msg);
    }

    /**
     * Method to translate a user id to their corresponding display name
     * 
     * @param   integer $uid    An integer representation of their Joomla user ID
     * 
     * @return  string  $name   The user's display name
     */
    public static function getName($uid)
    {
        return Factory::getUser($uid)->name;
    }

    /**
     * Method to check whether the user has deployed the article they are currently viewing
     *  Custom table definition:
     *      Table name: $db->quoteName('#__customtables_table_userstats')
     *      Columns:    es_userid, es_name, es_email, es_userpreference, es_pageliked, es_pagedisliked, es_pagedeployed
     * 
     * @return  boolean true if the user has deployed the article, false otherwise
     */
    public static function hasDeployed()
    {
        // Get the user id and article id
        $uid = Factory::getUser()->id;
        $aid = Factory::getApplication()->input->getInt('id');

        $db = Factory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select($db->quoteName('es_pagedeployed'))
            ->from($db->quoteName('#__customtables_table_userstats'))
            ->where('es_userid = ' . $uid);

        // Reset the query using the newly populated query object
        $db->setQuery($query);

        // Load the result as a stdClass object
        $result = $db->loadObject();

        // Convert json string to data
        $deployed = json_decode($result->es_pagedeployed);

        if(in_array($aid, $deployed))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Method to set the review form to the previously submitted review so the user can edit their review
     * 
     * @param   Form    $form   The Joomla form object
     * 
     * @return  boolean true if the user had a prior review to populate the current form field, false otherwise
     */
    public static function setForm($form)
    {
        // Get user id
        $uid = Factory::getUser()->id;

        // Get prior user submitted review 
        $review = self::getTestimonials($uid)[0];

        // Set the various field values 
        if(!is_null($review))
        {
            $form->setValue('rating', null, $review->rating);
            $form->setValue('review', null, $review->review);
            return true;
        }
        else 
        {
            return false;
        }
    }

    /**
     * Method to format date for printing, converts dates like "2020-05-03" to "03 May" including the year if it's earlier
     * 
     * @param   String  $date   date in a string with the format YYYY-MM-DD
     * 
     * @return  String  $r      date in a string with the format "DD Month" or "DD Month YYYY" if the year is earlier
     */
    public static function fmtDate($date)
    {
        $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July',
                   'August', 'September', 'October', 'November', 'December'];
        $date = explode('-', $date);
        $r = $date[2] . ' ' . $months[(int) $date[1] - 1];
        
        if($date[0] !== date('Y'))
        {
            $r .= ' ' . $date[0];
        }
        
        return $r;
    }

    /**
     * Method to get module configuration information from the backend to determine whether the user's review is published by default
     * 
     * @return  Boolean $publish    A boolean representing whether the review should be published by default
     */
    public static function publishDefault($params)
    {
        // An array of user group names mapped to their group id
        $group_names = array(
            2 => 'registered',
            3 => 'author',
            4 => 'editor',
            5 => 'publisher',
            6 => 'manager',
            7 => 'administrator',
            8 => 'super_user',
            10 => 'new_user',
            11 => 'teacher',
            12 => 'hod'
        );

        $group_ids = Factory::getUser()->groups;

        $publish = false;

        // Iterate through all groups the user belongs to and override to publish by default if any of the user's groups are set as such
        foreach($group_ids as $group_id)
        {
            $publish = $publish || (int) $params->get($group_names[$group_id]);
        }

        return $publish;
    }

    /**
     * Method returning the html for rendering a rating from 0-10 using stars given an integer
     *  method relies on the css file in the same module
     * 
     * @param   Integer $rating     An integer representing the rating
     * 
     * @return  String              A html string for rendering the star
     */
    public static function renderStarRating($rating, $css = '')
    {
        $filled = ' star-colour';
        $html = '<div class="rating" data-vote="0" style="%s">'.
                    '<div class="star-static hidden">'.
                        '<span class="full-static" data-value="0"></span>'.
                        '<span class="half-static" data-value="0"></span>'.
                    '</div>'.
                    '<div class="star-static">'.
                        '<span class="full-static' . ($rating >= 2 ? $filled : '') . '" data-value="2"></span>'.
                        '<span class="half-static' . ($rating >= 1 ? $filled : '') . '" data-value="1"></span>'.
                    '</div>'.
                    '<div class="star-static">'.
                        '<span class="full-static' . ($rating >= 4 ? $filled : '') . '" data-value="4"></span>'.
                        '<span class="half-static' . ($rating >= 3 ? $filled : '') . '" data-value="3"></span>'.
                    '</div>'.
                    '<div class="star-static">'.
                        '<span class="full-static' . ($rating >= 6 ? $filled : '') . '" data-value="6"></span>'.
                        '<span class="half-static' . ($rating >= 5 ? $filled : '') . '" data-value="5"></span>'.
                    '</div>'.
                    '<div class="star-static">'.
                        '<span class="full-static' . ($rating >= 8 ? $filled : '') . '" data-value="8"></span>'.
                        '<span class="half-static' . ($rating >= 7 ? $filled : '') . '" data-value="7"></span>'.
                    '</div>'.
                    '<div class="star-static">'.
                        '<span class="full-static' . ($rating >= 10 ? $filled : '') . '" data-value="10"></span>'.
                        '<span class="half-static' . ($rating >= 9? $filled : '') . '" data-value="9"></span>'.
                    '</div>'.
                '</div>';
                
        return sprintf($html, $css);
    }

    /**
     * Method to generate the statistics for an array of reviews, indicating the distribution of stars using a horizontal
     *  bar chart and percentages (add buttons to filter reviews by a star count)
     * 
     * @param   Array   $testimonials   An array containing published reviews
     * 
     * @return  String                  html code representing the statistics
     */
    public static function generateReviewStatistics($testimonials)
    {
        $html = '<div style="width: 100%; height: 188px;"><ul class="review-statistics" style="display: inline-block; list-style: none; margin-bottom: 20px; width: 20vw; min-width: 200px; float: left;">';
        $empty_bar = '<span class="horizontal-bar-chart horizontal-bar-chart-empty" style="height: 3px; width: 50%; margin-right: 10px; margin-top: 7px; background-color: #FFFFFF; float: left;">';
        $filled_bar = '<b class="horizontal-bar-chart horizontal-bar-chart-filled" style="display: block; height: 3px; width: %d%%; background-color: #ffd700;">';
        $bar_label = '<span class="horizontal-bar-chart-label" style="margin-right: 10px; text-align: left; width: auto; min-width: 65px; padding-right: 0; overflow: hidden; float: left;">%s</span>';

        $stats = array(5 => 0,
                       4 => 0,
                       3 => 0,
                       2 => 0,
                       1 => 0,
                       0 => 0,
                    );
        
        foreach($testimonials as $testimonial)
        {
            $stats[floor($testimonial->rating/2)]++;
        }

        // The number of reviews in total
        $total = array_sum($stats);
        // Variable to hold the total stars received by the article
        $sum = 0;

        foreach($stats as $stars => $present)
        {
            $sum += $stars * $present;
            $percentage = floor($present/$total*100);
            $result_bar = $empty_bar . sprintf($filled_bar, $percentage) . '</b></span>';
            $html .= '<li style="padding-bottom: 10px; width: auto; white-space: nowrap;">' . sprintf($bar_label, ($stars . ' Stars: ')) . $result_bar . $percentage . '%</li>';
        }

        $html .= '</ul>';

        $average = $sum/$total;

        $html .= sprintf('<div style="display: inline-block; height: 168px; line-height: 168px; vertical-align: top; width: 200px; float: left; text-align: left; margin-bottom: 20px;"><b>%1.1f</b> / 5%s</div></div>', $average, self::renderStarRating($average * 2, 'display: inline-block; float: none; padding-left: 10px; height: 168px;'));

        return $html;
    }

    /**
     * Method to parse a string for the ID of a video, creating a youtube embed with html
     *  
     * @param   String  $url    A url containing the ID of a youtube video
     * @param   String  $width  The width of the resulting iframe
     * @param   String  $height The height of the resulting iframe
     * @param   String  $tag    Optional tag specified if iframe will break an enclosing tag
     * 
     * @return  String          The html defining the iframe for the youtube video embed
     */
    public static function createYTEmbed($url, $tag = '', $width = '100%%', $height = '100%%')
    {
        // format string for container for iframe
        $container = '<div style="overflow: hidden; padding-top: 56.25%%; position: relative; margin-bottom: 5px;">%s</div>';
        // format string defining the iframe for the embed link
        $embed = '<iframe style="display: block; margin-top: 10px; margin-bottom: 10px; border: 0; left: 0; position: absolute; top: 0; width: ' . $width . '; height: ' . $height . ';" src="https://www.youtube.com/embed/%s"'.
            ' allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
        $html = '';
        $query = array();

        // Handle multiple url formats, storing matches in $query
        // if the url has a 'v' parameter
        if(preg_match('/\S+v=(\S+)/', $url, $query))
        {
        } // if the url is an embed link
        else if(preg_match('/\S+\/embed\/(\S+)/', $url, $query))
        {
        } // url has the form youtu.be/<id>[?t=[[0-9]+]]
        else
        {
            preg_match('/youtu\.be\/(\S+)/', $url, $query);
        }

        // Replace placeholder id with video ID
        $html = sprintf($embed, $query[1]);
        $html = sprintf($container, $html);

        if(!empty($tag))
        {
            $matches = array();
            preg_match('/[A-Za-z]+/', $tag, $matches);
            $html =  '</' . $matches[0] . '>' . $html . '<' . $matches[0] . '>';
        }

        return $html;
    }

    /**
     * A wrapper function for createYTEmbed(), calling the function on all matches for youtube video urls
     *  and returning the embedding code for the same video
     * Valid (recognised) youtube video url formats:
     *  https://www.youtube.com/watch?v=0DyCnca5nBo
     *  https://youtube.com/watch?v=0DyCnca5nBo
     *  www.youtube.com/watch?v=0DyCnca5nBo
     *  youtube.com/watch?v=0DyCnca5nBo
     *  https://www.youtube.com/embed/0DyCnca5nBo
     *  www.youtube.com/embed/0DyCnca5nBo
     *  https://youtube.com/embed/0DyCnca5nBo
     *  youtube.com/embed/0DyCnca5nBo
     *  https://youtu.be/0DyCnca5nBo
     *  youtu.be/0DyCnca5nBo
     *  https://youtu.be/0DyCnca5nBo?t=6
     * 
     * @param   String  $text   Submitted text by the user
     * 
     * @return  String          The same text with the youtube video url replaced by the embedding code
     */
    public static function replaceYTUrl($text, $tag = '')
    {
        // regex for a valid youtube video url
        $regex = '/(http(s)?:\/\/)?(www\.)?youtu(\.be|be\.com)\/(\S+)/';
        return preg_replace_callback($regex,
            function($matches) use ($tag) {
                return self::createYTEmbed($matches[0], $tag);
            },
            $text);
    }

    public static function updateRecommendationRecordsDB($userID, $articleID, $action) {
        /**
         * Function: Update recommendation database with the user's action on an article
         * @param   Integer $userID: Refers to the current user id
         * @param   Integer $articleID: Refers to the current article id
         * @param   String  $action: Refers to the user's action
         */
        // Create and populate an object.
        $record = new stdClass();
        $record->es_uid = $userID;
        $record->es_aid = $articleID;
        $record->es_action = $action;
        $record->es_date = date("Y-m-d");
        // Insert the object into the user profile table.
        $result = Factory::getDbo()->insertObject('#__customtables_table_recommendationstats', $record);
        }
    
}