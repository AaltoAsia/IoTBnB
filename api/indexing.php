<?php
//author: J. Robert
//creation date: 01/09/2017
//modification date: 01/09/2017

/* IMPORTANT FUNCTION FOR CALLING ODS API*/
function createSubdomain($omiName) {

    $data = array(
    'quota_license' => array(
    	'records' => 1000000000,
    	'api' => array(
    		'limit' => 10000000,
    		 'unit' => 'day'
    		),
    	'datasets' => 100000,
    	'records_by_dataset' => 100000000
    	),
    'properties' => array(
    	'ui.brand' => strtolower($omiName),
    	'ui.domain_title' => strtolower($omiName)
    	),
    'datasets' => [],
    'id' => strtolower($omiName),
    'owner' => 'bot@jeremy-robert.fr',
    'attributes' => [],
    'pages' => []
	);

	$jsonData = json_encode($data);
	$fullUrl = "https://biotope.opendatasoft.com/api/management/v2/subdomains/";
	$authorization = "Authorization: Basic <API_KEY/TOKEN>";
	$headerContent = array('Content-Type: application/json; charset=utf8', $authorization);

    $curl = curl_init($fullUrl);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headerContent);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $json_response = curl_exec($curl);
    
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	
	$createdSubdomain = $json_response;
	curl_close($curl);

	$obj = json_decode($createdSubdomain);

	if ($httpcode!=200) {
		return "err:".$httpcode;
	}
	else {
		return "OK";
	}
}

function createHarvester($omiName){
	$data = array(
		'fetcher' => 'omi_node',
  		'name' => strtolower($omiName)
		);

	$jsonData = json_encode($data);
	$fullUrl = "https://".strtolower($omiName)."-biotope.opendatasoft.com/api/management/v2/harvesters/";
	$authorization = "Authorization: Basic <API_KEY/TOKEN>";
	$headerContent = array('Content-Type: application/json; charset=utf8', $authorization);

    $curl = curl_init($fullUrl);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headerContent);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $json_response = curl_exec($curl);

    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	$createdHarvester = $json_response;
	curl_close($curl);
	
	$obj = json_decode($createdHarvester);

	if ($httpcode!=200) {
		return "err:".$httpcode;
	}
	else {
		return "OK";
	}
}

function setUpHarvester($omiName, $omiURL, $omiVersion){
	$data = array(
			'schedules' => null,
  			'name' => strtolower($omiName),
  			'version' => 1,
  			'params' => array(
  				'restrict_visibility' => false,
  				'delete' => false,
  				'url' => $omiURL,
  				"omi_node_version" => $omiVersion,
  				'infoitems_limit' => 10
  				)
			);

		$jsonData = json_encode($data);
		$fullUrl = "https://".strtolower($omiName)."-biotope.opendatasoft.com/api/management/v2/harvesters/".strtolower($omiName);
		$authorization = "Authorization: Basic <API_KEY/TOKEN>";
		$headerContent = array('Content-Type: application/json; charset=utf8', $authorization);

    	$curl = curl_init($fullUrl);
    	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
    	curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
    	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    	curl_setopt($curl, CURLOPT_HTTPHEADER, $headerContent);
    	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    	$json_response = curl_exec($curl);

    	$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		$createdHarvester = $json_response;
		curl_close($curl);

		//$message = "ongoing!";
	
		$obj = json_decode($createdHarvester);

	if ($httpcode!=200) {
		return "err:".$httpcode;
	}
	else {
		return "OK";
	}
}

function startHarvester($omiName){
		$fullUrl = "https://".strtolower($omiName)."-biotope.opendatasoft.com/api/management/v2/harvesters/".strtolower($omiName)."/start";
		$authorization = "Authorization: Basic <API_KEY/TOKEN>";
		$headerContent = array('Content-Type: application/json; charset=utf8', $authorization);

    	$curl = curl_init($fullUrl);
    	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
    	//curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
    	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    	curl_setopt($curl, CURLOPT_HTTPHEADER, $headerContent);
    	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    	$json_response = curl_exec($curl);

    	$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		$createdHarvester = $json_response;
		curl_close($curl);

		//$message = "ongoing!";
	
		$obj = json_decode($createdHarvester);

	if ($httpcode!=200) {
		return "err:".$httpcode;
	}
	else {
		return "OK";
	}

}

function publishDatasets($omiName){
		$fullUrl = "https://".strtolower($omiName)."-biotope.opendatasoft.com/api/management/v2/harvesters/".strtolower($omiName)."/publish";
		$authorization = "Authorization: Basic <API_KEY/TOKEN>";
		$headerContent = array('Content-Type: application/json; charset=utf8', $authorization);

    	$curl = curl_init($fullUrl);
    	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
    	//curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
    	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    	curl_setopt($curl, CURLOPT_HTTPHEADER, $headerContent);
    	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    	$json_response = curl_exec($curl);

    	$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		$createdHarvester = $json_response;
		curl_close($curl);

		//$message = "ongoing!";
	
		$obj = json_decode($createdHarvester);

	if ($httpcode!=200) {
		return "err:".$httpcode;
	}
	else {
		return "OK";
	}

}

function createHarvesterODSdomain($omiName){
	$data = array(
		'fetcher' => 'ods',
  		'name' => strtolower($omiName)
		);

	$jsonData = json_encode($data);
	$fullUrl = "https://biotope.opendatasoft.com/api/management/v2/harvesters/";
	$authorization = "Authorization: Basic <API_KEY/TOKEN>";
	$headerContent = array('Content-Type: application/json; charset=utf8', $authorization);

    $curl = curl_init($fullUrl);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headerContent);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $json_response = curl_exec($curl);

    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	$createdHarvester = $json_response;
	curl_close($curl);
	
	$obj = json_decode($createdHarvester);

	if ($httpcode!=200) {
		return "err:".$httpcode;
	}
	else {
		return "OK";
	}
}

function setUpHarvesterODSdomain($omiName, $omiURL, $themes){
	$data = array(
			'schedules' => null,
  			'name' => strtolower($omiName),
  			'version' => 1,
  			'params' => array(
  				'restrict_visibility' => false,
  				'delete' => false,
  				'domain_id' => "http://".strtolower($omiName)."-biotope.opendatasoft.com/",
  				'api_key' => '<API_KEY/TOKEN>',
  				'forced_metas' => array(
  					'custom' => array(
  						'omi-node-url' => $omiURL,
  						'price' => 0,
  						'reputation' => 0,
  						'type' => $themes))
  				)
			);

		$jsonData = json_encode($data);
		$fullUrl = "https://biotope.opendatasoft.com/api/management/v2/harvesters/".strtolower($omiName);
		$authorization = "Authorization: Basic <API_KEY/TOKEN>";
		$headerContent = array('Content-Type: application/json; charset=utf8', $authorization);

    	$curl = curl_init($fullUrl);
    	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
    	curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
    	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    	curl_setopt($curl, CURLOPT_HTTPHEADER, $headerContent);
    	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    	$json_response = curl_exec($curl);

    	$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		$createdHarvester = $json_response;
		curl_close($curl);

		//$message = "ongoing!";
	
		$obj = json_decode($createdHarvester);

	if ($httpcode!=200) {
		return "err:".$httpcode;
	}
	else {
		return "OK";
	}
}

function setUpHarvesterODSdomain2($omiName, $omiURL, $themes, $key){
	$data = array(
			'schedules' => null,
  			'name' => strtolower($omiName),
  			'version' => 1,
  			'params' => array(
  				'restrict_visibility' => false,
  				'delete' => false,
  				'domain_id' => "http://".strtolower($omiName)."-biotope.opendatasoft.com/",
  				'api_key' => $key,
  				'forced_metas' => array(
  					'custom' => array(
  						'omi-node-url' => $omiURL,
  						'price' => 0,
  						'reputation' => 0,
  						'type' => $themes))
  				)
			);

		$jsonData = json_encode($data);
		$fullUrl = "https://biotope.opendatasoft.com/api/management/v2/harvesters/".strtolower($omiName);
		$authorization = "Authorization: Basic <API_KEY/TOKEN>";
		$headerContent = array('Content-Type: application/json; charset=utf8', $authorization);

    	$curl = curl_init($fullUrl);
    	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
    	curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
    	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    	curl_setopt($curl, CURLOPT_HTTPHEADER, $headerContent);
    	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    	$json_response = curl_exec($curl);

    	$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		$createdHarvester = $json_response;
		curl_close($curl);

		//$message = "ongoing!";
	
		$obj = json_decode($createdHarvester);

	if ($httpcode!=200) {
		return "err:".$httpcode;
	}
	else {
		return "OK";
	}
}

function startHarvesterODSdomain($omiName){
		$fullUrl = "https://biotope.opendatasoft.com/api/management/v2/harvesters/".strtolower($omiName)."/start";
		$authorization = "Authorization: Basic <API_KEY/TOKEN>";
		$headerContent = array('Content-Type: application/json; charset=utf8', $authorization);

    	$curl = curl_init($fullUrl);
    	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
    	//curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
    	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    	curl_setopt($curl, CURLOPT_HTTPHEADER, $headerContent);
    	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    	$json_response = curl_exec($curl);

    	$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		$createdHarvester = $json_response;
		curl_close($curl);

		//$message = "ongoing!";
	
		$obj = json_decode($createdHarvester);

	if ($httpcode!=200) {
		return "err:".$httpcode;
	}
	else {
		return "OK";
	}

}

function publishDatasetsODSdomain($omiName){
		$fullUrl = "https://biotope.opendatasoft.com/api/management/v2/harvesters/".strtolower($omiName)."/publish";
		$authorization = "Authorization: Basic <API_KEY/TOKEN>";
		$headerContent = array('Content-Type: application/json; charset=utf8', $authorization);

    	$curl = curl_init($fullUrl);
    	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
    	//curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
    	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    	curl_setopt($curl, CURLOPT_HTTPHEADER, $headerContent);
    	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    	$json_response = curl_exec($curl);

    	$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		$createdHarvester = $json_response;
		curl_close($curl);

		//$message = "ongoing!";
	
		$obj = json_decode($createdHarvester);

	if ($httpcode!=200) {
		return "err:".$httpcode;
	}
	else {
		return "OK";
	}

}

function getSubDomainCreationStatus($omiName){
		$fullUrl = "https://".strtolower($omiName)."-biotope.opendatasoft.com/";
		$authorization = "Authorization: Basic <API_KEY/TOKEN>";
		$headerContent = array('Content-Type: application/json; charset=utf8', $authorization);

    	$curl = curl_init($fullUrl);
    	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
    	//curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
    	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    	curl_setopt($curl, CURLOPT_HTTPHEADER, $headerContent);
    	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    	$json_response = curl_exec($curl);

    	$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		//$createdHarvester = $json_response;
		curl_close($curl);
		//$message = "ongoing!";	
		//$obj = json_decode($createdHarvester);

	if ($httpcode!=200) {
		return "err:".$httpcode;
	}
	else {
		return "OK";
	}
}

function getHarvesterInSubDomainCreationStatus($omiName){
		$fullUrl = "https://".strtolower($omiName)."-biotope.opendatasoft.com/api/management/v2/harvesters/".strtolower($omiName)."/";
		$authorization = "Authorization: Basic <API_KEY/TOKEN>";
		$headerContent = array('Content-Type: application/json; charset=utf8', $authorization);

    	$curl = curl_init($fullUrl);
    	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
    	//curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
    	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    	curl_setopt($curl, CURLOPT_HTTPHEADER, $headerContent);
    	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    	$json_response = curl_exec($curl);

    	$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		//$createdHarvester = $json_response;
		curl_close($curl);

		//$message = "ongoing!";
	
		//$obj = json_decode($createdHarvester);

		return $httpcode;
}

function getHarvesterCreationStatus($omiName){
		$fullUrl = "https://biotope.opendatasoft.com/api/management/v2/harvesters/".strtolower($omiName)."/";
		$authorization = "Authorization: Basic <API_KEY/TOKEN>";
		$headerContent = array('Content-Type: application/json; charset=utf8', $authorization);

    	$curl = curl_init($fullUrl);
    	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
    	//curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
    	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    	curl_setopt($curl, CURLOPT_HTTPHEADER, $headerContent);
    	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    	$json_response = curl_exec($curl);

    	$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		//$createdHarvester = $json_response;
		curl_close($curl);

		//$message = "ongoing!";
	
		//$obj = json_decode($createdHarvester);

		return $httpcode;
}

function getHarvestingStatus($omiName){
		$fullUrl = "https://".strtolower($omiName)."-biotope.opendatasoft.com/api/management/v2/harvesters/".strtolower($omiName)."/";
		$authorization = "Authorization: Basic <API_KEY/TOKEN>";
		$headerContent = array('Content-Type: application/json; charset=utf8', $authorization);

    	$curl = curl_init($fullUrl);
    	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
    	//curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
    	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    	curl_setopt($curl, CURLOPT_HTTPHEADER, $headerContent);
    	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    	$json_response = curl_exec($curl);

    	$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		$createdHarvester = $json_response;
		curl_close($curl);

		//$message = "ongoing!";
	
		$obj = json_decode($createdHarvester);

	if ($httpcode!=200) {
		return "err:".$httpcode;
	}
	else {
		return $obj->{'status'};
	}

}

function getHarvestingDomainStatus($omiName){
		$fullUrl = "https://biotope.opendatasoft.com/api/management/v2/harvesters/".strtolower($omiName)."/";
		$authorization = "Authorization: Basic <API_KEY/TOKEN>";
		$headerContent = array('Content-Type: application/json; charset=utf8', $authorization);

    	$curl = curl_init($fullUrl);
    	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
    	//curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
    	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    	curl_setopt($curl, CURLOPT_HTTPHEADER, $headerContent);
    	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    	$json_response = curl_exec($curl);

    	$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		$createdHarvester = $json_response;
		curl_close($curl);

		//$message = "ongoing!";
	
		$obj = json_decode($createdHarvester);

	if ($httpcode!=200) {
		return "err:".$httpcode;
	}
	else {
		return $obj->{'status'};
	}

}

//TEMPORARY CALL FOR GETTING NEW API-KEY BEFORE SETTING UP HARVESTER ON THE MAIN DOMAIN
function getSubdomainLoginPage($omiName){
	$fullUrl = "https://".strtolower($omiName)."-biotope.opendatasoft.com/login/?next=/explore/";
	//$headerContent = array('Content-Type: application/json;; charset=utf8');

	$cookies_file=dirname(__FILE__).'/cookie.txt';
		$curl = curl_init($fullUrl);
    	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
    	//curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
    	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    	//curl_setopt($curl, CURLOPT_HTTPHEADER, $headerContent);
    	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    	curl_setopt($curl, CURLOPT_COOKIEJAR, $cookies_file); 
    	$result = curl_exec($curl);

	//$result = curl_exec($curl);
	$start = stripos($result, "csrfmiddlewaretoken");
    $end = stripos($result, ' />"}');
    $tokenpart1 = substr($result,$start,$end-$start);

    $start1 = stripos($tokenpart1, "=");
    $end1 = stripos($tokenpart1, "
                ");
    $tokenpart2 = substr($tokenpart1,$start1,$end1-$start1);

    $start2 = stripos($tokenpart2, "'");
    $end2 = stripos($tokenpart2, "' />");
    $token = substr($tokenpart2,$start2+1,$end2-$start2-1);

    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    //$size = curl_getinfo($curl, CURLINFO_REQUEST_SIZE);
    //$contentType = curl_getinfo($curl, CURLINFO_CONTENT_TYPE);
    //$rep=html_entity_decode($curl);

	curl_close($curl);

	//$obj = json_decode($result);

	if ($httpcode!=200) {
		return "err:".$httpcode;
	}
	else {
		return $token;
	}
}

function connectSubDomain($omiName, $CSRFTOKEN){
	$fullUrl = "https://".strtolower($omiName)."-biotope.opendatasoft.com/login/";
	$refererUrl = "https://".strtolower($omiName)."-biotope.opendatasoft.com/login/?next=/explore/";
	$originUrl = "https://".strtolower($omiName)."-biotope.opendatasoft.com/";
	//$authorization = "Authorization: Basic Ym90QGplcmVteS1yb2JlcnQuZnI6T0RTX2JvdDIwMTc=";
	$XCSRFToken='X-CSRFToken: '.$CSRFTOKEN;
	$OriginHeader="Origin: ".$originUrl;
	$headerContent = array('Content-Type: application/x-www-form-urlencoded', $XCSRFToken);

	$cookies_file=dirname(__FILE__).'/cookie.txt';
    $curl = curl_init($fullUrl);
    curl_setopt($curl, CURLOPT_POST, true);
    //curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($curl, CURLOPT_POSTFIELDS,"username=".urlencode("<email>")."&password=".urlencode("<password>")."&next=".urlencode("/explore/"));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headerContent);
    curl_setopt($curl, CURLOPT_REFERER, $refererUrl);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_COOKIEJAR, $cookies_file); 
    curl_setopt($curl, CURLOPT_COOKIEFILE, $cookies_file); 
    $resp = curl_exec($curl);

	$start0 = stripos($resp, "<body>");
    $end0 = stripos($resp, "</body>");
    $body = substr($resp,$start0,$end0-$start0);

    $httpcode2 = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    $start = stripos($resp, "csrfmiddlewaretoken");
    $end = stripos($resp, ' />"');
    $tokenpart1 = substr($resp,$start,$end-$start);

    $start1 = stripos($tokenpart1, "=");
    $token = substr($tokenpart1,$start1+2,64);

	curl_close($curl);
	
	//$obj = json_decode($createdHarvester);

	if ($httpcode2!=200) {
		return "err:".$httpcode2;
	}
	else {
		return 
		$token ;
	}
}

function getSubDomainAccount($omiName, $CSRFTOKEN2){
	$fullUrl = "https://".strtolower($omiName)."-biotope.opendatasoft.com/account/my-api-keys";
	$refererUrl = "https://".strtolower($omiName)."-biotope.opendatasoft.com";
	$originUrl = "https://".strtolower($omiName)."-biotope.opendatasoft.com/explore/";
	$OriginHeader="Origin: ".$originUrl;

	$XCSRFToken='X-CSRFToken: '.$CSRFTOKEN2;
	$headerContent = array('Content-Type: application/x-www-form-urlencoded', $OriginHeader, $XCSRFToken);

	$cookies_file=dirname(__FILE__).'/cookie.txt';
    $curl = curl_init($fullUrl);
    //curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
    //curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
    //curl_setopt($curl, CURLOPT_POSTFIELDS,"username=".urlencode("bot@jeremy-robert.fr")."&password=".urlencode("ODS_bot2017")."&next=".urlencode("/explore/"));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headerContent);
    curl_setopt($curl, CURLOPT_REFERER, $refererUrl);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_COOKIEFILE, $cookies_file); 
    $resp = curl_exec($curl);

	$start0 = stripos($resp, "<body>");
    $end0 = stripos($resp, "</body>");
    $body = substr($resp,$start0,$end0-$start0);

    $httpcode2 = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	$start = stripos($resp, "csrfmiddlewaretoken");
    $end = stripos($resp, ' />"');
    $tokenpart1 = substr($resp,$start,$end-$start);

    $start1 = stripos($tokenpart1, "=");
    $token = substr($tokenpart1,$start1+2,64);

	curl_close($curl);
	
	//$obj = json_decode($createdHarvester);

	if ($httpcode2!=200) {
		return "err:".$httpcode2."--".$body;
	}
	else {
		return $token ;
	}
}

function getNewKey($omiName, $CSRFTOKEN3){
	$fullUrl = "https://".strtolower($omiName)."-biotope.opendatasoft.com/api/account/apikey/generate/";
	$refererUrl = "https://".strtolower($omiName)."-biotope.opendatasoft.com/account/apikey";
	$originUrl = "https://".strtolower($omiName)."-biotope.opendatasoft.com/";
	$OriginHeader="Origin: ".$originUrl;
	$authorization = "Authorization: Basic <API_KEY/TOKEN>";

	$XCSRFToken='X-CSRFToken: '.$CSRFTOKEN3;
	$headerContent = array('Content-Type: application/json', $authorization, $XCSRFToken);

	$data = array();
	$jsonData = json_encode($data);
	$cookies_file=dirname(__FILE__).'/cookie.txt';
    $curl = curl_init($fullUrl);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
    //curl_setopt($curl, CURLOPT_POSTFIELDS,"username=".urlencode("bot@jeremy-robert.fr")."&password=".urlencode("ODS_bot2017")."&next=".urlencode("/explore/"));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headerContent);
    curl_setopt($curl, CURLOPT_REFERER, $refererUrl);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    //curl_setopt($curl, CURLOPT_COOKIEJAR, $cookies_file); 
    curl_setopt($curl, CURLOPT_COOKIEFILE, $cookies_file); 
    $resp = curl_exec($curl);

	$start0 = stripos($resp, "<body>");
    $end0 = stripos($resp, "</body>");
    $body = substr($resp,$start0,$end0-$start0);

    $httpcode2 = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	$obj = json_decode($resp);

	$key=$obj->key; 


	curl_close($curl);
	
	//$obj = json_decode($createdHarvester);

	if ($httpcode2!=200) {
		return "err:".$httpcode2."--".$body;
	}
	else {
		return $key ;
	}
}
//END TEMPORARY CALLS FUNCTION


/* BEGINNING ACTIONS WHEN AN USER CLICKS ON SAVE*/
$data = file_get_contents("php://input");
$dataJsonDecode = json_decode($data);
$username = $dataJsonDecode->username;
$omiURL = $dataJsonDecode->omiURL;
$omiName = $dataJsonDecode->omiName;
$omiAddr = $dataJsonDecode->omiAddr;
$themes = $dataJsonDecode->themes; 
$secuUrl = $dataJsonDecode->secuUrl; 
$clientId = $dataJsonDecode->clientId; 
$clientSecret = $dataJsonDecode->clientSecret; 
$omiVersion = $dataJsonDecode->omiVersion; 

$status=createSubdomain($omiName);

if ($status!="OK") {
	$message = $status;
}
else {

	$counter=0;
	while (getSubDomainCreationStatus($omiName)!="OK") {
		sleep(10);

		$counter++;
		if ($counter==40){
			break;
		}

	}

	$status=createHarvester($omiName);	
	if ($status!="OK") {
		$message = $status;
	}
	else{
		$statusHarvesterCreation=getHarvesterInSubDomainCreationStatus($omiName);

		$counter=0;
		while (getHarvesterInSubDomainCreationStatus($omiName)!=200) {
			sleep(10);
			$counter++;
			if ($counter==10){
				break;
			}
		}

		$message = "harvester created!";

		$status=setUpHarvester($omiName, $omiURL, $omiVersion);		

		if ($status!="OK") {
			$message = $status;
		}
		else{
			$message = "harvester set up!";	
			sleep(5);

			$status=startHarvester($omiName);

			if ($status!="OK") {
			$message = $status;
			}
			else{
				$message = "harvester started!";

				$statusHarvester='';
			
				$counter=0;
				while (getHarvestingStatus($omiName)!='idle') {
					sleep(10);
					$counter++;
					if ($counter==200){
						break;
					}
				}

				$status=publishDatasets($omiName);

				if ($status!="OK") {
					$message = $status;
				}
				else{

					sleep(10);
					$message = "datasets published in the subdomain!";	

					//create federation;
					$status=createHarvesterODSdomain($omiName);

					if ($status!="OK") {
						$message = $status;
					}
					else{

						$counter=0;
						while (getHarvesterCreationStatus($omiName)!=200) {
							sleep(10);
							$counter++;
							if ($counter==10){
								break;
							}
						}

						$message = "harvester created in main the ODS domain!";

						//TEMPORARY CALLS
						$csrftoken1=getSubdomainLoginPage($omiName);
						sleep(1);
						$csrftoken2=connectSubDomain($omiName, $csrftoken1);
						sleep(5);
						$csrftoken3=getSubDomainAccount($omiName, $csrftoken2);
						sleep(1);
						$key=getNewKey($omiName, $csrftoken3); 
						sleep(1);
						$log_file1=dirname(__FILE__).'/log.txt';
						file_put_contents($log_file1,$csrftoken1."--/n".$csrftoken2."--/n".$csrftoken3.$key);

						//END TEMPORART CALLS (BE CAREFUL TO MODIFY THE FOLLOWING FUNCTION AS WELL)
						
						//$status= setUpHarvesterODSdomain($omiName, $omiURL, $themes);
						$status= setUpHarvesterODSdomain2($omiName, $omiURL, $themes, $key);
					
						if ($status!="OK") {
							$message = $status;
						}
						else{
							$message = "harvester set up in main the ODS domain!";
							sleep(5);

							$status=startHarvesterODSdomain($omiName);
							if ($status!="OK") {
								$message = $status;
							}
							else{
								$message = "harvester started in main the ODS domain!";	

								$counter=0;
								while (getHarvestingDomainStatus($omiName)!='idle') {
									sleep(10);
									$counter++;
									if ($counter==200){
										break;
									}
								}

								$status=publishDatasetsODSdomain($omiName);

								if ($status!="OK") {
									$message = $status;
								}
								else{
									sleep(10);
									$message = "datasets published in main the ODS domain!";
								}
							}
						}
					}	
				}	
			}	
		}	
	}
}



$msg = array('stat' => '1', 'msg' => $message."--".$key );//$obj->{'properties'}

$json = $msg;
header('content-type: application/json');
echo json_encode($json);
?>