<?php

namespace Drupal\twitter_api_search\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * {@inheritdoc}
 */
class TwitterApiSearchForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'twitter_api_search_configuration_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'twitter_api_search.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $config = $this->config('twitter_api_search.settings');

    $form['header'] = [
      '#type' => 'html_tag',
      '#tag' => 'h3',
      '#value' => $this->t('Twitter App Credentials'),
    ];

    $form['introduction'] = [
      '#type' => 'html_tag',
      '#tag' => 'p',
      '#value' => $this->t('If you still don\'t have a Twitter App credentials, follow the steps and create a Developer account or an App: <a href="https://developer.twitter.com/en/apps" target="_blank">https://developer.twitter.com/en/apps</a>'),
    ];

    $form['consumer_api_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Consumer API key'),
      '#default_value' => $config->get('consumer_api_key'),
    ];

    $form['consumer_api_key_secret'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Consumer API key secret'),
      '#default_value' => $config->get('consumer_api_key_secret'),
    ];

    $form['access_token'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Access token'),
      '#default_value' => $config->get('access_token'),
    ];

    $form['access_token_secret'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Access token secret'),
      '#default_value' => $config->get('access_token_secret'),
    ];

    return parent::buildForm($form, $form_state);

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    parent::submitForm($form, $form_state);

    $this->config('twitter_api_search.settings')
      ->set('consumer_api_key', $form_state->getValue('consumer_api_key'))
      ->set('consumer_api_key_secret', $form_state->getValue('consumer_api_key_secret'))
      ->set('access_token', $form_state->getValue('access_token'))
      ->set('access_token_secret', $form_state->getValue('access_token_secret'))
      ->save();
  }

}
