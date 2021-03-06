<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>

    <title>NTQ Solution Admin Control Panel</title>

    <link rel="icon" type="image/ico" href="favicon.ico"/>

    <link href="css/stylesheets.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo base_url.'/public/css/pagination.css'; ?> " rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    
    <script type="text/javascript">
        $(document).ready(function(){
                $('#selectAll').on('click',function(){
                    if ($(this).is(':checked')) {
                        $('.chkbox').each(function(){
                            this.checked = true;
                        });
                    }else{
                        $('.chkbox').each(function(){
                            this.checked = false;
                        });
                    }
                })
            });
        
    </script>

</head>
<body>
<div class="breadLine">

        <ul class="breadcrumb">
            <li><a href="list-categories.html">List Categories</a></li>
        </ul>

    </div>

    <div class="workplace">

        <div class="row-fluid">
            <div class="span12 search">
                <form method="GET" action="../category/show">
                    <input type="text" class="span11" placeholder="Some text for search..." name="context" value="<?php if(isset($error['valueSearch'])) echo $error['valueSearch'];?>" />
                    <button class="btn span1" type="submit" name="search" value="OK" >Search</button>
                    <p style="color:red"><?php if(isset($error['search'])) echo '* '.$error['search']; ?></p>
                </form>
            </div>
        </div>
        <!-- /row-fluid-->

        <div class="row-fluid">

            <div class="span12">
                <div class="head">
                    <div class="isw-grid"></div>
                    <h1>Categories Management</h1>

                    <div class="clear"></div>
                </div>
                <div class="block-fluid table-sorting">
                    <a href="../category/add" class="btn btn-add">Add Category</a>
                    <p style="color:red" align="center"><?php if(isset($_SESSION['success'])){echo $_SESSION['success']; unset($_SESSION['success']);} ?></p>
                    <p style="color:red" align="center"><?php if(isset($_SESSION['activate'])){echo $_SESSION['activate']; unset($_SESSION['activate']);} ?></p>
                    <?php
                        $this->helper = new FT_Helper_Loader;
                        $this->helper->load('String');
                    ?>
                    <form method="POST" action="../category/process">
                    <table cellpadding="0" cellspacing="0" width="100%" class="table" id="tSortable_2">
                        <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAll"/></th>
                            <th width="15%" class="sorting"><a href="../category/show?<?php path('id'); ?>">ID</a></th>
                            <th width="35%" class="sorting"><a href="../category/show?<?php path('name'); ?>">Category Name</a></th>
                            <th width="20%" class="sorting"><a href="../category/show?<?php path('activate'); ?>">Activate</a></th>
                            <th width="10%" class="sorting"><a href="../category/show?<?php path('createdTime'); ?>">Time Created</a></th>
                            <th width="10%" class="sorting"><a href="../category/show?<?php path('updatedTime'); ?>">Time Updated</a></th>
                            <th width="10%">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($data as $row) {

                            if($row['activate'] == 0){
                                $row['activate']='Activate';

                            } else {
                                $row['activate']='Deactivate';

                            }
                            echo "
                            <tr>
                                <td><input class='chkbox' type='checkbox' name='c[]' value='{$row['id']}'/></td>
                                <td><a href='../category/edit?id={$row['id']}'>{$row['id']}</a></td>
                                <td>{$row['name']}</td>
                                <td><span class='text-success'>{$row['activate']}</span></td>
                                <td>{$row['createdTime']}</td>
                                <td>{$row['updatedTime']}</td>
                                <td><a href='../category/edit?id={$row['id']}' class='btn btn-info'>Edit</a></td>
                            </tr>";
                        }
                        ?>
                        
                        </tbody>
                    </table>
                    <div class="bulk-action">
                        <button class="btn btn-success" type="submit" name="activate" value="">Activate</button>
                        <button class="btn btn-success" type="submit" name="deactivate" value="">Deactivate</button>
                        <button class="btn btn-danger" type="submit" name="delete" value="">Delete</button>
                    </div><!-- /bulk-action-->
                    </form>
                    <div class="dataTables_paginate">
                        <?php if(isset($error['page_links']))  if(!empty($error['page_links']))  echo  $error['page_links']; ?>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>

        </div>
        <div class="dr"><span></span></div>

    </div>


</body>
</html>