<?php

function replace_dots($arr)
{
	$arr2=$arr;
	foreach($arr as $key=>$val)
	{
		if(strpos($key,'.')!==FALSE)
		{
			$key2=str_replace('.','_',$key);
			$arr2[$key2]=$val;
			unset($arr2[$key]);
		}
	}
	unset($arr);
	foreach($arr2 as $key=>$val)
	{
		if(is_array($val))
			$arr2[$key]=replace_dots($val);
	}
	return $arr2;
}
$data=file_get_contents('php://input');

if(!$data)
    die('no data received!');
$data=json_decode($data,1);
$data=replace_dots($data);
if(!$data || !$data['REPORT_ID'])
    die('data deserialization failed!');
$n=strpos($data['STACK_TRACE'], "\n");
if($n)
    $data['@message'] = substr($data['STACK_TRACE'], 0, $n);
else
    echo('invalid stack trace!');
if($data['USER_CRASH_DATE'])
    $data['@timestamp']=$data['USER_CRASH_DATE'];

$data['REMOTE_ADDR']=$_SERVER['REMOTE_ADDR'];
$data['HTTP_X_FORWARDED_FOR']=$_SERVER['HTTP_X_FORWARDED_FOR'];

$data=json_encode($data);

$config=file_get_contents('../config/acra2kibana.json');
if(!$config)
	die('No config file found!');
$config=json_decode($config,true);
if(!$config)
	die('Could not parse config!');
if(!$config['logDir'])
	die('Log location not specified!');
if(($config['logDir'][strlen($config['logDir'])-1])!='/')
	$config['logDir'].='/';
$res=file_put_contents($config['logDir'].'mvideo.log',$data."\n",FILE_APPEND);
if($res)
  echo 'data saved';
else
  echo 'failed to save data';
?>