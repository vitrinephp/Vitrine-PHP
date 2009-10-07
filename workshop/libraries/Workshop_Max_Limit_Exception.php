<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Workshop_Max_Limit_Exception exception class.
 *
 * @package    Workshop
 * @author     Thibaud Schindler
 * @copyright  (c) 2009 VitrineApp Project
 * @license    http://kohanaphp.com/license.html
 */
class Workshop_Max_Limit_Exception extends Kohana_Exception{
	
	const exception_key = 'workshop.max_limit_of_authors_was_reached';
	
	public function __construct(ORM $object, $number_in_db, $number_to_add, $max_limit)
	{
		$object_desc = $object->object_name . '[' . $object->id . ']';
		parent::__construct(Workshop_Max_Limit_Exception::exception_key, $object_desc, $number_in_db, $number_to_add, $max_limit);
	}
}