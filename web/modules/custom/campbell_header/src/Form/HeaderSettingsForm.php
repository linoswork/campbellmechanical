<?php

namespace Drupal\campbell_header\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class HeaderSettingsForm extends ConfigFormBase {

  protected function getEditableConfigNames() {
    return ['campbell_header.settings'];
  }

  public function getFormId() {
    return 'campbell_header_settings_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $c = $this->config('campbell_header.settings');

    $form['phone'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Phone'),
      '#default_value' => $c->get('phone') ?? '',
      '#required' => TRUE,
    ];

    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email'),
      '#default_value' => $c->get('email') ?? '',
    ];

    $form['hours'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Hours'),
      '#default_value' => $c->get('hours') ?? '',
    ];

    $form['cta_text'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Top bar CTA text'),
      '#default_value' => $c->get('cta_text') ?? '',
    ];

    $form['cta_url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Top bar CTA URL'),
      '#default_value' => $c->get('cta_url') ?? '',
      '#description' => $this->t('Absolute or internal path, e.g. /contact'),
    ];

    // Social URLs (flat keys so saving is simple).
    $form['facebook'] = [
      '#type' => 'url',
      '#title' => $this->t('Facebook URL'),
      '#default_value' => $c->get('facebook') ?? '',
      '#placeholder' => 'https://facebook.com/yourpage',
    ];
    $form['instagram'] = [
      '#type' => 'url',
      '#title' => $this->t('Instagram URL'),
      '#default_value' => $c->get('instagram') ?? '',
      '#placeholder' => 'https://instagram.com/yourhandle',
    ];
    $form['linkedin'] = [
      '#type' => 'url',
      '#title' => $this->t('LinkedIn URL'),
      '#default_value' => $c->get('linkedin') ?? '',
      '#placeholder' => 'https://www.linkedin.com/company/yourcompany',
    ];
    $form['youtube'] = [
      '#type' => 'url',
      '#title' => $this->t('YouTube URL'),
      '#default_value' => $c->get('youtube') ?? '',
      '#placeholder' => 'https://youtube.com/@yourchannel',
    ];

    return parent::buildForm($form, $form_state);
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->configFactory->getEditable('campbell_header.settings')
      ->set('phone', $form_state->getValue('phone'))
      ->set('email', $form_state->getValue('email'))
      ->set('hours', $form_state->getValue('hours'))
      ->set('cta_text', $form_state->getValue('cta_text'))
      ->set('cta_url', $form_state->getValue('cta_url'))
      ->set('facebook', $form_state->getValue('facebook'))
      ->set('instagram', $form_state->getValue('instagram'))
      ->set('linkedin', $form_state->getValue('linkedin'))
      ->set('youtube', $form_state->getValue('youtube'))
      ->save();

    parent::submitForm($form, $form_state);
  }
}
