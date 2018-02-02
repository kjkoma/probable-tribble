<?php
/**
 * Copyright (c) Japan Computer Services, Inc.
 *
 * Licensed under The MIT License
 *
 * @author    Japan Computer Services, Inc
 * @copyright Copyright (c) Japan Computer Services, Inc. (http://www.japacom.co.jp)
 * @since     1.0.0
 * @version   1.0.0
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 *
 * -- Histories --
 * 2017.12.31 R&D 新規作成
 */

/**
 * コンテンツ画面のサイドパネルの一覧を表示する
 *  
 * 主にマスター系の選択リストとして利用する
 *  
 * - - -
 * @param string  $title        タイトル
 * @param array   $list         一覧データ
 * @param string  $itemId       キー項目の名称（デフォルト：id）
 * @param string  $itemName     表示項目の名称（デフォルト：kname）
 * @param string  $colSize      カラムサイズ（例：col-sm4 col-lg-2）
 */
$itemId   = isset($itemId)   ? $itemId   : "id";
$itemName = isset($itemName) ? $itemName : "kname";
$colSize  = isset($colSize)  ? $colSize  : "col-sm-4";
?>

<!-- widget -->
<article class="<?= $colSize ?> sortable-grid ui-sortable">

  <!-- widget ID -->
  <div class="jarviswidget jarviswidget-color-blueDark" id="wid-side-list"
    data-widget-deletebutton="false"
    data-widget-editbutton="false"
    data-widget-colorbutton="false"
    data-widget-togglebutton="false"
    data-widget-sortable="false">

    <!-- NESTED LIST widget header -->
    <header>
      <span class="widget-icon"> <i class="fa fa-lg fa-list"></i> </span>
      <h2><?= $title ?></h2>
    </header>

    <!-- NESTED LIST widget body -->
    <div class="widget-body">

      <!-- list -->
      <div class="dd" id="nestable">

        <ol class="dd-list">
          <?php foreach ($list as $item) { ?>
            <li class="dd-item">
              <div class="dd-handle" data-id="<?= $item[$itemId] ?>" data-app-action-key="side-list">
                <span><?= $item[$itemName] ?></span>
              </div>
            </li>
          <?php } ?>
        </ol>

       <!-- End list -->
      </div>

    <!-- End NESTED LIST widget body -->
    </div>

  <!-- End widget ID -->
  </div>

<!-- End widget -->
</article>
