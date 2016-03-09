




<?php 
//excluding organisation type
require 'PHPToolkit/NSconfig.php';

 require 'PHPToolkit/NetSuiteService.php';
 
require_once 'PHPToolkit/NSPHPClient.php';


echo "Connecting to server ..........\n";
$ct=0;
$pageindex=0;
$service= new NetSuiteService();
$search = new TransactionSearchAdvanced();
$search->savedSearchId ="1454"; //Saved Search internal ID, where the internal id's of all sales order records are saved
$cnt=0;
$request = new SearchRequest();
$request->searchRecord = $search;
$searchResponse = $service->search($request);


//check if the records are present?
if (!$searchResponse->searchResult->status->isSuccess) {
            echo "SEARCH ERROR";
        } else {
         echo "SEARCH SUCCESS, records found: " . $searchResponse->searchResult->totalRecords . "\n";
         $totalRecords=$searchResponse->searchResult->totalRecords;
         $noofpages=$searchResponse->searchResult->totalPages;
         $noofpages=round($noofpages);
         
         for($p=0;$p<$noofpages;$p++)//going to next page index
         {
         	
         	
         	
         	
         	
         	
         	if($ct!=0)
         	{
         	
         	
         		$searchId = $searchResponse->searchResult->searchId;
         		$pageSize=$searchResponse->searchResult->pageSize;
         		$currentPage=$searchResponse->searchResult->pageIndex;
         		
         		$resultsOne = (($currentPage - 1) * $pageSize) + 1;
         		$resultsTwo = $totalRecords < ($pageSize * $currentPage) ? $totalRecords : ($pageSize * $currentPage);
         		$request = new SearchMoreWithIdRequest();
         		$request->searchId = $searchId;
         		$request->pageIndex = $currentPage+1;
         		$moreSearchResponse = $service->searchMoreWithId($request);
         		$searchResponse=$moreSearchResponse;
         	
         	
         		if (!$searchResponse->searchResult->status->isSuccess) {
         			echo "SEARCH ERROR";
         		} else {
         			echo "SEARCH SUCCESS, records found: " . $searchResponse->searchResult->totalRecords . "\n";
         			$totalrecords=$searchResponse->searchResult->totalRecords;
         			
         		}
         	}//end of moresearch
         	
         	
         	
         	$ct=$ct+1;
         	
    $records = $searchResponse->searchResult->searchRowList->searchRow;
    foreach ($records as $record)  {
        
    	$searchedRowItemJoin = $records[0]->customerMainJoin->customFieldList;
    	//var_dump($searchedRowItemJoin);
    	$organisationtype="";
    	
    	
    	
    	
    	$searchedRowItemJoin = $records[0]->basic->customFieldList;
    	//var_dump($searchedRowItemJoin);
    	
    	
        $brocs=$record->basic->internalId;//fetches Internal ID from record
        $storetype="";
        $freefrieght="";
        $industry="";
        $promotion="";
        //$orderreason="";
       // var_dump($record->basic);
        	echo "the internal id is............... ";
        	for($l=0;$l<12;$l++)
        	{
        		if(isset($record->basic->customFieldList->customField[$l])){
        			
        	if($record->basic->customFieldList->customField[$l]->scriptId=="custbody_storetype")
        	$storetype=print_r($record->basic->customFieldList->customField[$l]->searchValue, 1);
        
        	if($record->basic->customFieldList->customField[$l]->scriptId=="custbodysales_freefreight")
        	$freefrieght=$record->basic->customFieldList->customField[$l]->searchValue;
        	
        	if($record->basic->customFieldList->customField[$l]->scriptId=="custbodysales_industry")
        	$industry=print_r($record->basic->customFieldList->customField[$l]->searchValue->name, 1);
        	if($record->basic->customFieldList->customField[$l]->scriptId=="custbodysales_promotion")
        	$promotion=print_r($record->basic->customFieldList->customField[$l]->searchValue, 1);
        	if($record->basic->customFieldList->customField[$l]->scriptId=="custbodysales_freefreightreasoncode")
        	$freefrieght=print_r($record->basic->customFieldList->customField[$l]->searchValue->name, 1);
        	
        		}}
        	
        	$intid=$brocs[0]->searchValue->internalId;//saving internal id in a variable
        	
        	$s = print_r($intid, 1);
        	echo ($s);
        	if($cnt%2==0)
        	{
        	//emptying the cache( not mandatory)
        		ini_set('soap.wsdl_cache_enabled',0);
        		ini_set('soap.wsdl_cache_ttl',0);
        		ini_set("default_socket_timeout", 200);
        	}
        	
        	get_allorderfields($s,$service,$storetype,$organisationtype);//calling function to get all details in sales order record
        	//passing internal id and service as parameters
        	$cnt++;
        	
        	// for connection change keep_alive=true in NSPHPclient.php else the connection can break anytime in between
        	
    }}
    
        }
    
    

    function get_allorderfields($intid,$service,$storetype,$organisationtype)
    {
    	
    	$request = new GetRequest();
    	// A recordRef object that specifies the id of the record to be retrieved.
    	$request->baseRef = new RecordRef();// get request, get all types request related to what you need actually
    	$request->baseRef->internalId =$intid;//internal id of record to be fetched
    	$request->baseRef->type = "salesOrder";//Type of the record which is need to be fetched
    
    
    
    	
    
    
    	$getResponse = $service->get($request);//gets the object
    	if ( ! $getResponse->readResponse->status->isSuccess) {
    		echo "GET ERROR";
    	} else {
    		
    		$customer = $getResponse->readResponse->record;//fetches all the objects which is present inside a record
    		

    		
    		//var_dump($customer);
    		$launchyear=0;
    		
    		
    		
    		
    		$category=0;
    		$week=0;
    		
    		//all the objects are stored in variables which is to be pushed in database afterwards
    		$datew = print_r($customer->createdDate, 1);
    		$date=new DateTime(substr ($datew,0,10));
    		$date1=substr ($datew,0,10);
   			$date3=substr($date1,0,5) ."01-01";
			$date3=new DateTime($date3);
			$interval = $date3->diff($date);
			$week=($interval->format('%R%a')+1)/7;
			$week=(int) $week;
    		
    		
    		$date=print_r(substr($datew,0,10), 1);
    		
    		
    		$name= print_r($customer->entity->name, 1);
    		
    		$documentnum= print_r($customer->tranId, 1);
    		for($i=0;$i<50;$i++)
    		{
    			if(isset($customer->customFieldList->customField[$i]))
    			{
    				if ($customer->customFieldList->customField[$i]->scriptId=="custbody_businesstype")
    					$Businesstype= print_r($customer->customFieldList->customField[$i]->value->name, 1);
    				if ($customer->customFieldList->customField[$i]->scriptId=="custbodysales_salesregion")
    					$Salesregion= print_r($customer->customFieldList->customField[$i]->value->name, 1);
    				if ($customer->customFieldList->customField[$i]->scriptId=="custbodysalesorder_saleschannel")
    					$Saleschannel= print_r($customer->customFieldList->customField[$i]->value->name, 1);
    				if ($customer->customFieldList->customField[$i]->scriptId=="custbody_specifier")
    					$specifier=print_r($customer->customFieldList->customField[$i]->value->name, 1);
    				if ($customer->customFieldList->customField[$i]->scriptId=="custbody12")
    					$Ordereason=print_r($customer->customFieldList->customField[$i]->value->name, 1);
    				if ($customer->customFieldList->customField[$i]->scriptId=="custbodysales_industry")
    					$industry=print_r($customer->customFieldList->customField[$i]->value->name, 1);
    				if($customer->customFieldList->customField[$i]->scriptId=="custbodysales_freefreightreasoncode")
    					$freefrieght=print_r($customer->customFieldList->customField[$i]->value->name, 1);
    					
    			}
    		}
    		
    		
    		
    		
    		
    		
    		$projectname= print_r($customer->customForm->name, 1);
    		
    		$salesteammember= print_r($customer->salesTeamList->salesTeam[0]->employee->name, 1);
    		
    		$contributionpct=print_r($customer->salesTeamList->salesTeam[0]->contribution, 1);
    		
    		$shippingcity= print_r($customer->shippingAddress->city, 1);
    		
    		$shippingstate= print_r($customer->shippingAddress->state, 1);
    		
    		$shippingzip= print_r($customer->shippingAddress->zip, 1);
    		
    		$shippingcountry= print_r($customer->shippingAddress->country, 1);
    		$shippingcountry=substr ($shippingcountry,1);
    		$promotion=print_r($customer->promoCode->name, 1);
    		$class= print_r($customer->itemList->item[0]->class->name, 1);
    		
    		$sumofsales= print_r($customer->total, 1);
    		
    		//echo gettype($sumofsales);//string
    		$sumofsales = (double) $sumofsales;
    		
    		$itemname= print_r($customer->itemList->item[0]->item->name, 1);
    		
    		$description= print_r($customer->itemList->item[0]->description, 1);
    		
    		
    
    		$family=substr($description, 0, strpos($description, ' '));
    		$sumofquant= print_r($customer->itemList->item[0]->quantity, 1);
    		$sumofquant=(int)$sumofquant;
    		
    		$baseprice= print_r($customer->subTotal, 1);
    	
    
    		$billingzip=print_r($customer->billingAddress->zip, 1);
    	
    		$salesteamrole=print_r($customer->salesTeamList->salesTeam[0]->salesRole->name, 1);
    	
    			$id=$intid+$name+$documentnum;
    		
    		
    		
    		// push in database

    		$server="localhost";
    		$username="root";
    		$password="pass";
    		$dbname="NetSuite_alldata";
    		$conn = new mysqli($server, $username, $password,$dbname);//setting up connection
    		if ($conn->connect_error)
    		{
    			die("Connection failed: " . $conn->connect_error);
    		}
    		//mysql query to push data in database
    		$sql_query = "INSERT INTO `sales`(id,Week,Date,Document_Number,Name,Business_Type,
    		Sales_Channel,Sales_Region,Store_Type_Retail,Organization_Type_Contract,Project_Name,
    		Specifier,Sales_Team_Member,Contribution,Shipping_City,Shipping_StateProvince,
    		Shipping_Zip,Shipping_Country,Class,Family,Sum_of_Sales_Amount,Item_Name,Description,
    		Sum_of_Quantity,Category,Base_Price,Launch_Year,Promotion,Billing_Zip,Sales_Team_Role,
    		Industry,Order_Reason,Free_FreightReplacement_Reason)
    		VALUES('$id','$week','$date','$documentnum','$name','$Businesstype','$Saleschannel',
    		'$Salesregion','$storetype','$organisationtype','$projectname','$specifier','$salesteammember','$contributionpct',
    		'$shippingcity','$shippingstate','$shippingzip','$shippingcountry','$class',
    		'$family',$sumofsales,'$itemname','$description',$sumofquant,'','$baseprice',
    		'','$promotion','$billingzip','$salesteamrole','$industry','$Ordereason','$freefrieght');";
    		
    		
    		
    		//week-int
    		//date-date
    		//sumofsales-double
    		//sumofquant-int
    		
    		if($conn->query($sql_query))
    			echo "Data Pushed.....\n";
    		
    			$conn->close();
    		
   
   
    
    
    	}
    
    
    }
    
    
    
   
    
    
        
    


?>



