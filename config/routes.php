<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Core\Plugin;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

/**
 * The default class to use for all routes
 *
 * The following route classes are supplied with CakePHP and are appropriate
 * to set as the default:
 *
 * - Route
 * - InflectedRoute
 * - DashedRoute
 *
 * If no call is made to `Router::defaultRouteClass()`, the class used is
 * `Route` (`Cake\Routing\Route\Route`)
 *
 * Note that `Route` does not do any inflections on URLs which will result in
 * inconsistently cased URLs when used with `:plugin`, `:controller` and
 * `:action` markers.
 *
 */
Router::defaultRouteClass(DashedRoute::class);

if ( isset($_SERVER['HTTP_X_FORWARDED_PORT']) &&
        443 == $_SERVER['HTTP_X_FORWARDED_PORT'] ) {
 Router::fullbaseUrl( 'https://'.$_SERVER['HTTP_HOST'] );
}

Router::scope('/', function (RouteBuilder $routes) {
    /**
     * Here, we are connecting '/' (base path) to a controller called 'Pages',
     * its action called 'display', and we pass a param to select the view file
     * to use (in this case, src/Template/Pages/home.ctp)...
     */
    $routes->connect('/'         , ['controller' => 'Index' , 'action' => 'index']);
    $routes->connect('/login'    , ['controller' => 'Auth'  , 'action' => 'login' ]);
    $routes->connect('/logout'   , ['controller' => 'Auth'  , 'action' => 'logout' ]);
    $routes->connect('/home'     , ['controller' => 'Home'  , 'action' => 'home' ]);
    $routes->connect('/home/cd'  , ['controller' => 'Home'  , 'action' => 'changeDomain' ]);
    $routes->connect('/search'   , ['controller' => 'Home'  , 'action' => 'search' ]);

    /**
     * Connect catchall routes for all controllers.
     *
     * Using the argument `DashedRoute`, the `fallbacks` method is a shortcut for
     *    `$routes->connect('/:controller', ['action' => 'index'], ['routeClass' => 'DashedRoute']);`
     *    `$routes->connect('/:controller/:action/*', [], ['routeClass' => 'DashedRoute']);`
     *
     * Any route class can be used with this method, such as:
     * - DashedRoute
     * - InflectedRoute
     * - Route
     * - Or your own route class
     *
     * You can remove these routes once you've connected the
     * routes you want in your application.
     */
    // $routes->fallbacks(DashedRoute::class);

    /* Api Routes */
    Router::prefix('api', function (RouteBuilder $routes) {
        $routes->prefix('instock', function (RouteBuilder $routes) {
                $routes->fallbacks(DashedRoute::class);
        });
        $routes->prefix('picking', function (RouteBuilder $routes) {
                $routes->fallbacks(DashedRoute::class);
        });
        $routes->prefix('stock', function (RouteBuilder $routes) {
                $routes->fallbacks(DashedRoute::class);
        });
        $routes->prefix('stocktake', function (RouteBuilder $routes) {
                $routes->fallbacks(DashedRoute::class);
        });
        $routes->prefix('asset', function (RouteBuilder $routes) {
                $routes->fallbacks(DashedRoute::class);
        });
        $routes->prefix('repair', function (RouteBuilder $routes) {
            $routes->fallbacks(DashedRoute::class);
        });
        $routes->prefix('exchange', function (RouteBuilder $routes) {
            $routes->fallbacks(DashedRoute::class);
        });
        $routes->prefix('rental', function (RouteBuilder $routes) {
                $routes->fallbacks(DashedRoute::class);
        });

        $routes->prefix('master', function (RouteBuilder $routes) {
            $routes->prefix('system', function (RouteBuilder $routes) {
                $routes->fallbacks(DashedRoute::class);
            });
            $routes->prefix('admin', function (RouteBuilder $routes) {
                $routes->fallbacks(DashedRoute::class);
            });
            $routes->prefix('general', function (RouteBuilder $routes) {
                $routes->fallbacks(DashedRoute::class);
            });
        });
    });

    /* Instock Routes */
    Router::prefix('instock', function (RouteBuilder $routes) {
        $routes->fallbacks(DashedRoute::class);
    });
    /* Picking Routes */
    Router::prefix('picking', function (RouteBuilder $routes) {
        $routes->fallbacks(DashedRoute::class);
    });
    /* Stock Routes */
    Router::prefix('stock', function (RouteBuilder $routes) {
        $routes->fallbacks(DashedRoute::class);
    });
    /* StockTake Routes */
    Router::prefix('stocktake', function (RouteBuilder $routes) {
        $routes->fallbacks(DashedRoute::class);
    });
    /* Asset Routes */
    Router::prefix('asset', function (RouteBuilder $routes) {
        $routes->fallbacks(DashedRoute::class);
    });
    /* Repair Routes */
    Router::prefix('repair', function (RouteBuilder $routes) {
        $routes->fallbacks(DashedRoute::class);
    });
    /* Exchange Routes */
    Router::prefix('exchange', function (RouteBuilder $routes) {
        $routes->fallbacks(DashedRoute::class);
    });
    /* Rental Routes */
    Router::prefix('rental', function (RouteBuilder $routes) {
        $routes->fallbacks(DashedRoute::class);
    });


    /* Master Routes */
    Router::prefix('master', function (RouteBuilder $routes) {
        $routes->prefix('system', function (RouteBuilder $routes) {
            $routes->fallbacks(DashedRoute::class);
        });
        $routes->prefix('admin', function (RouteBuilder $routes) {
            $routes->fallbacks(DashedRoute::class);
        });
        $routes->prefix('general', function (RouteBuilder $routes) {
            $routes->fallbacks(DashedRoute::class);
        });
    });
});

/**
 * Load all plugin routes. See the Plugin documentation on
 * how to customize the loading of plugin routes.
 */
Plugin::routes();
