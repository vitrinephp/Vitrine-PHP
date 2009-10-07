<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Document_Metadata model class.
 *
 * @package    Document
 * @author     Thibaud Schindler
 * @copyright  (c) 2009 VitrineApp Project
 * @license    http://kohanaphp.com/license.html
 
CREATE TABLE IF NOT EXISTS `document_metadata` (
  `id` int(11) NOT NULL,
  `document_id` int(11) NOT NULL,
  `key` varchar(256) NOT NULL,
  `value` varchar(512) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `document_id` (`document_id`,`key`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

*/
class Document_Metadata_Model extends ORM {

	public $belongs_to = array('document');
	
	
}