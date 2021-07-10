<?php

namespace Drupal\hmrestate\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Listens to the dynamic route events.
 */
class RouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {
    if ($route = $collection->get('user.login')) {
        $route->setOption('_admin_route', TRUE);
    }
    if ($route = $collection->get('user.register')) {
        $route->setOption('_admin_route', TRUE);
    }
    if ($route = $collection->get('user.pass')) {
        $route->setOption('_admin_route', TRUE);
    }
    if ($route = $collection->get('user.page')) {
        $route->setOption('_admin_route', TRUE);
    }
    if ($route = $collection->get('entity.user.canonical')) {
        $route->setOption('_admin_route', TRUE);
    }
    if ($route = $collection->get('view.property.page_1')) {
        $route->setOption('_admin_route', TRUE);
    }
    if ($route = $collection->get('view.prospect.page_1')) {
        $route->setOption('_admin_route', TRUE);
    }
    if ($route = $collection->get('view.moderator_buyer.page_1')) {
        $route->setOption('_admin_route', TRUE);
    }
    if ($route = $collection->get('view.moderator_vendor.page_1')) {
        $route->setOption('_admin_route', TRUE);
    }
  }

}