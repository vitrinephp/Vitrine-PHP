<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * comment model class.
 *
 * @package    Comments
 * @author     Thibaud Schindler
 * @copyright  (c) 2009 VitrineApp Project
 * @license    http://kohanaphp.com/license.html
 *
 * SQL:
 * CREATE TABLE `comments` (
 * 	`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
 * 	`url_identifier` INT NOT NULL ,
 * 	`insertion_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
 * 	`insertion_user_id` INT NOT NULL ,
 * 	`title` VARCHAR( 512 ) NOT NULL ,
 * 	`content` TEXT NOT NULL ,
 * 	`moderated_state` VARCHAR( 256 ) NOT NULL ,
 * 	`underlying_object_name` VARCHAR( 256 ) NOT NULL ,
 * 	`underlying_object_id` INT NOT NULL ,
 * 	`in_reply_to_id` INT NOT NULL ,
 * 	INDEX ( `insertion_user_id` , `underlying_object_name` , `underlying_object_id` , `in_reply_to_id` ) ,
 * 	UNIQUE (`url_identifier`)
 * ) ENGINE = InnoDB 
*/
class Comment_Model extends ORM_Tree {

	public $belongs_to = array('insertion_user' => 'user');
	protected $ORM_Tree_children = 'comments';
	protected $ORM_Tree_parent_key = 'in_reply_to_id';

	public function __get($column)
	{
		if ($column == 'commented_object')
		{
			return $this->get_commented_object();
		}
		return parent::__get($column);
	}
	
	public function __set($column, $value)
	{
		if ($column == 'commented_object')
		{
			$this->set_commented_object($value);
			return;
		}
		parent::__set($column, $value);
	}
  
	public function get_commented_object()
	{
		if (isset($this->underlying_object_name) && $this->underlying_object_name != '')
		{
			return ORM::factory($this->underlying_object_name, $this->underlying_object_id);
		} else {
			return null;
		}		
	}
	
	public function set_commented_object(ORM $underlying_object)
	{
		if (comments::can_comment_on($underlying_object))
		{
			$this->underlying_object_name = $underlying_object->object_name;
			$this->underlying_object_id = $underlying_object->id;
		} else {
			throw new Comments_Not_Commentable_Object_Exception($underlying_object);
		}
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