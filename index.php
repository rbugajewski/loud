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
    $description = 'Added by <a href="http://jeredb.com/">Jered\'s</a> awesome Ping service.';
  }
  $items = Spyc::YAMLLoad('urls.yaml');
  $html = file_get_html($url);
  $item = array();
  $media_url = $html->find('a[href$=m4a]', 0);
  if ($media_url == '')
  {
    $media_url = $html->find('a[href$=mp3]', 0);
    if ($media_url == '')
    {
      die('I could not find any media files.');
    }
  }
  $resolved_media_url = resolve_url($media_url->href);

  foreach ($items as $i)
  {
    if ($i['enc_link'] == $resolved_media_url)
    {
      die('I already have this link. I will not add a duplicate.');
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
<title>Ping. A Private Sonar Feed.</title>
<meta charset='utf-8'>
<meta http-equiv="refresh" content="3;url=<?php echo $url; ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <link rel="stylesheet" href="stylesheets/base.css">
	<link rel="stylesheet" href="stylesheets/skeleton.css">
	<link rel="stylesheet" href="stylesheets/layout.css">
</head>
<body>
<div class="container add-top">
<div class="one-third column offset-by-six">
<h1>Ping!</h1><h2>File Added to</h2>
<h4>Your A Private Sonar Feed.</h4>
<p>You’ll be redirected back to <a class="bookmarklet" href="<?php echo $url; ?>"><?php echo $url; ?></a> in 3 seconds.</p>
</div>
</div>
</body>
</html>

  <?php
  exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Ping! A Private Sonar Feed.</title>
<meta charset='utf-8'>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link rel="stylesheet" href="stylesheets/base.css">
	<link rel="stylesheet" href="stylesheets/skeleton.css">
	<link rel="stylesheet" href="stylesheets/layout.css">
</head>
<body>
<div class="container add-top">
<div class="one-third column offset-by-six">
<h1>Ping!</h1><h4>A Private Sonar Feed.</h4>
<p>Drag the <a class="bookmarklet" href="javascript:location.href='<?php $feed_url = 'http://'.$_SERVER['HTTP_HOST'].'/'.get_subdir($_SERVER[PHP_SELF],'').'/ping/'; ?>index.php?add&url='+encodeURIComponent(location.href)+'&description='+encodeURIComponent(window.getSelection?window.getSelection():(document.getSelection?document.getSelection():(document.selection?document.selection.createRange().text:'')));">Ping</a> bookmarklet wherever you want.</p>
<textarea>
javascript:location.href='<?php $feed_url = '://'.$_SERVER['HTTP_HOST'].'/'.get_subdir($_SERVER[PHP_SELF],'').'/ping/'; ?>index.php?add&url='+encodeURIComponent(location.href)+'&description='+encodeURIComponent(window.getSelection?window.getSelection():(document.getSelection?document.getSelection():(document.selection?document.selection.createRange().text:'')));
</textarea>
<p>
  Subscribe:
  <?php $feed_url = '://'.$_SERVER['HTTP_HOST'].'/'.get_subdir($_SERVER[PHP_SELF],'').'/ping/feed/'; ?>
  <li><a href="http<?php echo $feed_url; ?>">Classic RSS/Atom Reader</a></li>
  <li><a href="itpc<?php echo $feed_url; ?>">iTunes</a></li>
  <li><a href="pcast<?php echo $feed_url; ?>">Podcast app</a></li>
</p>
</div>
</div>
</body>
</html>
