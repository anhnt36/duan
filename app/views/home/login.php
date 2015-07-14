<!DOCTYPE html>
<html lang="en">
<head>        
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />

    <title>NTQ Solution Admin Control Panel</title>

    <link rel="icon" type="image/ico'" href="<?php echo base_url.'/public/favicon.ico'; ?>"/>
    
    <link href="<?php echo base_url.'/public/css/stylesheets.css'; ?>" rel="stylesheet" type="text/css" />
</head>
<body>
    
    <div class="loginBox">        
        
        <div class="loginHead">
            <img src="<?php echo base_url.'/public/img/logo.png'; ?>" alt="NTQ Solution Admin Control Panel" title="NTQ Solution Admin Control Panel"/>
        </div>
        <form class="form-horizontal" action="" method="POST">            
            <div class="control-group">
                <label for="inputUsername">Username</label>                
                <input type="text" id="inputUsername" name='nameUser'/>
                <p style="color:red"><?php if(isset($data) && !empty($data['name'])) {echo '* '.$data['name'];} ?></p>
            </div>
            <div class="control-group">
                <label for="inputPassword">Password</label>                
                <input type="password" id="inputPassword" name='password'/>
                <p style="color:red"><?php if(isset($data) && !empty($data['pass'])) {echo '* '.$data['pass'];} ?></p>
                            
            </div>
            <div class="control-group" style="margin-bottom: 5px;">                
                <label class="checkbox"><input type="checkbox" name='rememberUser' value="1"> Remember me</label>                                                
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-block" name='OK'>Sign in</button>
            </div>
        </form>        
        
    </div>    
    
</body>
</html>
