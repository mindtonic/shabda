<?php
/*
CREATE TABLE `shabda_errors` (
  `id` int(11) NOT NULL auto_increment,
  `ip` text collate utf8_unicode_ci NOT NULL,
  `port` int(11) NOT NULL,
  `server_name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `method` varchar(255) collate utf8_unicode_ci NOT NULL,
  `time` int(11) NOT NULL,
  `error` text collate utf8_unicode_ci NOT NULL,
  `referer` varchar(255) collate utf8_unicode_ci NOT NULL,
  `request` varchar(255) collate utf8_unicode_ci NOT NULL,
  `agent` varchar(255) collate utf8_unicode_ci NOT NULL,
  `self` varchar(255) collate utf8_unicode_ci NOT NULL,
  `user` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;
*/
class shabda_errors extends Model
{
	static public function save_error()
	{
		$registry = Registry::instance();
		$mapper = ModelMapper::instance();
		$error = $mapper->get_model('shabda_errors');
		
		$error->set_value('ip', $_SERVER['REMOTE_ADDR']);
		$error->set_value('ip', $_SERVER['REMOTE_PORT']);
		$error->set_value('server_name', $_SERVER['SERVER_NAME']);
		$error->set_value('method', $_SERVER['REQUEST_METHOD']);
		$error->set_value('time', $_SERVER['REQUEST_TIME']);
		$error->set_value('error', $registry->shabda_error());
		$error->set_value('referer', $_SERVER['HTTP_REFERER']);
		$error->set_value('request', $_SERVER['REQUEST_URI']);
		$error->set_value('agent', $_SERVER['HTTP_USER_AGENT']);
		$error->set_value('self', $_SERVER['PHP_SELF']);
		$error->set_value('user', $registry->user_id());
		$error->save();
	}
}

?>
