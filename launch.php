<?php 
/*
Plugin Name: JumpOut
Plugin URI: http://makedreamprofits.ru/pp/
Description: Устанавливайте JumpOut попапы в один клик с нашим плагином для Вордпресс!
Version: 3.0.0
Author: MakeDreamProfits
Author URI: http://makedreamprofits.ru
*/

/*  Copyright 2012-2014  MakeDreamProfits, Евгений Бос  (email : eugene@makedreamprofits.ru) */


// Не пускаем левых
if (!defined('ABSPATH')) die("Aren't you supposed to come here via WP-Admin?");
//define('comebacker_DIR', dirname(__FILE__));


// Задаем нужные константы
if ( !defined('WP_CONTENT_URL') )
	define( 'WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
if ( !defined('WP_CONTENT_DIR') )
	define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );

// Guess the location
define('JUMPOUT_PATH', WP_CONTENT_DIR  . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR . plugin_basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR);
define('JUMPOUT_TEMPLATE_PATH', JUMPOUT_PATH . 'templates' . DIRECTORY_SEPARATOR);
//$addfoot_url = WP_CONTENT_URL . '/plugins/' . plugin_basename(dirname(__FILE__));


include_once 'class.php';


$JumpOut = new JumpOut();
$GLOBALS['JumpOutClass'] = &$JumpOut;

// Действуем в зависимости от того в админке мы или нет
if (is_admin()) {
	ob_start();
	add_action('admin_menu', array($JumpOut, 'createMenuItem'));
    add_action('admin_init', array($JumpOut, 'addScripts'));
} else {
    //add_action('wp_footer', array($JumpOut, 'frontendFooter'));
    add_action('wp_head', array($JumpOut, 'frontendHeader'));

	//add_action('init', array($JumpOut, 'frontendStart'), 0);
	//add_action('shutdown', array($JumpOut, 'frontendEnd'), 1000); 
}