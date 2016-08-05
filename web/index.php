<?php
$data=file_get_contents('php://input');

if(!$data)
    die('no data received!');
$data=json_decode($data,1);
if(!$data || !$data['REPORT_ID'])
    die('data deserialization failed!');
$n=strpos($data['STACK_TRACE'], "\n");
if($n)
    $data['@message'] = substr($data['STACK_TRACE'], 0, $n);
else
    echo('invalid stack trace!');
if($data['USER_CRASH_DATE'])
    $data['@timestamp']=substr($data['USER_CRASH_DATE'],0,strpos($data['USER_CRASH_DATE'],'.'));

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
$res=file_put_contents($config['logDir'].'mvideo.log',"\n".$data,FILE_APPEND);
if($res)
  echo 'data saved';
else
  echo 'failed to save data';
?>