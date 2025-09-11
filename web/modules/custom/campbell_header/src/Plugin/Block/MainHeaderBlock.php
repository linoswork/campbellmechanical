<?php

namespace Drupal\campbell_header\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * @Block(
 *   id = "campbell_main_header_block",
 *   admin_label = @Translation("Campbell Main Header (Sticky)")
 * )
 */
class MainHeaderBlock extends BlockBase {
  public function build() {
    return [
      '#theme' => 'campbell_main_header',
      '#attached' => ['library' => ['campbell_header/header']],
    ];
  }
}
