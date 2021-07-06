<?php

define('CHATWORK_API_TOKEN', 'xxxxxxxxxx');

$opt = getopt('r:b:', array('room:', 'body:'));

$roomID = null;
if (isset($opt['room'])) {
    $roomID = $opt['room'];
} else if (isset($opt['r'])) {
    $roomID = $opt['r'];
} else {
    exit;
}

$body = null;
if (isset($opt['body'])) {
    $body = $opt['body'];
} else if (isset($opt['b'])) {
    $body = $opt['b'];
} else {
    exit;
}

$url = 'https://api.chatwork.com/v2/rooms/'.$roomID.'/messages';
error_log('url='.$url);
$headers = array(
    'User-Agent: tiffany-test',
    'Content-Type: application/x-www-form-urlencoded',
    'X-ChatWorkToken: ' .CHATWORK_API_TOKEN,
);
$data = array(
    'body' => ('[API] '.$body),
);
$options = array('http' => array(
    'method' => 'POST',
    'header' => implode("\r\n", $headers),
    'content' => http_build_query($data),
));
$contents = file_get_contents($url, false, stream_context_create($options));

// unread message.
$res = json_decode($contents, true);
$messageID = $res['message_id'];
$url = 'https://api.chatwork.com/v2/rooms/'.$roomID.'/messages/unread';
$data = array(
    'message_id' => $messageID,
);
echo 'message_id='.$messageID.' '."\n";
$options = array('http' => array(
    'ignore_errors' => true,
    'method' => 'PUT',
    'header' => implode("\r\n", $headers),
    'content' => http_build_query($data),
));
$unread_status_all = '';
for ($i = 0; $i < 3; $i++) {
  file_get_contents($url, false, stream_context_create($options));
  $unread_status = $http_response_header[0];
  $unread_status_all .= $unread_status.',';
  $pos = strpos($unread_status, '200');
  if ($pos === false) {
    // not 200.
    $pos = strpos($unread_status, 'The messages is already marked as unread.');
    if ($pos !== false) {
      // already unread.
      break;
    }
    // unread.
    sleep(4);
    continue;
  }
  break;
}

echo $contents."\n";
echo $unread_status_all."\n";
