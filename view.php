<?
$dir    = 'data';
$files = scandir($dir);

foreach($files as $file)
{
  if(is_dir($dir.'/'.$file))
    continue;
  echo '<h1>'.$file.'</h1>';
  $data=file_get_contents($dir.'/'.$file);
  $data=json_decode($data,1);
  echo "<pre>".var_export($data,1)."</pre>";
}
?>