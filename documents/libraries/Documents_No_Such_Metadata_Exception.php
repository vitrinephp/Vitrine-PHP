<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Documents_No_Such_Metadata_Exception exception class.
 *
 * @package    Documents
 * @author     Thibaud Schindler
 * @copyright  (c) 2009 VitrineApp Project
 * @license    http://kohanaphp.com/license.html
 */
class Documents_No_Such_Metadata_Exception extends Kohana_Exception{
	
	const exception_key = 'documents.metadata_does_not_exist';
	
	public function __construct(Document $document, $metadata_name)
	{
		$document_desc = $document->reference . '[' . $document->id . ']';
		parent::__construct(Documents_No_Such_Metadata_Exception::exception_key, $document_desc, $metadata_name);
	}
}