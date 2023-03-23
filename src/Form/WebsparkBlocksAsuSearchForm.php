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
    $url_host = (Html::escape($_SERVER['HTTP_HOST'])) ?? '';
    $keyword =  Html::escape($form_state->getValue('search')) ?? '';
    $response = new TrustedRedirectResponse('https://search.asu.edu/?q=' . $keyword . '&url_host=' . $url_host . '&sort=date%3AD%3AL%3Ad1&search-tabs=all&gsc.tab=0&gsc.page=1&gsc.q=' . $keyword);
    $form_state->setResponse($response);
  }
}
