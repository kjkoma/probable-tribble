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
$this->assign('title', '修理一覧');
$this->Breadcrumbs->add('Home', ['prefix' => false, 'controller' => 'Home', 'action' => 'home']);
$this->Breadcrumbs->add('修理', '#');
$this->Breadcrumbs->add('修理一覧', ['controller' => 'Repairs', 'action' => 'list']);

?>

<!-- widget grid -->
<section id="widget-grid">

    <!-- ********************************** -->
    <!-- Repair List                        -->
    <!-- ********************************** -->

    <!-- list widget grid row -->
    <div class="row" id="grid-row-list">
        <!-- list widget -->
        <article class="col-sm-12 sortable-grid ui-sortable">

            <!-- widget ID -->
            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-list"
                 data-widget-deletebutton="false"
                 data-widget-editbutton="false"
                 data-widget-colorbutton="false"
                 data-widget-sortable="false">

                <!-- widget header -->
                <header role="heading">
                    <span class="widget-icon"> <i class="fa fa-lg fa-th-list"></i> </span>
                    <h2>修理一覧</h2>
                </header>

                <!-- content -->
                <div role="content">

                    <!-- widget body -->
                    <div class="widget-body">

                        <!-- form -->
                        <?= $this->Form->create(null, ['id' => 'form-condition', 'type' => 'post', 'class' => "smart-form"]) ?>

                            <fieldset>
                                <div class="row">
                                    <!-- ステータス -->
                                    <section class="col col-4">
                                        <?= $this->element('Parts/select-snames', [
                                            'snames'      => $repairSts,
                                            'name'        => 'cond.repair_sts',
                                            'id'          => 'repair_sts',
                                            'form'        => 'form-condition',
                                            'blank'       => true,
                                            'placeholder' => '-- 修理状況 --',
                                            'disabled'    => false,
                                        ]) ?>
                                    </section>
                                    <!-- 修理依頼日(From) -->
                                    <section class="col col-4">
                                        <label class="input">
                                            <i class="icon-append fa fa-calendar"></i>
                                            <input type="text" name="cond.req_date_from" id="req_date_from" class="input-sm datepicker"
                                                   data-dateformat="yy/mm/dd"
                                                   data-app-form="form-condition"
                                                   placeholder="修理依頼日（From）－yyyy/mm/dd形式">
                                        </label>
                                    </section>
                                    <!-- 修理依頼日(To) -->
                                    <section class="col col-4">
                                        <label class="input">
                                            <i class="icon-append fa fa-calendar"></i>
                                            <input type="text" name="cond.req_date_to" id="req_date_to" class="input-sm datepicker"
                                                   data-dateformat="yy/mm/dd"
                                                   data-app-form="form-condition"
                                                   placeholder="修理依頼日（To）－yyyy/mm/dd形式">
                                        </label>
                                    </section>
                                </div>
                                <div class="row">
                                    <!-- 故障区分 -->
                                    <section class="col col-4">
                                        <?= $this->element('Parts/select-snames', [
                                            'snames'      => $troubleKbn,
                                            'name'        => 'cond.trouble_kbn',
                                            'id'          => 'trouble_kbn',
                                            'form'        => 'form-condition',
                                            'blank'       => true,
                                            'placeholder' => '-- 故障区分 --',
                                            'disabled'    => false,
                                        ]) ?>
                                    </section>
                                    <!-- センドバック有無 -->
                                    <section class="col col-4">
                                        <?= $this->element('Parts/select-snames', [
                                            'snames'      => $sendbackKbn,
                                            'name'        => 'cond.sendback_kbn',
                                            'id'          => 'sendback_kbn',
                                            'form'        => 'form-condition',
                                            'blank'       => true,
                                            'placeholder' => '-- センドバック有無 --',
                                            'disabled'    => false,
                                        ]) ?>
                                    </section>
                                    <!-- データ抽出有無 -->
                                    <section class="col col-4">
                                        <?= $this->element('Parts/select-snames', [
                                            'snames'      => $datapickKbn,
                                            'name'        => 'cond.datapick_kbn',
                                            'id'          => 'datapick_kbn',
                                            'form'        => 'form-condition',
                                            'blank'       => true,
                                            'placeholder' => '-- データ抽出有無 --',
                                            'disabled'    => false,
                                        ]) ?>
                                    </section>
                                </div>
                                <div class="row">
                                    <!-- 修理依頼者 -->
                                    <section class="col col-4">
                                        <div class="form-group">
                                            <select name="cond.req_user_id" id="req_user_id" class="select2 form-control input-sm"
                                                   data-app-form="form-condition"
                                                   data-placeholder="修理依頼者"></select>
                                        </div>
                                    </section>
                                    <!-- 入庫日(From) -->
                                    <section class="col col-4">
                                        <label class="input">
                                            <i class="icon-append fa fa-calendar"></i>
                                            <input type="text" name="cond.instock_date_from" id="instock_date_from" class="input-sm datepicker"
                                                   data-dateformat="yy/mm/dd"
                                                   data-app-form="form-condition"
                                                   placeholder="入庫日（From）－yyyy/mm/dd形式">
                                        </label>
                                    </section>
                                    <!-- 入庫日(To) -->
                                    <section class="col col-4">
                                        <label class="input">
                                            <i class="icon-append fa fa-calendar"></i>
                                            <input type="text" name="cond.instock_date_to" id="instock_date_to" class="input-sm datepicker"
                                                   data-dateformat="yy/mm/dd"
                                                   data-app-form="form-condition"
                                                   placeholder="入庫日（To）－yyyy/mm/dd形式">
                                        </label>
                                    </section>
                                </div>
                                <div class="row">
                                    <!-- 製品分類 -->
                                    <section class="col col-4">
                                        <div class="form-group">
                                            <select name="cond.classification_id" id="classification_id" class="select2 form-control input-sm"
                                                   data-app-form="form-condition"
                                                   data-placeholder="製品分類"></select>
                                        </div>
                                    </section>
                                    <section class="col col-4">
                                    <!-- 製品 -->
                                        <div class="form-group">
                                            <select name="cond.product_id" id="product_id" class="select2 form-control input-sm"
                                                   data-app-form="form-condition"
                                                   data-placeholder="製品"></select>
                                        </div>
                                    </section>
                                    <!-- モデル -->
                                    <section class="col col-4">
                                        <div class="form-group">
                                            <select name="cond.product_model_id" id="product_model_id" class="select2 form-control input-sm"
                                                   data-app-form="form-condition"
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
                                    <!-- シリアル番号 -->
                                    <section class="col col-4">
                                        <label class="input">
                                            <input type="text" name="cond.serial_no" id="serial_no" class="input-sm"
                                                   data-app-form="form-condition"
                                                   placeholder="シリアル番号">
                                        </label>
                                    </section>
                                    <!-- 資産管理番号 -->
                                    <section class="col col-4">
                                        <label class="input">
                                            <input type="text" name="cond.asset_no" id="asset_no" class="input-sm"
                                                   data-app-form="form-condition"
                                                   placeholder="資産管理番号">
                                        </label>
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
                 data-widget-sortable="false">

                <!-- DETAILS widget header -->
                <header role="heading">
                    <span class="widget-icon"> <i class="fa fa-lg fa-th-list"></i> </span>
                    <h2>修理一覧(※条件未指定時：入庫済、修理中のみ表示)</h2>
                    <div class="widget-toolbar" role="menu">
                        <a href="javascript:void(0);" class="btn btn-info disabled" data-app-action-key="download"><i class="fa fa-download"></i>　ダウンロード</a>
                    </div>
                </header>

                <!-- content -->
                <div role="content">

                    <!-- DETAILS widget body -->
                    <div class="widget-body">

                        <table class="table table-striped table-bordered table-hover" id="repair-datatable">
                            <thead>
                                <tr>
                                    <th>修理状況</th>
                                    <th>依頼日</th>
                                    <th>依頼者</th>
                                    <th>カテゴリ</th>
                                    <th>分類</th>
                                    <th>メーカー</th>
                                    <th>製品</th>
                                    <th>資産管理</th>
                                    <th>シリアル</th>
                                    <th>故障区分</th>
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

            <!-- End list widget -->
        </article>

        <!-- End list widget grid row -->
    </div>

    <!-- back grid row -->
    <div class="row hidden" id="grid-row-back">
        <div class="col col-sm-12 text-right">
            <button type="button" class="btn btn-default" data-app-action-key="back"><i class="fa fa-chevron-left"></i>　一覧に戻る</button>
        </div>
    </div>

    <div class="row">
        <!-- histories widget -->
        <article class="col-sm-12 sortable-grid ui-sortable">

            <!-- Histories Widget -->
            <?= $this->element('Repair/repair_histories', ['conf' => [
                'hidden' => true]]) ?>

            <!-- End histories widget -->
        </article>

        <!-- End asset widget grid row -->
    </div>

    <!-- End widget grid-->
</section>

<!-- download form -->
<?= $this->Form->create(null, ['id' => 'form-download', 'type' => 'post', 'class' => "smart-form hidden", 'url' => ['controller' => 'repairs', 'action' => 'download-list']]) ?>
<?= $this->Form->end() ?>

<!-- load script -->
<?= $this->element('Common/load-datatable') ?>
<?php $this->Html->script('wnote/libs/wnote.lib.form.js', ['block' => true]); ?>
<?php $this->Html->script('wnote/element/wnote.repair_histories.js', ['block' => true]); ?>
<?php $this->Html->script('wnote/repair/repairs.list.js', ['block' => true]); ?>
