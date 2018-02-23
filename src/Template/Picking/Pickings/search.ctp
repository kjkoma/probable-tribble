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
$this->assign('title', '出庫検索');
$this->Breadcrumbs->add('Home', ['prefix' => false, 'controller' => 'Home', 'action' => 'home']);
$this->Breadcrumbs->add('出庫', '#');
$this->Breadcrumbs->add('出庫検索', ['controller' => 'Pickings', 'action' => 'search']);

?>

<!-- widget grid -->
<section id="widget-grid">

    <!-- ********************************** -->
    <!-- Picking  List                      -->
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
                 data-widget-sortable="false">

                <!-- DETAILS widget header -->
                <header role="heading">
                    <span class="widget-icon"> <i class="fa fa-lg fa-th-list"></i> </span>
                    <h2>出庫検索</h2>
                </header>

                <!-- content -->
                <div role="content">

                    <!-- DETAILS widget body -->
                    <div class="widget-body">

                        <!-- form -->
                        <?= $this->Form->create(null, ['id' => 'form-condition', 'type' => 'post', 'class' => "smart-form"]) ?>

                            <fieldset>
                                <div class="row">
                                    <!-- 出庫日(From) -->
                                    <section class="col col-4">
                                        <label class="input">
                                            <i class="icon-append fa fa-calendar"></i>
                                            <input type="text" name="cond.picking_date_from" id="picking_date_from" class="input-sm datepicker"
                                                   data-dateformat="yy/mm/dd"
                                                   data-app-form="form-condition"
                                                   maxlength="10"
                                                   placeholder="出庫日（From）－yyyy/mm/dd形式">
                                        </label>
                                    </section>
                                    <!-- 出庫日(To) -->
                                    <section class="col col-4">
                                        <label class="input">
                                            <i class="icon-append fa fa-calendar"></i>
                                            <input type="text" name="cond.picking_date_to" id="picking_date_to" class="input-sm datepicker"
                                                   data-dateformat="yy/mm/dd"
                                                   data-app-form="form-condition"
                                                   maxlength="10"
                                                   placeholder="出庫日（To）－yyyy/mm/dd形式">
                                        </label>
                                    </section>
                                    <!-- 伝票番号 -->
                                    <section class="col col-4">
                                        <label class="input">
                                            <input type="text" name="cond.voucher_no" id="voucher_no" class="input-sm"
                                                   data-app-form="form-condition"
                                                   maxlength="40"
                                                   placeholder="伝票番号">
                                        </label>
                                    </section>
                                </div>

                                <div class="row">
                                    <!-- 出庫担当者 -->
                                    <section class="col col-4">
                                        <div class="form-group">
                                            <select name="cond.picking_suser_id" id="picking_suser_id" class="select2 form-control input-sm"
                                                   data-app-form="form-condition"
                                                    maxlength="20"
                                                   data-placeholder="出庫担当者"></select>
                                        </div>
                                    </section>
                                    <!-- 出庫確認者 -->
                                    <section class="col col-4">
                                        <div class="form-group">
                                            <select name="cond.confirm_suser_id" id="confirm_suser_id" class="select2 form-control input-sm"
                                                   data-app-form="form-condition"
                                                    maxlength="20"
                                                   data-placeholder="出庫確認者"></select>
                                        </div>
                                    </section>
                                    <!-- 出庫確認有無 -->
                                    <section class="col col-4">
                                        <label class="select">
                                            <select name="cond.not_confirmation" id="not_confirmation" class="input-sm"
                                                   data-app-form="form-condition">
                                                <option value="0" selected="selected">出庫確認済を含む</option>
                                                <option value="1">出庫未確認のみ</option>
                                            </select>
                                        </label>
                                    </section>
                                </div>

                                <div class="row">
                                    <!-- 依頼者 -->
                                    <section class="col col-4">
                                        <div class="form-group">
                                            <select name="cond.req_user_id" id="req_user_id" class="select2 form-control input-sm"
                                                   data-app-form="form-condition"
                                                   data-placeholder="依頼者"></select>
                                        </div>
                                    </section>
                                    <section class="col col-4">
                                    <!-- 使用者 -->
                                        <div class="form-group">
                                            <select name="cond.use_user_id" id="use_user_id" class="select2 form-control input-sm"
                                                   data-app-form="form-condition"
                                                   data-placeholder="使用者"></select>
                                        </div>
                                    </section>
                                    <!-- 出庫先 -->
                                    <section class="col col-4">
                                        <div class="form-group">
                                            <select name="cond.dlv_user_id" id="dlv_user_id" class="select2 form-control input-sm"
                                                   data-app-form="form-condition"
                                                    maxlength="30"
                                                   data-placeholder="出庫先(ユーザー)"></select>
                                        </div>
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

                                <!-- 出庫備考 -->
                                <section>
                                    <label class="input">
                                        <input type="text" name="cond.remarks" id="remarks" class="input-sm"
                                               data-app-form="form-condition"
                                               maxlength="60"
                                               placeholder="出庫備考">
                                    </label>
                                </section>

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
                    <h2>検索結果(※最大500件表示)</h2>
                    <div class="widget-toolbar" role="menu">
                        <a href="javascript:void(0);" class="btn btn-info disabled" data-app-action-key="download"><i class="fa fa-download"></i>　ダウンロード</a>
                    </div>
                </header>

                <!-- content -->
                <div role="content">

                    <!-- DETAILS widget body -->
                    <div class="widget-body">

                        <table class="table table-striped table-bordered table-hover" id="picking-datatable">
                            <thead>
                                <tr>
                                    <th>出庫区分</th>
                                    <th>出庫日</th>
                                    <th>依頼者</th>
                                    <th>使用者</th>
                                    <th>分類</th>
                                    <th>メーカー</th>
                                    <th>製品</th>
                                    <th>シリアル</th>
                                    <th>資産管理</th>
                                    <th>伝票番号</th>
                                    <th>担当者</th>
                                    <th>確認者</th>
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
<?= $this->Form->create(null, ['id' => 'form-download', 'type' => 'post', 'class' => "smart-form hidden", 'url' => ['controller' => 'pickings', 'action' => 'download']]) ?>
<?= $this->Form->end() ?>

<!-- load script -->
<?= $this->element('Common/load-datatable') ?>
<?php $this->Html->script('wnote/picking/pickings.search.js', ['block' => true]); ?>

