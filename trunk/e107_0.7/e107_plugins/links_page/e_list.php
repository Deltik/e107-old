<?php

	if(!$links_install = $sql -> db_Select("plugin", "*", "plugin_path = 'links_page' AND plugin_installflag = '1' ")){
		return;
	}

	$LIST_CAPTION = $arr[0];
	$LIST_DISPLAYSTYLE = ($arr[2] ? "" : "none");

	if($mode == "new_page" || $mode == "new_menu" ){
		$lvisit = $this -> getlvisit();
		$qry = " l.link_category != '1' ";
	}else{
		$qry = " l.link_category != '1' ";
	}

	$bullet = $this -> getBullet($arr[6], $mode);

	$qry = "
	SELECT l.*, c.link_category_id, c.link_category_name
	FROM #links_page AS l
	LEFT JOIN #links_page_cat AS c ON c.link_category_id = l.link_category
	WHERE ".$qry." AND l.link_class REGEXP '".e_CLASS_REGEXP."'
	ORDER BY l.link_id DESC LIMIT 0,".$arr[7]." 
	";

	if(!$sql -> db_Select_gen($qry)){
		$LIST_DATA = "no links yet";
	}else{
		while($row = $sql -> db_Fetch()){

			$rowheading	= $this -> parse_heading($row['link_name'], $mode);
			$ICON		= $bullet;
			$HEADING	= "<a href='".$row['link_url']."' target='_blank' title='".$row['link_name']."'>".$rowheading."</a>";
			$AUTHOR		= "";
			$CATEGORY	= ($arr[4] ? "<a href='".e_PLUGIN."links_page/links.php?cat.".$row['link_category_id']."'>".$row['link_category_name']."</a>" : "");
			$DATE		= "";
			$INFO		= "";
			$LIST_DATA[$mode][] = array( $ICON, $HEADING, $AUTHOR, $CATEGORY, $DATE, $INFO );

		}
	}

?>