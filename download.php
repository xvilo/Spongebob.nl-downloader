<?php

// Get 3 commands from user
$url = readline("url: ");
$ep  = readline("EP. NO.: ");

// Download the video page
$html = file_get_contents($url);

//Get The first XML URL
preg_match_all("/mrss     : '(.*?)',/m", $html, $results);
$result = $results[1][0];

//Download The first XML
$videohtml = file_get_contents($result);

//Pare the first XML
$xml=simplexml_load_string($videohtml) or die("Error: Cannot create object");

//Get the second XML URL
$videopage = $xml->channel->item->children('media', true)->group->content->attributes()['url'];

//Download the second XML
$videopagehtml = file_get_contents($videopage);

//Get all video format's and download the highest availble
$videoxml=simplexml_load_string($videopagehtml) or die("Error: Cannot create object");

foreach ($videoxml->video->item->rendition as $item) {

    $rtmpurl = $item->src;
}

//Create the RTMPDump Command
$command = 'rtmpdump -r "'.$rtmpurl.'" -o "Spongebob Squarepants - '.$ep.'.mp4"'.PHP_EOL;

//Store The command in a SH File
$file = 'videos.sh';
$current = file_get_contents($file);
$current .= $command;
file_put_contents($file, $current);

//Done, you can now run the SH file with all your video's