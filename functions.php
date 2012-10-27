<?php

# http://stackoverflow.com/questions/2747508/php-extract-direct-sub-directory-from-path-string
function get_subdir($dir, $sub)
{
  $dir = array_slice(array_diff(explode('/', $dir), explode('/', $sub)), 0, 1);
  return $dir[0];
}

# http://stackoverflow.com/questions/5958725/get-size-of-remote-file-from-url
function remote_filesize($url)
{
  $contentLength = 0;
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_NOBODY, true);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HEADER, true);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

  $data = curl_exec($ch);
  curl_close($ch);

  if (preg_match('/Content-Length: (\d+)/', $data, $matches))
  {
    $contentLength = (int)$matches[1];
  }

  return $contentLength;
}

# https://gist.github.com/1196916
function resolve_url($url)
{
  $headers = get_headers($url);
  $headers = array_reverse($headers);
  foreach($headers as $header)
  {
    if (strpos($header, 'Location: ') === 0)
    {
      $url = str_replace('Location: ', '', $header);
      break;
    }
  }

  return $url;
}
