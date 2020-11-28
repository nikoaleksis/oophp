<?php
namespace Niko\CMS;

use Niko\TextFilter\MyTextFilter;

class Post
{
    /**
    * @var object $database The database that is to be used.
    * @var Textfilter $textFilter The textfilter that is used.
    */
    private $database;
    private $textFilter;

    /**
     * Constructor for creating a new post object.
     * @param object $database - The database that is to be used
     */
    public function __construct($database)
    {
        $this->database = $database;
        $this->database->connect();
        $this->textFilter = new MyTextFilter();
    }

    /**
     * Shows all the posts from the database.
     * @return object $res - The resultset from the database.
     */
    public function showPosts()
    {
        $sql = <<<EOD
            SELECT
            *,
            DATE_FORMAT(COALESCE(updated, published), '%Y-%m-%dT%TZ') AS published_iso8601,
            DATE_FORMAT(COALESCE(updated, published), '%Y-%m-%d') AS published
            FROM content
            WHERE type=?
            ORDER BY published DESC
            ;
        EOD;

        $res = $this->database->executeFetchAll($sql, ["post"]);

        foreach ($res as $key => $post) {
            $filter = explode(",", $post->filter);
            $text = $post->data;

            $post->data = $this->textFilter->parse($text, $filter);

            if ($post->deleted) {
                unset($res[$key]);
            }
        }

        return $res;
    }

    /**
     * Returns a single post.
     * @param string $slug - slug that matches the psot.
     * @return $res - The resultset from the database
     */
    public function showOnePost($slug)
    {
        $sql = <<<EOD
            SELECT
            *,
            DATE_FORMAT(COALESCE(updated, published), '%Y-%m-%dT%TZ') AS published_iso8601,
            DATE_FORMAT(COALESCE(updated, published), '%Y-%m-%d') AS published
            FROM content
            WHERE
            slug = ?
            AND type = ?
            AND (deleted IS NULL OR deleted > NOW())
            AND published <= NOW()
            ORDER BY published DESC
            ;
        EOD;

        $res = $this->database->executeFetch($sql, [$slug, "post"]);
        $filter = explode(",", $res->filter);
        $text = $res->data;

        $res->data = $this->textFilter->parse($text, $filter);

        return $res;
    }
}
