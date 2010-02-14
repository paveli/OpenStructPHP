{* Smarty *}
{strip}

<!-- home/index -->

<div>
	<ul>
		<li><a href="{$router->link(true, 'example')}">{$text->get('Example page')}</a></li>
		<li><a href="{$router->link(true, 'colors')}">{$text->get('Color demo')}</a></li>
	</ul>
</div>

<!-- Cache time: {$smarty.now|date_format:"%Y-%m-%d %H:%M:%S"} -->
<!-- Dynamic time: {dynamic}{$smarty.now|date_format:"%Y-%m-%d %H:%M:%S"}{/dynamic} -->
<!-- /home/index -->

{/strip}