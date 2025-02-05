<?php

namespace Drupal\custom_page\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Menu\MenuLinkTreeInterface;

/**
 * Controller for the custom page.
 */
class CustomPageController extends ControllerBase {

  protected $menuLinkTree;

  /**
   * Constructs the controller with menu link tree service.
   */
  public function __construct(MenuLinkTreeInterface $menuLinkTree) {
    $this->menuLinkTree = $menuLinkTree;
  }

  /**
   * Dependency injection to get existing navigation service.
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('menu.link_tree') // Using Drupal's built-in service
    );
  }

  /**
   * Display custom page with navigation menu.
   */
  public function showCustomPage() {
    // Fetch menu items from the 'main' menu.
    $menu_name = 'main';
    $parameters = $this->menuLinkTree->getCurrentRouteMenuTreeParameters($menu_name);
    $tree = $this->menuLinkTree->load($menu_name, $parameters);
  
    // Required fix: Define menu manipulation options.
    $manipulators = [
      ['callable' => 'menu.default_tree_manipulators:checkAccess'],
      ['callable' => 'menu.default_tree_manipulators:generateIndexAndSort'],
    ];
  
    // Apply transformations (Fixed: Now passing two arguments).
    $menu = $this->menuLinkTree->transform($tree, $manipulators);
  
    // Render menu items as a list.
    $menu_items = '<h2>Navigation Menu</h2><ul>';
    foreach ($menu as $item) {
      $menu_items .= '<li><a href="' . $item->link->getUrlObject()->toString() . '">' . $item->link->getTitle() . '</a></li>';
    }
    $menu_items .= '</ul>';
  
    // Return custom page content with the navigation menu.
    return [
      '#markup' => '<h1>Welcome to Custom Page</h1>' . $menu_items,
    ];
  }
  
}
