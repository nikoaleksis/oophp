<?php
/**
 * Routes to ease testing.
 */
return [
    // Path where to mount the routes, is added to each route path.
     //"mount" => "game",

    // All routes in order
    "routes" => [
        [
            "info" => "Content controller.",
            "mount" => "content",
            "handler" => "\Niko\Controller\CMSController",
        ],
    ]
];
