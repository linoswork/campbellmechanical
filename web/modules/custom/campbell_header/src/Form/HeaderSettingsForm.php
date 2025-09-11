<?php

namespace Drupal\campbell_header\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class HeaderSettingsForm extends ConfigFormBase {
  protected function getEditableConfigNames() { return ['campbell_header.settings']; }
  public function getFormId() { return 'campbell_header_settings_form'; }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $c = $this->config('campbell_header.settings');
    $form['phone'] = ['#type'=>'textfield','#title'=>'Phone','#default_value'=>$c->get('phone') ?? '619.731.1177', '#required'=>TRUE];
    $form['email'] = ['#type'=>'email','#title'=>'Email','#default_value'=>$c->get('email') ?? 'info@campbellmechanics.com'];
    $form['hours'] = ['#type'=>'textfield','#title'=>'Hours','#default_value'=>$c->get('hours') ?? 'Mon–Sat: 10:00 AM–4:00 PM'];
    $form['cta_text'] = ['#type'=>'textfield','#title'=>'Top bar CTA text','#default_value'=>$c->get('cta_text') ?? 'Get a Quote'];
    $form['cta_url'] = ['#type'=>'textfield','#title'=>'Top bar CTA URL','#default_value'=>$c->get('cta_url') ?? '/contact'];
    return parent::buildForm($form, $form_state);
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->configFactory->getEditable('campbell_header.settings')
      ->set('phone', $form_state->getValue('phone'))
      ->set('email', $form_state->getValue('email'))
      ->set('hours', $form_state->getValue('hours'))
      ->set('cta_text', $form_state->getValue('cta_text'))
      ->set('cta_url', $form_state->getValue('cta_url'))
      ->save();
    parent::submitForm($form, $form_state);
  }
}
