<?php

$folderIncludes = dirname(__FILE__)."/";

if(!function_exists("dmp"))
	require_once $folderIncludes . 'functions.php';

if(!class_exists("UniteFunctionsRev"))
	require_once $folderIncludes . 'functions.class.php';
	
if(!class_exists("UniteFunctionsWPRev"))
	require_once $folderIncludes . 'functions_wordpress.class.php';

if(!class_exists("UniteDBRev"))
	require_once $folderIncludes . 'db.class.php';


if(!class_exists("UniteSettingsRev"))
	require_once $folderIncludes . 'settings.class.php';

if(!class_exists("UniteCssParserRev"))
	require_once $folderIncludes . 'cssparser.class.php';
	
if(!class_exists("UniteSettingsAdvancedRev"))
	require_once $folderIncludes . 'settings_advances.class.php';

if(!class_exists("UniteSettingsOutputRev"))
	require_once $folderIncludes . 'settings_output.class.php';
	
	
if(!class_exists("UniteSettingsRevProductRev"))
	require_once $folderIncludes . 'settings_product.class.php';

if(!class_exists("UniteSettingsProductSidebarRev"))
	require_once $folderIncludes . 'settings_product_sidebar.class.php';

if(!class_exists("UniteImageViewRev"))
	require_once $folderIncludes . 'image_view.class.php';

if(!class_exists("UniteZipRev"))
	require_once $folderIncludes . 'zip.class.php';

	
?>