<?php

/**
 * Plugin Name: Photo Gallery by Supsystic
 * Description: Easy to use Gallery by Supsystic with professional gallery templates. Show off your best design, photography and creative work
 * Version: 1.10.9
 * Author: supsystic.com
 * Author URI: http://supsystic.com
 * Text Domain: grid-gallery
 **/

require_once dirname(__FILE__) . '/app/SupsysticGallery.php';

$supsysticGallery = new SupsysticGallery('1.10.9');
$supsysticGallery->run();