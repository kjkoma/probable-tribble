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
$this->Breadcrumbs->add('資産検索', ['controller' => 'Assets', 'action' => 'search']);

?>

<!-- widget grid -->
<section id="widget-grid">

    <!-- ********************************** -->
    <!-- Asset List                         -->
    <!-- ********************************** -->

    <!-- list widget grid row -->
    <div class="row" id="grid-row-search">
        <!-- DETAIL list widget -->
        <article class="col-sm-12 sortable-grid ui-sortable">

            <!-- widget ID -->
            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-search"
                 data-widget-deletebutton="false"
                 data-widget-editbutton="false"
                 data-widget-colorbutton="false"
                 data-widget-sortable="false">

                <!-- DETAILS widget header -->
                <header role="heading">
                    <span class="widget-icon"> <i class="fa fa-lg fa-th-list"></i> </span>
                    <h2>資産検索</h2>
                </header>

                <!-- content -->
                <div role="content">

                    <!-- DETAILS widget body -->
                    <div class="widget-body">

                        <!-- form -->
                        <?= $this->Form->create(null, ['id' => 'form-condition', 'type' => 'post', 'class' => "smart-form"]) ?>

                            <fieldset>
                                <div class="row">
                                    <!-- 資産タイプ -->
                                    <section class="col col-3">
                                        <?= $this->element('Parts/select-snames', [
                                            'snames'  => $assetType,
                                            'name'    => 'cond.asset_type',
                                            'id'      => 'asset_type',
                                            'form'    => 'form-condition',
                                            'disabled' => false,
                                            'blank'    => true,
                                            'placeholder' => '-- 資産タイプ選択 --'
                                        ]) ?>
                                    </section>
                                    <!-- 資産状況 -->
                                    <section class="col col-3">
                                        <?= $this->element('Parts/select-snames', [
                                            'snames'   => $assetSts,
                                            'name'     => 'cond.asset_sts',
                                            'id'       => 'asset_sts',
                                            'form'     => 'form-condition',
                                            'disabled' => false,
                                            'blank'    => true,
                                            'placeholder' => '-- 資産状況選択 --'
                                        ]) ?>
                                    </section>
                                    <!-- 資産状況（サブ） -->
                                    <section class="col col-3">
                                        <?= $this->element('Parts/select-snames', [
                                            'snames'   => $assetSubSts,
                                            'name'     => 'cond.asset_sub_sts',
                                            'id'       => 'asset_sub_sts',
                                            'form'     => 'form-condition',
                                            'disabled' => false,
                                            'blank'    => true,
                                            'placeholder' => '-- 資産状況(サブ)選択 --'
                                        ]) ?>
                                    </section>
                                    <!-- カテゴリ -->
                                    <section class="col col-3">
                                        <?= $this->element('Parts/select-categories', [
                                            'categories' => $categories,
                                            'name'     => 'cond.category_id',
                                            'id'       => 'category_id',
                                            'form'     => 'form-condition',
                                            'blank'    => true,
                                            'disabled' => false,
                                        ]) ?>
                                    </section>
                                </div>
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
                                    <!-- 製品 -->
                                    <section class="col col-4">
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
                                    <!-- シリアル番号 -->
                                    <section class="col col-4">
                                        <label class="input">
                                            <input type="text" name="cond.serial_no" id="serial_no" class="input-sm"
                                                   data-app-form="form-condition"
                                                   maxlength="120"
                                                   placeholder="シリアル番号">
                                        </label>
                                    </section>
                                    <!-- 資産管理番号 -->
                                    <section class="col col-4">
                                        <label class="input">
                                            <input type="text" name="cond.asset_no" id="asset_no" class="input-sm"
                                                   data-app-form="form-condition"
                                                   maxlength="60"
                                                   placeholder="資産管理番号">
                                        </label>
                                    </section>
                                </div>
                                <div class="row">
                                    <!-- 初回入庫日(From) -->
                                    <section class="col col-3">
                                        <label class="input">
                                            <i class="icon-append fa fa-calendar"></i>
                                            <input type="text" name="cond.first_instock_date_from" id="first_instock_date_from" class="input-sm datepicker"
                                                   data-dateformat="yy/mm/dd"
                                                   data-app-form="form-condition"
                                                   maxlength="10"
                                                   placeholder="初回入庫日（From）－yyyy/mm/dd形式">
                                        </label>
                                    </section>
                                    <!-- 初回入庫日(To) -->
                                    <section class="col col-3">
                                        <label class="input">
                                            <i class="icon-append fa fa-calendar"></i>
                                            <input type="text" name="cond.first_instock_date_to" id="first_instock_date_to" class="input-sm datepicker"
                                                   data-dateformat="yy/mm/dd"
                                                   data-app-form="form-condition"
                                                   maxlength="10"
                                                   placeholder="初回入庫日（To）－yyyy/mm/dd形式">
                                        </label>
                                    </section>
                                    <!-- 初回出庫日(From) -->
                                    <section class="col col-3">
                                        <label class="input">
                                            <i class="icon-append fa fa-calendar"></i>
                                            <input type="text" name="cond.account_date_from" id="account_date_from" class="input-sm datepicker"
                                                   data-dateformat="yy/mm/dd"
                                                   data-app-form="form-condition"
                                                   maxlength="10"
                                                   placeholder="計上日(初回出庫日)（From）－yyyy/mm/dd形式">
                                        </label>
                                    </section>
                                    <!-- 初回出庫日(To) -->
                                    <section class="col col-3">
                                        <label class="input">
                                            <i class="icon-append fa fa-calendar"></i>
                                            <input type="text" name="cond.account_date_to" id="account_date_to" class="input-sm datepicker"
                                                   data-dateformat="yy/mm/dd"
                                                   data-app-form="form-condition"
                                                   maxlength="10"
                                                   placeholder="計上日(初回出庫日)（To）－yyyy/mm/dd形式">
                                        </label>
                                    </section>
                                </div>
                                <div class="row">
                                    <!-- 廃棄日(From) -->
                                    <section class="col col-3">
                                        <label class="input">
                                            <i class="icon-append fa fa-calendar"></i>
                                            <input type="text" name="cond.abrogate_date_from" id="abrogate_date_from" class="input-sm datepicker"
                                                   data-dateformat="yy/mm/dd"
                                                   data-app-form="form-condition"
                                                   maxlength="10"
                                                   placeholder="廃棄日（From）－yyyy/mm/dd形式">
                                        </label>
                                    </section>
                                    <!-- 廃棄日(To) -->
                                    <section class="col col-3">
                                        <label class="input">
                                            <i class="icon-append fa fa-calendar"></i>
                                            <input type="text" name="cond.abrogate_date_to" id="abrogate_date_to" class="input-sm datepicker"
                                                   data-dateformat="yy/mm/dd"
                                                   data-app-form="form-condition"
                                                   maxlength="10"
                                                   placeholder="廃棄日（To）－yyyy/mm/dd形式">
                                        </label>
                                    </section>
                                    <!-- 保守期限日(From) -->
                                    <section class="col col-3">
                                        <label class="input">
                                            <i class="icon-append fa fa-calendar"></i>
                                            <input type="text" name="cond.support_limit_date_from" id="support_limit_date_from" class="input-sm datepicker"
                                                   data-dateformat="yy/mm/dd"
                                                   data-app-form="form-condition"
                                                   maxlength="10"
                                                   placeholder="保守期限日（From）－yyyy/mm/dd形式">
                                        </label>
                                    </section>
                                    <!-- 保守期限日(To) -->
                                    <section class="col col-3">
                                        <label class="input">
                                            <i class="icon-append fa fa-calendar"></i>
                                            <input type="text" name="cond.support_limit_date_to" id="support_limit_date_to" class="input-sm datepicker"
                                                   data-dateformat="yy/mm/dd"
                                                   data-app-form="form-condition"
                                                   maxlength="10"
                                                   placeholder="保守期限日（To）－yyyy/mm/dd形式">
                                        </label>
                                    </section>
                                </div>
                                <div class="row">
                                    <!-- 利用者(組織) -->
                                    <section class="col col-4">
                                        <div class="form-group">
                                            <select name="cond.current_organization_id" id="current_organization_id" class="select2 form-control input-sm"
                                                   data-app-form="form-condition"
                                                   maxlength="20"
                                                   data-placeholder="利用者(組織)"></select>
                                        </div>
                                    </section>
                                    <!-- 利用者(ユーザー) -->
                                    <section class="col col-4">
                                        <div class="form-group">
                                            <select name="cond.current_user_id" id="current_user_id" class="select2 form-control input-sm"
                                                   data-app-form="form-condition"
                                                   maxlength="20"
                                                   data-placeholder="利用者(ユーザー)"></select>
                                        </div>
                                    </section>
                                    <!-- 備考 -->
                                    <section class="col col-4">
                                        <label class="input">
                                            <input type="text" name="cond.remarks" id="remarks" class="input-sm"
                                                   data-app-form="form-condition"
                                                   maxlength="60"
                                                   placeholder="備考">
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
                    <h2>検索結果(※最大500件表示)</h2>
                    <div class="widget-toolbar" role="menu">
                        <a href="javascript:void(0);" class="btn btn-info disabled" data-app-action-key="download"><i class="fa fa-download"></i>　ダウンロード</a>
                    </div>
                </header>

                <!-- content -->
                <div role="content">

                    <!-- DETAILS widget body -->
                    <div class="widget-body">

                        <table class="table table-striped table-bordered table-hover" id="asset-datatable">
                            <thead>
                                <tr>
                                    <th>タイプ</th>
                                    <th>状況</th>
                                    <th>状況(サブ)</th>
                                    <th>資産名</th>
                                    <th>分類</th>
                                    <th>メーカー</th>
                                    <th>製品名</th>
                                    <th>シリアル</th>
                                    <th>資産管理</th>
                                    <th>利用者</th>
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

    <!-- detail widget grid row -->
    <div class="row hidden" id="grid-row-back">
        <div class="col col-sm-12 text-right">
            <button type="button" class="btn btn-default" data-app-action-key="back"><i class="fa fa-chevron-left"></i>　検索を表示</button>
        </div>
    </div>

    <div class="row">
        <!-- DETAIL asset widget -->
        <article class="col-sm-12 sortable-grid ui-sortable">

            <!-- Asset Widget -->
            <?= $this->element('Asset/asset', ['conf' => [
                'asset' => 'edit',
                'attr'  => 'edit',
                'user'    => true,
                'stock'   => true,
                'repair'  => true,
                'rental'  => true,
                'hidden' => true]]) ?>

            <!-- End DETAILS asset widget -->
        </article>

        <!-- End plan widget grid row -->
    </div>

    <!-- End widget grid-->
</section>

<!-- download form -->
<?= $this->Form->create(null, ['id' => 'form-download', 'type' => 'post', 'class' => "smart-form hidden", 'url' => ['controller' => 'assets', 'action' => 'download']]) ?>
<?= $this->Form->end() ?>

<!-- load script -->
<?= $this->element('Common/load-datatable') ?>
<?php $this->Html->script('wnote/libs/wnote.lib.form.js', ['block' => true]); ?>
<?= $this->element('Asset/load-asset') ?>
<?php $this->Html->script('wnote/asset/assets.search.js', ['block' => true]); ?>

