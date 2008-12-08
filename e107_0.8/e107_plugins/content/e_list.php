<?php

if (!defined('e107_INIT')) { exit; }

if(!$content_install = $sql->db_Select_gen("SELECT * FROM plugin WHERE plugin_path = 'content' AND plugin_installflag = '1' "))
{
	return;
}

require_once(e_PLUGIN."content/handlers/content_class.php");
$aa = new content;

$datequery = " AND content_datestamp < ".time()." AND (content_enddate=0 || content_enddate>".time().") ";

global $contentmode;
//contentmode : content_144 (content_ + idvalue)
$headingquery = ($contentmode ? " AND content_id = '".substr($contentmode,8)."' " : '');

//get main parent types
$sqlm = new db;
if(!$mainparents = $sqlm->db_Select_gen("SELECT content_id, content_heading FROM #pcontent WHERE content_class REGEXP '".e_CLASS_REGEXP."' AND content_parent = '0' ".$datequery." ".$headingquery." ORDER BY content_heading"))
{
	$LIST_DATA = CONTENT_E_LIST_LAN_1;
}
else
{		
	while($rowm = $sqlm->db_Fetch())
	{
		$ICON = "";
		$HEADING = "";
		$AUTHOR = "";
		$CATEGORY = "";
		$DATE = "";
		$INFO = "";
		$LIST_CAPTION = $rowm['content_heading'];

		//global var for this main parent
		$mainparent = $rowm['content_id'];

		//get path variables
		$content_recent_pref = $aa->getContentPref($mainparent, true);

		//prepare query string
		$array = $aa->getCategoryTree("", $mainparent, TRUE);
		$validparent = implode(",", array_keys($array));
		$qry = "content_refer !='sa' AND content_parent REGEXP '".$aa->CONTENTREGEXP($validparent)."' AND content_class REGEXP '".e_CLASS_REGEXP."' ";

		//check so only the preferences from the correct content_type (article, content, review etc) are used and rendered
		if(substr($contentmode,8) == $rowm['content_id'])
		{
			if($mode == "new_page" || $mode == "new_menu" )
			{
				$lvisit = $this->getlvisit();
				$qry = $qry." AND content_datestamp>".$lvisit;
			}
			else
			{
				$qry = $qry." ".$datequery;
			}
			$qry .= " ORDER BY content_datestamp DESC LIMIT 0,".intval($arr[7]);

			//get recent content for each main parent
			$sqli = new db;
			if(!$resultitem = $sqli->db_Select_gen("SELECT content_id, content_heading, content_parent, content_datestamp, content_icon, content_author FROM #pcontent WHERE ".$qry))
			{
				$LIST_DATA = LIST_LAN_1." ".$rowm['content_heading'];
			}
			else
			{
				$LIST_DISPLAYSTYLE = ($arr[2] ? "" : "none");
				while($rowi = $sqli->db_Fetch())
				{
					$rowheading = $this->parse_heading($rowi['content_heading'], $mode);
					$HEADING = "<a href='".e_PLUGIN_ABS."content/content.php?content.".$rowi['content_id']."' title='".$rowi['content_heading']."'>".$rowheading."</a>";
					//category
					if($arr[4])
					{
						$crumb = "";
						if(array_key_exists($rowi['content_parent'], $array))
						{
							$newarr = $array[$rowi['content_parent']];
							$newarr = array_reverse($newarr);
							$CATEGORY = "<a href='".e_PLUGIN_ABS."content/content.php?cat.".$newarr[1]."'>".$newarr[0]."</a>";
						}
					}

					$DATE = ($arr[5] ? $this->getListDate($rowi['content_datestamp'], $mode) : "");

					$image_link_append = "<a href='".e_PLUGIN_ABS."content/content.php?content.".$rowi['content_id']."'>";
					if($rowi['content_icon'] && file_exists($content_recent_pref["content_icon_path"].$rowi['content_icon']))
					{
						$ICON = $image_link_append."<img src='".$content_recent_pref["content_icon_path"].$rowi['content_icon']."' style='width:50px; border:1px solid #000;' alt='' /></a>";
					}
					else
					{
						$ICON = "";
					}

					//get author details
					if($arr[3])
					{
						$authordetails = $aa->getAuthor($rowi['content_author']);
						if(USER && is_numeric($authordetails[0]) && $authordetails[0] != "0")
						{
							$AUTHOR = "<a href='".e_HTTP."user.php?id.".$authordetails[0]."' >".$authordetails[1]."</a>";
						}
						else
						{
							$AUTHOR = $authordetails[1];
						}
					}
					else
					{
						$AUTHOR = "";
					}
					$INFO = "";

					$LIST_DATA[$mode][] = array( $ICON, $HEADING, $AUTHOR, $CATEGORY, $DATE, $INFO );
				}
			}
		}
	}
}

?>