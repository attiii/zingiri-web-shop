<?php
function wsSeo($page,$catid='',$prodid='') {
	$ret=array();
	if (isset($prodid)) {
		$db=new db();
		$db->select('select `productid`,`desc`,`seo_keywords`,`seo_description`,`description` from `##product`,`##category` where ##product.catid=##category.id and ##product.id='.qs($prodid));
		if ($db->next()) {
			if ($db->get('seo_title')) $ret['title']=$db->get('seo_title');
			else $ret['title']=$db->get('desc').' &raquo; '.$db->get('productid').' | ';
			$ret['keywords']=$db->get('seo_keywords');
			if ($db->get('seo_description')) $ret['description']=$db->get('seo_description');
			else $ret['description']=strip_tags($db->get('description'));
		}
	} elseif ($page=='browse' && isset($catid)) {
		$db=new db();
		$db->select('select `##group`.`name`,`##category`.`desc` from `##group`,`##category` where ##group.id=##category.groupid and ##category.id='.qs(intval($catid)));
		if ($db->next()) {
			if ($db->get('seo_title')) $ret['title']=$db->get('seo_title');
			else $ret['title']=$db->get('name').' - '.$db->get('desc').' | ';
		}
	}
	return $ret;
}

function wsProductTitle() {
	global $wsPages,$txt;
	$breadcrumb='';
	$pt=array();

	if (!is_home()) {
		//$pt[]=array('text' => get_option('blogname'),'url' => get_option('home'));
		if (is_category() || is_single()) {
			$pt[]=array('text' => get_the_category('title_li='),'url' => get_option('home'));
			if (is_single()) {
				$pt[]=array('text' => get_the_title(),'url' => get_option('home'));
			}
		} elseif (is_page()) {
			$pt[]=array('text' => get_the_title(),'url' => get_option('home'));
		}
	}

	if (isset($_GET['page']) && ($p=$_GET['page'])) {
		if (isset($wsPages[$p])) $pt[]=array('text' => $txt[$wsPages[$p]],'url' => zurl('index.php?page='.$p));
		//	} elseif ($_GET['zfaces'] && $p=$_GET['form']) {
		//		if ($f=zing_ws_get_form_title($_GET['form'])) $pt[]=array('text' => $txt[$wsPages[$p]],'url' => zurl('index.php?page='.$p));
	}
	if (isset($_GET['prod']) && $_GET['prod']) {
		$db=new db();
		if ($db->select('select `productid`,`##category`.`groupid`,`catid` from `##product`,`##category` where ##product.catid=##category.id and `##product`.`id`='.intval($_GET['prod'])) && $db->next()) {
			$catid=$db->get('catid');
			$groupid=$db->get('groupid');
			$productname=$db->get('productid');
		}
	} elseif (isset($_GET['kat']) && ($catid=$_GET['kat'])) {
		$db=new db();
			if ($db->select('select `groupid` from `##category` where `id`='.$catid) && $db->next()) {
				$groupid=$db->get('groupid');
			}
	}
	if ($p == 'browse' || $p == 'details') {
		if ($groupid || $groupid=intval($_GET['group'])) {
			$db=new db();
			if ($db->select('select `id`,`name` from `##group` where `id`='.$groupid) && $db->next()) {
				$pt[]=array('text' => $db->get('name'),'url' => zurl('index.php?page=browse&group='.$db->get('id')));
			}
		}
		if ($catid || $catid=intval($_GET['kat'])) {
			$db=new db();
			if ($db->select('select `id`,`desc` from `##category` where `id`='.$catid) && $db->next()) {
				$pt[]=array('text' => $db->get('desc'),'url' => zurl('index.php?page=browse&cat='.$db->get('id')));
			}
		}
		if ($_GET['prod']) {
			$db=new db();
			$pt[]=array('text' => $productname);
		}
	}
	if (($c=count($pt)) > 0) {
		foreach ($pt as $i => $p) {
			if ($i > 0) $breadcrumb.=' >> ';
			if ($i+1 < $c) $breadcrumb.='<a href="'.$p['url'].'">'.$p['text'].'</a>';
			else $breadcrumb.=$p['text'];
		}
	}
	return $breadcrumb;
}