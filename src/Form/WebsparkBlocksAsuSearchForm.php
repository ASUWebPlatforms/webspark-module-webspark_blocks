<?php

/**
 * @file
 * Contains \Drupal\webspark_blocks\Form\AsuUserAdminSettings.
 */

namespace Drupal\webspark_blocks\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Url;
use Drupal\Core\Routing\TrustedRedirectResponse;
use Drupal\Component\Utility\Html;

/**
 * Class AsuUserAdminSettings
 * @package Drupal\asu_user\Form
 */
class WebsparkBlocksAsuSearchForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'webspark_blocks_asu_search_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['icon'] = [
      '#type' => 'html_tag',
      '#tag' => 'i',
      '#attributes' => [
        'class' => ['fas', 'fa-search', 'search-icon'],
      ],
    ];
    $form['search'] = [
      '#type' => 'textfield',
      '#attributes' => [
        'placeholder' => $this->t('Search asu.edu'),
        'onkeypress' => ['if(event.keyCode==13){jQuery(".edit-submit).mousedown();}']
      ],
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#attributes' => ['hidden' => TRUE]
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $keyword = $form_state->getValue('search') ?? ''; // Should be sanitized on search.asu.edu as output to avoid double-sanitizing issues
    if (\Drupal::moduleHandler()->moduleExists('asu_brand')) {
      $search_config = \Drupal::config('asu_brand.settings');
      $search_url = $search_config->get('asu_brand.asu_brand_search_url');
      // Domain-specific results host
      $local_search_url = $search_config->get('asu_brand.asu_brand_local_search_url') ?? \Drupal::request()->getHost();
      // "opt-out" for use by ASU CMS, Integrated Search or with special dispensation
      $url_host = ($local_search_url === 'opt-out') ? '' : $local_search_url;
    } else {
      $search_url = 'https://search.asu.edu/search'; // Default for ASU searches
      $url_host = \Drupal::request()->getHost() ?? '';
    }
    $response = new TrustedRedirectResponse($search_url . '?q=' . $keyword . '&url_host=' . $url_host . '&sort=date%3AD%3AL%3Ad1&search-tabs=all');
    $form_state->setResponse($response);
  }
}
