<?php
#-------------------------------------------------------------------------
# Module: ShowRoom - Module proposant un systeme de showroom publique avec 
#			possibilite d'ajouter des urls de maniere annonyme pour le CMS
#           Cms Made Simple
#			
#  Attention : ce projet a ete cree specifiquement pour le site www.cmsmadesimple.fr 
#				Toute utilisation de votre part necessitera des modifications dans les fichiers !
#
# Version: de Kevin Danezis Aka "Bess"
# Author can be join on the french forum : http://www.cmsmadesimple.fr/forum 
#        or by email : contact [plop] furie [plap] be
#
# The discussion page around the module : http://www.cmsmadesimple.fr/forum/viewtopic.php?id=2958
# The author's git page is : http://github.com/besstiolle
# The module's git page is : http://github.com/besstiolle/showroom
# The module's demo page is : http://www.cmsmadesimple.fr/showroom
#
#-------------------------------------------------------------------------
# CMS - CMS Made Simple is (c) 2005 by Ted Kulp (wishy@cmsmadesimple.org)
# This project's homepage is: http://www.cmsmadesimple.org
#-------------------------------------------------------------------------
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
# Or read it online: http://www.gnu.org/licenses/licenses.html#GPL
#-------------------------------------------------------------------------

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