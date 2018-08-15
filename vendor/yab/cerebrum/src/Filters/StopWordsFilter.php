<?php

namespace Yab\Cerebrum\Filters;

class StopWordsFilter
{
    protected $language;

    protected $version;

    public $stopWords = [];

    /**
     * Populate the stopWords array.
     *
     * @param string $language
     */
    public function __construct($language = 'english')
    {
        $this->language = $language;
        $this->version = 1;

        $fileName = 'stop-words_'.$this->language.'_'.$this->version.'.txt';

        $path = __DIR__.'/../../data/'.basename($fileName);

        if (file_exists($path)) {
            $this->stopWords = array_map('trim', file($path));
        }
    }

    /**
     * Check if the stop word is in the list.
     *
     * @param string $word
     */
    public function filter($word)
    {
        if (in_array($word, $this->stopWords)) {
            return $word;
        }

        return null;
    }
}
