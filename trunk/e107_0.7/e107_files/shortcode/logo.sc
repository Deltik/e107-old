parse_str($parm);

if (isset($file) && $file && is_readable($file))
{
	$logo = $file;
}
else if (is_readable(THEME."images/e_logo.png"))
{
	$logo = THEME_ABS."images/e_logo.png";
}
else
{
	$logo = e_IMAGE."logo.png";
}

$dimensions = getimagesize($logo);

$image = "<img class='logo' src='".$logo."' style='width: ".$dimensions[0]."px; height: ".$dimensions[1]."px' alt='".SITENAME."' />\n";

if (isset($link) && $link) {
	if ($link == 'index') {
		$image = "<a href='".e_BASE."index.php'>".$image."</a>";
	}
	else
	{
		$image = "<a href='".$link."'>".$image."</a>";
	}
}

return $image;