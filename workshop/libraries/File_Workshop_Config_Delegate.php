<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * File_Workshop_Config_Delegate_Core library class.
 *
 * @package    Workshop
 * @author     Thibaud Schindler
 * @copyright  (c) 2009 VitrineApp Project
 * @license    http://kohanaphp.com/license.html
 */
class File_Workshop_Config_Delegate_Core implements IWorkshop_Config_Delegate {

	public function max_authors_for(ORM_Core $object)
	{
		return $this->max_authors_for_object_name($object->object_name);
	}
	
	public function max_authors_for_object_name($object_name)
	{
		$toBeReturned = Kohana::config('workshop.rules.' . $object_name . '.max');
		if (isset($toBeReturned) && $toBeReturned != null && $toBeReturned != '')
		{
			return $toBeReturned;
		} else {
			return Kohana::config('workshop.default.max_of_authors');
		}
	}
	
	public function min_authors_for(ORM_Core $object)
	{
		return $this->min_authors_for_object_name($object->object_name);
	}
	
	public function min_authors_for_object_name($object_name)
	{
		$toBeReturned = Kohana::config('workshop.rules.' . $object_name . '.min');
		if (isset($toBeReturned) && $toBeReturned != null && $toBeReturned != '')
		{
			return $toBeReturned;
		} else {
			return Kohana::config('workshop.default.min_of_authors');
		}
	}
}