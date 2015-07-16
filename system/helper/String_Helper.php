<?php
function string_to_int($str){
	return sprintf("%u",crc32($str));
}
function path($name){
    if(isset($_GET['search'])) {
                        echo 'context='.$_GET['context'].'&search='.$_GET['search'].'&';
                    } 
                    echo "s={$name}";
                    if(isset($_GET['type'])) {
                        if($_GET['type']=='DESC') echo '&type=ASC';
                        else echo '&type=DESC';
                    } else {
                        echo '&type=DESC';
                    }
                    if(isset($_GET['page'])) {
                        echo '&page='.$_GET['page'];
                    }
                }