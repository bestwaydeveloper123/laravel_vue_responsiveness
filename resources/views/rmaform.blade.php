<!doctype html>

<html lang="en">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Hydra2 : RMA Form</title>
</head>

<body>

	<style>
		/*css*/
	</style>
	<div style="border: 5px solid #DBDDCC;background:#f2f2f0">
		<div style="margin: 20px; border: 5px solid #DBDDCC;background:#f2f2f0">
			<div style="margin:30px;font-weight:700;background:#fff">
				<div style="border:1px solid #ddd;min-height:1260px;">
					<div>
						<table>
							<tr>
								<td style="border:1px solid #ddd;width:60%;padding:0px 20px 30px 20px">ALL FIELDS OF RETURN AUTHORIZATION- FORM MUST BE FILLED OUT, AND THE FORM MUST BE SIGNED AND DATED BEFORE ANY RMA WILL BE WORKED ON/REFUNDED. IN ORDER TO EXPEDITE THE RETURN PROCESS, PLEASE BE SURE TO INCLUDE AS MUCH INFORMATION AS POSSIBLE.</td>
								<td style="border:1px solid #ddd;width:40%;text-align:right;padding-bottom: 90px;padding-right: 10px;">
									Date: Aug 02,2019
									<br />Order # {{ $Account->id }}
								</td>
							</tr>
						</table>
						<table>
							<tr>
								<td style="border:1px solid #ddd;width:35%;padding:0px 20px 30px 20px;color:red">
									Return address:
									<br />{{ $Account->id }}
									<br />Flagship One Inc.
									<br />19 Wilbur Street
									<br />Lynbrook NY 11563
									<br />Phone: 516-766-2223
								</td>
								<td style="border:1px solid #ddd;width:25%;padding:0px 20px 30px 20px"></td>
								<td style="border:1px solid #ddd;width:10%;text-align:right;padding-bottom: 130px;padding-right: 10px;">To:</td>
								<td style="border:1px solid #ddd;width:30%;text-align:right;padding-bottom: 110px;padding-right: 10px;">
									{{ $shipto }}
									{{ $shopname }}
									{{ $street1 }}
									{{ $street2 }}
									{{ $city }}
									{{ $state }}
									{{ $zip }}
								</td>
							</tr>
						</table>
						<table class="table table-bordered" style="font-weight:500;margin-top:30px; width: 100%; ">
							<thead style="background:#DBDDCC">
								<tr>
									<th>ACCOUNT MANAGER</th>
									<th style="border-right: none;">VIN FOR VEHICLE AND PART #</th>
									<th style="border:none"></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td style="width:20%">{{ $Account->primaryaccountmanager? $Account->primaryaccountmanager : $Account->secondaryaccountmanager }}</td>
									<td style="width:50%">VIN: {{ $Account->customervin }}</td>
									<td>Part# {{ $Account->wrongpart }}</td>
								</tr>
							</tbody>
						</table>
						<table class="table table-bordered" style="font-weight:500;margin-top:30px; width: 100%;">
							<thead style="background:#DBDDCC">
								<tr>
									<th>Original problem(s) experienced with vehicle (including all symptoms and trouble-codes)</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td style="height:100px">{{ $original_problem }}</td>
								</tr>
							</tbody>
						</table>
						<table class="table table-bordered" style="font-weight:500;margin-top:30px; width: 100%;">
							<thead style="background:#D9D9D9">
								<tr>
									<th>Steps taken to diagnose the original problem(s)</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td style="height:100px">{{ $steps_taken_to_diagnose_problem }}</td>
								</tr>
							</tbody>
						</table>
						<table class="table table-bordered" style="font-weight:500;margin-top:30px; width: 100%;">
							<thead style="background:#D9D9D9">
								<tr>
									<th>Problem(s) experienced with the part received from Flagship One</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td style="height:100px">{{ $problems_with_part }}</td>
								</tr>
							</tbody>
						</table>
						<table class="table table-bordered" style="font-weight:500;margin-top:30px; width: 100%;">
							<thead style="background:#D9D9D9">
								<tr>
									<th>Steps taken to diagnose the problem(s) with the part from Flagship One</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td style="height:100px">{{ $steps_taken_to_diagnose_with_part }}</td>
								</tr>
							</tbody>
						</table>
						<table class="table table-bordered" style="font-weight:500;margin-top:30px; width: 100%;">
							<thead style="background:#D9D9D9">
								<tr>
									<th>Additional notes</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td style="height:100px">{{ $additional_notes }}</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div style="padding:10px 10px;font-weight:500">
						<p>
							<b>Return authorized by: XX</b>
						</p>
						<span style="font-size:12px">
							All of our items come with a lifetime warranty. Warranty does not include any labor associated with installation and/or removal of parts, key and/or Locksmith fees, FlagshipOne, Inc. will not reimburse Buyer for any such fees. Warranty will include the shipping charge only when the part is deemed to be faulty without being damaged by Buyer which is under the sole discretion of FlagshipOne ,Inc. Buyer hereby acknowledges that the Seller’s liability is limited to the price of the item sold. We will not be liable for any damage and/or injury that results from any use of used auto parts and/or any other item sold by any entity operated by Flagship One, Inc and the Buyer hereby now and forever relinquishes FlagshipOne, Inc. from any such liability. Electrical parts are tested before being sold and if returned, all units will be inspected for burnt components, physical damage and/or water damage; any such damage will void all Warranties. Warranty is also void if the item is misused, abused, modified, opened, tampered with, used for any purpose not originally intended, installation takes place outside of the United States, vehicle is involved in an accident and/or the part is sold to someone other than the listed Buyer. Warranty is not transferable. Buyer is solely responsible for making the correct interchange research. All interchange information provided by FlagshipOne, Inc. is speculative and must be verified by Buyer. Returned items must be in original condition and untampered with. Except as otherwise stated herein, all returned items are subject to a 20% restocking fee, plus the original shipping charge if the item(s) are purchased in error, Buyer’s remorse, vehicle was misdiagnosed and/or the item(s) are tested and deemed good by FlagshipOne, Inc. Items that are programmed and returned are subject to an $85 non-refundable programming fee. Items purchased with keys and returned are subject to a $90 non-refundable key fee in addition to the $85 non-refundable programming fee. All returns for money back must be received by FlagshipOne, Inc within 30 days from the date of the original purchase. All returns made after 30 days from the date of the original purchase include an option for an exchange or store credit—NO EXCEPTIONS. In the event an item is purchased on Ebay and is returned after 45 days from the date of the original purchase, Buyer is solely responsible for the commission charged by Ebay for that particular sale except when FlagshipOne, Inc has made an error in the listing which the Buyer relied upon prior to purchase. In the event that a unit is believed to be faulty, FlagshipOne, Inc expressly reserves the right to have the unit sent back to its facility for testing prior to replacement. FlagshipOne, Inc will NOT under any circumstances reimburse any fees a Buyer expends in connection with a possible faulty unit including, but not limited to Locksmith fees, diagnostic fees, rental car fees, storage fees, dealership fees, third party reprogramming fees, etc. The warranty includes only one claim. After one claim, the warranty is exhausted. Buyer acknowledges, agrees and accepts all the terms set forth herein upon purchase.
							<p style="margin-top:30px">To accept this return, sign here and return:</p>
							<img src="{{ $customer_signature }}" height="100" width="200">
							<p style="margin-top:10px">Print Name: <u> {{ $customer_name }} </u></p>
						</span>
					</div>
					<div style="padding:20px 10px;">
						<p style="font-size:22px;text-decoration:underline">PLEASE RETURN UNIT TO:</p>
						<p style="font-size:22px;color:red">
							Return address:
							<br />{{ $Account->id }}
							<br />Flagship One Inc.
							<br />19 Wilbur Street
							<br />Lynbrook NY 11563
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>

</html>