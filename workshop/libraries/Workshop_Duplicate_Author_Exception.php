<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Workshop_Duplicate_Author_Exception exception class.
 *
 * @package    Workshop
 * @author     Thibaud Schindler
 * @copyright  (c) 2009 VitrineApp Project
 * @license    http://kohanaphp.com/license.html
 */
class Workshop_Duplicate_Author_Exception extends Kohana_Exception{
	
	const exception_key = 'workshop.duplicate_author_for_object';
	
	public function __construct(User_Model $author, ORM $object)
	{
		$object_desc = $object->object_name . '[' . $object->id . ']';
		$author_desc = $author->username . '[' . $object->id . ']';
		parent::__construct(Workshop_Duplicate_Author_Exception::exception_key, $author_desc, $object_desc);
	}
}