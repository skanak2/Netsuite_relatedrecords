




<?php 
//for category and launch year
require 'PHPToolkit/NSconfig.php';

 require 'PHPToolkit/NetSuiteService.php';
 
require_once 'PHPToolkit/NSPHPClient.php';


echo "Connecting to server ..........\n";
$savedsrchid="1420";//saved search id
$ct=0;
$pageindex=0;
$service= new NetSuiteService();
$d=0;
$search = new TransactionSearchAdvanced();
$search->savedSearchId =$savedsrchid; //Saved Search internal ID, where the internal id's of all sales order records are saved
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
         $noofpages=$searchResponse->searchResult->totalPages;//counting total number of page in saved search
         $noofpages=round($noofpages);
         echo "total pages------------". $noofpages;
         //going to next page index
        
         for($p=0;$p<$noofpages;$p++)
         {
         	
         	
         	
         	
         	
         	
         	if($ct!=0)//after first 1000 records
         	{
         		
         		
         		$searchId = $searchResponse->searchResult->searchId;// getting search id
         		$pageSize=$searchResponse->searchResult->pageSize;//size of a page 	
         		$currentPage=$searchResponse->searchResult->pageIndex;//current page you are on
         		
         		$request = new SearchMoreWithIdRequest();//request for more records
         		$request->searchId = $searchId;
         		$request->pageIndex = $currentPage+1;//moving to next page
         		$moreSearchResponse = $service->searchMoreWithId($request);
         		$searchResponse=$moreSearchResponse;
         	
         	
         		if (!$searchResponse->searchResult->status->isSuccess) {
         			echo "SEARCH ERROR";
         		} else {
         			echo "SEARCH SUCCESS, records found: " . $searchResponse->searchResult->totalRecords . "\n";
         			$totalrecords=$searchResponse->searchResult->totalRecords;
         			
         		}
         	}//end of moresearch
         	
         	
         	
         	$ct=$ct+1;//if first page is fetched
        
    $records = $searchResponse->searchResult->searchRowList->searchRow;
    foreach ($records as $record)  {
        echo "Name: ";
        
       
      
        
        
        
        $searchedRowItemJoin = $record->itemJoin->customFieldList;
        //var_dump($record);
      
       //var_dump($searchedRow);
       
       
       
       
       
       
        for($h=0;$h<3;$h++)
        {
        	if(isset($searchedRowItemJoin->customField[$h])){
        		if($searchedRowItemJoin->customField[$h]->scriptId=="custitem_category"){
        		$intid2=$searchedRowItemJoin->customField[$h]->searchValue->typeId;//saving type id for further refrences
        		
        		$category1=$searchedRowItemJoin->customField[$h]->searchValue->internalId;
        		
        		$category1=(int)$category1;
        		echo "dddddddddddddddddd";
        		echo $category1;
        		
        		

        		$request = new GetRequest();
				$request->baseRef = new RecordRef();
				$request->baseRef->internalId =$intid2;
				
				$request->baseRef->type = "customList";
				$getResponse = $service->get($request);
        		$my_custom_lists[]= $getResponse->readResponse->record->customValueList;
        		
        		if (!$getResponse->readResponse->status->isSuccess) {
        			die("GET error!");
        		} else {
        		for ($mycust = 0; $mycust < count($my_custom_lists); $mycust++)
{
	
    $my_custom_value_obj[$mycust] = $my_custom_lists[$mycust]->customValue;
   
   //var_dump($my_custom_lists[$mycust]->customValue);
    for($f=0;$f<15;$f++)
    {
    	if(isset($my_custom_lists[$mycust]->customValue[$f])){
    	if($my_custom_lists[$mycust]->customValue[$f]->valueId==$category1)
    		$category=$my_custom_lists[$mycust]->customValue[$f]->value;
    	
    	}
    }//end of for
    
}
        			}
      
        		}//end of if 
        			
        		
        		
        		if($searchedRowItemJoin->customField[$h]->scriptId=="custitem12"){
        			
        			$intid2=$searchedRowItemJoin->customField[$h]->searchValue->typeId;//saving type id for further refrences
        			
        			$category1=$searchedRowItemJoin->customField[$h]->searchValue->internalId;
        			
        			$category1=(int)$category1;
        			
        			
        			
        			$request = new GetRequest();
        			$request->baseRef = new RecordRef();
        			$request->baseRef->internalId =$intid2;
        			
        			$request->baseRef->type = "customList";
        			$getResponse = $service->get($request);
        			$my_custom_lists[]= $getResponse->readResponse->record->customValueList;
        			
        			if (!$getResponse->readResponse->status->isSuccess) {
        				die("GET error!");
        			} else {
        				for ($mycust = 0; $mycust < count($my_custom_lists); $mycust++)
        				{
        			
        					$my_custom_value_obj[$mycust] = $my_custom_lists[$mycust]->customValue;
        					 
        					// var_dump($my_custom_lists[$mycust]->customValue);
        					for($f=0;$f<15;$f++)
        					{
        						if(isset($my_custom_lists[$mycust]->customValue[$f])){
        							if($my_custom_lists[$mycust]->customValue[$f]->valueId==$category1)
        								$launchyr=$my_custom_lists[$mycust]->customValue[$f]->value;
        								
        						}
        					}//end of for
        			
        				}
        			}
        			
        		
        			
        			
        		}
        		
        	}
        	
        	
        	
        	
        }//end of the $h for loop
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        //var_dump($category);
       // var_dump($launchyr);
    
    //var_dump($category);
    //var_dump($launchyr);
        $brocs=$record->basic->internalId;
        $droncs=$record->basic->type;
        $dtype=$droncs[0]->searchValue;
        $dtype=substr ($dtype,1);
        echo $dtype;
        	echo "the internal id is............... \n";
        	//var_dump($record);
        	
        	$intid=$brocs[0]->searchValue->internalId;//fetches Internal ID from record
        
        	$s = print_r($intid, 1);
        	echo ($s);
        	if($cnt%2==0)
        	{
        		//emptying the cache( not mandatory)
        		ini_set('soap.wsdl_cache_enabled',0);
        		ini_set('soap.wsdl_cache_ttl',0);
        		ini_set("default_socket_timeout", 200);
        	}
        	   echo "ksasfjsdghndfsbljkdfjkglsdfhgksjdghdsfhljdsghj";	
        	//var_dump($category);
        	//var_dump($launchyr);
        	get_allitemfulfillfields($s,$service,$dtype,$category,$launchyr);//calling function to get all details like id and type of record
        	//passing internal id, type and service as parameters
           		$cnt++;
        	
        	
        	
        	// for connection change keep_alive=true in NSPHPclient.php else the connection can break anytime in between
    }	
    }}
    
    
    
    

function get_allitemfulfillfields($intid,$service,$dtype,$category,$launchyr)
{
	
	$request = new GetRequest();
	// A recordRef object that specifies the id of the record to be retrieved.
	$request->baseRef = new RecordRef();// get request, get all types request related to what you need actually
	$request->baseRef->internalId =$intid;//internal id of record to be fetched
	$request->baseRef->type =$dtype;//Type of the record which is need to be fetched
	
	
	
	
	
	$getResponse = $service->get($request);//gets the object
	if ( ! $getResponse->readResponse->status->isSuccess) {
		echo "GET ERROR";
	} else {
	
		$customer = $getResponse->readResponse->record;//fetches all the objects which is present inside a record
		//var_dump($customer);//fetches all the objects in a record
		
		for($j=0;$j<35;$j++)// for the array in the objects, as the max object array in any of these records is 23
		{
			
			if(isset($customer->itemList->item[$j]))// if the array of this particular record is empty of not
			{
				echo "\n";
		$datew = print_r($customer->createdDate, 1);
		$date=substr ($datew,0,10);
		
		if($dtype)
		{
			$createfrom2="";
		}
		$postingperiod=$customer->postingPeriod->name;
		
		$description=$customer->itemList->item[$j]->description;
		$family=substr($description, 0, strpos($description, ' '));
		$price_level=$customer->itemList->item[$j]->price->name;
		$type=$dtype;
		$number=$customer->tranId;
		echo "price is". "$price_level";
		$status=$customer->shipStatus;
		$status=substr ($status,1);
		
		
		$name=$customer->entity->name;
		$accountname=$customer->account->name;
		
		$amount=$customer->shippingCost;
		$amount= (float)$amount;//converting to float
		
		//$createfrom2=$customer->customFieldList->customField[3]->value->name;
		$shippingcity=$customer->shippingAddress->city;
		
		$shippingstate=$customer->shippingAddress->state;
		$promotion=$customer->promoCode->name;
		$shippingzip=$customer->shippingAddress->zip;
		$class=$customer->itemList->item[0]->class->name;
		for($i=0;$i<50;$i++)//to find the needed datatype in this array
		{
			if(isset($customer->customFieldList->customField[$i])){//if array is empty or have next element
			if($customer->customFieldList->customField[$i]->scriptId== "custbody_projectname")
			{
				$projectname=$customer->customFieldList->customField[$i]->value;
				
			}
			if ($customer->customFieldList->customField[$i]->scriptId== "custbodysalesorder_saleschannel")
			{
				$saleschannel=$customer->customFieldList->customField[$i]->value->name;
				
			}
			
			if($customer->customFieldList->customField[$i]->scriptId== "custbodysales_salesregion")
			{
				$salesregion=$customer->customFieldList->customField[$i]->value->name;
				
			}
			
			if($customer->customFieldList->customField[$i]->scriptId== "custbody_storetype")
			{
				$storetype=$customer->customFieldList->customField[$i]->value;
			
			}
			
			}
		}
		
		$storetype;//...................
		
		
		
		//upper objects are not present in the fetched record
		
		$quantity=$customer->itemList->item[$j]->quantity;
		$quantity=(int)$quantity;
		
		$shippingcountry=$customer->shippingAddress->country;
		$shippingcountry=substr ($shippingcountry,1);
		
		$itemname=$customer->itemList->item[$j]->itemName;
		
		$createdfrom=$customer->createdFrom->name;
		$mainid=$datew.$intid.$number.$name.$itemname.$createdfrom.$description;
		
		
	
		
// push in database


		$server="localhost";
		$username="root";
		$password="pass";
		$dbname="NetSuite_alldata";//database name in which table exists
		$conn = new mysqli($server, $username, $password,$dbname);//setting up connection
		if ($conn->connect_error)
		{
			die("Connection failed: " . $conn->connect_error);
		}
		//mysql query to push data in database
		$sql_query = "INSERT INTO `cost`(id,Date,Period,Family,Type,Number,Status,Name,Account,Amount,
		Sales_Region,Shipping_City,Shipping_State,Shipping_Zip,Sales_Channel,Store_type,Project_Name,Class,
		Category,Launch_year,Description,Quantity,Shipping_Country,Item_Name,Created_From,Created_from2,Price_level,promotion)
		VALUES('$mainid','$date','$postingperiod','$family','$type','$number','$status','$name','$accountname',$amount,
		'$salesregion','$shippingcity','$shippingstate','$shippingzip','$saleschannel','$storetype',
		'$projectname','$class','$category','$launchyr','$description',$quantity,'$shippingcountry','$itemname',
		'$createdfrom','$createfrom2','$price_level','$promotion');";
		
		//date-date
		//amount-float
		//quantity-int
		
		if($conn->query($sql_query)) 
		echo "Data pushed.\n";
			
	
			$conn->close();
		
		
		
		
		
	}
		}
	}
	
	
}

    
    
    
    
   
    
    
    
    


?>



