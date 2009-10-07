<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Workshop library class.
 *
 * @package    Workshop
 * @author     Thibaud Schindler
 * @copyright  (c) 2009 VitrineApp Project
 * @license    http://kohanaphp.com/license.html
 */
class Workshop_Core {

	const AS_MIXED_ARRAY = 1;
	const AS_HIERARCHICAL_ARRAY = 2;
	
	const FULL_TRANSACTIONNAL = 1;
	const PARTIAL_TRANSACTIONNAL = 2;
	
	protected $config_delegate;
	
	public function __construct()
	{
		$config = Kohana::config('workshop.default');
		$config_delegate_class = $config['config_delegate'];
		$this->config_delegate = new $config_delegate_class();
	}
	
	public static function factory()
	{
		return new workshop();
	}

	public function add_as_authors_of(Array $authors, ORM $object, $mode = Workshop_Core::FULL_TRANSACTIONNAL)
	{
		switch ($mode)
		{
			case Workshop_Core::FULL_TRANSACTIONNAL:
				if ($this->is_possible_to_add_authors_to($object, count($authors)))
				{
					foreach($authors as $author)
					{
						$this->add_as_author_of($author, $object);
					}
				} else {
					throw new Workshop_Max_Limit_Exception($object, $this->get_number_of_authors_of($object), count($authors), $this->config_delegate->max_authors_for($object));
				}
				break;
			case Workshop_Core::PARTIAL_TRANSACTIONNAL:
				foreach($authors as $author)
				{
					$this->add_as_author_of($author, $object);
				}
				break;
			default:
				break;
		}
	}
	
	public function add_as_author_of(User_Model $author, ORM $object)
	{
		if ($this->is_possible_to_add_authors_to($object))
		{
			Benchmark::start('add_as_author_of');
			try{
				$database = new Database();
				$database->from('workshop_data');
				$database->set(array
					(
						'object_name' => $object->object_name,
						'user_id' => $author->id,
						'object_id' => $object->id,
					));
				$database->insert();
			} catch (Kohana_Database_Exception $e) {
				if (strstr($e->getMessage(),'Duplicate entry'))
				{
					throw new Workshop_Duplicate_Author_Exception($author, $object);
				} else {
					throw $e;
				}
			}
			Benchmark::stop('add_as_author_of');
		} else {
			throw new Workshop_Max_Limit_Exception($object, $this->get_number_of_authors_of($object), 1, $this->config_delegate->max_authors_for($object));
		}
	}
	
	public function get_authors_of(ORM $object)
	{
		$database = new Database();
		$database->select('user_id');
		$database->from('workshop_data');
		$database->where(array
			(
				'object_name' => $object->object_name,
				'object_id' => $object->id,
			));
		$database->orderby('user_id', 'ASC');
		$results = $database->get();
		$array_of_authors = array();
		foreach ($results as $row)
		{
			$array_of_authors[] = ORM::factory('User', $row->user_id);
		}
		return $array_of_authors;
	}
	
	public function get_number_of_authors_of(ORM $object)
	{
		$database = new Database();
		$database->select('COUNT(user_id)');
		$database->from('workshop_data');
		$database->where(array
			(
				'object_name' => $object->object_name,
				'object_id' => $object->id,
			));
		$row = $database->get()->result_array(FALSE);
		return $row[0]['COUNT(user_id)'];
	}
	public function get_objects_created_by(User_model $author, $mode = Workshop_Core::AS_MIXED_ARRAY)
	{
		return $this->get_objects_of_this_kind_created_by($author, NULL, $mode);
	}
	
	public function get_objects_of_this_kind_created_by(User_model $author, $object_names, $mode = Workshop_Core::AS_MIXED_ARRAY)
	{
		$database = new Database();
		$database->select('object_name, object_id');
		$database->from('workshop_data');
		$database->where(array
			(
				'user_id' => $author->id,
			));
		if ($object_names != null)
		{
			if (!is_array($object_names))
			{
				$database->where('object_name', $object_names);
			} else {
				$database->in('object_name', $object_names);
			}
		}
		$database->orderby(array('object_name' => 'ASC', 'object_id' => 'ASC'));
		$results = $database->get();
		$array_of_objects = array();
		
		switch ($mode)
		{
			case Workshop_Core::AS_MIXED_ARRAY:
				foreach ($results as $row)
				{
					$array_of_objects[] = ORM::factory($row->object_name, $row->object_id);
				}
				break;
			case Workshop_Core::AS_HIERARCHICAL_ARRAY:
				foreach ($results as $row)
				{
					$array_of_objects[$row->object_name][] = ORM::factory($row->object_name, $row->object_id);
				}
				break;
			default:
				break;
		}
		return $array_of_objects;
	}
	
	public function remove_authors_of(Array $authors, ORM $object, $mode = Workshop_Core::FULL_TRANSACTIONNAL)
	{
		switch ($mode)
		{
			case Workshop_Core::FULL_TRANSACTIONNAL:
				if ($this->is_possible_to_remove_authors_to($object, count($authors)))
				{
					foreach($authors as $author)
					{
						$this->remove_author_of($author, $object);
					}
				} else {
					throw new Workshop_Min_Limit_Exception($object, $this->get_number_of_authors_of($object), count($authors), $this->config_delegate->min_authors_for($object));
				}
				break;
			case Workshop_Core::PARTIAL_TRANSACTIONNAL:
				foreach($authors as $author)
				{
					$this->remove_author_of($author, $object);
				}
				break;
			default:
				break;
		}
	}
	
	public function remove_author_of(User_Model $author, ORM $object)
	{
		if ($this->is_possible_to_remove_authors_to($object))
		{
			$database = new Database();
			$database->from('workshop_data');
			$database->where(array
				(
					'user_id' => $author->id,
					'object_name' => $object->object_name,
					'object_id' => $object->id,
				));
			$database->delete();
		} else {
			throw new Workshop_Min_Limit_Exception($object, $this->get_number_of_authors_of($object), 1, $this->config_delegate->min_authors_for($object));
		}
	}
	
	public function is_possible_to_add_authors_to(ORM $object, $number = 1)
	{
		if ($this->config_delegate->max_authors_for($object) >= $this->get_number_of_authors_of($object) + $number)
		{
			return true;
		}
		return false;
	}
	
	public function is_possible_to_remove_authors_to(ORM $object, $number = 1)
	{
		if ($this->config_delegate->min_authors_for($object) <= $this->get_number_of_authors_of($object) - $number)
		{
			return true;
		}
		return false;
	}
}