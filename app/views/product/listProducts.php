<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>

    <title>NTQ Solution Admin Control Panel</title>

    <link rel="icon" type="image/ico" href="favicon.ico"/>

    <link href="<?php echo base_url.'/public/css/stylesheets.css'; ?>" rel="stylesheet" type="text/css"/>
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
            <li><a href="list-products.html">List Products</a></li>
        </ul>

    </div>

    <div class="workplace">

        <div class="row-fluid">
            <div class="span12 search">
                <form method="GET" action="../product/show">
                    <input type="text" class="span11" placeholder="Some text for search..." name="context" value="<?php if(isset($error['valueSearch'])) echo $error['valueSearch'];?>" />
                    <button class="btn span1" type="submit" name="search" value="OK">Search</button>
                </form>
            </div>
        </div>
        <!-- /row-fluid-->

        <div class="row-fluid">

            <div class="span12">
                <div class="head">
                    <div class="isw-grid"></div>
                    <h1>Products Management</h1>

                    <div class="clear"></div>
                </div>
                <form method="POST" action="../product/process">
                <div class="block-fluid table-sorting">
                    <a href="add" class="btn btn-add">Add Product</a>
                    <p style="color:red" align="center"><?php if(isset($_SESSION['success'])){echo $_SESSION['success']; unset($_SESSION['success']);} ?></p>
                    <p style="color:red" align="center"><?php if(isset($_SESSION['activate'])){echo $_SESSION['activate']; unset($_SESSION['activate']);} ?></p>
                    <table cellpadding="0" cellspacing="0" width="100%" class="table" id="tSortable_2">
                        <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAll"/></th>
                            <th width="10%" class="sorting"><a href="../product/show?<?php if(isset($_GET['search'])){echo 'context='.$_GET['context'].'&search='.$_GET['search'].'&';} ?>s=id<?php if(isset($_GET['type'])){if($_GET['type']=='DESC'){echo '&type=ASC';}else{echo '&type=DESC';}}else{echo '&type=DESC';}if(isset($_GET['page'])){echo '&page='.$_GET['page'];} ?>">ID</a></th>
                            <th width="30%" class="sorting"><a href="../product/show?<?php if(isset($_GET['search'])){echo 'context='.$_GET['context'].'&search='.$_GET['search'].'&';} ?>s=name<?php if(isset($_GET['type'])){if($_GET['type']=='DESC'){echo '&type=ASC';}else{echo '&type=DESC';}}else{echo '&type=DESC';}if(isset($_GET['page'])){echo '&page='.$_GET['page'];} ?>">Product Name</a></th>
                            <th width="15%" class="sorting"><a href="#">Price</a></th>
                            <th width="15%" class="sorting"><a href="#">Activate</a></th>
                            <th width="10%" class="sorting"><a href="#">Time Created</a></th>
                            <th width="10%" class="sorting"><a href="#">Time Updated</a></th>
                            <th width="10%">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php   
                        foreach ($data as $row) {
                            if($row['activate']==0){
                                $row['activate']='activate';
                            } else {
                                $row['activate']='Deactivate';
                            }
                            echo "    <tr>
                                    <td><input type='checkbox' class='chkbox' name='c[]' value='{$row['id']}'/></td>
                                    <td>{$row['id']}</td>
                                    <td>{$row['name']}</td>
                                    <td>".number_format($row['price'],0,',','.')." VND</td>
                                    <td><span class='text-success'>{$row['activate']}</span></td>
                                    <td>{$row['createdTime']}</td>
                                    <td>{$row['updatedTime']}</td>
                                    <td><a href='../product/edit?id={$row['id']}' class='btn btn-info'>Edit</a></td>
                                </tr>
                                ";
                        }
                        ?>
                        </tbody>
                    </table>
                    <div class="bulk-action">
                        <button class="btn btn-success" type="submit" name="activate" value="">Activate</button>
                        <button class="btn btn-success" type="submit" name="deactivate" value="">Deactivate</button>
                        <button class="btn btn-danger" type="submit" name="delete" value="">Delete</button>
                    </div><!-- /bulk-action-->
                    <div class="dataTables_paginate">
                        <?php if(isset($error['page_links']))  if(!empty($error['page_links']))  echo  $error['page_links']; ?>
                    </div>
                    <div class="clear"></div>
                </div>
                </form>
            </div>

        </div>
        <div class="dr"><span></span></div>

    </div>

</body>
</html>