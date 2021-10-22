<?php
/**
 * Plugin Name: My Onboarding Plugin
 */


require 'classes\first-plugin.php';
require 'classes\admin-menu-options.php';

new First_Plugin();

new Admin_Menu_Options();
