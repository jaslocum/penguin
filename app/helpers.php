<?php

function flash($title = null, $message = null, $level = null)
{
    $flash = app('App\Http\Flash');

    if (func_num_args()==0) {
        return $flash;
   }

    return $flash->overlay($title, $message, $level);
}

/**
 * @param $md5
 * @return string
 */
function md5path($md5)
{

    $path = "images/";

    $md5path = $path.substr($md5,0,3)."/".substr($md5,3,3)."/".substr($md5,6,3)."/";
    return $md5path;

}

/**
 * @param $md5
 * @return string
 */
function md5filename($md5)
{

    $md5filename = substr($md5,9,23);
    return $md5filename;

}
