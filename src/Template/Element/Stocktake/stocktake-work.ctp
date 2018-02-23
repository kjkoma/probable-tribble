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
 * 棚卸実施 ウィジット
 *  
 * - - -
 * @param mixed $conf 表示/入力の設定情報
 * - - -
 */
$default = [
    'hidden'  => false
];
$conf = isset($conf) ? array_merge($default, $conf) : $default;
?>
<!-- widget ID -->
<div class="jarviswidget jarviswidget-color-blueDark <?= ($conf['hidden']) ? 'hidden' : '' ?>" id="wid-id-elem-stocktake-work"
     data-widget-deletebutton="false"
     data-widget-editbutton="false"
     data-widget-colorbutton="false"
     data-widget-togglebutton="false"
     data-widget-fullscreenbutton="false"
     data-widget-sortable="false">

    <!-- DETAILS widget header -->
    <header role="heading">
        <ul id="tab-menu-stocktake-work" class="nav nav-tabs pull-left">
            <!-- 資産棚卸 タブ -->
            <li class="active">
                <a data-toggle="tab" href="#elem-stocktake-work-asset" data-app-action-key="elem-stocktake-work-asset">
                    <i class="fa fa-lg fa fa-lg fa-barcode"></i>
                    <span class="hidden-mobile hidden-tablet"> 資産棚卸 </span>
                </a>
            </li>
            <!-- 数量棚卸 タブ -->
            <li class="">
                <a data-toggle="tab" href="#elem-stocktake-work-count" data-app-action-key="elem-stocktake-work-count">
                    <i class="fa fa-lg fa fa-lg fa-pencil"></i>
                    <span class="hidden-mobile hidden-tablet"> 数量棚卸 </span>
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
                <!-- 資産棚卸                           -->
                <!-- ********************************** -->
                <!-- asset tab contents -->
                <div class="tab-pane fade in active" id="elem-stocktake-work-asset">

                    <!-- 資産棚卸タブコンテンツ -->
                    <?= $this->element('Stocktake/stocktake-work.asset', ['conf' => $conf]) ?>

                    <!-- End asset tab contents -->
                </div>

                <!-- ********************************** -->
                <!-- 数量棚卸                           -->
                <!-- ********************************** -->
                <!-- attr tab contents -->
                <div class="tab-pane fade" id="elem-stocktake-work-count">

                    <!-- 数量棚卸タブコンテンツ -->
                    <?= $this->element('Stocktake/stocktake-work.count', ['conf' => $conf]) ?>

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


