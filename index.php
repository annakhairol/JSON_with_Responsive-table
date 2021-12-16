<!DOCTYPE html>
<html>
<head>
	<title> BizAcademia </title>

	<!-- jquery -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

	<!-- bootstrap -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js" integrity="sha384-LtrjvnR4Twt/qOuYxE721u19sVFLVSA4hf/rRt6PrZTmiPltdZcI7q7PXQBYTKyf" crossorigin="anonymous"></script>

	<!-- datatable -->
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.bootstrap4.min.css">

	<script type="text/javascript" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.0/js/dataTables.buttons.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.bootstrap4.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.html5.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.print.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.colVis.min.js"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style type="text/css">

    	.pagination a {
		  transition: background-color .3s;
		  margin: 0 4px; /* 0 is for top and bottom. Feel free to change it */
		}

		.pagination{
		    display: inline-flex;
		}
		div.dataTables_wrapper div.dataTables_paginate{
		    text-align: center;
		}

		pre {outline: 1px solid #ccc; padding: 5px; margin: 5px; }
		.string { color: green; }
		.number { color: blue; }
		.boolean { color: darkorange; }
		.null { color: magenta; }
		.key { font-weight: bold; }

    </style>

</head>
<body>

	<div class="container-fluid mt-5">

		<!-- Button trigger modal -->
		<button type="button" class="btn btn-primary btn-sm float-right" onclick="getDataList()">
		   <i class="fa fa-refresh"> Reload</i> 
		</button>

	</div>

	<div class="container-fluid mt-5 mb-10">
		<table id="dataList" border="1" class="table table-bordered table-striped" width="100%" style="margin-top: 15px">
		  <thead class="thead-dark">
		    <tr>
		      <th> Item ID </th>
		      <th> Item Name </th>
		      <th> Item Price </th>
		      <th> Discount Code </th>
		      <th width="2%"> Action </th>
		    </tr>
		  </thead>
		  <tbody></tbody>
		</table>
	</div>
	
	<script type="text/javascript">
		
			$(document).ready(function() {
				getDataList();
			});

			function getDataList()
			{
				var table = $('#dataList').DataTable().clear().destroy();	

				table = $('#dataList').DataTable({
					"pagingType": "full_numbers",
					'paging' 		: true,
					'ordering' 		: true,
					'info' 			: false,
					'lengthChange' 	: false,
					'responsive' 	: false,
					'buttons' 		: [ 'copy', 'excel', 'pdf', 'colvis' ],
				});

				table.buttons().container().appendTo( '#dataList_wrapper .col-md-6:eq(0)' );

				$.ajax({
					type : 'GET',
					url : 'api/1',
					dataType : "JSON",
					success : function(response) {

						$.each(response, function(key, value) {

							table.row.add([
								response[key].ItemID,
								response[key].ItemName,
								response[key].ItemPrice,
								response[key].DiscountCode,
								"<center>\
									<a href='#' onclick='updateRecord("+response[key].ItemID+")' class='btn btn-info btn-sm'> <i class='fa fa-edit'></i>\
									</a>\
								</center>"
							]).draw();

						});

						output(syntaxHighlight(response));
					}
				});

			}

			function updateRecord(id){
				$.ajax({
					type : 'GET',
					url : 'api/2/'+id,
					dataType : "JSON",
					success : function(response) {
						output(syntaxHighlight(response));
					}
				});
			}

			function output(inp) {
				if( $('pre').length )  
				{
				  	$('pre').html(inp);
				}else{
			   		document.body.appendChild(document.createElement('pre')).innerHTML = inp;
				}
			}

			function syntaxHighlight(json) {
			    if (typeof json != 'string') {
			         json = JSON.stringify(json, undefined, 2);
			    }
			    json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
			    return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
			        var cls = 'number';
			        if (/^"/.test(match)) {
			            if (/:$/.test(match)) {
			                cls = 'key';
			            } else {
			                cls = 'string';
			            }
			        } else if (/true|false/.test(match)) {
			            cls = 'boolean';
			        } else if (/null/.test(match)) {
			            cls = 'null';
			        }
			        return '<span class="' + cls + '">' + match + '</span>';
			    });
			}

	</script>

</body>
</html>