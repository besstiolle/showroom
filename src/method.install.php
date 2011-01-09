<?php


if (!isset($gCms)) exit;


$db =& $gCms->GetDb();
$taboptarray = array( 'mysql' => 'TYPE=MyISAM' );
$dict = NewDataDictionary( $db );

// table schema description
$flds = "
     id I KEY,
	 url C(50),
	 text X,
	 id_user I,
	 id_category I,
	 state I,
	 date_submit " . CMS_ADODB_DT . "
";
			
//TODO : verifier les erreurs
$sqlarray = $dict->CreateTableSQL( cms_db_prefix()."module_showroom_room",
				   $flds, 
				   $taboptarray);
$dict->ExecuteSQLArray($sqlarray);
$query = 'ALTER TABLE '.cms_db_prefix().'module_showroom_room ADD INDEX (date_submit)';
if ($db->Execute($query) === false)
	die('erreur grave durant l\'installation');
$query = 'ALTER TABLE '.cms_db_prefix().'module_showroom_room ADD INDEX (id_user)';
if ($db->Execute($query) === false)
	die('erreur grave durant l\'installation');
$query = 'ALTER TABLE '.cms_db_prefix().'module_showroom_room ADD INDEX (id_category)';
if ($db->Execute($query) === false)
	die('erreur grave durant l\'installation');
$query = 'ALTER TABLE '.cms_db_prefix().'module_showroom_room ADD INDEX (state)';
if ($db->Execute($query) === false)
	die('erreur grave durant l\'installation');
// create a sequence
$db->CreateSequence(cms_db_prefix()."module_showroom_room_seq");


$this->AddEventHandler('Core', 'ContentPostRender', false);

// create a permission
$this->CreatePermission('Set ShowRoom Prefs','ShowRoom : Set Prefs');

# Setup display template
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


// put mention into the admin log
$this->Audit( 0, $this->Lang('friendlyname'), $this->Lang('installed', $this->GetVersion()));
?>