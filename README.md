#Kohana CheatSheet Module

Short description: module for quick review of present Kohana classes and their properties/methods.

Current version: 0.5

Demo: http://ko3.kupreev.com/cs

Author: Alexander Kupreyeu (Kupreev)

Email: alexander dot kupreev at gmail dot com

##Requirements

 1. PHP framework Kohana 3.0.6+
 2. Kohana Userguide Module activated.

##Installation

 1. Unpack module folder in Kohana\_PATH/modules/ dir.
 2. Add to file Kohana_PATH/application/bootstrap.php into array parameter of Kohana::modules() entry 
	'cheatsheet' => MODPATH.'cheatsheet',

##Usage

Browse http://example.com/cs

Click on a question sign near the class name to see the class description.

Click on a property/method name to see its parameters and description (if they exist). Click one more time to hide block. Or click on "close" button inside the description block to close it.

You can resize page to change text size and number of columns in the browser window. 

You can manually invalidate cache by clicking "Invalidate cache" link at the bottom of the page.

You can set in module config (Kohana_PATH/modules/cheatsheet/config/cs.php):

 1. some classes that should not be included in the sheet (you can use masks with asterisk). In the current version you can set precise class name to ignore (e.g., 'kohana\_log') or use following mask types:

     * beginning of the class name (e.g. 'someword*' will exclude from the sheet 'someword\_about\_love')

     * ending of the class name (e.g. '*someword' will exclude from the sheet 'tell\_me\_someword')

     * occurrence in the class name (e.g. '\*someword\*' will exclude from the sheet 'tell\_me\_someword\_about\_love') 

 2. should cache be turned on (TRUE or FALSE) -- I recommend set to TRUE, cache is on real data, not expiration time 

##Notes

In the current version module does not support i18n — works only in English.

Module was tested in Firefox 3.5+, Opera 10+ browsers.

##Changelog

###From v. 0.4

 * updated jquery to 1.4.4
 
 * added class info
 
 * fixed media output issues

###From v. 0.3

 * updated class excluding options variants

 * fixed failure while working with Kohana 3.0.6

###From v. 0.2

 * added manual cache invalidation

 * added ignore directories

 * added "close" button for easier description block closing

###From v. 0.1

 * partially fixed bug when code in param/method desription goes outside of a block 

 * added data cache

 * a few design modifications 

##Used code

 1. Module uses classes of Kohana Userguide Module, and in some places partially modified code from Kohana Userguide Module (it is underlined in methods comments).

 2. Module uses jQuery (jQuery JavaScript Library) v1.4.4

	http://jquery.com/
	Copyright (c) 2010 John Resig
	Dual licensed under the MIT and GPL licenses (http://docs.jquery.com/License)
	
 3. Module uses Columnizer jQuery Plugin v1.4.0

	http://welcome.totheinter.net/columnizer-jquery-plugin/
	Copyright (c) Adam Wulf
	Licensed under a Creative Commons Attribution 3.0 United States License (http://creativecommons.org/licenses/by/3.0/us/)