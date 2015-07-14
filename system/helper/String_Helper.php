<?php
function string_to_int($str){
	return sprintf("%u",crc32($str));
}