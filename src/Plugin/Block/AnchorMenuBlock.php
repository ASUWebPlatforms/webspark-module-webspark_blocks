<?php

namespace Drupal\webspark_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides an anchor menu block.
 *
 * @Block(
 *   id = "webspark_blocks_anchor_menu",
 *   admin_label = @Translation("Anchor Menu"),
 *   category = @Translation("ASU")
 * )
 */
class AnchorMenuBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build['#theme'] = 'webspark_blocks_anchor_menu';
    $build['#attached']['library'] = ['webspark_blocks/anchor_menu'];

    return $build;
  }

}
