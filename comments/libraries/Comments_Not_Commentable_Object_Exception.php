<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Comments_Not_Commentable_Object_Exception exception class.
 *
 * @package    Comments
 * @author     Thibaud Schindler
 * @copyright  (c) 2009 VitrineApp Project
 * @license    http://kohanaphp.com/license.html
 */
class Comments_Not_Commentable_Object_Exception extends Kohana_Exception{
	
	const exception_key = 'workshop.duplicate_author_for_object';
	
	public function __construct(ORM $object)
	{
		$object_desc = $object->object_name . '[' . $object->id . ']';
		parent::__construct(Comments_Not_Commentable_Object_Exception::exception_key, $object_desc);
	}
}