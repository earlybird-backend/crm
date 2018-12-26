<?php
function responseBase($err,$message,$data=array())
{
    $result = array(
        "error"=>$err,
        "message"=>$message,
        "data"=>$data);
    return $result;
}
function responseErr($errMessage,$data=array())
{
    $result = responseBase(1,$errMessage,$data);
    return $result;
}
function responseErrStr($errMessage,$data=array())
{
    $result = responseErr($errMessage,$data);
    return json_encode($result);
}
function responseTrue($message,$data=array())
{
    $result = responseBase(0,$message,$data);
    return $result;
}
function responseTrueStr($message,$data=array())
{
    $result = responseTrue($message,$data);
    return json_encode($result);
}