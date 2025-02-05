<?php

namespace Drupal\custom_crud\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Provides a controller for the CRUD operations page.
 */
class CrudPageController extends ControllerBase {

  /**
   * Displays the CRUD operations page with menu links.
   */
  public function crudPage() {
    // Menu links for CRUD operations
    $menu = [
      [
        'title' => $this->t('Add Customer'),
        'url' => \Drupal\Core\Url::fromRoute('custom_crud.add_customer'),
      ],
      [
        'title' => $this->t('Customer List'),
        'url' => \Drupal\Core\Url::fromRoute('custom_crud.customer_list'),
      ],
    ];

    // Render the menu
    $menu_items = [];
    foreach ($menu as $item) {
      $menu_items[] = [
        '#type' => 'link',
        '#title' => $item['title'],
        '#url' => $item['url'],
        '#attributes' => ['class' => ['menu-item']],
      ];
    }

    // Return the menu as a render array
    return [
      '#theme' => 'item_list',
      '#items' => $menu_items,
      '#attributes' => ['class' => ['crud-menu']],
      '#attached' => [
        'library' => ['custom_crud/crud_menu_styles'], // To attach CSS for the menu styling
      ],
    ];
  }
}
