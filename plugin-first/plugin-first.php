<?php
/**
 * Plugin Name: My Onboarding Plugin
 *
 */


require 'classes\class-first-plugin.php';
require 'classes\class-admin-menu-options.php';

new First_Plugin();

new Admin_Menu_Options();
