<style>
	.error {border: 1px solid black; padding: 8px 0 8px 12px; margin: 10px;}
	.error .hd {font-size: 16pt; font-weight: bold; margin-bottom: 10px;}
	.error .bd .info {margin: 2px 0;}
	.error .bd .info.source {margin-top: 10px;}
	.error .bd .info.source table {padding: 0; margin: 0;}
	.error .bd .info.source table tr.this {background-color: #eeeeee;}
	.error .bd .info.source table td {padding: 0 2px; margin: 0; border: 0; font-family: monospace; vertical-align: top;}
	.error .bd .info.source table td.line {border-right: 1px solid black;}
	.error .bd .info.trace {margin-top: 10px;}
	.error .bd .info .data {font-family: monospace;}
</style>

<div align="left" class="error">

	<div class="hd">Game Over</div>

	<div class="bd">
		<div class="info">
			<b>Message</b>: <span class="data"><?=$message?></span>
		</div>
		<div class="info">
			<b>Level</b>: <span class="data"><?=$level;?></span>
		</div>
		<div class="info">
			<b>File</b>: <span class="data"><?=htmlentities($file, ENT_COMPAT, CHARSET);?></span>
		</div>
		<div class="info">
			<b>Line</b>: <span class="data"><?=$line;?></span>
		</div>
		<?php if( $source !== FALSE ): ?>
			<div class="info source">
				<b>Source:</b>
				<table>
					<?php foreach($source as $key => &$value): ?>
						<tr <?php if($line == $key+1):?>class="this"<?php endif; ?>>
							<td class="line"><?=$key+1;?></td>
							<td><?=$value?></td>
						</tr>
					<?php endforeach; ?>
				</table>
			</div>
		<?php endif; ?>
		<div class="info trace">
			<b>Trace</b>:<br /><span class="data"><?=$trace;?></span>
		</div>
	</div>

</div>