<!doctype html>
<html lang="zh">
<head>
	{inc:includes/head.inc.php}
</head>
<body>
{inc:includes/navbar/navbar.inc.php}
<main class="main">
	<h1>{:title}</h1>
	<h2>{:message}</h2>
</main>
{inc:includes/side/side.inc.php}
{inc:includes/footer/footer.inc.php}
{if:debug}
{inc:debug/index.php}
{endif}
</body>
</html>
