$search = array(
"#\[link\]([a-z]+?://){1}(.*?)\[/link\]#si",
"#\[link\](.*?)\[/link\]#si",
"#\[link=([a-z]+?://){1}(.*?)\](.*?)\[/link\]#si",
"#\[link=(.*?)\](.*?)\[/link\]#si"
);

$replace = array(
'<a href="\1\2">\1\2</a>',
'<a href="http://\1">\1</a>',
'<a href="\1\2">\3</a>',
'<a href="\1">\2</a>'
);

return preg_replace($search,$replace,$full_text);
