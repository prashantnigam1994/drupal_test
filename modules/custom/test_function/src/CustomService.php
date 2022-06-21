<?php

namespace Drupal\test_function;

use Drupal\Core\Session\AccountInterface;

/**
 * Class CustomService
 * @package Drupal\test_function\Services
 */
class CustomService {

  protected $currentUser;

  /**
   * CustomService constructor.
   * @param AccountInterface $currentUser
   */
  public function __construct(AccountInterface $currentUser) {
    $this->currentUser = $currentUser;
  }


  /**
   * @return \Drupal\Component\Render\MarkupInterface|string
   */
  public function getData() {
    return $this->currentUser->getDisplayName();
  }

}