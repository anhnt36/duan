    <div class="workplace">
        <div class="row-fluid">

            <div class="span12">
                <div class="head">
                    <div class="isw-grid"></div>
                    <h1>Users Management</h1>
                    <div class="clear"></div>
                </div>
                <div class="block-fluid">
                    <form enctype ="multipart/form-data" action="" method="POST">
                        <div class="row-form">
                            <div class="span3">Username:</div>
                            <div class="span9"><input type="text" value="<?php if(isset($data['name'])) {echo $data['name'];} ?>" name='name' /></div>
                            <div class="span3"></div>
                            <p style="color:red"><?php if(isset($error['name'])) {echo '* '.$error['name'];} ?></p>
                            <div class="clear"></div>
                        </div> 
                        <div class="row-form">
                            <div class="span3">Email:</div>
                            <div class="span9"><input type="email" value="<?php if(isset($data['email'])) {echo $data['email'];} ?>" name='email'/></div>
                            <div class="span3"></div>
                            <p style="color:red"><?php if(isset($error['email'])) {echo '* '.$error['email'];} ?></p>
                            <div class="clear"></div>
                        </div> 
                        <div class="row-form">
                            <div class="span3">Password:</div>
                            <div class="span9"><input type="password" value="<?php if(isset($data['pass'])) {echo $data['pass'];} ?>" name='pass'/></div>
                            <div class="span3"></div>
                            <p style="color:red"><?php if(isset($error['pass'])) {echo '* '.$error['pass'];} ?></p>
                            <div class="clear"></div>
                        </div> 
                        <div class="row-form">
                            <div class="span3">Upload Avatar:</div>
                            <div class="span6"><input type="file" size="19" name= 'file'><?php if (isset($data['avatar']) && $data['avatar']!='' && !isset($error['file'])) {echo "<img src='".base_url.'/public/img/'.$data['avatar']."'  height='90' width='90'/>";}?></div><br />
                            <?php if (isset($data['avatar']) && $data['avatar']!='' && !isset($error['file'])){
                                echo"<div>Are you delete this image?</div>
                                    <div><input type='checkbox' name='img' value='1'></div>";
                            }
                            ?>
                            <div class="clear"></div>
                        </div> 
                        <?php
                        if(isset($error['file'])) {
                            echo "
                            <div class='row-form'>
                                <div class='span3'></div>
                                <p style='color:red'>&nbsp&nbsp&nbsp&nbsp&nbsp* {$error['file']}</p>
                                <div class='clear'></div>
                            </div> ";
                        } 
                        ?>

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
                            <button class="btn btn-success" type="submit" name='OK'>Create</button>
                            <div class="clear"></div>
                        </div>
                    </form>
                    <div class="clear"></div>
                </div>
            </div>

        </div>
        <div class="dr"><span></span></div>

    </div>
