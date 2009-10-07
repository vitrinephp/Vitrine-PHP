<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * IWorkshop_Config_Delegate library interface.
 *
 * @package    Workshop
 * @author     Thibaud Schindler
 * @copyright  (c) 2009 VitrineApp Project
 * @license    http://kohanaphp.com/license.html
 */
interface IWorkshop_Config_Delegate {

	public function max_authors_for(ORM_Core $object);
	public function max_authors_for_object_name($object_name);
	public function min_authors_for(ORM_Core $object);
	public function min_authors_for_object_name($object_name);
}