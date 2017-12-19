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
 * コンテンツ画面のサイドパネルの組織ツリーを表示する
 *  
 * / 主にマスター系の選択リストとして利用する
 * / ツリー表示にはBootstrap Treeviewを利用する
 *  
 * - - -
 * @param string  $title        タイトル
 * @param array   $customers    資産管理会社一覧
 * @param string  $itemId       キー項目の名称（デフォルト：id）
 * @param string  $itemName     表示項目の名称（デフォルト：kname）
 */
$itemId   = isset($itemId)   ? $itemId   : "id";
$itemName = isset($itemName) ? $itemName : "kname";
?>

<!-- widget -->
<article class="col-sm-4 sortable-grid ui-sortable">

  <!-- widget ID -->
  <div class="jarviswidget jarviswidget-color-blueDark" id="wid-side-list"
    data-widget-deletebutton="false"
    data-widget-editbutton="false"
    data-widget-colorbutton="false"
    data-widget-togglebutton="false"
    data-widget-sortable="false">

    <!-- TREE VIEW widget header -->
    <header>
      <span class="widget-icon"> <i class="fa fa-lg fa-tree"></i> </span>
      <h2><?= $title ?></h2>
    </header>

    <!-- TREE VIEW widget body toolbar -->
    <div class="widget-body-toolbar">
        <form id="form-select-customer" role="form">
            <div class="row">
                <div class="col-xs-12">
                    <div class="form-group">
                        <?= $this->element('Parts/select-customers', [
                            'customers' => $customers,
                            'name'      => '',
                            'id'        => 'select-customer',
                            'form'      => '',
                            'disabled'  => false,
                            'class'     => 'form-control',
                            'labeled'   => false,
                        ]) ?>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- TREE VIEW widget body -->
    <div class="widget-body">

      <!-- tree -->
      <div id="tree">

       <!-- End list -->
      </div>

    <!-- End TREE VIEW widget body -->
    </div>

  <!-- End widget ID -->
  </div>

<!-- End widget -->
</article>

<!-- load script -->
<?php $this->Html->script('wnote/wnote.tree.js', ['block' => true]); ?>
