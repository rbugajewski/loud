<?php

require_once 'Spyc.php';
require_once 'simple_html_dom.php';
require_once 'functions.php';

if (isset($_GET['add']))
{
  $url = urldecode($_GET['url']);
  $description = urldecode($_GET['description']);
  if ($description == '')
  {
    $description = 'Added by <a href="https://www.cocobit.software/">Cocobit Software</a> Loud.';
  }
  $items = Spyc::YAMLLoad('urls.yaml');
  $html = file_get_html($url);
  $item = array();
  $media_url = $html->find('a[href$=m4a', 0);
  if ($media_url == '')
  {
    $media_url = $html->find('a[href$=mp3]', 0);
    if ($media_url == '')
    {
      die('Cannot find any media files.');
    }
  }
  $resolved_media_url = resolve_url($media_url->href);

  foreach ($items as $i)
  {
    if ($i['enc_link'] == $resolved_media_url)
    {
      die('Duplicate link detected. Skip.');
    }
  }

  $item['title'] = $html->find('head title', 0)->innertext;
  $item['link'] = $url;
  $item['date'] = time();
  $item['description'] = $description;
  $item['enc_link'] = $resolved_media_url;
  $item['enc_length'] = remote_filesize($item['enc_link']);

  array_unshift($items, $item);
  file_put_contents('urls.yaml', Spyc::YAMLDump($items));
  ?>

<!DOCTYPE html>
<html>
<head>
<title>Loud.</title>
<meta charset='utf-8'>
<meta http-equiv="refresh"
content="3;url=<?php echo $url; ?>">
</head>
<body>
<h1>Added file to Loud.</h1>
<p>You’ll be redirected back to <a href="<?php echo $url; ?>"><?php echo $url; ?></a> in 3 seconds.</p>
<p>&copy; 2012 <a href="https://www.cocobit.software/">Cocobit Software</a></p>
</body>
</html>

  <?php
  exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Loud.</title>
<meta charset='utf-8'>
</head>
<body>
<h1>Loud.</h1>
<p>Drag the <a href="javascript:location.href='https://<?php echo $_SERVER['HTTP_HOST'].'/'.get_subdir($_SERVER[PHP_SELF]).'/' ?>?add&url='+encodeURIComponent(location.href)+'&description='+encodeURIComponent(window.getSelection?window.getSelection():(document.getSelection?document.getSelection():(document.selection?document.selection.createRange().text:'')));">Loud./a> bookmarklet wherever you want.</p>
<p>
  Subscribe:
  <?php $feed_url = '://'.$_SERVER['HTTP_HOST'].'/'.get_subdir($_SERVER[PHP_SELF]).'/feed/'; ?>
  <li><a href="https<?php echo $feed_url; ?>">Classic RSS/Atom Reader</a></li>
  <li><a href="itpc<?php echo $feed_url; ?>">iTunes</a></li>
  <li><a href="pcast<?php echo $feed_url; ?>">Podcast app</a></li>
</p>
<p>&copy; 2012–2022 <a href="https://www.cocobit.software/">Cocobit Software</a></p>
</body>
</html>

<?php
