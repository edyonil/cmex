<?php

return array(
	/**
	 * --------------------------------------------------------------------------
	 * Template
	 * --------------------------------------------------------------------------
	 * Fill in the directory name of the templates here.
	 * Default: default
	 */
	'template' => 'default',

	/**
	 * --------------------------------------------------------------------------
	 * Error 404 View
	 * --------------------------------------------------------------------------
	 * Paste here which view to load, in case the page was not found
	 */
	'error404_default' => '404view',

	/**
	 * --------------------------------------------------------------------------
	 * Homepage
	 * --------------------------------------------------------------------------
	 * Which page to show, when the user accesses http://your_url/to/cmex
	 * That way you could even show a splashscreen here, which changes every
	 * month or something like this
	 * You can also add a redirect instead of a page name:
	 * Example: Redirect::to('myhomepage')
	 */
	'homepage' => 'home',

	/**
	 * --------------------------------------------------------------------------
	 * Akismet/Typepad
	 * --------------------------------------------------------------------------
	 * cmex! provides a basic interface for spam protection using Aksimet
	 * or Typepad. Put your credentials here!
	 * Currently supported: Typepad
	 */
	'spam_driver' => 'typepad',

	'spam_apikey' => 'eec041dd12d3b0ed4d12064e2825d9d0',

	'spam_enable_check' => false,
);