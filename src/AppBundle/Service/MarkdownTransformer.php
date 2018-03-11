<?php

namespace AppBundle\Service;


use Doctrine\Common\Cache\Cache;
use Knp\Bundle\MarkdownBundle\MarkdownParserInterface;
use Knp\Bundle\MarkdownBundle\Parser\MarkdownParser;

class MarkdownTransformer
{

    /**
     * @var MarkdownParserInterface
     */
    private $markdownParser;
    /**
     * @var Cache
     */
    private $cache;

    public function __construct(MarkdownParserInterface $markdownParser, Cache $cache)
    {
        $this->markdownParser = $markdownParser;
        $this->cache = $cache;
    }
    
    /**
     * does the transformation and caching
     * @param $str
     * @return string
     */
    public function parse($str)
    {
        $cache = $this->cache;
        $key = md5($str);
        if ($cache->contains($key)) {
            return $cache->fetch($key);
        }
        sleep(1);
        $str = $this->markdownParser
            ->transformMarkdown($str);
        $cache->save($key, $str);
        return $str;
    }

}