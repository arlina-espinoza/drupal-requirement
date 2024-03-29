<?php

namespace Drupal\requirement\Routing;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\requirement\Plugin\RequirementManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Route;

/**
 * Provides routes for requirement.
 */
class RequirementRoutes implements ContainerInjectionInterface {

  /**
   * The requirement manager.
   *
   * @var \Drupal\requirement\Plugin\RequirementManagerInterface
   */
  protected $requirementManager;

  /**
   * RequirementRoutes constructor.
   *
   * @param \Drupal\requirement\Plugin\RequirementManagerInterface $requirement_manager
   *   The requirement manager.
   */
  public function __construct(RequirementManagerInterface $requirement_manager) {
    $this->requirementManager = $requirement_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('plugin.manager.requirement')
    );
  }

  /**
   * Returns an array of route objects.
   *
   * @return \Symfony\Component\Routing\Route[]
   *   An array of route objects.
   */
  public function routes() {
    $routes = [];

    foreach ($this->requirementManager->listRequirement() as $requirement) {
      if ($form = $requirement->getForm()) {
        $routes["requirement.{$requirement->getId()}"] = new Route(
          "/admin/reports/requirement/{$requirement->getId()}",
          [
            '_form' => $form,
            '_title' => $requirement->getLabel(),
            'requirement_id' => $requirement->getId(),
          ],
          [
            '_permission' => 'administer site configuration',
          ]
        );
      }
    }

    return $routes;
  }

}
