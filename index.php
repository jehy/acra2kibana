<?
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
$res=file_put_contents('data/'.time().'.json',$data);
if($res)
  echo 'data saved';
else
  echo 'failed to save data';
?>