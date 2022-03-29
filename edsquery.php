<?php

/*
* for our example, the username/password used to access
* the REST service is "user:password"
*/
$username = 'studentunion-portal';
$password = 'VPpVGqArH08bsyzvzY3i3CdsftLCLdER';
$url = 'https://eds.arizona.edu/people/';
$cred = sprintf('Authorization: Basic %s', base64_encode($username.':'.$password));
$opts = array( 'http' => array ('method'=>'GET', 'header'=>$cred));
$ctx = stream_context_create($opts);

// send our request and retrieve the DSML response
$dsml = file_get_contents($url,false,$ctx);

// create SimpleXMLElement from response
$xml = new SimpleXMLElement($dsml);

// set namespace context for XPath query
$xml->registerXPathNamespace('dsml', 'http://www.dsml.org/DSML');


// retrieve all values for 'eduPersonAffiliation' attribute
$query = "//dsml:entry/dsml:attr[@name='eduPersonAffiliation']/dsml:value";
$vals = $xml->xpath($query);



// examine values
foreach($vals as $node) {
echo 'eduPersonAffiliation: ',$node,"<br />";
}
?>