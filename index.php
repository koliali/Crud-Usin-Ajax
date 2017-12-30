<?php include('dbconnection.php'); ?><!DOCTYPE html>
<html>
    <head>
        <title>Insert Update Delete in Ajax</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <style type="text/css">
            .form-group{
                padding: 20px !important;
            }
            .tbl-data{
                margin-top: 50px;
            }
            .tbl{
                margin-top: 10px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div id="msg">

            </div>
            <div class="" id="form-data">

                <div class="form-group">
                    <label class="control-label col-sm-2" for="Name">Name:</label>
                    <div class="col-sm-10" id="hidden-id">

                        <input type="text" class="form-control" id="Name" placeholder="Enter name" name="Name">

                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="Email">Email:</label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control" id="Email" placeholder="Enter email" name="Email">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="PhoneNumber">Phone Number:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="PhoneNumber" placeholder="Enter phone number" name="PhoneNumber">
                    </div>
                </div>
                <div class="form-group">        
                    <div class="col-sm-offset-2 col-sm-10">

                        <button type="button" id="btn-submit" class="btn btn-default">Submit</button>
                    	<button type="button" id="btn-update" class="btn btn-default">Update</button>
                        <button type="button" id="btn-cancel" class="btn btn-default">Cancel</button>
                    </div>
                </div>
            </div>
            <div class="tbl-data" id="form-table">
                <button class="btn btn-defualt" id="btn-add">Add Button</button>
                <table class="table table-bordered tbl">
                    <thead>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Action</th>
                    </thead>
                    <tbody id="tbl-data">
                        <?php
                        $sql = "Select * from user";
                        $result = mysqli_query($conn, $sql);
                        while($row = mysqli_fetch_array($result)) {
                            ?>
                        <tr>
                            <td><?php echo $row['ID'];?></td>
                            <td><?php echo $row['Name'];?></td>
                            <td><?php echo $row['Email'];?></td>
                            <td><?php echo $row['PhoneNumber'];?></td>
                            <td class='text-center'>
                                <div class="btn-group">
                                    <button class="btn btn-primary" onclick="edit(<?php echo $row['ID'];?>)"><i class="glyphicon glyphicon-pencil"></i></button>
                                    <button class="btn btn-danger" onclick="deleteuser(<?php echo $row['ID'];?>)"><i class="glyphicon glyphicon-trash"></i></button></div></td>
                        </tr>
<?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script type="text/javascript">
        	$(document).ready(function(){
        		$('#btn-update').hide();
			});
            $('#form-data').hide();
            $('#btn-add').click(function () {
            	$('#form-data').slideUp("slow");
            	$('#Name').val('');
                $('#Email').val('');
                $('#PhoneNumber').val('');
                $('#form-data').slideDown("slow");
                $('#btn-submit').show();
            	$('#btn-update').hide();
            	$('#ID').remove();
            });
            $('#btn-cancel').click(function () {
                $('#form-data').slideUp("slow");
            });

            $('#btn-submit').click(function () {
                var name, email, phonenumber;
                name = $('#Name').val();
                email = $('#Email').val();
                phonenumber = $('#PhoneNumber').val();

                $.ajax({//create an ajax request to load_page.php
                    type: "Post",
                    url: "process.php",
                    dataType: "JSON", //expect html to be returned                
                    data: {Name: name, Email: email, PhoneNumber: phonenumber},
                    success: function (response) {
                        if (response.error == false) {
                            $('#Name').val('');
                            $('#Email').val('');
                            $('#PhoneNumber').val('');
                            $('#form-data').slideUp("slow");
                            $('#msg').html(
                                    '<div class="alert alert-success alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success!</strong> Successfullly Inserted.</div>'
                                    );
                            $.ajax({//create an ajax request to load_page.php
                                type: "Get",
                                url: "process.php",
                                dataType: "JSON", //expect html to be returned                
                                success: function (response) {
                                    var user_data;
                                    $.each(response.message, function (key, value) {
                                        user_data += '<tr>' +
                                                '<td>' + value.ID + '</td>' +
                                                '<td>' + value.Name + '</td>' +
                                                '<td>' + value.Email + '</td>' +
                                                '<td>' + value.PhoneNumber + '</td>' +
                                                '<td class="text-center"><div class="btn-group"><button class="btn btn-primary" onclick="edit(' + value.ID + ')"><i class="glyphicon glyphicon-pencil"></i></button><button class="btn btn-danger" onclick="deleteuser(' + value.ID + ')"><i class="glyphicon glyphicon-trash"></i></button></div></td>' +
                                                '</tr>';
                                    });
                                    $('#tbl-data').html(user_data);
                                    console.log(response.message);
                                },
                                error: function () {

                                },
                                complete: function () {

                                }
                            });


                        } else {
                            $('#msg').html(
                                    '<div class="alert alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error!</strong> ' +
                                    response.message +
                                    '.</div>'
                                    );
                        }

                        console.log(response.error);
                    },
                    error: function () {

                    },
                    complete: function () {

                    }
                });
            });

            $('#btn-update').click(function () {
                var id,name, email, phonenumber;
                id = $('#ID').val();
                name = $('#Name').val();
                email = $('#Email').val(); 
                phonenumber = $('#PhoneNumber').val();

                $.ajax({//create an ajax request to load_page.php
                    type: "Post",
                    url: "process.php",
                    dataType: "JSON", //expect html to be returned                
                    data: {ID: id,Name: name, Email: email, PhoneNumber: phonenumber},
                    success: function (response) {
                        if (response.error == false) {
                            $('#Name').val('');
                            $('#Email').val('');
                            $('#PhoneNumber').val('');
                            $('#form-data').slideUp("slow");
                            $('#msg').html(
                                    '<div class="alert alert-success alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success!</strong> Successfullly Updated.</div>'
                                    );
                            $.ajax({//create an ajax request to load_page.php
                                type: "Get",
                                url: "process.php",
                                dataType: "JSON", //expect html to be returned                
                                success: function (response) {
                                    var user_data;
                                    $.each(response.message, function (key, value) {
                                        user_data += '<tr>' +
                                                '<td>' + value.ID + '</td>' +
                                                '<td>' + value.Name + '</td>' +
                                                '<td>' + value.Email + '</td>' +
                                                '<td>' + value.PhoneNumber + '</td>' +
                                                '<td class="text-center"><div class="btn-group"><button class="btn btn-primary" onclick="edit(' + value.ID + ')"><i class="glyphicon glyphicon-pencil"></i></button><button class="btn btn-danger" onclick="deleteuser(' + value.ID + ')"><i class="glyphicon glyphicon-trash"></i></button></div></td>' +
                                                '</tr>';
                                    });
                                    $('#tbl-data').html(user_data);
                                    console.log(response.message);
                                },
                                error: function () {

                                },
                                complete: function () {

                                }
                            });


                        } else {
                            $('#msg').html(
                                    '<div class="alert alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error!</strong> ' +
                                    response.message +
                                    '.</div>'
                                    );
                        }

                        console.log(response.error);
                    },
                    error: function () {

                    },
                    complete: function () {

                    }
                });
            });
            
            function edit(userID){
            	var userid = userID;
            	 $.ajax({//create an ajax request to load_page.php
                                type: "Get",
                                url: "process.php",
                                dataType: "JSON", //expect html to be returned 
                                data: {userID: userid},          
                                success: function (response) {
                                    if(response.error == false){
                                    	$('#btn-submit').hide();
                                    	$('#btn-update').show();
                                    	$('#Name').val(response.message['Name']);
			                            $('#Email').val(response.message['Email']);
			                            $('#PhoneNumber').val(response.message['PhoneNumber']);
			                            $('#form-data').slideDown("slow");
			                            $('#ID').remove();
			                            $('#hidden-id').append('<input type="hidden" id="ID" value="'+response.message['ID']+'" />');

                                    }else{
                                    	$('#msg').html(
	                                    '<div class="alert alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error!</strong> ' +
	                                    response.message +
	                                    '.</div>'
	                                    );
                                    }
                                    console.log(response.message);
                                },
                                error: function () {

                                },
                                complete: function () {

                                }
                            });
            }

            function deleteuser(userID){
                var userid = userID;
                 $.ajax({//create an ajax request to load_page.php
                                type: "Delete",
                                url: "process.php?deleteID="+userid,
                                dataType: "JSON", //expect html to be returned 
                                data: {userID: userid},          
                                success: function (response) {
                                    if(response.error == false){
                                       $('#msg').html(
                                    '<div class="alert alert-success alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success!</strong> Successfullly Deleted.</div>'
                                    );
                                    $.ajax({//create an ajax request to load_page.php
                                        type: "Get",
                                        url: "process.php",
                                        dataType: "JSON", //expect html to be returned                
                                        success: function (response) {
                                            var user_data;
                                            $.each(response.message, function (key, value) {
                                                user_data += '<tr>' +
                                                        '<td>' + value.ID + '</td>' +
                                                        '<td>' + value.Name + '</td>' +
                                                        '<td>' + value.Email + '</td>' +
                                                        '<td>' + value.PhoneNumber + '</td>' +
                                                        '<td class="text-center"><div class="btn-group"><button class="btn btn-primary" onclick="edit(' + value.ID + ')"><i class="glyphicon glyphicon-pencil"></i></button><button class="btn btn-danger" onclick="deleteuser(' + value.ID + ')"><i class="glyphicon glyphicon-trash"></i></button></div></td>' +
                                                        '</tr>';
                                            });
                                            $('#tbl-data').html(user_data);
                                            console.log(response.message);
                                        },
                                        error: function () {

                                        },
                                        complete: function () {

                                        }
                                    });
                                    }else{
                                        $('#msg').html(
                                        '<div class="alert alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error!</strong> ' +
                                        response.message +
                                        '.</div>'
                                        );
                                    }
                                    console.log(response.message);
                                },
                                error: function () {

                                },
                                complete: function () {

                                }
                            });
            }
            
            
        </script>
    </body>
</html>