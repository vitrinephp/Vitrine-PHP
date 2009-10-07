<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Document model class.
 *
 * @package    Document
 * @author     Thibaud Schindler
 * @copyright  (c) 2009 VitrineApp Project
 * @license    http://kohanaphp.com/license.html

CREATE TABLE IF NOT EXISTS `documents` (
  `id` int(11) NOT NULL auto_increment,
  `insertion_date` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `insertion_user_id` int(11) NOT NULL,
  `url_identifier` varchar(256) NOT NULL,
  `reference` varchar(512) NOT NULL,
  `title` varchar(512) NOT NULL,
  `date` date NOT NULL,
  `description` text NOT NULL,
  `authors_names` varchar(512)) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `url_identifier` (`url_identifier`),
  KEY `insertion_user_id` (`insertion_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
*/
class Document_Model extends ORM {

	public $belongs_to = array('insertion_user' => 'user');
	public $has_many = array('document_metadata');
	
	public function __get($key)
	{
		try
		{
			$value = parent::__get($key);
		} catch (Exception $e) {
			$metadata = ORM::factory('document_metadata')->where('document_id', $this->id)->where('key', $key)->find();
			if ($metadata->loaded)
				$value = $metadata->value;
			else
				throw new Documents_No_Such_Metadata_Exception($this, $key);
		}
		return $value;
	}
	
	public function __set($key, $value)
	{
		try
		{
			$value = parent::__set($key, $value);
		} catch (Exception $e) {
			$metadata = ORM::factory('document_metadata')->where('document_id', $this->id)->where('key', $key)->find();
			if (!$metadata->loaded)
			{
				$metadata->key = $key;
				$metadata->document_id = $this->id;
			}
			$metadata->value = $value;
			$metadata->save();
		}
		return $value;
	}
	
	public function get_document_metadata_fields()
	{
		$database = new Database();
		$database->select('key');
		$database->from('document_metadata');
		$database->where('document_id', $this->id);
		$results = arr::rotate($database->get()->result_array(false));
		return $results['key'];
	}
	
	public function unique_key($id = NULL)
	{
		if (!empty($id) && is_string($id) && !ctype_digit($id))
		{
			return 'url_identifier';
		}
		return parent::unique_key($id);
	}
}