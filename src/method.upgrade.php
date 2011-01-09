<?php

if (!isset($gCms)) exit;

$db =& $gCms->GetDb();

$current_version = $oldversion;

switch($current_version)
{
  case '0.0.1':
  case '0.0.2':

	$fn = dirname(__FILE__).DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'default.tpl';
	if(file_exists( $fn ))
	{
		$template = @file_get_contents($fn);
		$this->SetPreference('default_form_template_contents',$template);
		$this->SetTemplate('formSample',$template);
		$this->SetPreference('current_form_template','Sample');
	}
	$fn = dirname(__FILE__).DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'showByCategorie.tpl';
	if(file_exists( $fn ))
	{
		$template = @file_get_contents($fn);
		$this->SetPreference('default_showByCategorie_template_contents',$template);
		$this->SetTemplate('showByCategorieSample',$template);
		$this->SetPreference('current_showByCategorie_template','Sample');
	}
	$fn = dirname(__FILE__).DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'showCategories.tpl';
	if(file_exists( $fn ))
	{
		$template = @file_get_contents($fn);
		$this->SetPreference('default_showCategories_template_contents',$template);
		$this->SetTemplate('showCategoriesSample',$template);
		$this->SetPreference('current_showCategories_template','Sample');
	}
	$fn = dirname(__FILE__).DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'showLast.tpl';
	if(file_exists( $fn ))
	{
		$template = @file_get_contents($fn);
		$this->SetPreference('default_showLast_template_contents',$template);
		$this->SetTemplate('showLastSample',$template);
		$this->SetPreference('current_showLast_template','Sample');
	}
}

?>