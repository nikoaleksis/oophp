<?php

namespace Niko\Controller;

use Niko\CMS\Content;
use Niko\CMS\Page;
use Niko\CMS\Post;

use Anax\Commons\AppInjectableInterface;
use Anax\Commons\AppInjectableTrait;

// use Anax\Route\Exception\ForbiddenException;
// use Anax\Route\Exception\NotFoundException;
// use Anax\Route\Exception\InternalErrorException;

/**
* A controller for rendering content and playing the dice game.
* @SuppressWarnings(PHPMD.TooManyPublicMethods)
*/
class CMSController implements AppInjectableInterface
{
    use AppInjectableTrait;

    public function initialize()
    {
        $this->content = new Content($this->app->db);
        $this->page = new Page($this->app->db);
        $this->post = new Post($this->app->db);
    }

    /**
    * Show the complete list of content.
    * @return object ResponseUtility
    */
    public function showAllActionGet() : object
    {
        $title = "Content";

        $res = $this->content->showContent();

        $this->app->page->add("content/show-all", [
            "res" => $res,
        ]);

        return $this->app->page->render([
            "title" => $title,
        ]);
    }

    /**
    * Show the eror page duplicate slug.
    * @return object ResponseUtility
    */
    public function duplicateSlugActionGet() : object
    {
        $title = "Duplicate Slug";

        $this->app->page->add("content/duplicate-slug", [
        ]);

        return $this->app->page->render([
            "title" => $title,
        ]);
    }

    /**
    * Show the complete list of content.
    * @return object ResponseUtility
    */
    public function createActionGet() : object
    {
        $title = "Create Content";

        $res = $this->content->showContent();

        $this->app->page->add("content/create", [
            "res" => $res,
        ]);

        return $this->app->page->render([
            "title" => $title,
        ]);
    }

    /**
    * Handles post request for creating new *content.
    * @return object ResponseUtility
    */
    public function createActionPost() : object
    {
        $contentTitle = $this->app->request->getPost("contentTitle");

        $this->content->createContent($contentTitle);

        return $this->app->response->redirect("content/edit?id=".  $this->app->db->lastInsertId());
    }

    /**
    * Show the complete list of content.
    * @return object ResponseUtility
    */
    public function adminActionGet() : object
    {
        $title = "Administrate";

        $res = $this->content->showContent();

        $this->app->page->add("content/admin", [
            "res" => $res,
        ]);

        return $this->app->page->render([
            "title" => $title,
        ]);
    }

    /**
    * Show the edit view.
    * @return object ResponseUtility
    */
    public function editActionGet() : object
    {
        $title = "Administrate";

        $contentId = $this->app->request->getGet("id") ?? -1;

        $res = $this->content->showOne($contentId);

        $this->app->page->add("content/edit", [
            "res" => $res,
            "contentId" => $contentId
        ]);

        return $this->app->page->render([
            "title" => $title,
        ]);
    }

    /**
    * Handles post request for editing content.
    * @return object ResponseUtility
    */
    public function editActionPost() : object
    {
        $contentId = $this->app->request->getPost("contentId") ?? -1;

        if ($this->app->request->getPost("doDelete")) {
            return $this->app->response->redirect("content/delete?id=" . $contentId);
        }

        $contentTitle = $this->app->request->getPost("contentTitle");
        $contentPath = $this->app->request->getPost("contentPath");
        $contentSlug = $this->app->request->getPost("contentSlug");
        $contentData = $this->app->request->getPost("contentData");
        $contentType = $this->app->request->getPost("contentType");
        $contentFilter = $this->app->request->getPost("contentFilter");
        $contentPublish = $this->app->request->getPost("contentPublish");

        $content = new Content($this->app->db);
        $params = array(
            "title" => $contentTitle,
            "path" => $contentPath,
            "slug" => slugify($contentSlug),
            "data" => $contentData,
            "type" => $contentType,
            "filter" => $contentFilter,
            "publish" => $contentPublish,
            "id" => $contentId
        );

        $res = $content->editContent($params);

        if ($res == -1) {
            return $this->app->response->redirect("content/duplicate-slug/");
        }

        return $this->app->response->redirect("content/admin");
    }

    /**
    * Show the delete view.
    * @return object ResponseUtility
    */
    public function deleteActionGet() : object
    {
        $title = "Delete";

        $contentId = $this->app->request->getGet("id") ?? -1;

        $res = $this->content->showOne($contentId);

        $this->app->page->add("content/delete", [
            "res" => $res,
            "contentId" => $contentId
        ]);

        return $this->app->page->render([
            "title" => $title,
        ]);
    }

    /**
    * Handles post request for editing content.
    * @return object ResponseUtility
    */
    public function deleteActionPost() : object
    {
        $contentId = $this->app->request->getPost("contentId") ?? -1;

        $this->content->deleteContent($contentId);

        return $this->app->response->redirect("content/admin");
    }


    /**
    * Show the views for pages.
    * @return object ResponseUtility
    */
    public function pagesActionGet()
    {
        $title = "Pages";

        $route = $this->app->request->getGet("route");
        $res = $this->page->showPages();

        if (!$route) {
            $this->app->page->add("page/pages", [
                "res" => $res,
            ]);
        } else {
            $res = $this->page->showOnePage($route);

            $this->app->page->add("page/page", [
                "res" => $res,
            ]);
        }

        return $this->app->page->render([
            "title" => $title,
        ]);
    }

    /**
    * Show the views for posts.
    * @return object ResponseUtility
    */
    public function postsActionGet()
    {
        $title = "Pages";

        $slug = $this->app->request->getGet("route");
        $res = $this->post->showPosts();

        if (!$slug) {
            $this->app->page->add("post/blog", [
                "res" => $res,
            ]);
        } else {
            $res = $this->post->showOnePost($slug);

            $this->app->page->add("post/blogpost", [
                "res" => $res,
            ]);
        }

        return $this->app->page->render([
            "title" => $title,
        ]);
    }

    /**
    * Show the view for testing.
    * @return object ResponseUtility
    */
    public function testActionGet()
    {
        $title = "Test";

        //    $content = new Content($this->app->db);
        $res = $this->post->showPosts();

        $this->app->page->add("content/test", [
            "res" => $res,
        ]);

        return $this->app->page->render([
            "title" => $title,
        ]);
    }
}
