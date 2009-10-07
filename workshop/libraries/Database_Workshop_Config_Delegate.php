<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Database_Workshop_Config_Delegate_Core library class.
 *
 * @package    Workshop
 * @author     Thibaud Schindler
 * @copyright  (c) 2009 VitrineApp Project
 * @license    http://kohanaphp.com/license.html
 */
class Database_Workshop_Config_Delegate_Core implements IWorkshop_Config_Delegate {

	public function max_authors_for(ORM_Core $object)
	{
		return $this->max_authors_for_object_name($object->object_name);
	}
	
	public function max_authors_for_object_name($object_name)
	{
		$database = new Database();
		$database->select('value');
		$database->from('workshop_config');
		$database->where(array
			(
				'object_name' => $object_name,
				'rule_name' => 'max',
			));
		$results = $database->get()->result_array(FALSE);
		if (count($results) == 0)
		{
			return Kohana::config('workshop.default.max_of_authors');
		} else {
			return $results[0]['value'];
		}
	}
	
	public function min_authors_for(ORM_Core $object)
	{
		return $this->min_authors_for_object_name($object->object_name);
	}
	
	public function min_authors_for_object_name($object_name)
	{
		$database = new Database();
		$database->select('value');
		$database->from('workshop_config');
		$database->where(array
			(
				'object_name' => $object_name,
				'rule_name' => 'min',
			));
		$results = $database->get()->result_array(FALSE);
		if (count($results) == 0)
		{
			return Kohana::config('workshop.default.min_of_authors');
		} else {
			return $results[0]['value'];
		}
	}
}