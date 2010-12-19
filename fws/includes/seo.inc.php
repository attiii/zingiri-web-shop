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