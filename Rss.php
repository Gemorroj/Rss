<?php
/**
 *
 * This software is distributed under the GNU GPL v3.0 license.
 *
 * @author    Gemorroj
 * @copyright 2012 http://wapinet.ru
 * @license   http://www.gnu.org/licenses/gpl-3.0.txt
 * @link      https://github.com/Gemorroj/Rss
 * @version   0.1
 *
 */

class Rss extends DOMDocument
{
    /**
     * @var DOMNode
     */
    private $channel;


    /**
     * @param string $title
     * @param string $link
     * @param string $description
     */
    public function __construct($title, $link, $description)
    {
        parent::__construct('1.0', 'UTF-8');

        $date = new DateTime();
        $root = $this->createElement('rss');
        $root->setAttribute('version', '2.0');
        $rss = $this->appendChild($root);

        $channel = $rss->appendChild($this->createElement('channel'));

        $channel->appendChild($this->createElement('title', $title));
        $channel->appendChild($this->createElement('link', $link));
        $channel->appendChild($this->createElement('description', $description));
        $channel->appendChild($this->createElement('pubDate', $date->format(DateTime::RSS)));

        $this->channel = $channel;
    }


    /**
     * @param string         $title
     * @param string         $link
     * @param string         $description
     * @param DateTime       $date
     */
    public function addItem($title, $link, $description, DateTime $date = null)
    {
        $item = $this->createElement('item');
        $item->appendChild($this->createElement('title', $title));
        $item->appendChild($this->createElement('link', $link));
        $item->appendChild($this->createElement('description', $description));

        if ($date !== null) {
            $item->appendChild($this->createElement('pubDate', $date->format(DateTime::RSS)));
        }

        $this->channel->appendChild($item);
    }


    /**
     * Output rss
     */
    public function output()
    {
        header('Content-Type: application/rss+xml; charset=UTF-8');
        echo $this->saveXML();
    }
}
