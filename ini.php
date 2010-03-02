<?php

#
#   Settings
#

// Site Settings
define("SITE_TITLE", "Shabda");															# What this is called
define("ROOT_PATH", "http://shabdacms.com/");								# Where you can find it
define("SESSION_ID", "shabda");															# What the session is called (no spaces)
define("DELIMITER", " .:. ");                               # Delimiter for titles, etc.

// Mail Settings
define("SHABDA_MAIL", "mindtonic@jaysanders.net");					# Who gets the error messages
define("SHABDA_MANAGER", "Jay Sanders");										# What they want to be called
define("FROM_NAME", "Shabda Content Management");						# Who sends the system mail
define("FROM_EMAIL", "info@shabdacms.com");									# Where the system mail comes from
define("CONTACT_EMAIL", "info@shabdacms.com");							# Who gets the contact emails
define("CONTACT_RECIPIENT", "Shabda Manager");							# What they want to be called

// Analytics
define("GOOGLE_ANALYTICS_ID", "UA-3621893-1");							# Google Analytics ID number

// Options
define("ALLOW_REGISTRATION", true);
define("REQUIRE_EMAIL_VALIDATION", true);

#
# 	Database
#

// Site Mode
#define("MODE", 'production');
define("MODE", 'development');

if (MODE == 'production')
{
	// Production
	define("DB_NAME", "shabda");														# Production Database Name
	define("DB_USER", "mindtonic");													# Production Database Username
	define("DB_PWORD", "YHB54tgb");													# Production Database Password
	define("DB_HOST", "mysql.mindtonic.net");								# Production Database Host
}
elseif (MODE == 'development')
{
	// Development
	define("DB_NAME", "shabda_development");								# Development Database Name
	define("DB_USER", "mindtonic");													# Development Database Username
	define("DB_PWORD", "YHB54tgb");													# Development Database Password
	define("DB_HOST", "localhost");													# Development Database Host
}

#
#   Custom User Analyser
#

define("USER_TRACKER", false);

#
#   Error Handling Preferences
#

define("LOG_ERRORS", true);																	# Record Errors in logs/errors.log
define("SHOW_ERRORS", true);																# false shows the pretty 'Oops!' error page
define("SEND_ERROR_MAIL", false);														# Mail Errors to SHABDA_MANAGER
define("IGNORE_NULL_DATABASE_RETURNS", true);								# Report an error if queries return a null set

#
#		System Manager
#

define("LOG_SYSTEM_OBJECTS_LOADING", false);								# Log class names as they are loaded in logs/system.log
define("LOG_CONTROLLER", false);
define("LOG_REDIRECTS", false);
define("LOG_DATABASE_QUERIES", false);

// Defaults
define('DEFAULT_CONTROLLER', 'home');
define('DEFAULT_ACTION', 'index');
define('DEFAULT_LAYOUT', 'application');

?>
