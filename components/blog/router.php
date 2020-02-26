<?php
/******************************************************************************/
//                                                                            //
//                        LICENSED BY GNU/GPL v2                              //
//                                                                            //
/******************************************************************************/

    function routes_blog(){

        $routes[] = array(
                            '_uri'  => '/^blog\/publishpost([0-9]+).html$/i',
                            'do'    => 'publishpost',
                            1       => 'post_id'
                         );

        $routes[] = array(
                            '_uri'  => '/^blog\/([0-9]+)\/editblog.html$/i',
                            'do'    => 'config',
                            1       => 'id'
                         );

        $routes[] = array(
                            '_uri'  => '/^blog\/delpost([0-9]+).html$/i',
                            'do'    => 'delpost',
                            1       => 'post_id'
                         );

        $routes[] = array(
                            '_uri'  => '/^blog\/delcat([0-9]+).html$/i',
                            'do'    => 'delcat',
                            1       => 'cat_id'
                         );
        
        $routes[] = array(
                            '_uri'  => '/^blog\/newpost([0-9]+).html$/i',
                            'do'    => 'newpost',
                            1       => 'cat_id'
                         );

        $routes[] = array(
                            '_uri'  => '/^blog\/newpost.html$/i',
                            'do'    => 'newpost'
                         );

        $routes[] = array(
                            '_uri'  => '/^blog\/([0-9]+)\/newcat.html$/i',
                            'do'    => 'newcat',
                            1       => 'id'
                         );

        $routes[] = array(
                            '_uri'  => '/^blog\/editpost([0-9]+).html$/i',
                            'do'    => 'editpost', 
                            1       => 'post_id'
                         );

        $routes[] = array(
                            '_uri'  => '/^blog\/editcat([0-9]+).html$/i',
                            'do'    => 'editcat',
                            1       => 'cat_id'
                         );

        $routes[] = array(
                            '_uri'  => '/^blog\/moderate.html$/i',
                            'do'    => 'view',
                            'on_moderate' => 1
                         );

        $routes[] = array(
                            '_uri'  => '/^blog\/page\-([0-9]+)$/i',
                            'do'    => 'view',
                            1       => 'page'
                         );

        $routes[] = array(
                            '_uri'  => '/^blog\/cat\-([0-9]+)$/i',
                            'do'    => 'view',
                            1       => 'cat_id'
                         );

        $routes[] = array(
                            '_uri'  => '/^blog\/page\-([0-9]+)\/cat\-([0-9]+)$/i',
                            'do'    => 'view',
                            1       => 'page',
                            2       => 'cat_id'
                         );

        $routes[] = array(
                            '_uri'  => '/^blog\/([a-zA-Z0-9\-]+).html$/i',
                            'do'    => 'post',
                            1       => 'seolink'
                         );
        
        $routes[] = array(
                            '_uri'  => '/^blog$/i',
                            'do'    => 'view'
                         );

        return $routes;

    }

?>
