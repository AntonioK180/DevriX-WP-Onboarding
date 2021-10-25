<?php
/*
 * Plugin Name: Student CPT
 * Description: Plugin for my WordPress Onboarding at DevriX
 */

if ( ! defined( 'S_CPT_DIR' ) ) {
	define( 'S_CPT_DIR', dirname( __FILE__ ) . '/' );
}

require 'classes\class-student-widget.php';
require 'classes\class-student.php';
require 'classes\class-student-sidebar.php';
require 'classes\class-student-controller.php';

new Student_CPT();

new Student_Sidebar();

new Student_Controller();
