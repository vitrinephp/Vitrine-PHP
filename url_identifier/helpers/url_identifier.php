<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * url_identifier helper class.
 *
 * @package    url_identifier
 * @author     Thibaud Schindler
 * @copyright  (c) 2009 VitrineApp Project
 * @license    http://kohanaphp.com/license.html
 */
class url_identifier_Core {

	public static function get_next_available_url_identifier(ORM $object, $url_identifier)
	{
		$database = new Database();
		$database->select('COUNT(*)');
		$database->from($object->table_name);
		$database->like('url_identifier', $url_identifier . '%', FALSE);
		$row = $database->get()->result_array(FALSE);
		$count = $row[0]['COUNT(*)'];
		if ($count > 0)
		{
			$url_identifier .= '-' . ($count + 1);
		}
		return $url_identifier;
	}
}