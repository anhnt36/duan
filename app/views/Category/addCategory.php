<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>

    <title>NTQ Solution Admin Control Panel</title>

    <link rel="icon" type="image/ico" href="favicon.ico"/>

    <link href="css/stylesheets.css" rel="stylesheet" type="text/css"/>

</head>
<body>


    <div class="breadLine">

        <ul class="breadcrumb">
            <li><a href="list-categories.html">List Categories</a> <span class="divider">></span></li>
            <li class="active">Add</li>
        </ul>

    </div>

    <div class="workplace">

        <div class="row-fluid">

            <div class="span12">
                <div class="head">
                    <div class="isw-grid"></div>
                    <h1>Categories Management</h1>

                    <div class="clear"></div>
                </div>
                <div class="block-fluid">
                    <form method="POST" action="">
                    	<div class="row-form">
                            <div class="span3">Category Name:</div>
                            <div class="span9"><input type="text" placeholder="some text value..." name="name" value="<?php if(isset($data['name'])) echo $data['name'];?>" /></div>
                            <div class="span3"></div>
                            <p style="color:red"><?php if(isset($error['name'])) echo $error['name']; ?></p>
                            <div class="clear"></div>
                        </div> 
                        <div class="row-form">
                            <div class="span3">Activate:</div>
                            <div class="span9">
                                <?php
                                $Activate='';
                                $Deactivate='';
                                if(isset($data['activate'])) {
                                    if($data['activate'] == '0') $Activate = 'selected';
                                    else $Deactivate = 'selected';
                                }
                                echo "<select name='activate'>
                                        <option value='0' $Activate>Activate</option>
                                        <option value='1' $Deactivate>Deactivate</option>
                                    </select>";

                                ?>
                            </div>
                            <div class="clear"></div>
                        </div>                          
                        <div class="row-form">
                        	<button class="btn btn-success" type="submit" name="OK">Create</button>
							<div class="clear"></div>
                        </div>
                    </form>
                    <div class="clear"></div>
                </div>
            </div>

        </div>
        <div class="dr"><span></span></div>

    </div>



</body>
</html>