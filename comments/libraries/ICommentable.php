<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * ICommentable library interface.
 *
 * @package    Comments
 * @author     Thibaud Schindler
 * @copyright  (c) 2009 VitrineApp Project
 * @license    http://kohanaphp.com/license.html
 */
interface ICommentable {

	public function is_commentable();
}