<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>

    <title>NTQ Solution Admin Control Panel</title>

    <link rel="icon" type="image/ico" href="<?php echo base_url.'/public/favicon.ico'?>"/>

    <link href="<?php echo base_url.'/public/css/stylesheets.css' ?>" rel="stylesheet" type="text/css"/>
    <script type="text/javascript">
        function logOut(){
            confirm('Bạn có muốn đăng xuất không ?');
        }
    </script>
    <?php $this->view= new FT_View_Loader; ?>
</head>
<body>

<div class="header">
    <a class="logo" href="list-categories.html">
        <img src="<?php echo base_url.'/public/img/logo.png' ?>" alt="NTQ Solution - Admin Control Panel" title="NTQ Solution - Admin Control Panel"/>
    </a>
    
</div>

<div class="menu">

    <div class="breadLine">
        <div class="arrow"></div>
        <div class="adminControl active">
            <?php 
                if(isset($_SESSION['name']))
                    echo 'Hi! '.$_SESSION['name'] ;
            ?>
        </div>
    </div>
    <?php
            // echo base_url.'/public/img/users/avatar.jpg';
            // echo base_url.'/public/img/'.$_SESSION['avatar'];
        ?>
    <div class="admin">
        <div class="image">
        
            <img src="
            <?php 
            
            
            if(!empty($_SESSION['avatar'])) echo base_url.'/public/img/'.$_SESSION['avatar'];
            else echo base_url.'/public/img/users/avatar.jpg';
            ?>

            " class="img-polaroid"/>
        </div>
        <ul class="control">
            <li><span class="icon-cog"></span> <a href="../user/edit?id=<?php echo $_SESSION['id']; ?>">Update Profile</a></li>
            <li><span class="icon-share-alt"></span> <a href="../user/logout">Logout</a></li>
        </ul>
    </div>

    <ul class="navigation">
        <li>
            <a href="../category/show">
                <span class="isw-grid"></span><span class="text">Categories</span>
            </a>
        </li>
        <li>
            <a href="../product/show">
                <span class="isw-list"></span><span class="text">Products</span>
            </a>
        </li>
        <li>
            <a href="../user/show">
                <span class="isw-user"></span><span class="text">Users</span>
            </a>
        </li>
    </ul>

</div>

<div class="content">
    <?php  
        if($content!='') {
            $this->view->load($content,$data,'',$error);
        }
        
    ?>
</div>

</body>
</html>