<?php
namespace Niko\CMS;

use Niko\TextFilter\MyTextFilter;

class Page
{
    /**
     * @var object $database The database that is to be used.
     * @var Textfilter $textFilter The textfilter that is used.
    */
    private $database;
    private $textFilter;

    /**
     * Constructor to initiate Page.
     * @param object $database - The database that is to be used.
     */
    public function __construct($database)
    {
        $this->database = $database;
        $this->database->connect();
        $this->textFilter = new MyTextFilter();
    }

    /**
     * Show all the pages from the database.
     * @return object $res - The resultset from the database.
     */
    public function showPages()
    {
        $sql =
        <<<EOD
            SELECT
            *,
            CASE
            WHEN (deleted <= NOW()) THEN "isDeleted"
            WHEN (published <= NOW()) THEN "isPublished"
            ELSE "notPublished"
            END AS status
            FROM content
            WHERE type=?
            ;
        EOD;

        $res = $this->database->executeFetchAll($sql, ["page"]);

        foreach ($res as $key => $post) {
            if ($post->deleted) {
                unset($res[$key]);
            }
        }
        return $res;
    }

    /**
     * Shows a single path.
     * @param string $path - The page path.
     */
    public function showOnePage($path)
    {
        $sql = <<<EOD
            SELECT
            *,
            DATE_FORMAT(COALESCE(updated, published), '%Y-%m-%dT%TZ') AS modified_iso8601,
            DATE_FORMAT(COALESCE(updated, published), '%Y-%m-%d') AS modified
            FROM content
            WHERE
            path = ?
            AND type = ?
            AND (deleted IS NULL OR deleted > NOW())
            AND published <= NOW()
            ;
        EOD;

        $res = $this->database->executeFetch($sql, [$path, "page"]);
        $filter = explode(",", $res->filter);
        $text = $res->data;

        $res->data = $this->textFilter->parse($text, $filter);

        return $res;
    }
}
