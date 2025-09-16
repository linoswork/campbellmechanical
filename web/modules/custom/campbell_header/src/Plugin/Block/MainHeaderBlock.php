<?php

namespace Drupal\campbell_header\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * @Block(
 *   id = "campbell_main_header_block",
 *   admin_label = @Translation("Campbell Main Header (Sticky)"),
 *   category = @Translation("Campbell Header")
 * )
 */
class MainHeaderBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    // Use the core System Menu Block derivative directly as a render array.
    // No pre-rendering to HTML; let Drupal/Twig handle it.
    $menu = [];
    try {
      $block_manager = \Drupal::service('plugin.manager.block');
      // Derivative plugin id: system_menu_block:<menu_name>
      $plugin_block = $block_manager->createInstance('system_menu_block:main', [
        'label'            => '',
        'label_display'    => FALSE,
        'level'            => 1,
        'depth'            => 3,
        'expand_all_items' => TRUE,   // expand so you always see items
        'menu'             => 'main',
        'menu_name'        => 'main',
      ]);
      $menu = $plugin_block ? $plugin_block->build() : [];
    } catch (\Throwable $e) {
      \Drupal::logger('campbell_header')->error('Menu block build failed: @m', ['@m' => $e->getMessage()]);
    }

    $cfg = \Drupal::config('campbell_header.settings');

    return [
      '#theme'     => 'campbell_main_header',
      '#menu'      => $menu,                                  // <— render array
      '#phone'     => (string) ($cfg->get('phone') ?? ''),
      '#cta_text'  => (string) ($cfg->get('cta_text') ?: 'Get a Quote'),
      '#cta_url'   => (string) ($cfg->get('cta_url') ?: '/contact'),
      '#attached'  => ['library' => ['campbell_header/header']],
      '#cache'     => [
        'tags' => ['config:campbell_header.settings', 'config:system.menu.main'],
        'contexts' => ['route', 'url.path', 'user.permissions'],
      ],
    ];
  }
}
