<?php
/*  shipping.php
    Copyright 2006, 2007, 2008 Elmar Wenners
    Support site: http://www.chaozz.nl

    This file is part of FreeWebshop.org.

    FreeWebshop.org is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    FreeWebshop.org is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with FreeWebshop.org; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/
?>
<?php if ($index_refer <> 1) { exit(); } ?>
<?php include (dirname(__FILE__)."/includes/checklogin.inc.php"); ?>
<?php
if (LoggedIn() == true) {
	global $shippingid,$weightid,$paymentid,$notes,$discount_code;
	CheckoutNextStep();
    // if the cart is empty, then you shouldn't be here
   if (CountCart($customerid) == 0) {
      PutWindow($gfx_dir, $txt['cart1'], $txt['cart2'], "carticon.gif", "50");	   
	  exit();
   }
   
   // the shipping and payment selection is diveded in 2 steps
   $step =1;
   if (!empty($_POST['step'])) {
 	   $step=2;
   }
   elseif (!empty($_GET['step'])) {
 	   $step=2;
   }
   if ($step == 2 && isset($_POST['shipping'])) { list($weightid, $shippingid) = explode(":", $_POST['shipping']); }
   elseif ($step == 2 && isset($_GET['shipping'])) { $weightid=$_GET['weightid']; $shippingid=$_GET['shippingid']; }
?>
    <?php
      	CheckoutShowProgress();
    if ($step == 1) {
			//echo "<h4><img src=\"".$gfx_dir."/1_.gif\" alt=\"1\">&nbsp;<img src=\"".$gfx_dir."/arrow.gif\" alt=\"2\">&nbsp;<img src=\"".$gfx_dir."/2.gif\" alt=\"step 2\">&nbsp;<img src=\"".$gfx_dir."/3_.gif\" alt=\"3\">&nbsp;<img src=\"".$gfx_dir."/4_.gif\" alt=\"4\">&nbsp;<img src=\"".$gfx_dir."/5_.gif\" alt=\"5\"></h4><br /><br />";
	?>
	<table width="100%" class="datatable">
    <caption><?php echo $txt['shipping1']; ?></caption>
    <tr><td>
        <form method="post" action="<?php zurl('index.php?page=shipping');?>">
         <input type="hidden" name="step" value="2">
          <?php echo $txt['shipping2'] ?><br />
          <SELECT NAME="shipping">
           <?php 
                 // find out the shipping methods
                 $query="SELECT * FROM `".$dbtablesprefix."shipping` ORDER BY `id`";
                 $sql = mysql_query($query) or die(mysql_error());
     
                 while ($row = mysql_fetch_row($sql)) {
	                    // there must be at least 1 payment option available, so lets check that
		                $pay_query="SELECT * FROM `".$dbtablesprefix."shipping_payment` WHERE `shippingid`=".$row[0];
		                $pay_sql = mysql_query($pay_query) or die(mysql_error());
	                    if (mysql_num_rows($pay_sql) <> 0) {
	                        if ($row[2] == 0 || ($row[2] == 1 && IsCustomerFromDefaultSendCountry($send_default_country) == 1)) { 
							    // now check the weight and the costs
								$cart_weight = WeighCart($customerid);
								$weight_query = "SELECT * FROM `".$dbtablesprefix."shipping_weight` WHERE '".$cart_weight."' >= `FROM` AND '".$cart_weight."' <= `TO` AND `SHIPPINGID` = '".$row[0]."'";
								$weight_sql = mysql_query($weight_query) or die(mysql_error());
								while ($weight_row = mysql_fetch_row($weight_sql)) { 
		                            echo "<OPTION VALUE=\"".$weight_row[0].":".$row[0]."\">".$row[1]."&nbsp;(".$currency_symbol_pre.myNumberFormat($weight_row[4],$number_format).$currency_symbol_post.")"; 
								}
	                        }
                        }
                 }
           ?>
          </SELECT>
      <?php
        }    
        else {
			//echo "<h4><img src=\"".$gfx_dir."/1_.gif\" alt=\"1\">&nbsp;<img src=\"".$gfx_dir."/2_.gif\" alt=\"step 2\">&nbsp;<img src=\"".$gfx_dir."/arrow.gif\" alt=\"2\">&nbsp;<img src=\"".$gfx_dir."/3.gif\" alt=\"3\">&nbsp;<img src=\"".$gfx_dir."/4_.gif\" alt=\"4\">&nbsp;<img src=\"".$gfx_dir."/5_.gif\" alt=\"5\"></h4><br /><br />";
	?>
	<table width="100%" class="datatable">
    <caption><?php echo $txt['shipping1']; ?></caption>
    <tr><td>
        <form method="post" action="<?php zurl('index.php?page=discount');?>">
         <input type="hidden" name="shippingid" value="<?php echo $shippingid; ?>">
         <input type="hidden" name="weightid" value="<?php echo $weightid; ?>">
         <?php echo $txt['shipping10'] ?><br />
          <SELECT NAME="paymentid">
           <?php 
                 // find out the payment methods
		         $query="SELECT * FROM `".$dbtablesprefix."shipping_payment` WHERE `shippingid`='".$shippingid."' ORDER BY `paymentid`";
		         $sql = mysql_query($query) or die(mysql_error());
                 
                 while ($row = mysql_fetch_row($sql)) {
		                 $query_pay="SELECT * FROM `".$dbtablesprefix."payment` WHERE `id`='".$row[1]."'";
		                 $sql_pay = mysql_query($query_pay) or die(mysql_error());
		                 
		                 while ($row_pay = mysql_fetch_row($sql_pay)) {
                                echo "<OPTION VALUE=\"".$row_pay[0]."\">".$row_pay[1]; 
                         }
                 }
           ?>
          </SELECT>
         <br />
         <br />
       	 <?php echo $txt['shipping3']."<br /><textarea name=\"notes\" rows=\"15\" cols=\"65\">".$pdescription."</textarea><br />"; ?>
      <?php
      
        }
      ?> 
         <br /><br /> 
         <div style="text-align:center;"><input type=submit value="<?php echo $txt['shipping9'] ?> >>"></div>
       </form>
	   </td>
    </tr>
   </table>
<?php } ?>   