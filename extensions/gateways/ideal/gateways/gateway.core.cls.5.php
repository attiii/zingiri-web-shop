<?php

	class GatewayCore
	{
		protected $oRecord = false;
		protected $aSettings = false;


		/*
			Static methods
		*/

		// Create a random code with N digits.
		public static function randomCode($iLength = 64)
		{
			$aCharacters = array('a', 'b', 'c', 'd', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9');

			$sResult = '';

			for($i = 0; $i < $iLength; $i++)
			{
				$sResult .= $aCharacters[rand(0, sizeof($aCharacters) - 1)];
			}

			return $sResult;
		}

		// Retrieve ROOT url of script
		public static function getRootUrl($iParent = 0)
		{
			$sRootUrl = get_option('home').'/';
			return $sRootUrl;
			
			//ebo:remove this
			$sRootUrl = '';

			if(isset($_SERVER['HTTPS']) && (strcasecmp($_SERVER['HTTPS'], 'ON') === 0))
			{
				$sRootUrl .= 'https://';
			}
			else
			{
				$sRootUrl .= 'http://';
			}

			$sRootUrl .= $_SERVER['HTTP_HOST'];

/*
			// Port detection
			if((strpos($sRootUrl, ':') === false) && isset($_SERVER['SERVER_PORT']) && (strcmp($_SERVER['SERVER_PORT'], '80') !== 0))
			{
				$sRootUrl .= ':' . $_SERVER['SERVER_PORT'];
			}
*/

			$sRootUrl .= '/';

			if(isset($_SERVER['SCRIPT_NAME']))
			{
				$a = explode('/', substr($_SERVER['SCRIPT_NAME'], 1));

				while(sizeof($a) > ($iParent + 1))
				{
					$sRootUrl .= $a[0] . '/';
					array_shift($a);
				}
			}

			return $sRootUrl;
		}


		// Print html to screen
		public static function output($sHtml, $sImage = false)
		{
			// Detect ideal folder
			$sRootUrl = GatewayCore::getRootUrl();
			
			if(($iStrPos = strrpos($sRootUrl, '/ideal/')) !== false)
			{
				$sRootUrl = substr($sRootUrl, 0, $iStrPos) . '/';
			}
			
			echo '
		<div class="wrapper">
			' . ($sImage ? $sImage : '<p><img alt="iDEAL" border="0" src="' . ZING_URL . 'extensions/gateways/ideal/images/ideal.gif"></p>') . '

' . $sHtml . '

		</div>';

			//exit;
		}

		
		public function __construct()
		{
			$this->init();
		}
		
		public function __call($sName, $aArguments)
		{
			if(strcmp($sName, 'doSetup') === 0)
			{
				GatewayCore::output('<p>Invalid setup request.</p>');
			}
			elseif(strcmp($sName, 'doTransaction') === 0)
			{
				GatewayCore::output(wsGatewayMessage('Invalid transaction request.'));
			}
			elseif(strcmp($sName, 'doReturn') === 0)
			{
				GatewayCore::output(wsGatewayMessage('Invalid return request.'));
			}
			elseif(strcmp($sName, 'doReport') === 0)
			{
				GatewayCore::output(wsGatewayMessage('Invalid report request.'));
			}
			elseif(strcmp($sName, 'doValidate') === 0)
			{
				GatewayCore::output('<p>This gateway doesn\'t support a validation request.</p>');
			}
			else
			{
				die('Unknown method "' . $sName . '".');
			}
		}

	
		// Load iDEAL settings
		public function init()
		{
			$this->aSettings = gateway_getSettings();
		}


		// Load record from table #transactions using order_id and order_code
		public function getRecordByOrder($sOrderId, $sOrderCode = false)
		{
			$sql = "SELECT * FROM `" . DATABASE_PREFIX . "transactions` WHERE (`order_id` = '" . addslashes($sOrderId) . "')" . ($sOrderCode === false ? "" : " AND (`order_code` = '" . addslashes($sOrderCode) . "')") . " AND (`transaction_method` = '" . addslashes($this->aSettings['GATEWAY_METHOD']) . "') ORDER BY `id` DESC LIMIT 1;";
			$oRecordset = mysql_query($sql) or die('QUERY: ' . $sql . '<br><br>ERROR: ' . mysql_error() . '<br><br>FILE: ' . __FILE__ . '<br><br>LINE: ' . __LINE__);

			if(mysql_num_rows($oRecordset))
			{
				$this->oRecord = mysql_fetch_assoc($oRecordset);
			}
			else
			{
				$this->oRecord = false;
			}

			return $this->oRecord;
		}


		// Load record from table #transactions using transaction_id and transaction_code
		public function getRecordByTransaction($sTransactionId, $sTransactionCode = false)
		{
			$sql = "SELECT * FROM `" . DATABASE_PREFIX . "transactions` WHERE (`transaction_id` = '" . addslashes($sTransactionId) . "')" . ($sTransactionCode === false ? "" : " AND (`transaction_code` = '" . addslashes($sTransactionCode) . "')") . " AND (`transaction_method` = '" . addslashes($this->aSettings['GATEWAY_METHOD']) . "') ORDER BY `id` DESC LIMIT 1;";
			$oRecordset = mysql_query($sql) or die('QUERY: ' . $sql . '<br><br>ERROR: ' . mysql_error() . '<br><br>FILE: ' . __FILE__ . '<br><br>LINE: ' . __LINE__);

			if(mysql_num_rows($oRecordset))
			{
				$this->oRecord = mysql_fetch_assoc($oRecordset);
			}
			else
			{
				$this->oRecord = false;
			}

			return $this->oRecord;
		}


		// Update transaction record
		public function save($oRecord = false)
		{
			if($oRecord === false)
			{
				$oRecord = $this->oRecord;
			}

			if($oRecord)
			{
				$sql = "UPDATE `" . DATABASE_PREFIX . "transactions` SET";

				foreach($oRecord as $k => $v)
				{
					$sql .= "`" . $k . "` = '" . addslashes($v) . "', ";
				}

				$sql = substr($sql, 0, -2) . " WHERE `id` = '" . $oRecord['id'] . "' LIMIT 1;";
				mysql_query($sql) or die('QUERY: ' . $sql . '<br><br>ERROR: ' . mysql_error() . '<br><br>FILE: ' . __FILE__ . '<br><br>LINE: ' . __LINE__);

				return true;
			}

			return false;
		}		
	}

?>