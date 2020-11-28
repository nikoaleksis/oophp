<?php

namespace Niko\TextFilter;

use Michelf\MarkdownExtra;
use Niko\TextFilter\MyTextFilterException;

/**
* Filter and format text content.
*
* @SuppressWarnings(PHPMD.UnusedFormalParameter)
* @SuppressWarnings(PHPMD.UnusedPrivateField)
*/
class MyTextFilter
{
    /**
    * @var array $filters Supported filters with method names of
    *                     their respective handler.
    */
    private $filters = [
        "bbcode"    => "bbcode2html",
        "link"      => "makeClickable",
        "markdown"  => "markdown",
        "nl2br"     => "nl2br",
    ];



    /**
    * Call each filter on the text and return the processed text.
    *
    * @param string $text   The text to filter.
    * @param array  $filter Array of filters to use.
    *
    * @return string with the formatted text.
    */
    public function parse($text, $filter)
    {
        $parsedText = $text;
        foreach ($filter as $value) {
            if (!in_array($value, array_keys($this->filters)) && $value != null) {
                throw new MyTextFilterException("--ERROR-- {$value} Is not a valid filter.\n" . "On text: {$parsedText}. Please change or remove filter to view the page.");
            }
            if ($value != null) {
                $function = $this->filters[$value];
                $parsedText = $this->$function($text);
            }
            $text = $parsedText;
        }

        return $parsedText;
    }



    /**
    * Helper, BBCode formatting converting to HTML.
    *
    * @param string $text The text to be converted.
    *
    * @return string the formatted text.
    */
    public function bbcode2html($text)
    {
        $search = [
            '/\[b\](.*?)\[\/b\]/is',
            '/\[i\](.*?)\[\/i\]/is',
            '/\[u\](.*?)\[\/u\]/is',
            '/\[img\](https?.*?)\[\/img\]/is',
            '/\[url\](https?.*?)\[\/url\]/is',
            '/\[url=(https?.*?)\](.*?)\[\/url\]/is'
        ];

        $replace = [
            '<strong>$1</strong>',
            '<em>$1</em>',
            '<u>$1</u>',
            '<img src="$1" />',
            '<a href="$1">$1</a>',
            '<a href="$1">$2</a>'
        ];

        return preg_replace($search, $replace, $text);
    }



    /**
    * Make clickable links from URLs in text.
    *
    * @param string $text The text that should be formatted.
    *
    * @return string with formatted anchors.
    */
    public function makeClickable($text)
    {
        return preg_replace_callback(
            '#\b(?<![href|src]=[\'"])https?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#',
            function ($matches) {
                return "<a href=\"{$matches[0]}\">{$matches[0]}</a>";
            },
            $text
        );
    }



    /**
    * @SuppressWarnings(PHPMD.StaticAccess)
    * Format text according to Markdown syntax.
    *
    * @param string $text The text that should be formatted.
    *
    * @return string as the formatted html text.
    */
    public function markdown($text)
    {
        return MarkdownExtra::defaultTransform($text);
    }



    /**
    * For convenience access to nl2br formatting of text.
    *
    * @param string $text The text that should be formatted.
    *
    * @return string the formatted text.
    */
    public function nl2br($text)
    {
        return nl2br($text);
    }
}
