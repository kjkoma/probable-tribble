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
$this->assign('title', '在庫集計表');
$this->Breadcrumbs->add('Home', ['prefix' => false, 'controller' => 'Home', 'action' => 'home']);
$this->Breadcrumbs->add('在庫管理', '#');
$this->Breadcrumbs->add('在庫集計表', ['controller' => 'Stocks', 'action' => 'summary']);

?>

<!-- widget grid -->
<section id="widget-grid">

    <!-- ********************************** -->
    <!-- Instock Plan List                  -->
    <!-- ********************************** -->

    <!-- list widget grid row -->
    <div class="row">
        <!-- DETAIL list widget -->
        <article class="col-sm-12 sortable-grid ui-sortable">

            <!-- widget ID -->
            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-search"
                 data-widget-deletebutton="false"
                 data-widget-editbutton="false"
                 data-widget-colorbutton="false"
                 data-widget-togglebutton="false"
                 data-widget-sortable="false">

                <!-- DETAILS widget header -->
                <header role="heading">
                    <span class="widget-icon"> <i class="fa fa-lg fa-th-list"></i> </span>
                    <h2>集計条件</h2>
                </header>

                <!-- content -->
                <div role="content">

                    <!-- DETAILS widget body -->
                    <div class="widget-body">

                        <!-- form -->
                        <?= $this->Form->create(null, ['id' => 'form-condition', 'type' => 'post', 'class' => "smart-form"]) ?>

                            <fieldset>
                                <div class="row">
                                    <!-- 製品分類 -->
                                    <section class="col col-4">
                                        <div class="form-group">
                                            <select name="cond.classification_id" id="classification_id" class="select2 form-control input-sm"
                                                   data-app-form="form-condition"
                                                   maxlength="20"
                                                   data-placeholder="製品分類"></select>
                                        </div>
                                    </section>
                                    <section class="col col-4">
                                    <!-- 製品 -->
                                        <div class="form-group">
                                            <select name="cond.product_id" id="product_id" class="select2 form-control input-sm"
                                                   data-app-form="form-condition"
                                                   maxlength="30"
                                                   data-placeholder="製品"></select>
                                        </div>
                                    </section>
                                    <!-- モデル -->
                                    <section class="col col-4">
                                        <div class="form-group">
                                            <select name="cond.product_model_id" id="product_model_id" class="select2 form-control input-sm"
                                                   data-app-form="form-condition"
                                                   maxlength="30"
                                                   data-placeholder="製品モデル／型"></select>
                                        </div>
                                    </section>
                                </div>

                                <div class="row">
                                    <!-- メーカー -->
                                    <section class="col col-4">
                                        <?= $this->element('Parts/select-makers', [
                                            'makers'   => $makers,
                                            'name'     => 'cond.maker_id',
                                            'id'       => 'maker_id',
                                            'form'     => 'form-condition',
                                            'blank'    => true,
                                            'disabled' => false,
                                        ]) ?>
                                    </section>
                                    <!-- ステータス -->
                                    <section class="col col-4">
                                        <?= $this->element('Parts/select-snames', [
                                            'snames'   => $assetSts,
                                            'name'     => 'cond.asset_sts',
                                            'id'       => 'asset_sts',
                                            'form'     => 'form-condition',
                                            'blank'    => true,
                                            'placeholder' => '-- 資産状況 --',
                                            'disabled' => false,
                                        ]) ?>
                                    </section>
                                    <!-- サブステータス -->
                                    <section class="col col-4">
                                        <?= $this->element('Parts/select-snames', [
                                            'snames'   => $assetSubSts,
                                            'name'     => 'cond.asset_sub_sts',
                                            'id'       => 'asset_sub_sts',
                                            'form'     => 'form-condition',
                                            'blank'    => true,
                                            'placeholder' => '-- 資産状況(サブ) --',
                                            'disabled' => false,
                                        ]) ?>
                                    </section>
                                </div>

                                <section>
                                    <button type="button" class="btn btn-lg btn-block btn-info" data-app-action-key="search"><i class="fa fa-search"></i>　検索</button>
                                </section>

                            </fieldset>

                        <!-- End form -->
                        <?= $this->Form->end() ?>

                        <!-- End widget body -->
                    </div>

                    <!-- End content -->
                </div>

                <!-- End widget ID -->
            </div>


            <!-- widget ID -->
            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-list"
                 data-widget-deletebutton="false"
                 data-widget-editbutton="false"
                 data-widget-colorbutton="false"
                 data-widget-togglebutton="false"
                 data-widget-sortable="false">

                <!-- DETAILS widget header -->
                <header role="heading">
                    <span class="widget-icon"> <i class="fa fa-lg fa-th-list"></i> </span>
                    <h2>集計結果</h2>
                    <div class="widget-toolbar" role="menu">
                        <a href="javascript:void(0);" class="btn btn-info disabled" data-app-action-key="download"><i class="fa fa-download"></i>　ダウンロード</a>
                    </div>
                </header>

                <!-- content -->
                <div role="content">

                    <!-- DETAILS widget body -->
                    <div class="widget-body">

                        <table class="table table-striped table-bordered table-hover" id="stock-datatable">
                            <thead>
                                <tr>
                                    <th>カテゴリ</th>
                                    <th>分類</th>
                                    <th>メーカー</th>
                                    <th>製品</th>
                                    <th>モデル／型</th>
                                    <th>ステータス</th>
                                    <th>サブステータス</th>
                                    <th>在庫数</th>
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
<?= $this->Form->create(null, ['id' => 'form-download', 'type' => 'post', 'class' => "smart-form hidden", 'url' => ['controller' => 'stocks', 'action' => 'download-summary']]) ?>
<?= $this->Form->end() ?>

<!-- load script -->
<?= $this->element('Common/load-datatable') ?>
<?php $this->Html->script('wnote/stock/stocks.summary.js', ['block' => true]); ?>

