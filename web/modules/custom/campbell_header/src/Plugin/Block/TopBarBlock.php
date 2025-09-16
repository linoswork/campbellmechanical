<?php

namespace Drupal\campbell_header\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides the Campbell Top Bar.
 *
 * @Block(
 *   id = "campbell_top_bar_block",
 *   admin_label = @Translation("Campbell Top Bar")
 * )
 */
class TopBarBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /** @var \Drupal\Core\Config\ImmutableConfig */
  protected $settings;

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $i = new static($configuration, $plugin_id, $plugin_definition);
    $i->settings = $container->get('config.factory')->get('campbell_header.settings');
    return $i;
  }

  public function build() {
    return [
      '#theme'    => 'campbell_top_bar',
      // plain variables for Twig
      '#phone'     => $this->settings->get('phone'),
      '#email'     => $this->settings->get('email'),
      '#hours'     => $this->settings->get('hours'),
      '#cta_text'  => $this->settings->get('cta_text'),
      '#cta_url'   => $this->settings->get('cta_url'),
      '#facebook'  => $this->settings->get('facebook'),
      '#instagram' => $this->settings->get('instagram'),
      '#linkedin'  => $this->settings->get('linkedin'),
      '#youtube'   => $this->settings->get('youtube'),
      '#attached' => ['library' => ['campbell_header/header']],
      '#cache'    => ['tags' => ['config:campbell_header.settings']],
    ];
  }
}
