<?
Class CNovaPoshta{	
	
	protected $key;
	
	function __construct($API_KEY = '')
	{
		$this->setKey($API_KEY);
	}
	
	private function setKey($key) {
		$this->key = $key;
		return $this;
	}
	
	private function getKey() {
		return $this->key;
	}
	
	private function array2xml(array $array, $xml = false){
		($xml === false) AND $xml = new \SimpleXMLElement('<root/>');
		foreach($array as $key => $value){
			if (is_array($value)){
				$this->array2xml($value, $xml->addChild($key));
			} else {
				$xml->addChild($key, $value);
			}
		}
		return $xml->asXML();
	}
	
	private function sendRequest($xml) {
       
 	    $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.novaposhta.ua/v2.0/xml/');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
	
	public function getCity($findByString = '', $page = 0) {
		
		$xml = $this->array2xml( 
			array(
				'apiKey' => $this->getKey(), 
				'modelName' => 'Address',
				'calledMethod' => 'getWarehouses',
				'methodProperties' => array(
										'Page' => $page,
										'FindByString' => $findByString,
										'Ref' => $ref,
									  )
									  
			)
		);
		
		return $this->sendRequest($xml);
   	
	}
	
	public function getSklad($cityRef, $page = 0) {
		
		$xml = $this->array2xml( 
			array(
				'apiKey' => $this->getKey(), 
				'modelName' => 'Address',
				'calledMethod' => 'getWarehouses',
				'methodProperties' => array(
										'Documents' => array(
											'CityRef' => $cityRef,
											'Page' => $page
										)
									  )
			)
		);
		
		$result = $this->sendRequest($xml);
   		
		if($result)
		{	
			print $result;
		}
	}
	
	public function getStatus($code = '') {
        
		$xml = $this->array2xml( 
			array(
				'apiKey' => $this->getKey(), 
				'modelName' => 'InternetDocument',
				'calledMethod' => 'documentsTracking',
				'methodProperties' => array(
										'Documents' => array(
											'item' => $code
										)
									  )
			)
		);	
		
		$result = $this->sendRequest($xml);
   		
		if($result)
		{
			header ("content-type: text/xml");
			print $result;
		}
	}
}	
?>