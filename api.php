<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// 1) Able to read all data from database
// 2) Able to read specific data from database
// 3) Calculate the Discount Price.
// 4) Output in JSON Format.
// 5) Final Output for end user to see.

// include database
include 'connection.php';

// api (using GET METHOD)
if(isset($_GET['code'])) 
{
	$code = (isset($_GET['code'])) ? $_GET['code'] : NULL; 
	$id = (isset($_GET['id'])) ? $_GET['id'] : NULL; 

	// Question 1 : Able to read all data from database
	if ($code == 1) {
		$sql = "SELECT * FROM inventory AS i JOIN discount AS d WHERE i.DiscountCode=d.DiscountCode";
		$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

		if($result)
		{
			$data = $item = array(); // initialize empty array

			$DiscountPrice = 0; // initialize discount price

			foreach ($result as $row) {
				$DiscountPrice = $row['ItemPrice'] - ($row['ItemPrice']*$row['DiscountValue']); // insert calculation for total price after discount
				$item = array(
					'DiscountCode' => $row['DiscountCode'],
					'DiscountPrice' => $DiscountPrice,
					'ItemID' => $row['ItemID'], 
					'ItemName' => $row['ItemName'], 
					'ItemPrice' => $row['ItemPrice']
				);

				array_push($data, $item);
			}

			response($data, 200);

		}else{
			response(NULL, 400);
		}
	}

	// Question 2 : Able to read specific data from database
	else if ($code == 2) {

		if(empty($id))
		{
			response(NULL, 400);
		}
		else
		{

			$sql = "SELECT * FROM inventory AS i JOIN discount AS d WHERE `ItemID` = '$id' AND i.DiscountCode=d.DiscountCode";

			$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

			if(mysqli_num_rows($result) > 0)
			{
				$data = array(); // initialize empty array
				$DiscountPrice = 0; // initialize discount price

				$row = mysqli_fetch_array($result);

				$DiscountPrice = $row['ItemPrice'] - ($row['ItemPrice']*$row['DiscountValue']); // insert calculation for total price after discount

				$data = array(
					'DiscountCode' => $row['DiscountCode'],
					'DiscountPrice' => $DiscountPrice,
					'ItemID' => $row['ItemID'], 
					'ItemName' => $row['ItemName'], 
					'ItemPrice' => $row['ItemPrice']
				);

				response($data, 200);

			}else{
				response(NULL, 400);
			}
		}
		
	}

	else{
		response(NULL, 405); // method not allowed
	}
}
else
{
	response(NULL, 405); // method not allowed
}

function response($data, $response_code)
{
	http_response_code($response_code);
	echo json_encode($data, JSON_PRETTY_PRINT);
}



