<?php

/**
* TargetPay Example class
* 
* SVE
* 
* 11-01-2009
*/
  
abstract class TargetPay {
    
   /**
   * @var int rtlo partner ID 
   */
    protected $intRtlo = 0;
    
    
    /**
    * @desc construction class
    * @Var int rtlo partner ID
    */
    
    public function __construct( $intRtlo ) {
        $this->setRtlo ( $intRtlo );
    }
    
   /**
   * Get response for a targetpay request
   * 
   * @param array $aParams
   * @return string
   */
   
    protected function getResponse( $aParams, $sRequest = 'https://www.targetpay.com/api/plugandpay?'  ) {
      
        # convert params
        $strParamString = $this->makeParamString( $aParams );

        # get request
        $strResponse = @file_get_contents( $sRequest . $strParamString);
        if ( $strResponse === false )
            throw new Exception('Could not fetch response');
        
        return $strResponse;
    
    }
    
   /**
   * Make string from params
   * 
   * @param array $aParams
   * @return string
   */
   
   protected function makeParamString( $aParams ) {
      
        $strString = '';
        foreach ( $aParams as $strKey => $strValue ) 
          $strString .= '&' . urlencode($strKey) . '=' . urlencode($strValue);
        
        # remove first &  
        return substr( $strString ,1 )  ;          
    
    }
    
    /**
    * Get the base request with IP, RTLO, domain,
    * 
    * @return array
    */
    protected function getBaseRequest() {
      
      # return array with base parameters
      $aParams = array();
      $aParams['action'] = 'start';
      $aParams['ip'] = $_SERVER['REMOTE_ADDR'];
      $aParams['domain'] = $this->strDomain ;
      $aParams['rtlo'] = $this->intRtlo ;
        
        return $aParams;
    
    }
    
    /**
    * @desc set domain
    * 
    */
    
    public function setDomain ( $strDomain ) {
        $this->strDomain = $strDomain;   
    }
    
    /**
    * @desc set rtlo partner id
    * @Var int rtlo partner ID
    */
    
    public function setRtlo ( $intRtlo ) {
        $this->intRtlo = $intRtlo;   
    }
    
    /**
    * Return rtlo
    *
    * @return int
    */ 
     
    public function getRtlo () {
        return $this->intRtlo;   
    }     
}


/**
* TargetPay ideal example class
* 
*  SVE
*  11-01-2009
*/
  
  
class TargetPayIdeal extends TargetPay {
  
  # ofcourse construct
  public function __construct( $intRtlo ) {
    
    # call parent constructor 
    parent::__construct( $intRtlo );
      
  }
  
  /**
  * @Desc start payment
  * @Return array ( trxid, idealReturnUrl )
  */
  
    public function startPayment () {
      
      try {
          
          
          # Build parameter string
          //$aParameters = $this->getBaseRequest();
          $aParameters = array();
          $aParameters['rtlo'] = $this->intRtlo;
          $aParameters['bank'] = $this->idealIssuer;
          $aParameters['description'] = $this->strDescription;
          $aParameters['currency'] = $this->strCurrency;
          $aParameters['amount'] =  $this->idealAmount;
          $aParameters['language'] = $this->strLanguage;
          $aParameters['returnurl'] = $this->strReturnUrl;
          $aParameters['reporturl'] = $this->strReportUrl;

          # do request
          $strResponse = $this->getResponse( $aParameters, 'https://www.targetpay.com/ideal/start?');
          $aResponse = explode('|', $strResponse );

          # Bad response
          if ( !isset ( $aResponse[1] ) ) {
            throw new Exception( 'Error' . $aResponse[0] );    
          }
          
          $iTrxID = explode ( ' ', $aResponse[0] );
          
          # We return TRXid and url to rederict
          return array ( $iTrxID[1], $aResponse[1] );
              
      } 
      catch( Exception $e ) {
      
        # error, could not proceed 
        echo $e->getMessage();
      
      }    
  }
 
  
  
  
  /**
   * Validate the payment now by trxId
   *
   * @return bool
   */
  
   public function validatePayment ( $intTrxId, $iOnce = 1, $iTest = 0 ) {
       
       try {
        
           # Build parameter string
           $aParameters = array();
           $aParameters['rtlo'] = $this->intRtlo;
           $aParameters['trxid'] = $intTrxId;
           $aParameters['once'] = $iOnce;
           $aParameters['test'] = $iTest; 
           
           # do request
           $strResponse = $this->getResponse ( $aParameters , 'https://www.targetpay.com/ideal/check?');
           $aResponse = explode('|', $strResponse );



		   // iDEAL Checkout Status Fix
		   if(substr($aResponse[0], 0, 9) == '000000 OK')
		   {
               return 'SUCCESS';
		   }
		   elseif(substr($aResponse[0], 0, 6) == 'TP0010')
		   {
               return 'OPEN';
		   }
		   elseif(substr($aResponse[0], 0, 6) == 'TP0011')
		   {
               return 'CANCELLED';
		   }
		   elseif(substr($aResponse[0], 0, 6) == 'TP0012')
		   {
               return 'EXPIRED';
		   }
		   else
		   {
               return 'FAILURE';
		   }


		   
           # Bad response
           if (  $aResponse[0] != '000000 OK' ) {
                throw new Exception( $aResponse[0] );    
           }
           
           return true;
       
       }    
       catch( Exception $e ) {
      
        # error, could not proceed 
        //echo $e->getMessage();
      
      } 
               
   }
   
  
  /**
  * 
  * @Desc set ideal return url
  * 
  */
  
  public function setIdealReturnUrl ( $strReturnUrl ) {
    $this->strReturnUrl = $strReturnUrl;  
     return $this;   
  }
  
  /**
  * 
  * @Desc set ideal return url
  * 
  */
  
  public function setIdealReportUrl ( $strReportUrl ) {
    $this->strReportUrl = $strReportUrl;  
     return $this;   
  }
  
  /**
  * 
  * @Desc set ideal description for transaction
  * 
  */
  
  public function setIdealDescription ( $strDescription ) {
    $this->strDescription = $strDescription;  
     return $this;   
  }
  
  /**
  * @Desc set ideal amount
  * 
  */
  
  public function setIdealAmount ( $intIdealAmount ) {
    
      # Is this a valid ideal amount?
      if ( is_numeric ( $intIdealAmount ) && $intIdealAmount > 0 ) {
        $this->idealAmount = $intIdealAmount;    
      }
      else {
        throw new Exception( 'Invalid ideal amount, please check.' );   
      }
       return $this;
  }
  
  /**
  * @Desc set ideal issuer
  * 
  */
  
  public function setIdealissuer ( $intIdealIssuer ) {

  	  $this->idealIssuer = $intIdealIssuer;    

  	  return $this;
  }
  
  /**
  * Get available issuers, and return array
  *
  * @return array
  */
    
//  public static function getBanks() {
//    return array('0031'=>'ABN AMRO Bank', '0761'=>'ASN Bank', '0081'=>'Fortis Bank', '0091'=>'Friesland Bank', '0721'=>'ING Bank', '0021'=>'Rabobank', '0751'=>'SNS Bank', '0771'=>'SNS Regio Bank');
//  }
  
}
  
  
?>