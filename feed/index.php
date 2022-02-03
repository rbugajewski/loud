<?php
require_once 'FeedWriter.php';
require_once '../Spyc.php';
require_once '../functions.php';

$TestFeed = new FeedWriter(ATOM);
$TestFeed->setTitle('Loud');
$TestFeed->setLink('http://'.$_SERVER['HTTP_HOST'].'/'.get_subdir($_SERVER[PHP_SELF]).'/');
#print_r(get_subdir($_SERVER[PHP_SELF]));
$TestFeed->setChannelElement('updated', date(DATE_ATOM , time()));
$TestFeed->setChannelElement('author', array('name'=>'Cocobit Software (https://www.cocobit.software/)'));

$items = Spyc::YAMLLoad('../urls.yaml');
foreach ($items as $item)
{
  $newItem = $TestFeed->createNewItem();
  $newItem->setTitle($item['title']);
  $newItem->setLink($item['link']);
  $newItem->setDate($item['date']);
  $newItem->setDescription($item['description']);
  $newItem->setEncloser($item['enc_link'], $item['enc_length'], 'audio/mpeg');

  $TestFeed->addItem($newItem);
}

$TestFeed->genarateFeed();
