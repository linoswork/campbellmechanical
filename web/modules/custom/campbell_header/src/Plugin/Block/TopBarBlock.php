<?php

namespace Drupal\campbell_header\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;

/**
 * @Block(
 *   id = "campbell_top_bar_block",
 *   admin_label = @Translation("Campbell Top Bar")
 * )
 */
class TopBarBlock extends BlockBase implements ContainerFactoryPluginInterface {

  protected $config;

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $instance = new static($configuration, $plugin_id, $plugin_definition);
    $instance->config = $container->get('config.factory')->get('campbell_header.settings');
    return $instance;
  }

  public function build() {
    return [
      '#theme' => 'campbell_top_bar',
      '#phone' => $this->config->get('phone'),
      '#email' => $this->config->get('email'),
      '#hours' => $this->config->get('hours'),
      '#cta_text' => $this->config->get('cta_text'),
      '#cta_url' => $this->config->get('cta_url'),
      '#attached' => ['library' => ['campbell_header/header']],
    ];
  }
}
