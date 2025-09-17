<?php

namespace Drupal\campbell_header\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Menu\MenuTreeParameters;
use Drupal\Core\Url;

/**
 * Provides the Campbell Main Header (Sticky).
 *
 * @Block(
 *   id = "campbell_main_header",
 *   admin_label = @Translation("Campbell Main Header (Sticky)"),
 *   category = @Translation("Campbell")
 * )
 */
class MainHeaderBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      // Ensure these always exist even on older block instances.
      'logo_src'    => '/themes/custom/campbell/logo.svg',
      'logo_alt'    => 'Campbell Mechanical',

      'cta_label'   => 'Get a Quote',
      'cta_path'    => '/get-a-quote',
      'cta_new_tab' => FALSE,

      'show_phone'  => TRUE,
      'phone_text'  => '+1 (619) 731-1177',

      'menu_name'   => 'main',
    ] + parent::defaultConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    // Merge saved config with defaults so keys are always present.
    $config = $this->configuration + $this->defaultConfiguration();

    // --- Logo (editable) ---
    $form['logo_src'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Logo URL (src)'),
      '#default_value' => (string) $config['logo_src'],
      '#description' => $this->t('Example: /themes/custom/campbell/logo.webp or a full URL.'),
      '#required' => TRUE,
    ];
    $form['logo_alt'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Logo alt text'),
      '#default_value' => (string) $config['logo_alt'],
      '#required' => TRUE,
    ];

    // --- CTA + phone ---
    $form['cta_label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('CTA button text'),
      '#default_value' => (string) $config['cta_label'],
      '#required' => TRUE,
    ];
    $form['cta_path'] = [
      '#type' => 'textfield',
      '#title' => $this->t('CTA link (URL or internal path)'),
      '#description' => $this->t('Examples: /get-a-quote, /contact, or https://example.com'),
      '#default_value' => (string) $config['cta_path'],
      '#required' => TRUE,
    ];
    $form['cta_new_tab'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Open CTA in a new tab'),
      '#default_value' => !empty($config['cta_new_tab']),
    ];
    $form['show_phone'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Show phone number in header'),
      '#default_value' => !empty($config['show_phone']),
    ];
    $form['phone_text'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Phone text'),
      '#default_value' => (string) $config['phone_text'],
      '#states' => [
        'visible' => [
          ':input[name="settings[show_phone]"]' => ['checked' => TRUE],
        ],
      ],
    ];

    // --- Menu dropdown ---
    $menus = \Drupal::entityTypeManager()->getStorage('menu')->loadMultiple();
    $options = [];
    foreach ($menus as $id => $menu) {
      $options[$id] = $menu->label() . ' (' . $id . ')';
    }
    $form['menu_name'] = [
      '#type' => 'select',
      '#title' => $this->t('Primary menu'),
      '#options' => $options,
      '#default_value' => $config['menu_name'] ?? 'main',
      '#required' => TRUE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    foreach (['logo_src','logo_alt','cta_label','cta_path','cta_new_tab','show_phone','phone_text','menu_name'] as $k) {
      $this->configuration[$k] = $form_state->getValue($k);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    // Merge again at render time (guards against missing keys).
    $config = $this->configuration + $this->defaultConfiguration();

    // Build level-1 menu.
    $menu_tree = \Drupal::service('menu.link_tree');
    $parameters = (new MenuTreeParameters())->setMaxDepth(1);
    $tree = $menu_tree->load($config['menu_name'], $parameters);
    $tree = $menu_tree->transform($tree, [
      ['callable' => 'menu.default_tree_manipulators:checkAccess'],
      ['callable' => 'menu.default_tree_manipulators:generateIndexAndSort'],
    ]);
    $primary_menu = $menu_tree->build($tree);

    // CTA URL normalize.
    $cta_path = trim($config['cta_path']);
    if (strpos($cta_path, '/') === 0) {
      $cta_url = Url::fromUserInput($cta_path)->toString();
    } elseif (preg_match('@^https?://@i', $cta_path)) {
      $cta_url = Url::fromUri($cta_path)->toString();
    } else {
      $cta_url = Url::fromRoute('<front>')->toString();
    }

    // Phone href.
    $phone_text = (string) $config['phone_text'];
    $phone_href = 'tel:' . preg_replace('/[^\d\+]/', '', $phone_text);

    return [
      '#theme' => 'campbell_main_header',
      '#primary_menu' => $primary_menu,
      '#logo' => [
        'src' => (string) $config['logo_src'],
        'alt' => (string) $config['logo_alt'],
      ],
      '#cta' => [
        'label' => (string) $config['cta_label'],
        'url' => $cta_url,
        'new_tab' => !empty($config['cta_new_tab']),
      ],
      '#phone' => [
        'show' => !empty($config['show_phone']),
        'text' => $phone_text,
        'href' => $phone_href,
      ],
      '#attached' => ['library' => ['campbell_header/header']],
      '#cache' => ['contexts' => ['url.path','user.permissions']],
    ];
  }
}
