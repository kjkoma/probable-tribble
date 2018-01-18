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
 * 資産表示 ウィジット
 *  
 * - - -
 * @param mixed $conf 表示/入力の設定情報
 * - - -
 * 設定デフォルト
 * <code>
 *   'conf' => [
 *       'asset'  => 'view', // 'input'指定可(資産番号/資産名称/ステータス/サブステータス/初回入庫日/計上日/保守期限日/廃棄日/補足のみ入力可)
 *       'attr'   => 'view', // 'input'指定可
 *       'user'   => false,
 *       'stock'  => false,
 *       'repair' => false,
 *       'rental' => false,
 *       'hidden' => false   // true: 初期非表示/false: 初期表示
 *   ]
 * </code>
 */
$default = [
    'asset'   => 'view',
    'attr'    => 'view',
    'user'    => false,
    'stock'   => false,
    'repair'  => false,
    'rental'  => false,
    'hidden'  => false
];
$conf = isset($conf) ? array_merge($default, $conf) : $default;
?>
<!-- widget ID -->
<div class="jarviswidget jarviswidget-color-blueDark <?= ($conf['hidden']) ? 'hidden' : '' ?>" id="wid-id-elem-asset"
     data-widget-deletebutton="false"
     data-widget-editbutton="false"
     data-widget-colorbutton="false"
     data-widget-togglebutton="false"
     data-widget-fullscreen="false"
     data-widget-sortable="false">

    <!-- DETAILS widget header -->
    <header role="heading">
        <ul id="tab-menu-assetview" class="nav nav-tabs pull-left">
            <!-- 資産情報 タブ -->
            <li class="active <?= ($conf['asset']) ? '' : 'hidden' ?>)">
                <a data-toggle="tab" href="#elem-asset-asset-contents" data-app-action-key="elem-asset-asset-contents">
                    <i class="fa fa-lg fa fa-lg fa-pencil-o"></i>
                    <span class="hidden-mobile hidden-tablet"> 資産情報 </span>
                </a>
            </li>
            <!-- 資産属性 タブ -->
            <li class="<?= ($conf['attr']) ? '' : 'hidden' ?>">
                <a data-toggle="tab" href="#elem-asset-attr-contents" data-app-action-key="elem-asset-attr-contents">
                    <i class="fa fa-lg fa fa-lg fa-info"></i>
                    <span class="hidden-mobile hidden-tablet"> 資産属性 </span>
                </a>
            </li>
            <!-- 利用者履歴 タブ -->
            <li class="<?= ($conf['user']) ? '' : 'hidden' ?>">
                <a data-toggle="tab" href="#elem-asset-user-contents" data-app-action-key="elem-asset-user-contents">
                    <i class="fa fa-lg fa fa-lg fa-info"></i>
                    <span class="hidden-mobile hidden-tablet"> 利用者履歴 </span>
                </a>
            </li>
            <!-- 在庫／在庫変動履歴 タブ -->
            <li class="<?= ($conf['stock']) ? '' : 'hidden' ?>">
                <a data-toggle="tab" href="#elem-asset-stock-contents" data-app-action-key="elem-asset-stock-contents">
                    <i class="fa fa-lg fa fa-lg fa-info"></i>
                    <span class="hidden-mobile hidden-tablet"> 在庫／在庫変動履歴 </span>
                </a>
            </li>
            <!-- 修理履歴 タブ -->
            <li class="<?= ($conf['repair']) ? '' : 'hidden' ?>">
                <a data-toggle="tab" href="#elem-asset-repair-contents" data-app-action-key="elem-asset-repair-contents">
                    <i class="fa fa-lg fa fa-lg fa-info"></i>
                    <span class="hidden-mobile hidden-tablet"> 修理履歴 </span>
                </a>
            </li>
            <!-- 貸出・返却履歴 タブ -->
            <li class="<?= ($conf['rental']) ? '' : 'hidden' ?>">
                <a data-toggle="tab" href="#elem-asset-rental-contents" data-app-action-key="elem-asset-rental-contents">
                    <i class="fa fa-lg fa fa-lg fa-info"></i>
                    <span class="hidden-mobile hidden-tablet"> 貸出／返却履歴 </span>
                </a>
            </li>
        </ul>
    </header>

    <!-- content -->
    <div role="content">

        <!-- DETAILS widget body -->
        <div class="widget-body">

            <!-- tab contents -->
            <div class="tab-content">

                <!-- ********************************** -->
                <!-- 資産情報                           -->
                <!-- ********************************** -->
                <!-- asset tab contents -->
                <div class="tab-pane fade in active" id="elem-asset-asset-contents">

                    <!-- 資産情報タブコンテンツ -->
                    <?= $this->element('Asset/asset.asset', ['conf' => $conf]) ?>

                    <!-- End asset tab contents -->
                </div>

                <!-- ********************************** -->
                <!-- 資産属性情報                       -->
                <!-- ********************************** -->
                <!-- attr tab contents -->
                <div class="tab-pane fade" id="elem-asset-attr-contents">

                    <!-- 資産属性情報タブコンテンツ -->
                    <?= $this->element('Asset/asset.attr', ['conf' => $conf]) ?>

                    <!-- End attr tab contents -->
                </div>

                <!-- ********************************** -->
                <!-- 利用情報                           -->
                <!-- ********************************** -->
                <!-- attr tab contents -->
                <div class="tab-pane fade" id="elem-asset-user-contents">

                    <!-- 利用情報タブコンテンツ -->
                    <?= $this->element('Asset/asset.user', ['conf' => $conf]) ?>

                    <!-- End attr tab contents -->
                </div>

                <!-- ********************************** -->
                <!-- 在庫情報                           -->
                <!-- ********************************** -->
                <!-- attr tab contents -->
                <div class="tab-pane fade" id="elem-asset-stock-contents">

                    <!-- 利用情報タブコンテンツ -->
                    <?= $this->element('Asset/asset.stock', ['conf' => $conf]) ?>

                    <!-- End attr tab contents -->
                </div>

                <!-- ********************************** -->
                <!-- 修理情報                           -->
                <!-- ********************************** -->
                <!-- attr tab contents -->
                <div class="tab-pane fade" id="elem-asset-repair-contents">

                    <!-- 利用情報タブコンテンツ -->
                    <?= $this->element('Asset/asset.repair', ['conf' => $conf]) ?>

                    <!-- End attr tab contents -->
                </div>

                <!-- ********************************** -->
                <!-- 貸出/返却情報                      -->
                <!-- ********************************** -->
                <!-- attr tab contents -->
                <div class="tab-pane fade" id="elem-asset-rental-contents">

                    <!-- 利用情報タブコンテンツ -->
                    <?= $this->element('Asset/asset.rental', ['conf' => $conf]) ?>

                    <!-- End attr tab contents -->
                </div>

                <!-- End tab contents -->
            </div>

            <!-- End widget body -->
        </div>

        <!-- End content -->
    </div>

    <!-- End widget ID -->
</div>


