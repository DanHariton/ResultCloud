<?php
include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Library.utility.php');

Library::using(Library::UTILITIES);
Library::using(Library::EXTENTIONS_NOTIFICATION);

class RSS extends BaseNotifier
{
    const NOTIFY_ID = "rss";
    const NOTIFIER_PUBLIC = true;
    public function notify($title, $body, $bodyShort, $to)
    {
    	$xml = array();
    	if (!file_exists($_SERVER['DOCUMENT_ROOT']."/rss.xml")) {
    		$xml_string = "<?xml version=\"1.0\"?>".
					"<rss version=\"2.0\">".
					"  <channel>".
					"  <title>ResultCloud News</title>".
    				"  <link>http://result-cloud.org/</link>".
    				"  <description>ResultCloud analysing results</description>".
                    "  <language>en-us</language>".
			    	"  <docs>http://result-cloud.org/rss.xml</docs>".
					"  </channel>".
					"</rss>";
			$xml = simplexml_load_string($xml_string);		
    	} else {
    		$xml = simplexml_load_file($_SERVER['DOCUMENT_ROOT']."/rss.xml");
    	}
    	$found = false;
    	if (isset($xml->channel->item)) {
    		foreach ($xml->channel->item as $item) {
    			if ($item->title == $title) {
    				$found = true;
    				$item->description = $body;
    				$item->link = $to;
    				break;
    			}
    		}
    		if (!$found) {
    			$xml->channel->addChild('item');
    			$xml->channel->item[count($xml->channel->item)-1]->addChild('title', $title);
    			$xml->channel->item[count($xml->channel->item)-1]->addChild('description', $body);
    			$xml->channel->item[count($xml->channel->item)-1]->addChild('link', $to);
    		}
    	} else {
			$xml->channel->addChild('item');
			$xml->channel->item[count($xml->channel->item)-1]->addChild('title', $title);
			$xml->channel->item[count($xml->channel->item)-1]->addChild('description', $body);
			$xml->channel->item[count($xml->channel->item)-1]->addChild('link', $to);
    	}
		$xml->asXml($_SERVER['DOCUMENT_ROOT']."/rss.xml");
    }
}
