<?php

/**
 * Plugin Name: Sanitized Links
 */

require 'classes\class-custom-admin-menu.php';
require 'classes\class-links-displayer.php';

new Custom_Admin_Menu();

new Links_Displayer();
