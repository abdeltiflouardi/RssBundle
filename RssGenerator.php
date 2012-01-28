<?php

namespace OS\RssBundle;

use Doctrine\ORM\EntityManager,
    DOMDocument,
    DateTime;

/**
 *
 * @author ouardisoft
 */
class RssGenerator
{
    private $em;
    private $dom;
    private $configs;
    private $router;

    function __construct(EntityManager $em, $configs, $router)
    {
        $this->em = $em;
        $this->configs = unserialize($configs);
        $this->router = $router;
    }

    public function bindChannel($rss)
    {
        $channel = $this->dom->createElement('channel');

        // Channel infos
        $tags = array('title', 'description', 'language', 'webMaster', 'link');
        foreach ($tags as $tag) {
            $elem = $this->dom->createElement($tag);
            $text = $this->dom->createTextNode($this->configs[$tag]);
            $elem->appendChild($text);

            $channel->appendChild($elem);
        }

	$elem = $this->dom->createElement('atom:link');
	$elem->setAttribute('href', $this->configs['link']);
	$elem->setAttribute('rel', 'self');
	$elem->setAttribute('type', 'application/rss+xml');

	$channel->appendChild($elem);

        // Channel items
        $this->bindItems($channel);

        $rss->appendChild($channel);        
    }

    public function bindItems($channel)
    {
        $itemConfigs = $this->configs['item'];

        $entities = $this->em->getRepository($itemConfigs['entity'])->findAll();

        $itemTags = array('title', 'link', 'description', 'pubDate', 'guid');
        foreach ($entities as $entity) {
            $item = $this->dom->createElement('item');
            foreach ($itemTags as $tag) {
                $elem = $this->dom->createElement($tag);
                $text = $this->dom->createTextNode($this->getItemTagValue($entity, $tag));

                $elem->appendChild($text);

                $item->appendChild($elem);
            }
            $channel->appendChild($item);
        }
    }    
    
    public function getItemTagValue($entity, $tag)
    {
        $itemConfigs = $this->configs['item'];
        
        if (!is_array($itemConfigs[$tag])) {
            $value = $entity->{'get' . ucfirst($itemConfigs[$tag])}();

            if ($value instanceof DateTime) {
                $value = $value->format('r');
            } else {
                $value = substr($value, 0, 100);
            }

            return $value;
        } else {
            extract($itemConfigs[$tag]);
            
            foreach ($params as $key => $param) {
                if (is_array($param)) {
                    $value = $entity->{'get' . ucfirst($param['field'])}();
                    $object = new $param['class'];
                    $params[$key] = $object->{$param['method']}($value);
                } else {
                    $value = $entity->{'get' . ucfirst($param)}();
                    $params[$key] = $value;
                }
            }
            return $this->router->generate($route, $params, true);
        }
    }
    
    public function getContent()
    {
        $this->dom = new DOMDocument('1.0', 'UTF-8');
        $this->dom->formatOutput = true;
        $this->dom->substituteEntities = false;

        $rss = $this->dom->createElement('rss');
        $rss->setAttribute('version', '2.0');
	$rss->setAttribute('xmlns:atom', 'http://www.w3.org/2005/Atom');        
        $this->bindChannel($rss);

        $this->dom->appendChild($rss);

        return $this->dom->saveXML();
    }

}

?>