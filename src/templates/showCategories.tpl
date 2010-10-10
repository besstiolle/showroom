<fieldset><legend>{$module->Lang('title_categories')}</legend>
{if count($listeCategorie) == 0}
	<p>Aucun site n'est encore enregistr&eacute;</p>
{else}
	<ul>
	{foreach from=$listeCategorie item=categorie}
		<li>{$categorie->link} ({$categorie->compteur})</li>
	{/foreach}
	</ul>
{/if}
</fieldset>