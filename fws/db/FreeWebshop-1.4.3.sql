-- special status for temporary orders with payments in process 
update `##order` set status=0 where `id` in (select `orderid` from `##basket` where `status`=0);

-- default product image
ALTER TABLE `##product` ADD `DEFAULTIMAGE` VARCHAR( 255 ) NULL DEFAULT NULL;  

-- Google checkout merchant ID parsing
UPDATE `##payment` SET `code` = '<form name="autosubmit" method="POST" action="https://checkout.google.com/api/checkout/v2/checkoutForm/Merchant/%merchantid%" accept-charset="utf-8"> <input type="hidden" name="item_name_1" value="%webid%"/> <input type="hidden" name="item_description_1" value="%webid%"/> <input type="hidden" name="item_quantity_1" value="1"/> <input type="hidden" name="item_price_1" value="%total%"/> <input type="hidden" name="item_currency_1" value="USD"/> <input type="hidden" name="tax_rate" value="0"/> <input type="hidden" name="_charset_"/> <input type="image" name="Google Checkout" alt="Fast checkout through Google" src="http://checkout.google.com/buttons/checkout.gif?merchant_id=%merchantid%&w=180&h=46&style=white&variant=text&loc=en_US" height="46" width="180"/> </form> ' WHERE  `code` LIKE '%YOUR_MERCHANT_ID%';
