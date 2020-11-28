<?php
namespace Niko\CMS;

class Content
{

    /**
     * @var object $database The database which serves * the content.
    */
    private $database;


    /**
     * Constructor to initiate Content.
     * @param object $database - The database object that is to be used.
     */
    public function __construct($database)
    {
        $this->database = $database;
        $this->database->connect();
    }

    /**
     * Shpw aöö the content.
     * @return object $resuötset -The database content.
     */
    public function showContent()
    {
        $sql = "SELECT * FROM content;";
        $resultset = $this->database->executeFetchAll($sql);

        return $resultset;
    }

    /**
     * Add new content to the database.
     * @param string $title - The title of the new content.
     */
    public function createContent($title)
    {
        $sql = "INSERT INTO content (title) VALUES (?);";
        $this->database->execute($sql, [$title]);
    }

    /**
     * Select a single content item.
     * @param int $id The id of the content.
     * @return object The resultset.
     */
    public function showOne($contentId)
    {
        $sql = "SELECT * FROM content WHERE id = ?;";
        $res = $this->database->executeFetch($sql, [$contentId]);

        return $res;
    }

    /**
     * Edit new content.
     * @param array $params - Array of filters.
     */
    public function editContent($params)
    {
        if ($this->checkForDuplicateSlug($params["slug"], $params["id"])->count != 0) {
            return -1;
        }

        if (!$params["slug"]) {
            $params["slug"] = slugify($params["title"]);
        }

        if (!$params["path"]) {
            $params["path"] = null;
        }

        $sql = "UPDATE content SET title=?, path=?, slug=?, data=?, type=?, filter=?, published=? WHERE id = ?;";
        $this->database->execute($sql, array_values($params));
    }

    /**
     * Soft delete content from the database.
     * @param int $id The content id.
     */
    public function deleteContent($contentId)
    {
        $sql = "UPDATE content SET deleted=NOW() WHERE id=?;";
        $this->database->execute($sql, [$contentId]);
    }

    /**
     * Checks if duplicate slugs exists.
     * @param string $slug - The slug that is to be checked.
     * @param int $contentId The id of the item.
     * @return object The resultset from the database
     */
    private function checkForDuplicateSlug($slug, $contentId)
    {
        $sql = "SELECT count(id) AS count FROM content WHERE slug = ? AND NOT id = ?";

        return $this->database->executeFetch($sql, [$slug, $contentId]);
    }
}
