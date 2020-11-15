<?php

namespace Niko\Controller;

use Anax\Commons\AppInjectableInterface;
use Anax\Commons\AppInjectableTrait;

// use Anax\Route\Exception\ForbiddenException;
// use Anax\Route\Exception\NotFoundException;
// use Anax\Route\Exception\InternalErrorException;

/**
* A controller for rendering content and playing the dice game.
* @SuppressWarnings(PHPMD.TooManyPublicMethods)
*/
class MovieController implements AppInjectableInterface
{
    use AppInjectableTrait;

    /**
    * Show the complete list of movies.
    * @return object ResponseUtility
    */
    public function showAllActionGet() : object
    {
        $title = "Movie database | oophp";

        $this->app->db->connect();
        $sql = "SELECT * FROM movie;";
        $res = $this->app->db->executeFetchAll($sql);

        $this->app->page->add("movie/show-all", [
            "res" => $res,
        ]);

        return $this->app->page->render([
            "title" => $title,
        ]);
    }

    /**
    * Show the movies paginated.
    * @return object ResponseUtility
    */
    public function showAllPaginateActionGet() : object
    {
        $title = "Show, paginate movies";

        $this->app->db->connect();

        //Number of hits, check input
        $hits = $this->checkForNumberOfHits();

        // Get max number of pages
        $sql = "SELECT COUNT(id) AS max FROM movie;";
        $max = $this->app->db->executeFetchAll($sql);
        $max = ceil($max[0]->max / $hits);

        // Get current page, check input
        $page = $this->checkForPage($hits, $max);
        $offset = $hits * ($page - 1);

        // Incoming matches valid value sets
        $orderBy = $this->checkOrderByValue();
        $order = $this->checkOrderValue();

        $sql = "SELECT * FROM movie ORDER BY $orderBy $order LIMIT $hits OFFSET $offset;";
        $res = $this->app->db->executeFetchAll($sql);

        $this->app->page->add("movie/show-all-paginate", [
            "res" => $res,
            "max" => $max,
        ]);

        return $this->app->page->render([
            "title" => $title,
        ]);
    }

    /**
    * View for searching for a movie by the title.
    * @return object ResponseUtility
    */
    public function searchTitleActionGet()
    {
        $title = "Search for a movie by title";
        $searchTitle = $this->app->request->getGet("searchTitle");

        $this->app->db->connect();

        if ($searchTitle) {
            $sql = "SELECT * FROM movie WHERE title LIKE ?;";
            $res = $this->app->db->executeFetchAll($sql, [$searchTitle]);
        }

        $this->app->page->add("movie/search-title", [
            "searchTitle" => $searchTitle,
        ]);
        if (isset($res)) {
            $this->app->page->add("movie/show-all", [
                "res" => $res,
            ]);
        }

        return $this->app->page->render([
            "title" => $title,
        ]);
    }

    /**
    * View for searching for movies based on which year they were released.
    * @return object ResponseUtility
    */
    public function searchYearActionGet()
    {
        $title = "Search by year";

        $this->app->db->connect();

        $fromYear = $this->app->request->getGet("fromYear");
        $toYear = $this->app->request->getGet("toYear");

        if ($fromYear && $toYear) {
            $sql = "SELECT * FROM movie WHERE year >= ? AND year <= ?;";
            $res = $this->app->db->executeFetchAll($sql, [$fromYear, $toYear]);
        } elseif ($fromYear) {
            $sql = "SELECT * FROM movie WHERE year >= ?;";
            $res = $this->app->db->executeFetchAll($sql, [$fromYear]);
        } elseif ($toYear) {
            $sql = "SELECT * FROM movie WHERE year <= ?;";
            $res = $this->app->db->executeFetchAll($sql, [$toYear]);
        }

        $this->app->page->add("movie/search-year", [
            "fromYear" => $fromYear,
            "toYear" => $toYear,
        ]);

        if (isset($res)) {
            $this->app->page->add("movie/show-all", [
                "res" => $res,
            ]);
        }

        return $this->app->page->render([
            "title" => $title,
        ]);
    }

    /**
    * View for administrating the movie database.
    * @return object ResponseUtility
    */
    public function movieSelectActionGet()
    {
        $title = "Select a movie";

        //Destroy previous sessions
        $this->app->session->destroy();

        // Initiate the session for the game.
        $this->app->session->start();

        $this->app->db->connect();

        $sql = "SELECT id, title FROM movie;";
        $res = $this->app->db->executeFetchAll($sql);

        $this->app->page->add("movie/movie-select", [
            "res" => $res,
        ]);

        if ($this->app->request->getGet("showAll")) {
            $sql = "SELECT * FROM movie;";
            $res = $this->app->db->executeFetchAll($sql);

            $this->app->page->add("movie/show-all", [
                "res" => $res,
            ]);
        }

        return $this->app->page->render([
            "title" => $title,
        ]);
    }

    /**
    * Handles the post request from the administration view.
    * @return object ResponseUtility
    */
    public function movieSelectActionPost()
    {
        $this->app->db->connect();

        $movieId = $this->app->request->getPost("movieId");

        if ($this->app->request->getPost("doDelete")) {
            $sql = "DELETE FROM movie WHERE id = ?;";
            $this->app->db->execute($sql, [$movieId]);
            return $this->app->response->redirect("movie/movie-select?showAll=true");
        } elseif ($this->app->request->getPost("doAdd")) {
            return $this->app->response->redirect("movie/movie-insert");
        } elseif ($this->app->request->getPost("doEdit") && is_numeric($movieId)) {
            $this->app->session->set("movieId", $movieId);
            return $this->app->response->redirect("movie/movie-edit");
        }
    }

    /**
    * Renders the view for reseting the database.
    * @return object ResponseUtility
    */
    public function resetActionGet()
    {
        $title = "Reset the database";
        $output = $this->app->session->get("output") ?: " ";

        $this->app->session->destroy();

        $this->app->page->add("movie/reset", [
            "output" => $output,
        ]);

        return $this->app->page->render([
            "title" => $title,
        ]);
    }

    /**
    * Handles the post request from the reset view.
    * @return object ResponseUtility
    */
    public function resetActionPost()
    {
        $this->app->session->start();

        // Restore the database to its original settings
        $file   = ANAX_INSTALL_PATH . "/sql/movie/setup.sql";
        $mysql  = "mysql";
        $output = null;
        $databaseConfig = $this->app->configuration->load("database")["config"];

        // Extract hostname and databasename from dsn
        $dsnDetail = [];
        preg_match("/mysql:host=(.+);dbname=([^;.]+)/", $databaseConfig["dsn"], $dsnDetail);
        $host = $dsnDetail[1];
        $database = $dsnDetail[2];
        $login = $databaseConfig["username"];
        $password = $databaseConfig["password"];

        if ($this->app->request->getPost("reset")) {
            $command = "$mysql -h{$host} -u{$login} -p{$password} $database < $file 2>&1";
            $output = [];
            $status = null;
            exec($command, $output, $status);
            $output = "<p>The command was: <code>$command</code>.<br>The command exit status was $status."
            . "<br>The output from the command was:</p><pre>"
            . print_r($output, 1);
            $this->app->session->set("output", $output);
        }


        return $this->app->response->redirect("movie/reset");
    }

    /**
    * Renders the view for inserting a movie.
    * @return object ResponseUtility
    */
    public function movieInsertActionGet()
    {
        $title = "Add a movie";

        $this->app->db->connect();

        $this->app->page->add("movie/movie-insert", [
        ]);

        if ($this->app->request->getGet("showAll")) {
            $sql = "SELECT * FROM movie;";
            $res = $this->app->db->executeFetchAll($sql);

            $this->app->page->add("movie/show-all", [
                "res" => $res,
            ]);
        }

        return $this->app->page->render([
            "title" => $title,
        ]);
    }

    /**
    * Handles the post request from the insert view.
    * @return object ResponseUtility
    */
    public function movieInsertActionPost()
    {
        $this->app->db->connect();

        $movieTitle = $this->app->request->getPost("movieTitle");
        $movieYear  = $this->app->request->getPost("movieYear");
        $movieImage = $this->app->request->getPost("movieImage");

        if ($this->app->request->getPost("doSave")) {
            $sql = "INSERT INTO movie (title, year, image) VALUES (?, ?, ?);";
            $this->app->db->execute($sql, [$movieTitle, $movieYear, $movieImage]);
            return $this->app->response->redirect("movie/movie-select?showAll=true");
        }
        if ($this->app->request->getPost("reset")) {
            return $this->app->response->redirect("movie/movie-insert");
        }
    }

    /**
    * Renders the edit movie view.
    * @return object ResponseUtility
    */
    public function movieEditActionGet()
    {
        $title = "Edit a movie";

        $this->app->db->connect();

        $movieId = $this->app->session->get("movieId");

        $sql = "SELECT * FROM movie WHERE id = ?;";
        $movie = $this->app->db->executeFetch($sql, [$movieId]);

        $this->app->page->add("movie/movie-edit", [
            "movie" => $movie,
        ]);

        if ($this->app->request->getGet("showAll")) {
            $sql = "SELECT * FROM movie;";
            $res = $this->app->db->executeFetchAll($sql);

            $this->app->page->add("movie/show-all", [
                "res" => $res,
            ]);
        }

        if ($this->app->request->getGet("showMovie")) {
            $sql = "SELECT * FROM movie WHERE id = ?;";
            $res = $this->app->db->executeFetchAll($sql, [$movieId]);

            $this->app->page->add("movie/show-all", [
                "res" => $res,
            ]);
        }

        return $this->app->page->render([
            "title" => $title,
        ]);
    }

    /**
    * Handles the POST-request from the edit movie view.
    * @return object ResponseUtility
    */
    public function movieEditActionPost()
    {
        $this->app->db->connect();

        $movieId    = $this->app->request->getPost("movieId");
        $movieTitle = $this->app->request->getPost("movieTitle");
        $movieYear  = $this->app->request->getPost("movieYear");
        $movieImage = $this->app->request->getPost("movieImage");

        if ($this->app->request->getPost("doSave")) {
            $sql = "UPDATE movie SET title = ?, year = ?, image = ? WHERE id = ?;";
            $this->app->db->execute($sql, [$movieTitle, $movieYear, $movieImage, $movieId]);
            $this->app->session->destroy();
            return $this->app->response->redirect("movie/movie-select?showAll=true");
        }
        if ($this->app->request->getPost("reset")) {
            return $this->app->response->redirect("movie/movie-edit");
        }
    }

    /**
    * Renders the debug view.
    * @return object ResponseUtility
    */
    public function debugActionGet()
    {
        $title = "Debug";

        $this->app->page->add("movie/debug", [
        ]);

        return $this->app->page->render([
            "title" => $title,
        ]);
    }

    //Private functions to reduce cyclomatic complexity

    /**
    * Checks the input from the get variable "hits".
    * @return string $hits the number of hits;
    */
    private function checkForNumberOfHits()
    {
        // Get number of hits per page
        $hits = $this->app->request->getGet("hits", 4);

        if (!(is_numeric($hits) && $hits > 0 && $hits <= 8)) {
            $hits = 4;
        }
        return $hits;
    }

    /**
    * Checks the input from the get variable "page".
    * @return string $page Returns the page number;
    */
    private function checkForPage($hits, $max)
    {
        $page = $this->app->request->getGet("page", 1);
        if (!(is_numeric($hits) && $page > 0 && $page <= $max)) {
            $page = 1;
        }
        return $page;
    }

    /**
    * Checks the input from the get variable "orderBy".
    * @return string $orderBy returns the order by value;
    */
    private function checkOrderByValue()
    {
        // Only these values are valid
        $columns = ["id", "title", "year", "image"];
        $orderBy = $this->app->request->getGet("orderby") ?: "id";

        if (!in_array($orderBy, $columns)) {
            $orderBy = "id";
        }
        return $orderBy;
    }

    /**
    * Checks the input from the get variable "order".
    * @return string $order returns the order;
    */
    private function checkOrderValue()
    {
        $orders = ["asc", "desc"];
        $order = $this->app->request->getGet("order") ?: "asc";

        if (!in_array($order, $orders)) {
            $order = "desc";
        }
        return $order;
    }
}
