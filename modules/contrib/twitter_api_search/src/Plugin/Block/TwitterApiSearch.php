<?php

namespace Drupal\twitter_api_search\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Twitter API Search' block.
 *
 * @Block(
 *   id = "twitter_api_search_block",
 *   admin_label = @Translation("Twitter API Search block"),
 * )
 */
class TwitterApiSearch extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The config factory service.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The language manager.
   *
   * @var \Drupal\Core\Language\LanguageManagerInterface
   */
  protected $languageManager;

  /**
   * The messenger.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * Constructs a new TwitterApiSearch.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin ID for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   * @param \Drupal\Core\Language\LanguageManagerInterface $language_manager
   *   The language manager.
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The messenger.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ConfigFactoryInterface $config_factory, LanguageManagerInterface $language_manager, MessengerInterface $messenger) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->configFactory = $config_factory;
    $this->languageManager = $language_manager;
    $this->messenger = $messenger;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('config.factory'),
      $container->get('language_manager'),
      $container->get('messenger')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {

    $data = [
      '#theme' => 'twitter_api_search_block',
      '#attached' => [
        'library' => [
          'twitter_api_search/custom',
        ],
      ],
    ];

    // Check if library exists:
    if (!class_exists('TwitterAPIExchange')) {

      if (function_exists('libraries_get_path')) {
        $library_path = libraries_get_path('twitter-api-php') . '/TwitterAPIExchange.php';
      }
      else {
        $library_path = DRUPAL_ROOT . '/libraries/twitter-api-php/TwitterAPIExchange.php';
      }

      if (file_exists($library_path)) {
        include_once $library_path;
      }

      if (!class_exists('TwitterAPIExchange')) {
        $this->messenger->addWarning($this->t('TwitterAPIExchange library not installed. Please install it from <a href="https://github.com/J7mbo/twitter-api-php" target="_blank">https://github.com/J7mbo/twitter-api-php</a>'));
        return;
      }

    }

    $config = $this->getConfiguration();

    $globalConfig = $this->configFactory->get('twitter_api_search.settings');

    if (!$globalConfig->get('consumer_api_key') || !$globalConfig->get('consumer_api_key_secret') || !$globalConfig->get('access_token') || !$globalConfig->get('access_token_secret')) {

      $this->messenger->addWarning($this->t('Twitter API credentials are not configured'));

      return;

    }

    $settings = [
      'consumer_key' => $globalConfig->get('consumer_api_key'),
      'consumer_secret' => $globalConfig->get('consumer_api_key_secret'),
      'oauth_access_token' => $globalConfig->get('access_token'),
      'oauth_access_token_secret' => $globalConfig->get('access_token_secret'),
    ];

    $requestMethod = 'GET';
    $url = 'https://api.twitter.com/1.1/search/tweets.json';
    $getfield = '?count=' . $config['limit'] . '&q=' . urlencode($config['search_string']);

    if ($config['show_retweets'] == 'hide') {
      $getfield .= '%20-filter:nativeretweets';
    }

    $twitter = new \TwitterAPIExchange($settings);

    $responseJson = $twitter->setGetfield($getfield)
      ->buildOauth($url, $requestMethod)
      ->performRequest();

    $response = json_decode($responseJson);

    $language = $this->languageManager->getCurrentLanguage()->getId();

    $link_color = $config['link_color'];
    $card_max_width = $config['card_max_width'];
    $tweets = [];

    foreach ($response->statuses as $status) {

      $tweetUrl = 'https://twitter.com/' . $status->user->screen_name . '/status/' . $status->id;

      if ($config['show_media'] == 'hide') {
        $oembedUrl = 'https://publish.twitter.com/oembed?chrome=nofooter&lang=' . $language . '&link_color=' . urlencode($link_color) . '&maxwidth=' . urlencode($card_max_width) . '&hide_thread=true&hide_media=true&url=' . urlencode($tweetUrl);
      }
      else {
        $oembedUrl = 'https://publish.twitter.com/oembed?chrome=nofooter&lang=' . $language . '&link_color=' . urlencode($link_color) . '&maxwidth=' . urlencode($card_max_width) . '&hide_thread=true&url=' . urlencode($tweetUrl);
      }

      $oembedContent = json_decode(file_get_contents($oembedUrl));

      if ($oembedContent->html) {
        $tweets[] = $oembedContent->html;
      }

    }

    $data['#tweets'] = $tweets;

    $data['#account'] = $config['twitter_account'];

    $data['#header'] = '<a href="https://twitter.com/search?q=' . urlencode($config['search_string']) . '" target="_blank">' . $this->t('Tweets about') . ' ' . $config['search_string'] . '</a>';

    $data['#max_height'] = $config['max_height'];

    return $data;

  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {

    $form = parent::blockForm($form, $form_state);

    $config = $this->getConfiguration();

    $form['search_string'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Search text'),
      '#description' => $this->t('Enter the text you want to search on Twitter. <em>e.g. hands, #hands, @hands, etc.</em>'),
      '#default_value' => isset($config['search_string']) ? $config['search_string'] : '',
    ];

    $form['twitter_account'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Twitter account'),
      '#default_value' => isset($config['twitter_account']) ? $config['twitter_account'] : '',
    ];

    $form['show_media'] = [
      '#type' => 'radios',
      '#title' => $this->t('Show tweets media?'),
      '#options' => ['hide' => 'Hide', 'show' => 'Show'],
      '#default_value' => isset($config['show_media']) ? $config['show_media'] : 'hide',
    ];

    $form['show_retweets'] = [
      '#type' => 'radios',
      '#title' => $this->t('Show retweets?'),
      '#options' => ['hide' => 'Hide', 'show' => 'Show'],
      '#default_value' => isset($config['show_retweets']) ? $config['show_retweets'] : 'hide',
    ];

    $form['limit'] = [
      '#type' => 'number',
      '#title' => $this->t('Limit tweets'),
      '#description' => $this->t('Maximum number of tweets'),
      '#min' => 1,
      '#max' => 100,
      '#step' => 1,
      '#default_value' => isset($config['limit']) ? $config['limit'] : 10,
    ];

    $form['max_height'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Max height'),
      '#description' => $this->t('Max wrapper height. <em>e.g. 600px, 100%, 20cm</em>'),
      '#default_value' => isset($config['max_height']) ? $config['max_height'] : '600px',
    ];

    $form['card_max_width'] = [
      '#type' => 'number',
      '#title' => $this->t('Card max width'),
      '#description' => $this->t('Maximum width of tweet card'),
      '#min' => 220,
      '#max' => 550,
      '#step' => 1,
      '#default_value' => isset($config['card_max_width']) ? $config['card_max_width'] : 325,
    ];

    $form['link_color'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Links color'),
      '#description' => $this->t('Links color. <em>e.g. #0f0f0f</em>'),
      '#default_value' => isset($config['link_color']) ? $config['link_color'] : '',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {

    parent::blockSubmit($form, $form_state);

    $values = $form_state->getValues();

    $this->configuration['search_string'] = $values['search_string'];
    $this->configuration['twitter_account'] = $values['twitter_account'];
    $this->configuration['show_media'] = $values['show_media'];
    $this->configuration['show_retweets'] = $values['show_retweets'];
    $this->configuration['limit'] = $values['limit'];
    $this->configuration['max_height'] = $values['max_height'];
    $this->configuration['card_max_width'] = $values['card_max_width'];
    $this->configuration['link_color'] = $values['link_color'];

  }

}
