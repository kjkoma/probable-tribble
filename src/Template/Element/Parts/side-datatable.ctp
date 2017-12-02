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
 * コンテンツ画面のサイドパネルの検索用一覧を表示する
 *  
 * 主にマスター系の検索選択リストとして利用する
 *  
 * - - -
 * @param string  $title        タイトル
 * @param array   $list         一覧データ
 * @param string  $header       カラムヘッダー名
 * @param string  $itemId       キー項目の名称（デフォルト：id）
 * @param string  $itemName     表示項目の名称（デフォルト：kname）
 * @param string  $itemNameSub  括弧内表示項目の名称（デフォルト：なし）
 * @param string  $fa           一覧行の先頭表示アイコン（FontAwesome／デフォルト：caret-right）
 */
$itemId   = isset($itemId)   ? $itemId   : "id";
$itemName = isset($itemName) ? $itemName : "kname";
$fa       = isset($fa)       ? $fa       : "caret-right";
?>
<!-- widget -->
<article class="col-sm-4 sortable-grid ui-sortable">

  <!-- widget ID -->
  <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-0"
    data-widget-deletebutton="false"
    data-widget-editbutton="false"
    data-widget-colorbutton="false"
    data-widget-togglebutton="false"
    data-widget-sortable="false">

    <!-- widget header -->
    <header>
      <span class="widget-icon"> <i class="fa fa-lg fa-list"></i> </span>
      <h2><?= $title ?></h2>
    </header>

    <!-- widget body -->
    <div class="widget-body">

      <table id="side-datatable" class="table table-hover" width="100%">
        <thead class="hide">
          <tr><td> <?= $header ?></td></tr>
        </thead>
        <tbody>
          <?php foreach($list as $item) {
              $sub = isset($itemNameSub) ? '('.$item[$itemNameSub].')' : "";
          ?>
            <tr>
              <td data-id=<?= $item[$itemId] ?> data-app-action-key="side-datatable">
                <i class="fa fa-<?= $fa ?>"></i>
                <?= $item[$itemName] ?><?= $sub ?>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>

    <!-- End widget body -->
    </div>

  <!-- End widget ID -->
  </div>

<!-- End widget -->
</article>

