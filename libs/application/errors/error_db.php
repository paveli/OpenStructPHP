<html>
	<head>
		<title>DB Error</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
		<style type="text/css">

		body {
		background-color:	#fff;
		margin:				0px;
		font-family:		Lucida Grande, Verdana;
		font-size:			10pt;
		color:				#000;
		}

		.error {border: 1px solid black; padding: 10px 0 10px 20px; margin:0 0 10px 0;}
		.error .hd {font-size: 16pt; font-weight: bold; margin-bottom: 10px;}
		.error .bd .info {margin: 2px 0;}
		.error .bd .info.source {margin-top: 10px;}
		.error .bd .info.source table {padding: 0; margin: 0;}
		.error .bd .info.source table tr.this {font-weight: bold; color: orangered;}
		.error .bd .info.source table td {padding: 0 2px; margin: 0; border: 0; font-family: monospace; vertical-align: top;}
		.error .bd .info.source table td.line {border-right: 1px solid black;}
		.error .bd .info.trace {margin-top: 10px;}
		.error .bd .info .data {font-family: monospace;}
		</style>
	</head>
	
	<body>
		<div align="left" class="error">

			<div class="hd"><?=$level;?> &mdash; Oops...</div>

			<div class="bd">
				<div class="info">
					<span><?=$message?></span>
				</div>
				<div class="info trace">
					<b>Trace</b>:<br /><span class="data"><?=$trace;?></span>
				</div>
			</div>

		</div>
	</body>
</html>