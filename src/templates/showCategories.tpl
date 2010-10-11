<p></p>
<h2>{$module->Lang('title_categories')}</h2>
{if count($listeCategorie) == 0}
	<p>Aucun site n'est enregistr&eacute;</p>
{else}
	<ul>
	{foreach from=$listeCategorie item=categorie}
		<li>{$categorie->link} ({$categorie->compteur})</li>
	{/foreach}
	</ul>
{/if}