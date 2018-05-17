<?php
/*
Plugin Name: Easy Insert Quran
Plugin URI: https://themeae.com/insertquran
Description: Easy Insert Quran helps you to insert any Quranic text in your WYSIWYG Editor.
Author: Zunan Arif R.
Version: 1.1.0
Author URI: https://themeae.com
License: GPLv2 or later
Text Domain: easy-insert-quran
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

define ( 'EIQ_PLUGIN_URL', plugins_url ( '', __FILE__ ) );
define ( 'EIQ_PLUGIN_PATH', plugin_dir_path (__FILE__ ) );
define ( 'EIQ_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

include_once EIQ_PLUGIN_PATH.'includes/functions.php';
include_once EIQ_PLUGIN_PATH.'includes/config.php';
include_once EIQ_PLUGIN_PATH.'includes/shortcode.php';
include_once EIQ_PLUGIN_PATH.'includes/options.php';