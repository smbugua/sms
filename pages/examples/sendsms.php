<?php
include('connect.php');
// Be sure to include the file you've just downloaded
require_once('AfricasTalkingGateway.php');
// Specify your login credentials
$username   = "drawgift";
$apikey     = "0e243f895e2b851d90cad7d21ca706afd5f699b9af0e58a87cf18d432a1da867";
// NOTE: If connecting to the sandbox, please use your sandbox login credentials
// Specify the numbers that you want to send to in a comma-separated list
// Please ensure you include the country code (+254 for Kenya in this case)
//$recipients = "+254722856900,+254728944815,+254724661481,+254716671496";
$q=mysql_query("SELECT id,tel,customername,points,shop,amount,dateadded from entries where msg=0 order by id ASC ");
while($query=mysql_fetch_array($q)){
$id=$query['id'];
$phone=$query['tel'];
$name=$query['customername'];
$points=$query['points'];
$shop=$query['shop'];
$amount=$query['amount'];
$dateadded=$query['dateadded'];
$recipients=$phone;
// And of course we want our recipients to know what we really do
$message    ="Hello ".$name." your shopping at ".$shop." on ".$dateadded." worth ".$amount." has awarded you ".$points." points in our Shop & Win Promotion.";
$from = "GALLERIA";
// Create a new instance of our awesome gateway class
$gateway    = new AfricasTalkingGateway($username, $apikey);
// NOTE: If connecting to the sandbox, please add the sandbox flag to the constructor:
/*************************************************************************************
             ****SANDBOX****
$gateway    = new AfricasTalkingGateway($username, $apiKey, "sandbox");
**************************************************************************************/
// Any gateway error will be captured by our custom Exception class below, 
// so wrap the call in a try-catch block
try 
{ 
  // Thats it, hit send and we'll take care of the rest. 
  $results = $gateway->sendMessage($recipients, $message,$from);
            
  foreach($results as $result) {
    // status is either "Success" or "error message"
    echo " Number: " .$result->number;
    echo " Status: " .$result->status;
    echo " MessageId: " .$result->messageId;
    echo " Cost: "   .$result->cost."\n";
  }
}
catch ( AfricasTalkingGatewayException $e )
{
  echo "Encountered an error while sending: ".$e->getMessage();
}
mysql_query("UPDATE entries SET msg=1 where id='$id'");


}
  echo "<script>location.replace('index.php')</script>";
// DONE!!! 
?>