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
$this->assign('title', '資産');
$this->Breadcrumbs->add('Home', ['prefix' => false, 'controller' => 'Home', 'action' => 'home']);
$this->Breadcrumbs->add('資産', '#');
$this->Breadcrumbs->add('廃棄予定', ['controller' => 'Assets', 'action' => 'abrogatePlans']);

?>

<!-- widget grid -->
<section id="widget-grid">

    <!-- ********************************** -->
    <!-- Abrogate Actions                   -->
    <!-- ********************************** -->

    <!-- detail widget grid row -->
    <?php if ($this->AppUser->hasDomainGeneral()) { ?>
        <div class="row">
            <div class="col col-sm-12 text-right">
                <button type="button" class="btn btn-primary" data-app-action-key="all"><i class="fa fa-list"></i>　すべて廃棄する</button>
                <button type="button" class="btn btn-primary" data-app-action-key="selected"><i class="fa fa-check"></i>　選択行を廃棄する</button>
            </div>
        </div>
    <?php } ?>

    <!-- ********************************** -->
    <!-- Abrogate Plan List                 -->
    <!-- ********************************** -->

    <!-- list widget grid row -->
    <div class="row" id="grid-row-list">
        <!-- DETAIL list widget -->
        <article class="col-sm-12 sortable-grid ui-sortable">

            <!-- widget ID -->
            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-list"
                 data-widget-deletebutton="false"
                 data-widget-editbutton="false"
                 data-widget-colorbutton="false"
                 data-widget-sortable="false">

                <!-- DETAILS widget header -->
                <header role="heading">
                    <span class="widget-icon"> <i class="fa fa-lg fa-th-list"></i> </span>
                    <h2>廃棄予定一覧</h2>
                    <div class="widget-toolbar" role="menu">
                        <?php if ($this->AppUser->hasDomainGeneral()) { ?>
                            <a href="javascript:void(0);" class="btn btn-info" data-app-action-key="download"><i class="fa fa-download"></i>　ダウンロード</a>
                        <?php } ?>
                    </div>
                </header>

                <!-- content -->
                <div role="content">

                    <!-- DETAILS widget body -->
                    <div class="widget-body">

                        <table class="table table-striped table-bordered table-hover" id="abrogate-plans-datatable">
                            <thead>
                                <tr>
                                    <th>分類</th>
                                    <th>メーカー</th>
                                    <th>製品名</th>
                                    <th>シリアル</th>
                                    <th>資産管理</th>
                                    <th>修理回数</th>
                                    <th>廃棄日</th>
                                    <th>廃棄者</th>
                                    <th>廃棄理由</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>

                        <!-- End widget body -->
                    </div>

                    <!-- End content -->
                </div>

                <!-- End widget ID -->
            </div>

            <!-- End DETAILS list widget -->
        </article>

        <!-- End list widget grid row -->
    </div>

    <!-- End widget grid-->
</section>

<!-- download form -->
<?= $this->Form->create(null, ['id' => 'form-download', 'type' => 'post', 'class' => "smart-form hidden", 'url' => ['controller' => 'assets', 'action' => 'download_abrogate_plans']]) ?>
<?= $this->Form->end() ?>

<!-- load script -->
<?= $this->element('Common/load-datatable') ?>
<?php $this->Html->script('wnote/asset/assets.abrogate_plans.js', ['block' => true]); ?>

