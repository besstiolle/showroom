{if $msgNOK != null}<h2 style='background-color:#F00;padding:10px 30px;'>{$msgNOK}</h2>{/if}
{if $msgOk != null}<h2 style='background-color:#3F7F47;padding:10px 30px;'>{$msgOk}</h2>{/if}

<h3> Statuts compte Shotbot : {$statut_shotbot->used} utilis&eacute;s sur {$statut_shotbot->total}</h3>

{$tabs_start}
      {$sitesTpl}
	  
	  {$startFormFiltre}
	   {$formDropDownCategorieSites}
	   {$submitFiltre}
	  {$endFormFiltre}
	  
	  {$resetFiltre}
	  	<table cellspacing="0" class="pagetable">
			<thead>
				<tr>
					<th>{$module->Lang('th_id')}</th>
					<th>{$module->Lang('th_miniature')}</th>
					<th>{$module->Lang('th_url')}</th>
					<th>{$module->Lang('th_state')}</th>
					<th>{$module->Lang('th_categorie')}</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			{if count($listeImg) == 0}<tr><td colspan='5'>Aucun enregistrement</td></tr>{/if}
			{foreach from=$listeImg item=entry}
				<tr class="{$entry->rowclass}" onmouseover="this.className='{$entry->rowclass}hover';" onmouseout="this.className='{$entry->rowclass}';">
					<td>{$entry->id}</td>
					<td><img alt='{$entry->url} est r&eacute;alis&eacute; avec Cms Made Simple' src='{$entry->miniature}'/></td>
					<td><a href='{$entry->url}' target='_blank'>{$entry->url}</a></td>
					<td>{$entry->state}</td>
					<td>{$entry->categorie}</td>
					<td>{$entry->linkRefresh} - {$entry->linkRetest} - {$entry->linkEdition} - {$entry->linkDelete} | {$entry->linkStatNew} {$entry->linkStatOk} {$entry->linkStatKo}</td>
				</tr>
			{/foreach}
			</tbody>
		</table>
   {$tab_end}
   {$importTpl}
	  {$startFormImport}
		{$formDropDownCategorieImport}<br/>
		{$areaImport}<br/>{$submitImport}
	  {$endFormImport}
   {$tab_end}
    {$checkTpl}
	  {$startFormCheck}
		{$areaCheck}<br/>{$submitCheck}
	  {$endFormCheck}
   {$tab_end}

{$tabs_end}

