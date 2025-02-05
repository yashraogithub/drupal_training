<?php

namespace Drupal\custom_module\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\custom_module\Service\SimpleService;

class SimpleController extends ControllerBase {
  protected $simpleService;

  public function __construct(SimpleService $simpleService) {
    $this->simpleService = $simpleService;
  }

  public static function create(ContainerInterface $container) {
    return new static($container->get('custom_module.simple_service'));
  }

  public function content() {
    return [
      '#markup' => $this->simpleService->getMessage(),
    ];
  }
}
