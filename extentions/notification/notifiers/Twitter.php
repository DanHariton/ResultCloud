<?php
include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Library.utility.php');

Library::using(Library::UTILITIES);
Library::using(Library::EXTENTIONS_NOTIFICATION);

class Twitter extends BaseNotifier
{
    const NOTIFY_ID = "twitter";
    const NOTIFIER_PUBLIC = true;
    public function notify($title, $body, $bodyShort, $to)
    {
    	$settings = array(
		    'oauth_access_token' => "468194650-Lzvs1dmEpkqrLWmHnc2ovC9QyiYj5IdhMdap7e5M",
		    'oauth_access_token_secret' => "djb24ZnjJvVUwwN9EOl9CJqiaOVxg8r7kFMm7cmWY4N6U",
		    'consumer_key' => "wI5tAVWxFBsz5lqNgk0J9CAFL",
		    'consumer_secret' => "c5OOdSSwNapIIoiFXUDr4Y9jKYJnRGwRj3cGMdCCgIzMtk4Uqv"
		);
    	$url = 'https://api.twitter.com/1.1/statuses/update.json';
		$requestMethod = 'POST';

        $postfields = array(
		    'status' => $bodyShort 
		);

		$twitter = new TwitterAPIExchange($settings);
		error_log($twitter->buildOauth($url, $requestMethod)
		    ->setPostfields($postfields)
		    ->performRequest());
    }
}
