<?php

class Syndication
{
	public $rss;
	public $controller;
	public $rss_link;
	public $link;
	public $title;
	public $description;
	public $xml;
	public $feed;
	public $items = array();
	public $results;


	private $directory;
	private $filename;

	public function set_value($att, $value)
	{
		if (property_exists($this, $att))
			$this->$att = $value;
	}
		
	public function attach_item(feed_item $item)
	{
		$this->items[] = $item;
	}
	
	public function load_from_feed(feeds $feed)
	{
	  $this->feed = $feed;
	  $this->set_value('title', $feed->title);
		$this->set_value('description', $feed->description);
		if ($reader = @simplexml_load_string($feed->feed_cache))
			$this->load_items($reader->channel->item);
		else $this->results = "Unable to load the feed.  It has been saved in the database.  Please try again later.";
	}
	
	public function load_feed($address)
	{
	  $this->xml = file_get_contents($address);
		$feed = simplexml_load_string($this->xml);
		
		$this->set_value('title', $feed->channel->title);
		$this->set_value('description', $feed->channel->description);

		$this->load_items($feed->channel->item);
	}

	private function load_items($items)
	{
		foreach($items as $item)
		{
			$object = new feed_item;
			$object->simple_xml_attributes($item);
			$this->attach_item($object);
		}
	}
	
}

#############################################################

class rss_writer extends Syndication
{

	function __construct($controller, $title, $link)
	{
		$this->controller = $controller;
		$this->title = $title;
		$this->link = $link;
	}
	
	public function syndicate($head = true)
	{
		$this->assemble_rss();
		$this->screen_rss($head);
	}
	
	public function assemble_rss()
	{
		$this->rss = $this->rss_wrapper_top();
		$this->rss .= $this->rss_head();	

		foreach ($this->items as $item)
			$this->rss .= $this->rss_item($item);

		$this->rss .= $this->rss_wrapper_bottom();	
	}
	
	private function rss_head()
	{
		return '	
			<title>'.$this->title.'</title>
			<link>'.htmlentities($this->link, ENT_QUOTES, 'ISO8859-15', false).'</link>
			<description>'.$this->description.'</description>';
	}
	
	private function rss_item(feed_item $item)
	{
		return	'
				<item>
					<title>'.$item->title.'</title>
					<link>'.$item->link.'</link>
					<description><![CDATA[ '.$item->description.' ]]></description>
					<pubDate>'.$item->pubDate.'</pubDate>
				</item>';
	}
	
	private function rss_wrapper_top()
	{
		return 	'<?xml version="1.0"?>
<rss version="2.0">
	<channel>';
	}
	
	private function rss_wrapper_bottom()
	{
		return 	'
	</channel>
</rss>';
	}	

	private function screen_rss($head)
	{
		if ($head)
		{
			header('Content-type: application/rss+xml');
			echo $this->rss;
			die;
		}
		else diagnostics($this->rss);
	}

}

###############################################################################

class feed_item extends Model
{
	// Accesible Attributes
	protected $accesor_attributes = array('title', 'link', 'description', 'pubDate', 'guid', 'author');

	// Custom Constructor
	function __construct()
	{
		// Install Custom Attributes
		$this->custom_accessors();
	}

	public function simple_xml_attributes(SimpleXMLElement $attributes)
	{
		foreach ($attributes as $key => $value)
			$this->set_value($key,$value);
	}

	function set_pub_date($when)
	{
		$this->set_value('pubDate', date("D, d M Y H:i:s ", strtotime($when))."GMT");
	}
}

?>
