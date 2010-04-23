<?php
$menus=array();
$menus['orderadmin']=array('group' => 'menu9', 'label' => 'admin2','href' => 'page=orderadmin', 'img' => 'orders.jpg', 'func' => 'CountAllOrders');
$menus['showcustomers']=array('hide' => true,'page' => 'customeradmin', 'group' => 'menu9', 'label' => 'admin3','href' => 'page=customeradmin&action=showcustomers', 'img' => 'customers.gif', 'func' => 'CountCustomers','param' => 'CUSTOMER');
$menus['productadmin']=array('group' => 'menu9', 'label' => 'productadmin6','href' => 'page=productadmin&action=add_product', 'img' => 'products.gif');
$menus['browse']=array('group' => 'menu9', 'label' => 'menu15','href' => 'page=browse&includesearch=1', 'img' => 'productswh.png', 'func' => 'CountProducts');
$menus['stockadmin']=array('group' => 'menu9', 'label' => 'admin32','href' => 'page=stockadmin', 'img' => 'stockadmin.gif');
$menus['showadmins']=array('hide' => true,'page' => 'customeradmin', 'group' => 'admin23', 'label' => 'admin29','href' => 'page=customeradmin&action=showadmins', 'img' => 'admins.gif', 'func' => 'CountCustomers','param' => 'ADMIN');
$menus['groupadmin']=array('group' => 'admin23', 'label' => 'admin6','href' => 'page=groupadmin', 'img' => 'groups.gif');
$menus['paymentadmin']=array('group' => 'admin23', 'label' => 'admin21','href' => 'page=paymentadmin', 'img' => 'paymentadmin.gif');
$menus['shippingadmin']=array('group' => 'admin23', 'label' => 'admin18','href' => 'page=shippingadmin', 'img' => 'shippingadmin.gif');
$menus['uploadadmin']=array('group' => 'admin23', 'label' => 'admin9','href' => 'page=uploadadmin', 'img' => 'uploadlist.gif');
$menus['editsettings']=array('group' => 'admin23', 'label' => 'admin8','href' => 'page=editsettings', 'img' => 'settings.gif');
$menus['advancedsettings']=array('type' => 'apps', 'group' => 'admin23', 'label' => 'editsettings93','href' => 'zfaces=form&form=settings&action=edit&no_redirect=1&id=1', 'img' => 'pc.gif');
$menus['discountadmin']=array('type' => 'apps', 'group' => 'admin23', 'label' => 'admin38','href' => 'zfaces=list&form=discount', 'img' => 'discount.gif');
$menus['taxes']=array('type' => 'apps', 'group' => 'admin23', 'label' => 'admin100','href' => 'zfaces=list&form=taxes', 'img' => 'taxes.png');
$menus['adminedit']=array('group' => 'admin24', 'label' => 'admin15','href' => 'page=adminedit&filename=conditions.sql&root=0&wysiwyg=0', 'img' => 'conditionsadmin.gif');
$menus['admineditbanned']=array('page' => 'adminedit', 'group' => 'admin24', 'label' => 'admin19','href' => 'page=adminedit&filename=banned.txt&root=1&wysiwyg=0', 'img' => 'banned.gif');
$menus['admineditmain']=array('page' => 'adminedit', 'group' => 'admin24', 'label' => 'admin22','href' => 'page=adminedit&filename=main.sql&root=0', 'img' => 'mainadmin.gif');
$menus['admineditcountries']=array('page' => 'adminedit', 'group' => 'admin24', 'label' => 'admin37','href' => 'page=adminedit&filename=countries.txt&root=1&wysiwyg=0', 'img' => 'countries.gif');
$menus['prompts']=array('type' => 'apps', 'group' => 'admin24', 'label' => 'admin101','href' => 'zfaces=list&form=prompt', 'img' => 'update.gif');
$menus['errorlogadmin']=array('group' => 'admin25', 'label' => 'admin26','href' => 'page=errorlogadmin', 'img' => 'errorlog.gif');
$menus['accesslogadmin']=array('group' => 'admin25', 'label' => 'admin31','href' => 'page=accesslogadmin', 'img' => 'accesslog.gif');
$menus['admin']=array('group' => 'admin25', 'label' => 'admin7','href' => 'page=admin&adminaction=optimize_tables', 'img' => 'optimize.gif');
$menus['mailinglist']=array('group' => 'admin25', 'label' => 'admin35','href' => 'page=mailinglist', 'img' => 'mailinglist.gif');

$menus['customer']=array('hide' => true, 'group' => 'my5', 'label' => 'my7','href' => 'page=customer&action=show', 'img' => 'customers.gif');
$menus['orders']=array('hide' => true, 'group' => 'my5', 'label' => 'my8','href' => 'page=orders&id='.$customerid, 'img' => 'orders.gif');
$menus['cart']=array('hide' => true, 'group' => 'my5', 'label' => 'my9','href' => 'page=cart&action=show', 'img' => 'carticon.gif');
$menus['products']=array('hide' => true, 'group' => 'my5', 'label' => 'admin5','href' => 'page=products&action=show', 'img' => 'products.gif');
$menus['readorder']=array('page' => 'readorder', 'hide' => true, 'group' => 'admin23', 'label' => 'details7','href' => 'page=orderadmin', 'img' => 'orders.gif', 'func' => 'CountAllOrders');

if (file_exists(dirname(__FILE__).'/../builder.php')) {
	$menus['builder']=array('group' => 'admin23', 'label' => 'details7','href' => 'page=builder', 'img' => 'pagesadmin_add.png');
}
?>