<?php
class invoice {

	var $id="";
	var $totprice_ex_vat=0;
	var $totprice_in_vat=0;
	var $vat=0;
	var $lines=array();
	var $c=0;
	var $duedate;
	var $currency;
	var $customerid;
	var $orderid=0;

	function __construct($id=0) {
		$this->settings=new settings();
		if ($id) {
			$this->id=$id;
			$invoice=new db();
			$invoice->select("select * from ##invoice where id=".$this->id);
			$invoice->next();
			$this->duedate=$invoice->get('duedate');
			$this->currency=$invoice->get('currency');
			$this->customerid=$invoice->get('customerid');
			$this->getCustomer();

		}
	}

	function getCustomer() {
		$this->customer=new db();
		$this->customer->select("select * from ##customer where id=".$this->customerid);
		$this->customer->next();
	}

	function create($duedate,$currency,$customerid,$paymentid) {
		global $order_prefix,$order_suffix;
		$this->duedate=$duedate;
		$this->currency=$currency;
		$this->customerid=$customerid;
		$this->paymentid=$paymentid;
		$this->getCustomer();
	}

	function addPackage($package) {
		$this->c++;
		$price=new price($package->productid,$package->frequency,$this->currency);
		$this->lines[$this->c]['REFERENCE']=$package->id;
		$this->lines[$this->c]['DESCRIPTION']=$package->domain;
		$this->lines[$this->c]['TYPE']='10';
		$this->lines[$this->c]['PRICE']=$price->in_vat;
		$this->totprice_ex_vat+=$price->ex_vat;
		$this->totprice_in_vat+=$price->in_vat;
		$this->vat=$this->totprice_in_vat-$this->totprice_ex_vat;
	}

	function addDomain($domain) {
		$this->c++;
		$this->lines[$this->c]['REFERENCE']=$domain->id;
		$this->lines[$this->c]['DESCRIPTION']=$domain->name;
		$this->lines[$this->c]['TYPE']='20';
		$this->lines[$this->c]['PRICE']=$domain->in_vat;
		$this->totprice_ex_vat+=$domain->ex_vat;
		$this->totprice_in_vat+=$domain->in_vat;
		$this->vat=$this->totprice_in_vat-$this->totprice_ex_vat;
	}
	
	function close() {
		//create invoice
		$inv=array();
		$inv['CUSTOMERID']=$this->customerid;
		$inv['DUEDATE']=$this->duedate;
		$inv['STATUS']='20';
		$inv['TOPAY']=$this->totprice_in_vat;
		$inv['CURRENCY']=$this->currency;
		$inv['DATE_CREATED']=date("Y-m-d H:i:s");
		$sql=new db();
		$this->id=$sql->insertRecord("invoice",null,$inv);

		//add line items
		foreach ($this->lines as $c => $line) {
			$line['INVOICEID']=$this->id;
			$line['DATE_CREATED']=date("Y-m-d H:i:s");
			$sql->insertRecord("invoiceline",null,$line);
		}

		//update webid & PDF
		$date_array = GetDate();
		$this_year = $date_array['year'];
		$this->webid = $order_prefix . $this_year. str_pad($this->id, 6, "0", STR_PAD_LEFT) . $order_suffix;
		$random = CreateRandomCode(5);
		$this->pdf = $this->webid."_".$random.".pdf";	
		
		$key=array();
		$inv=array();
		$key['ID']=$this->id;
		$inv['PDF']=$this->pdf;
		$inv['WEBID']=$this->webid;
		$sql->updateRecord("invoice",$key,$inv);
		
		//create output based on template
		$this->prepareMessage();
		
		//prepare PDF
		$this->makePDF();
		
		//payment code
		$this->paynow=$this->paymentCode();
	}

	function prepareMessage($type='invoice') {
		global $txt;
		global $page_footer;
		
		$t=new template(null,$type);
		$t->replace('DUEDATE',$this->duedate);
		$t->replace('INVOICENUMBER',$this->id);
		$t->replace('ORDERNUMBER',$this->orderid);
		$t->replace('FIRSTNAME',$this->customer->get('initials'));
		$t->replace('LASTNAME',$this->customer->get('lastname'));
		$t->replace('AMOUNT',$this->currency." ".$this->totprice_in_vat);
		$t->replace('COMPANY',$this->settings->shopname);
		$t->replace('SLOGAN',$this->settings->slogan);
		$t->replace('BILLINGEMAIL',$this->settings->webmaster_mail);
		//$t->replace('PAYMENT',$this->paymentCode());


		$invoicedetails='<table>';
		foreach ($this->lines as $c => $line) {
			$invoicedetails.='<tr>';
			$invoiceline='';
			$invoiceline.='<td>'.$txt['invoicetype'.$line['TYPE']].'</td>';
			$invoiceline.='<td>'.$line['REFERENCE'].'</td>';
			$invoiceline.='<td>'.$line['DESCRIPTION'].'</td>';
			$invoiceline.='<td>'.$line['PRICE'].'</td>';
			$invoicedetails.=$invoiceline;
			$invoicedetails.='</tr>';
		}
		$invoicedetails.='</table>';
		$t->replace('INVOICELINES',$invoicedetails);

		$t->replace('CLIENTURL','<a href="'.$this->settings->shopurl.'">'.$this->settings->shopurl.'</a>');
		$t->replace('FOOTER',$page_footer);
		$this->content=$t->content;
		$this->title=$t->title;
	}

	function send($type='invoice') {
		if (empty($this->content)) $this->prepareMessage($type);

		$ret=mymail($this->settings->webmaster_mail, $this->customer->get('email'), $this->title, $this->content, $this->settings->charset);
		//if ($ret) echo 'Email sent to '.$this->customer->get('email').BR;
		//else echo 'Email sending failed'.BR;
		return $ret;
	}

	function content($type='invoice') {
		if (empty($this->content)) $this->prepareMessage($type);
		return $this->content;
	}

	function overdue() {
		$this->send('overdue');
		$k['ID']=$this->id;
		$d['REMINDER']=date('Y-m-d');
		$sql=new db();
		$sql->updateRecord('invoice',$k,$d);
	}

	function paymentCode() {
		global $dbtablesprefix;
		global $shopurl,$lang,$sales_mail;
		$query = sprintf("SELECT * FROM `".$dbtablesprefix."payment` WHERE `id` = %s", quote_smart($this->paymentid));
		$sql = mysql_query($query) or die(mysql_error());

		// read the payment method
		if ($row = mysql_fetch_row($sql)) {
			$payment_descr = $row[1];
			$payment_code = $row[2];
			// there could be some variables in the code, like %total%, %webid% and %shopurl% so lets update them with the correct values
			//$payment_code = str_replace("%total_nodecimals%", $total_nodecimals, $payment_code);
			$payment_code = str_replace("%total%", $this->totprice_in_vat, $payment_code);
			$payment_code = str_replace("%webid%", $this->id, $payment_code);
			$payment_code = str_replace("%shopurl%", $shopurl, $payment_code);
			$payment_code = str_replace("%currency%", $this->currency, $payment_code);
			$payment_code = str_replace("%lang%", $lang, $payment_code);
			$payment_code = str_replace("%customer%", $this->customer->get('ID'), $payment_code);
			$payment_code = str_replace("%firstname%", $this->customer->get('INITIALS'), $payment_code);
			$payment_code = str_replace("%lastname%", $this->customer->get('LASTNAME'), $payment_code);
			$payment_code = str_replace("%address%", $this->customer->get('ADDRESS'), $payment_code);
			$payment_code = str_replace("%city%", $this->customer->get('CITY'), $payment_code);
			$payment_code = str_replace("%state%", $this->customer->get('STATE'), $payment_code);
			$payment_code = str_replace("%zip%", $this->customer->get('ZIP'), $payment_code);
			$payment_code = str_replace("%country%", $this->customer->get('COUNTRY'), $payment_code);
			$payment_code = str_replace("%email%", $this->customer->get('EMAIL'), $payment_code);
			$payment_code = str_replace("%phone%", $this->customer->get('PHONE'), $payment_code);
			$payment_code = str_replace("%ipn%", ZING_URL.'zhg/module-hosting/gateways', $payment_code);
			$payment_code = str_replace("%paypal_email%", $sales_mail, $payment_code);
			$payment_code = str_replace("%return%", $shopurl . '/index.php?page=my', $payment_code);
			$payment_code = str_replace("%cancel%", $shopurl . '/index.php?page=my', $payment_code);
		}
		return $payment_code;
	}

	function makePDF() {
		global $create_pdf,$dbtablesprefix,$orders_dir;
		// make pdf
		$pdf = "";
		$fullpdf = "";
		if ($create_pdf == 1) {
			require_once(ZING_LOC."comlib/addons/dompdf/dompdf_config.inc.php");
			$dompdf = new DOMPDF();
			$dompdf->load_html($this->content);
			$dompdf->render();
			$output = $dompdf->output();
			$fullpdf = $orders_dir."/".$this->pdf;
			file_put_contents($fullpdf, $output);
		}
	}

}
?>