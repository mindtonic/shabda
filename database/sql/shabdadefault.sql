-- INITIAL SHABDA DATABASE FILES

-- --------------------------------------------------------
ALTER DATABASE `shabda_development` DEFAULT CHARACTER SET utf8;

-- --------------------------------------------------------
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


CREATE TABLE `user_tracker` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `user_id` int(11) default NULL,
  `guest` tinyint(1) NOT NULL default '0',
  `controller` varchar(100) NOT NULL,
  `action` varchar(100) NOT NULL,
  `params` varchar(255) default NULL,
  `ip` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `user_id` (`user_id`),
  KEY `ip` (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `role_id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `controller` varchar(100) NOT NULL,
  `active` tinyint(1) NOT NULL default '1',
  `order` int(3) NOT NULL default '999',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `role_id` (`role_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `role_id`, `name`, `controller`, `active`, `order`, `created_at`, `updated_at`) VALUES
(1, 1, 'Users', 'users', 1, 1, '2008-08-26 10:22:08', '2009-03-13 11:55:52'),
(2, 1, 'Navigation', 'navigation', 1, 2, '2008-08-26 10:22:26', '2009-03-13 11:55:46'),
(3, 2, 'Content', 'content', 1, 3, '2008-08-26 10:49:45', '2009-03-13 11:55:59'),
(4, 1, 'Admin Navigation', 'admin', 1, 99, '2008-08-26 10:55:59', '2009-03-13 11:47:55'),
(5, 1, 'Roles', 'roles', 1, 100, '2008-08-26 10:56:12', '2009-03-13 11:57:07'),
(6, 2, 'Pages', 'pages', 1, 4, '2009-03-13 11:47:43', '2009-03-13 11:56:03'),
(7, 3, 'Forums', 'forums', 1, 5, '2009-03-13 11:48:16', '2009-03-13 11:56:09'),
(8, 4, 'Galleries', 'galleries', 1, 6, '2009-03-13 11:48:54', '2009-03-13 11:56:14'),
(9, 3, 'Guest Book', 'guest_book', 1, 7, '2009-03-13 11:49:18', '2009-03-13 11:56:24'),
(10, 2, 'Link Types', 'link_types', 1, 8, '2009-03-13 11:49:48', '2009-03-13 11:49:48'),
(11, 2, 'Links', 'links', 1, 9, '2009-03-13 11:49:59', '2009-03-13 11:49:59'),
(12, 2, 'Mailing List', 'mailing_list', 1, 11, '2009-03-13 11:50:31', '2009-03-13 11:56:37'),
(13, 2, 'Musicians', 'musicians', 1, 12, '2009-03-13 11:50:44', '2009-03-13 11:56:43'),
(22, 2, 'News', 'news', 1, 5, '2009-03-16 17:23:09', '2009-03-16 17:23:59'),
(16, 5, 'Recordings', 'recordings', 1, 15, '2009-03-13 11:51:55', '2009-03-16 16:51:01'),
(17, 6, 'Tour Artists', 'tour_artists', 1, 16, '2009-03-13 11:53:30', '2009-03-13 11:53:30'),
(18, 6, 'Tour Venues', 'tour_venues', 1, 17, '2009-03-13 11:53:45', '2009-03-13 11:53:45'),
(19, 6, 'Tour Dates', 'tour_dates', 1, 18, '2009-03-13 11:54:04', '2009-03-13 11:54:24');

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

DROP TABLE IF EXISTS `blogs`;
CREATE TABLE IF NOT EXISTS `blogs` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(150) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `views` int(11) NOT NULL default '0',
  `active` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `blogs`
--

INSERT INTO `blogs` (`id`, `user_id`, `name`, `description`, `created_at`, `updated_at`, `views`, `active`) VALUES
(1, 1, 'The Band Blog', 'Where we talk about stuff.', '2009-03-16 17:24:40', '2009-03-16 17:24:40', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `blog_comments`
--

DROP TABLE IF EXISTS `blog_comments`;
CREATE TABLE IF NOT EXISTS `blog_comments` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `blog_entry_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `blog_entry_id` (`blog_entry_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `blog_comments`
--


-- --------------------------------------------------------

--
-- Table structure for table `blog_entries`
--

DROP TABLE IF EXISTS `blog_entries`;
CREATE TABLE IF NOT EXISTS `blog_entries` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `blog_id` int(11) NOT NULL,
  `blog_topic_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `views` int(11) NOT NULL default '0',
  `comments_count` int(11) NOT NULL default '0',
  `active` tinyint(1) NOT NULL default '1',
  `feature` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `blog_id` (`blog_id`),
  KEY `blog_topic_id` (`blog_topic_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `blog_entries`
--


-- --------------------------------------------------------

--
-- Table structure for table `blog_topics`
--

DROP TABLE IF EXISTS `blog_topics`;
CREATE TABLE IF NOT EXISTS `blog_topics` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `blog_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `blog_id` (`blog_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `blog_topics`
--


-- --------------------------------------------------------

--
-- Table structure for table `change_log`
--

DROP TABLE IF EXISTS `change_log`;
CREATE TABLE IF NOT EXISTS `change_log` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `entry` text NOT NULL,
  `version` varchar(5) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

DROP TABLE IF EXISTS `contact`;
CREATE TABLE IF NOT EXISTS `contact` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `contact`
--


-- --------------------------------------------------------

--
-- Table structure for table `content`
--

DROP TABLE IF EXISTS `content`;
CREATE TABLE IF NOT EXISTS `content` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `handle` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `edited_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `handle` (`handle`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `content`
--


-- --------------------------------------------------------

--
-- Table structure for table `forums`
--

DROP TABLE IF EXISTS `forums`;
CREATE TABLE IF NOT EXISTS `forums` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `description` varchar(100) default NULL,
  `created_at` datetime NOT NULL default '0000-00-00 00:00:00',
  `updated_at` datetime default NULL,
  `topics_count` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `forums`
--

INSERT INTO `forums` (`id`, `name`, `description`, `created_at`, `updated_at`, `topics_count`) VALUES
(1, 'Shabda Forum', '', '2009-03-16 17:07:01', '2009-03-16 17:07:01', 0);

-- --------------------------------------------------------

--
-- Table structure for table `forum_posts`
--

DROP TABLE IF EXISTS `forum_posts`;
CREATE TABLE IF NOT EXISTS `forum_posts` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `topic_id` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  `title` varchar(120) NOT NULL,
  `description` text NOT NULL,
  `created_at` datetime NOT NULL default '0000-00-00 00:00:00',
  `updated_at` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `topic` (`topic_id`),
  KEY `user` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `forum_posts`
--

INSERT INTO `forum_posts` (`id`, `topic_id`, `user_id`, `title`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Welcome To Shabda', 'This content management system can do anything you want, but in it''s current form it is being designed to handle a band''s website.', '2009-03-16 17:10:04', '2009-03-16 17:10:04');

-- --------------------------------------------------------

--
-- Table structure for table `forum_topics`
--

DROP TABLE IF EXISTS `forum_topics`;
CREATE TABLE IF NOT EXISTS `forum_topics` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `forum_id` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  `name` varchar(50) NOT NULL default '',
  `created_at` datetime NOT NULL default '0000-00-00 00:00:00',
  `updated_at` datetime default NULL,
  `posts_count` int(11) NOT NULL default '0',
  `views` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `user` (`user_id`),
  KEY `forum` (`forum_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `forum_topics`
--

INSERT INTO `forum_topics` (`id`, `forum_id`, `user_id`, `name`, `created_at`, `updated_at`, `posts_count`, `views`) VALUES
(1, 1, 1, 'Shabda FAQ', '2009-03-16 17:10:04', '2009-03-16 17:10:04', 0, 4);

-- --------------------------------------------------------

--
-- Table structure for table `galleries`
--

DROP TABLE IF EXISTS `galleries`;
CREATE TABLE IF NOT EXISTS `galleries` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(120) NOT NULL,
  `subtitle` varchar(120) default NULL,
  `description_title` varchar(120) NOT NULL,
  `description` text,
  `views` int(11) NOT NULL default '0',
  `active` tinyint(1) NOT NULL default '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `galleries`
--

INSERT INTO `galleries` (`id`, `name`, `subtitle`, `description_title`, `description`, `views`, `active`, `created_at`, `updated_at`) VALUES
(1, 'Photo Gallery', '', '', '<br _moz_editor_bogus_node="TRUE" _moz_dirty=""/>', 0, 1, '2009-03-16 17:11:58', '2009-03-16 17:11:58');

-- --------------------------------------------------------

--
-- Table structure for table `gallery_slides`
--

DROP TABLE IF EXISTS `gallery_slides`;
CREATE TABLE IF NOT EXISTS `gallery_slides` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `gallery_id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `filepath` varchar(255) NOT NULL,
  `filesize` int(11) NOT NULL,
  `mime_type` varchar(20) NOT NULL,
  `width` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  `size_tags` varchar(100) NOT NULL,
  `parent_id` int(11) default NULL,
  `handle` varchar(100) NOT NULL default 'image',
  `name` varchar(255) NOT NULL,
  `description` text,
  `order` int(11) NOT NULL default '0',
  `active` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `gallery_id` (`gallery_id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `gallery_slides`
--

INSERT INTO `gallery_slides` (`id`, `gallery_id`, `filename`, `filepath`, `filesize`, `mime_type`, `width`, `height`, `size_tags`, `parent_id`, `handle`, `name`, `description`, `order`, `active`, `created_at`, `updated_at`) VALUES
(1, 1, 'ThingsThatMakeYouGoHmmmm.jpeg', 'images/galleries/1/1/ThingsThatMakeYouGoHmmmm.jpeg', 18610, 'image/jpeg', 460, 345, 'width="460" height="345"', 0, '', 'Things That Make You Go Hmmmm....', '', 1, 1, '2009-03-16 17:12:27', '2009-03-16 17:12:28'),
(2, 0, 'ThingsThatMakeYouGoHmmmm_TNAIL.jpeg', 'images/galleries/1/1/ThingsThatMakeYouGoHmmmm_TNAIL.jpeg', 2319, 'image/jpeg', 100, 75, 'width="100" height="75"', 1, 'thumbnail', 'Things That Make You Go Hmmmm....', '', 0, 0, '2009-03-16 17:12:28', '2009-03-16 17:12:28'),
(3, 1, 'ABigWhatWhat.jpeg', 'images/galleries/1/3/ABigWhatWhat.jpeg', 26431, 'image/jpeg', 460, 345, 'width="460" height="345"', 0, '', 'A Big What What!', '', 2, 1, '2009-03-16 17:12:50', '2009-03-16 17:12:52'),
(4, 0, 'ABigWhatWhat_TNAIL.jpeg', 'images/galleries/1/3/ABigWhatWhat_TNAIL.jpeg', 2775, 'image/jpeg', 100, 75, 'width="100" height="75"', 3, 'thumbnail', 'A Big What What!', '', 0, 0, '2009-03-16 17:12:52', '2009-03-16 17:12:52');

-- --------------------------------------------------------

--
-- Table structure for table `guest_book`
--

DROP TABLE IF EXISTS `guest_book`;
CREATE TABLE IF NOT EXISTS `guest_book` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `description` text NOT NULL,
  `name` varchar(120) NOT NULL,
  `email` varchar(255) NOT NULL,
  `location` varchar(120) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `ip` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `guest_book`
--


-- --------------------------------------------------------

--
-- Table structure for table `links`
--

DROP TABLE IF EXISTS `links`;
CREATE TABLE IF NOT EXISTS `links` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `link_type_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `url` varchar(255) NOT NULL,
  `order` int(11) NOT NULL default '0',
  `active` tinyint(1) NOT NULL default '1',
  `edited_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `link_type_id` (`link_type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `links`
--


-- --------------------------------------------------------

--
-- Table structure for table `link_types`
--

DROP TABLE IF EXISTS `link_types`;
CREATE TABLE IF NOT EXISTS `link_types` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `link_types`
--


-- --------------------------------------------------------

--
-- Table structure for table `mailing_list`
--

DROP TABLE IF EXISTS `mailing_list`;
CREATE TABLE IF NOT EXISTS `mailing_list` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `email` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `mailing_list`
--


-- --------------------------------------------------------

--
-- Table structure for table `musicians`
--

DROP TABLE IF EXISTS `musicians`;
CREATE TABLE IF NOT EXISTS `musicians` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `instruments` varchar(255) NOT NULL,
  `equipment` text,
  `url` varchar(255) default NULL,
  `email` varchar(255) default NULL,
  `links` text,
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `current` tinyint(1) NOT NULL default '1',
  `active` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `musicians`
--


-- --------------------------------------------------------

--
-- Table structure for table `musicians_images`
--

DROP TABLE IF EXISTS `musicians_images`;
CREATE TABLE IF NOT EXISTS `musicians_images` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `musicians_id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `filepath` varchar(255) NOT NULL,
  `filesize` int(11) NOT NULL,
  `mime_type` varchar(20) NOT NULL,
  `width` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  `size_tags` varchar(100) NOT NULL,
  `parent_id` int(11) default NULL,
  `handle` varchar(100) NOT NULL default 'image',
  `name` varchar(255) NOT NULL,
  `description` text,
  `order` int(11) NOT NULL default '0',
  `active` tinyint(1) NOT NULL,
  `edited_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `musicians_id` (`musicians_id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `musicians_images`
--


-- --------------------------------------------------------

--
-- Table structure for table `navigation`
--

DROP TABLE IF EXISTS `navigation`;
CREATE TABLE IF NOT EXISTS `navigation` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `parent_id` int(11) default NULL,
  `name` varchar(20) NOT NULL,
  `controller` varchar(100) NOT NULL,
  `action` varchar(100) default NULL,
  `item` varchar(15) default NULL,
  `link` varchar(255) default NULL,
  `section` varchar(100) default NULL,
  `description` varchar(100) default NULL,
  `active` tinyint(1) NOT NULL default '1',
  `order` int(3) NOT NULL default '999',
  PRIMARY KEY  (`id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `navigation`
--

INSERT INTO `navigation` (`id`, `parent_id`, `name`, `controller`, `action`, `item`, `link`, `section`, `description`, `active`, `order`) VALUES
(1, 0, 'Home', 'home', '', '', '', '', '', 1, 0),
(2, 0, 'Contact', 'contact', '', '', '', '', '', 1, 10),
(3, 5, 'Forums', 'forums', '', '', '', '', '', 1, 1),
(4, 7, 'Galleries', 'galleries', '', '', '', '', '', 1, 1),
(5, 0, 'Community', '', '', '', '', '', '', 1, 2),
(6, 5, 'Guest Book', 'guest_book', '', '', '', '', '', 1, 2),
(7, 0, 'Media', '', '', '', '', '', '', 1, 3),
(8, 7, 'Recordings', 'recordings', '', '', '', '', '', 1, 2),
(9, 0, 'Shows', '', '', '', '', '', '', 1, 4),
(10, 9, 'Artists', 'tour_artists', '', '', '', '', '', 1, 1),
(11, 9, 'Venues', 'tour_venues', '', '', '', '', '', 1, 2),
(12, 9, 'Dates', 'tour_dates', '', '', '', '', '', 1, 3),
(13, 0, 'News', 'news', '', '', '', '', '', 1, 5);

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
CREATE TABLE IF NOT EXISTS `news` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `sticky` tinyint(1) NOT NULL default '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `edited_by` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `title`, `description`, `sticky`, `created_at`, `updated_at`, `created_by`, `edited_by`) VALUES
(1, 'We''re #1!', 'Oh yeah, we are.', 0, '2009-03-16 17:24:13', '2009-03-16 17:24:13', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `news_images`
--

DROP TABLE IF EXISTS `news_images`;
CREATE TABLE IF NOT EXISTS `news_images` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `news_id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `filepath` varchar(255) NOT NULL,
  `filesize` int(11) NOT NULL,
  `mime_type` varchar(20) NOT NULL,
  `width` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  `size_tags` varchar(100) NOT NULL,
  `parent_id` int(11) default NULL,
  `handle` varchar(100) NOT NULL default 'image',
  `name` varchar(255) NOT NULL,
  `description` text,
  `order` int(11) NOT NULL default '0',
  `active` tinyint(1) NOT NULL,
  `edited_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `news_id` (`news_id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `news_images`
--


-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `link` varchar(64) NOT NULL default '',
  `title` varchar(100) NOT NULL default '',
  `subtitle` varchar(160) default NULL,
  `description` text NOT NULL,
  `meta_keywords` varchar(255) default NULL,
  `meta_description` varchar(255) default NULL,
  `url` varchar(255) NOT NULL default '',
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_at` datetime NULL,
  `updated_by` int(11) NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `link` (`link`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pages`
--


-- --------------------------------------------------------

--
-- Table structure for table `quotes`
--

DROP TABLE IF EXISTS `quotes`;
CREATE TABLE IF NOT EXISTS `quotes` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `quote` text NOT NULL,
  `source` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `views` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `quotes`
--

INSERT INTO `quotes` (`id`, `quote`, `source`, `created_at`, `updated_at`, `views`) VALUES
(1, 'I don''t know where my creativity comes from, and I don''t want to know.', 'Johnny Carson', '2007-11-14 14:24:55', '2007-11-14 14:24:55', 126),
(6, 'Learn to see... listen... and think for yourself.', 'Malcolm X', '2007-11-14 14:42:31', '2007-11-14 14:42:31', 119),
(7, 'You cannot separate peace from freedom, because no one can be at peace until he has freedom.', 'Malcolm X', '2007-11-14 14:43:16', '2007-11-14 14:43:16', 116),
(8, 'An idealist is one who, on noticing that a rose smells better than a cabbage, concludes that it will also make better soup.', 'H.L. Mencken', '2007-11-14 14:50:04', '2007-11-14 14:50:04', 140),
(9, 'The only way to keep your health is to eat what you don''t want, drink what you don''t like and do what you''d rather not.', 'Mark Twain', '2007-11-14 14:51:38', '2007-11-14 14:51:38', 128),
(10, 'Never underestimate a man who overestimates himself.', 'Franklin D. Roosevelt', '2007-11-14 14:52:57', '2007-11-14 14:52:57', 124),
(12, 'Anyone can make the simple complicated. Creativity is making the complicated simple.', 'Charles Mingus', '2007-11-14 17:58:16', '2007-11-14 17:58:16', 116),
(14, 'The fool doth think himself wise, but the wise man knows himself to be a fool.', 'William Shakespeare', '2007-11-19 10:21:47', '2007-11-19 10:21:47', 141),
(15, 'Men occasionally stumble over the truth, but most of them pick themselves up and hurry off as if nothing ever happened.', 'Winston Churchill', '2007-11-19 10:23:32', '2007-11-19 10:23:32', 121),
(19, 'To succeed, jump as quickly at opportunities as you do at conclusions.', 'Benjamin Franklin', '2007-11-25 02:12:38', '2007-11-25 02:12:38', 124),
(18, 'They who would give up an essential liberty for temporary security, deserve neither liberty or security.', 'Benjamin Franklin', '2007-11-25 02:11:29', '2007-11-25 02:11:29', 126),
(20, 'Where liberty is, there is my country.', 'Benjamin Franklin', '2007-11-25 02:13:08', '2007-11-25 02:13:08', 132),
(21, 'There are more love songs than anything else. If songs could make you do something we''d all love one another.', 'Frank Zappa', '2007-11-25 02:14:42', '2007-11-25 02:14:42', 110),
(22, 'Music, in performance, is a type of sculpture. The air in the performance is sculpted into something.', 'Frank Zappa', '2007-11-25 02:15:05', '2007-11-25 02:15:05', 119),
(23, 'Music was my refuge. I could crawl into the space between the notes and curl my back to loneliness.', 'Maya Angelou', '2007-11-25 02:15:41', '2007-11-25 02:15:41', 98),
(24, 'The memory of things gone is important to a jazz musician. Things like old folks singing in the moonlight in the back yard on a hot night or something said long ago.', 'Louis Armstrong', '2007-11-25 02:16:09', '2007-11-25 02:16:09', 126),
(26, 'Discontent is the first step in the progress of a man or a nation.', 'Oscar Wilde', '2007-11-25 14:59:24', '2007-11-25 14:59:24', 108),
(27, 'Experience is the name everyone gives to their mistakes.', 'Oscar Wilde', '2007-11-25 15:00:13', '2007-11-25 15:00:13', 127),
(28, 'The well-bred contradict other people.  The wise contradict themselves.', 'Oscar Wilde', '2007-11-25 15:01:05', '2007-11-25 15:01:05', 112),
(29, 'You can''t change the music of your soul.', 'Katherine Hepburn', '2007-11-25 15:01:40', '2007-11-25 15:01:40', 109),
(31, 'We should all be obliged to appear before a board every five years to justify our existence... on pain of liquidation.', 'George Bernard Shaw', '2007-11-25 15:03:59', '2007-11-25 15:03:59', 121),
(32, 'The power of accurate observation is commonly called cynicism by those who have not got it.', 'George Bernard Shaw', '2007-11-25 15:04:50', '2007-11-25 15:04:50', 118),
(46, 'A goal is a dream with a deadline.', 'igvita.com', '2008-02-28 03:39:18', '2008-02-28 03:39:18', 35),
(34, 'Everybody talks about the weather and nobody does anything about it.', 'Carl Sandburg', '2007-11-25 15:07:00', '2007-11-25 15:07:00', 118),
(35, 'May you live to eat the hen that scratches on your grave.', 'Carl Sandburg', '2007-11-25 15:07:31', '2007-11-25 15:07:31', 111),
(37, 'Why is the bribe-taker convicted so often and the bribe-giver so seldom?', 'Carl Sandburg', '2007-11-25 15:09:10', '2007-11-25 15:09:10', 116),
(39, 'Let us rise to a standard to which the wise and honest can repair?', 'George Washington', '2007-11-25 15:10:44', '2007-11-25 15:10:44', 119),
(40, 'In a free and republican government, you cannot restrain the voice of the multitude.', 'George Washington', '2007-11-25 15:11:48', '2007-11-25 15:11:48', 118),
(41, 'It is only after time has been given for cool and deliberate reflection that the real voice of the people can be known.', 'George Washington', '2007-11-25 15:12:31', '2007-11-28 16:27:18', 116),
(42, 'Talkers are not good doers.', 'Wiliam Shakespeare', '2007-11-25 15:13:58', '2007-11-25 15:13:58', 113),
(43, 'Circumstance has no value. It is how one relates to a situation that has value. All true meaning lies in the personal relationship to a phenomenon, what it means to you.', 'Chris McCandless', '2007-12-09 04:15:58', '2007-12-09 04:15:58', 119),
(44, 'I was surprised, as always, by how easy the act of leaving was, and how good it felt. The world was suddenly rich with possibility.', 'Jon Krakauer', '2007-12-09 04:16:21', '2007-12-09 04:16:21', 119),
(45, 'But for the sound of heaven have I seen all the colors of the Earth.', 'Jay Sanders', '2008-01-17 21:42:24', '2008-01-17 21:42:24', 103);

-- --------------------------------------------------------

--
-- Table structure for table `recordings`
--

DROP TABLE IF EXISTS `recordings`;
CREATE TABLE IF NOT EXISTS `recordings` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `artist` varchar(255) default NULL,
  `year` int(4) default NULL,
  `purchase_link` varchar(255) NOT NULL,
  `download_link` varchar(255) NOT NULL,
  `description` text,
  `tracks` text,
  `personnel` text,
  `comments` text,
  `order` int(11) NOT NULL default '999',
  `active` tinyint(1) NOT NULL,
  `edited_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `recordings`
--

INSERT INTO `recordings` (`id`, `name`, `artist`, `year`, `purchase_link`, `download_link`, `description`, `tracks`, `personnel`, `comments`, `order`, `active`, `edited_by`, `created_at`, `updated_at`) VALUES
(2, 'Rick & Rocky', 'Snake Oil Medicine Show', 0, '', '', '', '', '', '', 1, 1, 1, '2009-03-16 17:13:51', '2009-03-16 17:13:51');

-- --------------------------------------------------------

--
-- Table structure for table `recordings_images`
--

DROP TABLE IF EXISTS `recordings_images`;
CREATE TABLE IF NOT EXISTS `recordings_images` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `recordings_id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `filepath` varchar(255) NOT NULL,
  `filesize` int(11) NOT NULL,
  `mime_type` varchar(20) NOT NULL,
  `width` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  `size_tags` varchar(100) NOT NULL,
  `parent_id` int(11) default NULL,
  `handle` varchar(100) NOT NULL default 'image',
  `name` varchar(255) NOT NULL,
  `description` text,
  `order` int(11) NOT NULL default '0',
  `active` tinyint(1) NOT NULL,
  `edited_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `recordings_id` (`recordings_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `recordings_images`
--

INSERT INTO `recordings_images` (`id`, `recordings_id`, `filename`, `filepath`, `filesize`, `mime_type`, `width`, `height`, `size_tags`, `parent_id`, `handle`, `name`, `description`, `order`, `active`, `edited_by`, `created_at`, `updated_at`) VALUES
(3, 2, 'RickRocky.gif', 'images/recordings/2/3/RickRocky.gif', 7751, 'image/gif', 126, 125, 'width="126" height="125"', 0, '', 'Rick & Rocky', '', 0, 0, 1, '2009-03-16 17:13:51', '2009-03-16 17:13:51'),
(4, 0, 'RickRocky_TNAIL.gif', 'images/recordings/2/3/RickRocky_TNAIL.gif', 5894, 'image/gif', 100, 99, 'width="100" height="99"', 3, 'thumbnail', 'Rick & Rocky', '', 0, 0, 1, '2009-03-16 17:13:51', '2009-03-16 17:13:51');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL auto_increment,
  `role` varchar(20) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `role` (`role`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role`) VALUES
(1, 'super'),
(2, 'editor'),
(3, 'moderator'),
(4, 'gallery manager'),
(5, 'merchandise'),
(6, 'tour manager');

-- --------------------------------------------------------

--
-- Table structure for table `roles_users`
--

DROP TABLE IF EXISTS `roles_users`;
CREATE TABLE IF NOT EXISTS `roles_users` (
  `role_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY  (`role_id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `roles_users`
--

INSERT INTO `roles_users` (`role_id`, `user_id`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tour_artists`
--

DROP TABLE IF EXISTS `tour_artists`;
CREATE TABLE IF NOT EXISTS `tour_artists` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `description` text,
  `url` varchar(255) default NULL,
  `image` varchar(255) default NULL,
  `image_tnail` varchar(255) default NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `unique_name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tour_artists`
--

INSERT INTO `tour_artists` (`id`, `name`, `description`, `url`, `image`, `image_tnail`, `created_at`, `updated_at`) VALUES
(1, 'The Band', '', '', '', '', '2009-03-16 17:17:03', '2009-03-16 17:17:03');

-- --------------------------------------------------------

--
-- Table structure for table `tour_dates`
--

DROP TABLE IF EXISTS `tour_dates`;
CREATE TABLE IF NOT EXISTS `tour_dates` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `tour_venue_id` int(11) NOT NULL,
  `tour_artist_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `door_time` time NOT NULL,
  `start_time` time NOT NULL,
  `advance_tickets` float(7,2) NOT NULL,
  `door_tickets` float(7,2) NOT NULL,
  `tickets_url` varchar(255) NOT NULL,
  `show_url` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `active` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id`),
  KEY `tour_venue_id` (`tour_venue_id`),
  KEY `tour_artist_id` (`tour_artist_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tour_dates`
--

INSERT INTO `tour_dates` (`id`, `tour_venue_id`, `tour_artist_id`, `date`, `door_time`, `start_time`, `advance_tickets`, `door_tickets`, `tickets_url`, `show_url`, `description`, `created_at`, `updated_at`, `active`) VALUES
(1, 1, 1, '2011-09-16 00:00:00', '00:00:00', '00:00:00', 0.00, 0.00, '', '', '', '2009-03-16 17:21:51', '2009-03-16 17:21:51', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tour_venues`
--

DROP TABLE IF EXISTS `tour_venues`;
CREATE TABLE IF NOT EXISTS `tour_venues` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `description` text,
  `address1` varchar(255) NOT NULL,
  `address2` varchar(255) default NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(100) NOT NULL,
  `zip` varchar(12) NOT NULL,
  `country` varchar(100) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `url` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `image_tnail` varchar(255) NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tour_venues`
--

INSERT INTO `tour_venues` (`id`, `name`, `description`, `address1`, `address2`, `city`, `state`, `zip`, `country`, `phone`, `url`, `email`, `image`, `image_tnail`, `updated_at`, `created_at`) VALUES
(1, 'The Place', '', '', '', 'Outer Mongolia', 'Mars', '', '', '', '', '', '', '', '2009-03-16 17:22:14', '2009-03-16 17:19:01');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `username` varchar(24) character set latin1 collate latin1_german2_ci NOT NULL default '',
  `email` varchar(255) character set latin1 collate latin1_german2_ci NOT NULL default '',
  `hashed_password` varchar(64) character set latin1 collate latin1_german2_ci NOT NULL default '',
  `validation` varchar(64) character set latin1 collate latin1_german2_ci NOT NULL default '',
  `authentication` varchar(64) character set latin1 collate latin1_german2_ci NOT NULL default '',
  `salt` varchar(255) character set latin1 collate latin1_german2_ci NOT NULL default '',
  `first_name` varchar(255) character set latin1 collate latin1_german2_ci NOT NULL default '',
  `last_name` varchar(255) character set latin1 collate latin1_german2_ci NOT NULL default '',
  `city` varchar(255) character set latin1 collate latin1_german2_ci default NULL,
  `state` varchar(100) character set latin1 collate latin1_german2_ci default NULL,
  `zip` varchar(10) character set latin1 collate latin1_german2_ci default NULL,
  `country` varchar(255) character set latin1 collate latin1_german2_ci default NULL,
  `profile` text character set latin1 collate latin1_german2_ci,
  `url` varchar(255) character set latin1 collate latin1_german2_ci default NULL,
  `contact` tinyint(1) NOT NULL default '1',
  `user_agreement` tinyint(1) NOT NULL default '0',
  `created_at` datetime NOT NULL default '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL default '0000-00-00 00:00:00',
  `last_login` datetime default NULL,
  `login_count` int(11) NOT NULL default '0',
  `active` tinyint(1) NOT NULL default '1',
  `super` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `unique` (`username`,`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `hashed_password`, `validation`, `authentication`, `salt`, `first_name`, `last_name`, `city`, `state`, `zip`, `country`, `profile`, `url`, `contact`, `user_agreement`, `created_at`, `updated_at`, `last_login`, `login_count`, `active`, `super`) VALUES
(1, 'mindtonic', 'mindtonic@yahoo.com', '6c76f32f3cffa4b6a7ea0cce0e2f192cd8ca680d', '04c2807e15979ca53214c29c276ef786', '', '1966419610', '', '', '', '', '', '', '', '', 0, 0, '2008-08-06 19:30:20', '2009-03-16 17:33:57', '2008-08-26 16:13:33', 16, 1, 1),
(2, 'spacemonkey', 'spacemonkey@mindtonic.net', '64859252ed00df19e9954582aaa179d862ec6286', 'ebf81c2b6e0a2947b292788123b4de58', '', '17628', 'Space', 'Monkey', 'Outer Mongolia', 'NY', '', '', '', '', 0, 0, '2008-08-09 22:52:09', '2008-08-09 22:52:33', '2008-08-09 22:52:09', 1, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users_images`
--

DROP TABLE IF EXISTS `users_images`;
CREATE TABLE IF NOT EXISTS `users_images` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `users_id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `filepath` varchar(255) NOT NULL,
  `filesize` int(11) NOT NULL,
  `mime_type` varchar(20) NOT NULL,
  `width` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  `size_tags` varchar(100) NOT NULL,
  `parent_id` int(11) default NULL,
  `handle` varchar(100) NOT NULL default 'image',
  `name` varchar(255) NOT NULL,
  `description` text,
  `order` int(11) NOT NULL default '0',
  `active` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `users_id` (`users_id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users_images`
--

INSERT INTO `users_images` (`id`, `users_id`, `filename`, `filepath`, `filesize`, `mime_type`, `width`, `height`, `size_tags`, `parent_id`, `handle`, `name`, `description`, `order`, `active`, `created_at`, `updated_at`) VALUES
(1, 1, 'mindtonic.jpeg', 'images/users/1/1/mindtonic.jpeg', 7229, 'image/jpeg', 200, 162, 'width="200" height="162"', 0, '', 'mindtonic', '', 0, 0, '2009-03-16 17:33:57', '2009-03-16 17:33:57'),
(2, 0, 'mindtonic_TNAIL.jpeg', 'images/users/1/1/mindtonic_TNAIL.jpeg', 2866, 'image/jpeg', 100, 81, 'width="100" height="81"', 1, 'thumbnail', 'mindtonic', '', 0, 0, '2009-03-16 17:33:57', '2009-03-16 17:33:57');
