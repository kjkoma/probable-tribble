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
 * 棚卸結果表示 ウィジット
 *  
 * - - -
 * @param mixed $conf 表示/入力の設定情報
 * - - -
 */
$default = [
    'hidden' => false,
    'edit'   => false
];
$conf = isset($conf) ? array_merge($default, $conf) : $default;
?>
<!-- widget ID -->
<div class="jarviswidget jarviswidget-color-blueDark <?= ($conf['hidden']) ? 'hidden' : '' ?>" id="wid-id-elem-stocktake"
     data-widget-deletebutton="false"
     data-widget-editbutton="false"
     data-widget-colorbutton="false"
     data-widget-togglebutton="false"
     data-widget-fullscreenbutton="false"
     data-widget-sortable="false">

    <!-- DETAILS widget header -->
    <header role="heading">
        <ul id="tab-menu-stocktake" class="nav nav-tabs pull-left">
            <!-- 棚卸サマリ タブ -->
            <li class="active">
                <a data-toggle="tab" href="#elem-summary-content" data-app-action-key="elem-summary-content">
                    <i class="fa fa-lg fa fa-lg fa-info"></i>
                    <span class="hidden-mobile hidden-tablet"> 棚卸サマリ </span>
                </a>
            </li>
            <!-- 棚卸差分（数量差分） タブ -->
            <li class="">
                <a data-toggle="tab" href="#elem-unmatch-content" data-app-action-key="elem-unmatch-content">
                    <i class="fa fa-lg fa fa-lg fa-info"></i>
                    <span class="hidden-mobile hidden-tablet"> 棚卸差分（数量差分） </span>
                </a>
            </li>
            <!-- 棚卸差分（在庫なし） タブ -->
            <li class="">
                <a data-toggle="tab" href="#elem-nostock-content" data-app-action-key="elem-nostock-content">
                    <i class="fa fa-lg fa fa-lg fa-info"></i>
                    <span class="hidden-mobile hidden-tablet"> 棚卸差分（在庫なし） </span>
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
                <!-- 棚卸サマリ                         -->
                <!-- ********************************** -->
                <!-- asset tab contents -->
                <div class="tab-pane fade in active" id="elem-summary-content">

                    <!-- 資産情報タブコンテンツ -->
                    <?= $this->element('Stocktake/stocktake.summary', ['conf' => $conf]) ?>

                    <!-- End asset tab contents -->
                </div>

                <!-- ********************************** -->
                <!-- 棚卸差分（数量差分）               -->
                <!-- ********************************** -->
                <!-- attr tab contents -->
                <div class="tab-pane fade" id="elem-unmatch-content">

                    <!-- 棚卸差分（数量差分）タブコンテンツ -->
                    <?= $this->element('Stocktake/stocktake.unmatch', ['conf' => $conf]) ?>

                    <!-- End attr tab contents -->
                </div>

                <!-- ********************************** -->
                <!-- 棚卸差分（在庫なし）               -->
                <!-- ********************************** -->
                <!-- attr tab contents -->
                <div class="tab-pane fade" id="elem-nostock-content">

                    <!-- 棚卸差分（在庫なし）タブコンテンツ -->
                    <?= $this->element('Stocktake/stocktake.nostock', ['conf' => $conf]) ?>

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


