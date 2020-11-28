<?php

namespace Niko\Controller;

use Anax\Commons\AppInjectableInterface;
use Anax\Commons\AppInjectableTrait;
use Niko\TextFilter\MyTextFilter;

// use Anax\Route\Exception\ForbiddenException;
// use Anax\Route\Exception\NotFoundException;
// use Anax\Route\Exception\InternalErrorException;

/**
* A controller for rendering content and playing the dice game.
* @SuppressWarnings(PHPMD.TooManyPublicMethods)
*/
class TextFilterController implements AppInjectableInterface
{
    use AppInjectableTrait;
    private $filter;

    public function initialize()
    {
        $this->filter = new MyTextFilter();
    }

    /**
    * Show bbcode.
    * @return object ResponseUtility
    */
    public function bbCodeActionGet() : object
    {
        $title = "BBCODE";
        $text = file_get_contents(__DIR__ . "/../../htdocs/text/bbcode.txt");
        $filters = array("bbcode");
        $filters2 = array("bbcode", "nl2br");
        $bbcodeToHtml = $this->filter->parse($text, $filters);
        $htmlToNl2br = $this->filter->parse($text, $filters2);

        $data = [
            "bbcodeToHtml" => $bbcodeToHtml,
            "htmlToNl2br" => $htmlToNl2br,
            "text" => $text
        ];

        $this->app->page->add("textfilter/bbcode", $data);

        return $this->app->page->render([
            "title" => $title,
        ]);
    }

    /**
    * Show clickable.
    * @return object ResponseUtility
    */
    public function clickableActionGet() : object
    {
        $title = "Clickable";
        $text = file_get_contents(__DIR__ . "/../../htdocs/text/clickable.txt");
        $filters = array("link");
        $html = $this->filter->parse($text, $filters);

        $data = [
            "html" => $html,
            "text" => $text
        ];

        $this->app->page->add("textfilter/clickable", $data);

        return $this->app->page->render([
            "title" => $title,
        ]);
    }

    /**
    * Show Markdown.
    * @return object ResponseUtility
    */
    public function markdownActionGet() : object
    {
        $title = "Markdown";
        $text = file_get_contents(__DIR__ . "/../../htdocs/text/sample.md");
        $filters = array("markdown");
        $html = $this->filter->parse($text, $filters);

        $data = [
            "html" => $html,
            "text" => $text
        ];

        $this->app->page->add("textfilter/markdown", $data);

        return $this->app->page->render([
            "title" => $title,
        ]);
    }
}
