<?php
/**
*
* Hide Bots extension for the phpBB Forum Software package.
*
* @copyright 2016 Rich McGirr (RMcGirr83)
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/
namespace rmcgirr83\hidebots\event;

/**
* @ignore
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class listener implements EventSubscriberInterface
{
	/** @var \phpbb\auth\auth */
	protected $auth;

	/* @var \phpbb\config\config */
	protected $config;

	/**
	* Constructor
	*
	*/
	public function __construct (
			\phpbb\auth\auth $auth,
			\phpbb\config\config $config)
	{
		$this->auth = $auth;
		$this->config = $config;
	}

	static public function getSubscribedEvents ()
	{
		return array(
			'core.obtain_users_online_string_sql'	=>	'hide_bots',
		);
	}

	public function hide_bots ($event)
	{
		// only run for non admins
		if (!$this->auth->acl_get('a_'))
		{
			$sql_ary = $event['sql_ary'];
			$sql_ary['WHERE'] .= ' AND u.user_type <> ' . USER_IGNORE;
			$event['sql_ary'] = $sql_ary;
		}
	}
}
