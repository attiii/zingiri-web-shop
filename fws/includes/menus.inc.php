<?php
$menus=array();
$menus['orderadmin']=array('group' => 'admin23', 'label' => 'admin2','href' => 'page=orderadmin', 'img' => 'orders.gif', 'func' => 'CountAllOrders');
$menus['customeradmin']=array('group' => 'admin23', 'label' => 'admin3','href' => 'page=customeradmin&action=showcustomers', 'img' => 'customers.gif', 'func' => 'CountCustomers','param' => 'CUSTOMER');
$menus['customeradmin2']=array('group' => 'admin23', 'label' => 'admin29','href' => 'page=customeradmin&action=showadmins', 'img' => 'admins.gif', 'func' => 'CountCustomers','param' => 'ADMIN');
$menus['productadmin']=array('group' => 'admin23', 'label' => 'admin5','href' => 'page=productadmin&action=add_product', 'img' => 'products.gif', 'func' => 'CountProducts');
$menus['groupadmin']=array('group' => 'admin23', 'label' => 'admin6','href' => 'page=groupadmin', 'img' => 'groups.gif');
$menus['paymentadmin']=array('group' => 'admin23', 'label' => 'admin21','href' => 'page=paymentadmin', 'img' => 'paymentadmin.gif');
$menus['shippingadmin']=array('group' => 'admin23', 'label' => 'admin18','href' => 'page=shippingadmin', 'img' => 'shippingadmin.gif');
$menus['uploadadmin']=array('group' => 'admin23', 'label' => 'admin9','href' => 'page=uploadadmin', 'img' => 'uploadlist.gif');
$menus['editsettings']=array('group' => 'admin23', 'label' => 'admin8','href' => 'page=editsettings', 'img' => 'settings.gif');
$menus['discountadmin']=array('group' => 'admin23', 'label' => 'admin38','href' => 'page=discountadmin', 'img' => 'discount.gif');
$menus['taxes']=array('group' => 'admin23', 'label' => 'admin100','href' => 'zfaces=list&form=taxes', 'img' => 'taxes.png');
$menus['adminedit']=array('group' => 'admin24', 'label' => 'admin15','href' => 'page=adminedit&filename=conditions.txt&root=0&wysiwyg=0', 'img' => 'conditionsadmin.gif');
$menus['adminedit2']=array('group' => 'admin24', 'label' => 'admin19','href' => 'page=adminedit&filename=banned.txt&root=1&wysiwyg=0', 'img' => 'banned.gif');
$menus['adminedit3']=array('group' => 'admin24', 'label' => 'admin22','href' => 'page=adminedit&filename=main.txt&root=0', 'img' => 'mainadmin.gif');
$menus['adminedit4']=array('group' => 'admin24', 'label' => 'admin37','href' => 'page=adminedit&filename=countries.txt&root=1&wysiwyg=0', 'img' => 'countries.gif');
$menus['errorlogadmin']=array('group' => 'admin25', 'label' => 'admin26','href' => 'page=errorlogadmin', 'img' => 'errorlog.gif');
$menus['accesslogadmin']=array('group' => 'admin25', 'label' => 'admin31','href' => 'page=accesslogadmin', 'img' => 'accesslog.gif');
$menus['stockadmin']=array('group' => 'admin25', 'label' => 'admin32','href' => 'page=stockadmin', 'img' => 'stockadmin.gif');
$menus['admin']=array('group' => 'admin25', 'label' => 'admin7','href' => 'page=admin&adminaction=optimize_tables', 'img' => 'optimize.gif');
$menus['mailinglist']=array('group' => 'admin25', 'label' => 'admin35','href' => 'page=mailinglist', 'img' => 'mailinglist.gif');
?>