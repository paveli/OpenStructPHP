{* Smarty *}
{strip}

<!-- home/colors -->

{literal}
<style type="text/css">
	.colors {padding-top: 32px;}
	.colors-first {padding-top: 8px;}
	.colors .category-name {font-size: 16pt; font-weight: bold; color: black;}
	.colors .color {height: 64px; margin: 4px;}
	.colors .color span {font-size: 12pt; font-weight: bold;}
</style>
{/literal}

{foreach name=webColors from=$webColors key=categoryName item=category}
	<div class="colors{if $smarty.foreach.webColors.first} colors-first{/if}">
		<span class="category-name">{$text->dget('colors', $categoryName)}</span>
		<div class="yui-g">
			<div class="yui-u first">
				{foreach from=$category key=key item=color}
					<div class="color" style="background-color: {$color};">
						<span style="color: {$webColorsInverted.$categoryName.$key};">{$color}</span>
					</div>
				{/foreach}
			</div>
			<div class="yui-u">
				{foreach from=$category key=key item=color}
					<div class="color" style="background-color: {$webColorsValues.$categoryName.$key};">
						<span style="color: {$webColorsInverted.$categoryName.$key};">{$webColorsValues.$categoryName.$key}</span>
					</div>
				{/foreach}
			</div>
		</div>
	</div>
{/foreach}

<!-- Cache time: {$smarty.now|date_format:"%Y-%m-%d %H:%M:%S"} -->
<!-- Dynamic time: {dynamic}{$smarty.now|date_format:"%Y-%m-%d %H:%M:%S"}{/dynamic} -->
<!-- /home/colors -->

{/strip}