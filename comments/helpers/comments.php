<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * comments helper class.
 *
 * @package    Comment
 * @author     Thibaud Schindler
 * @copyright  (c) 2009 VitrineApp Project
 * @license    http://kohanaphp.com/license.html
 */
class comments_Core {

	public static function can_comment_on(ORM $object)
	{
		if ($object instanceof ICommentable)
		{
			return $object->is_commentable();
		}
		return false;
	}
	
	public static function get_comments_for(ORM $object)
	{
		return ORM::factory('comment')->where(array
			(
				'underlying_object_name' => $object->object_name,
				'underlying_object_id' => $object->id,
			));
	}
	
	public static function make_a_comment_to(ORM $object, $insertion_user, $title, $content, $moderated_state = 'DEFAULT', Comment_Model $in_reply_to = NULL, $url_identifier = '')
	{
		$comment_to_insert = ORM::factory('comment');
		$comment_to_insert->commented_object = $object;
		$comment_to_insert->insertion_user_id = $insertion_user->id;
		Workshop::factory()->add_as_author_of($insertion_user, $object);
		$comment_to_insert->title = $title;
		$comment_to_insert->content = $content;
		$comment_to_insert->moderated_state = $moderated_state;
		if ($in_reply_to != NULL)
			$comment_to_insert->in_reply_to_id = $in_reply_to->id;
		if ($url_identifier == '')
			$url_identifier = comments::make_url_identifier($comment_to_insert);
		$comment_to_insert->url_identifier = url_identifier::get_next_available_url_identifier($comment_to_insert, $url_identifier);
		$comment_to_insert->save();
	}
	
	public static function moderate_comment(Comment_Model $object, $moderated_state, $new_content = NULL)
	{
		$object->moderated_state = $moderated_state;
		//TODO: mail à l'intéressé ...
		if ($new_content != NULL)
		{
			$object->content = $new_content;
		}
		$object->save();
	}
	
	public static function remove_comment(Comment_Model $object)
	{
		$object->delete();
	}
	
	public static function make_url_identifier(Comment_Model $comment)
	{
		$url_identifier = '';
		if (isset($comment->commented_object->url_identifier))
		{
			$url_identifier = $comment->commented_object->url_identifier;
		} else {
			$url_identifier = url::title($comment->underlying_object_name . "-" . $comment->underlying_object_id . "-" . $comment->title);
		}
		return $url_identifier;
	}
}