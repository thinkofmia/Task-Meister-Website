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

require_once __DIR__.'/../../helper.php';

// The class name must always be the same as the filename (in camel case)
class JFormFieldRating extends JFormField {

	//The field class must know its own type through the variable $type.
	protected $type = 'Rating';

	public function getLabel() {
        // code that returns HTML that will be shown as the label
        parent::getLabel();
	}

	public function getInput() {
        // code that returns HTML that will be shown as the form field
        $value = $this->__get('value');
        $filled = ' star-colour';
        $name = $this->getAttribute('name');
        $html = '<div class="rating" data-vote="0">'.
                    '<div class="star-animated hidden">'.
                        '<span class="full" data-value="0"></span>'.
                        '<span class="half" data-value="0"></span>'.
                    '</div>'.
                    '<div class="star-animated">'.
                        '<span class="full' . ($value >= 2 ? $filled : '') . '" data-value="2"></span>'.
                        '<span class="half' . ($value >= 1 ? $filled : '') . '" data-value="1"></span>'.
                        '<span class="selected"></span>'.
                    '</div>'.
                    '<div class="star-animated">'.
                        '<span class="full' . ($value >= 4 ? $filled : '') . '" data-value="4"></span>'.
                        '<span class="half' . ($value >= 3 ? $filled : '') . '" data-value="3"></span>'.
                        '<span class="selected"></span>'.
                    '</div>'.
                    '<div class="star-animated">'.
                        '<span class="full' . ($value >= 6 ? $filled : '') . '" data-value="6"></span>'.
                        '<span class="half' . ($value >= 5 ? $filled : '') . '" data-value="5"></span>'.
                        '<span class="selected"></span>'.
                    '</div>'.
                    '<div class="star-animated">'.
                        '<span class="full' . ($value >= 8 ? $filled : '') . '" data-value="8"></span>'.
                        '<span class="half' . ($value >= 7 ? $filled : '') . '" data-value="7"></span>'.
                        '<span class="selected"></span>'.
                    '</div>'.
                    '<div class="star-animated">'.
                        '<span class="full' . ($value >= 10 ? $filled : '') . '" data-value="10"></span>'.
                        '<span class="half' . ($value >= 9? $filled : '') . '" data-value="9"></span>'.
                        '<span class="selected"></span>'.
                    '</div>'.
                    '<input id="' . $name . '" name="' . $name . '" type="hidden" class="score" value="' . $value .'">'.
                '</div>';
        
        return $html;
	}
}