// USAGE: [flash=width,height,param_name:value]http://www.example.com/file.swf[/flash]

$movie_path   = (substr($code_text,0,4) == 'http') ? 'http://'.preg_replace('[http://]', '',$code_text) : $code_text;
$movie_path   = $tp -> toAttribute($movie_path);

$parm_array   = explode(',',$parm);

$width_type   = strpos($parm_array[0], '%') !== FALSE ? '%' : '';
$height_type  = strpos($parm_array[1], '%') !== FALSE ? '%' : '';

$width_value  = preg_replace('[^0-9]','',$parm_array[0]);
$height_value = preg_replace('[^0-9]','',$parm_array[1]);

$width_value  = varset($width_value, '50').$width_type;
$height_value = varset($height_value, '50').$height_type;

$n = 0;
foreach ($parm_array as &$value) 
{
	if(strpos($value, ':'))
	{
		$extra_parm_array[$n] = explode(':', $value);
		$n ++;
	}
}

$text = "<object type='application/x-shockwave-flash' data='$movie_path' width='$width_value' height='$height_value'>
				<param name='movie'   value='$movie_path' />
				<param name='quality' value='high' />
				<param name='allowscriptaccess' value='samedomain' />";
for ($i = 0; $i < $n; $i++)
{
	$text .= "	<param name='{$extra_parm_array[$i][0]}' value='{$extra_parm_array[$i][1]}' />";
}
$text .= "</object>";

return $text;