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
$this->assign('title', '入庫予定登録');
$this->Breadcrumbs->add('Home', ['prefix' => false, 'controller' => 'Home', 'action' => 'home']);
$this->Breadcrumbs->add('入庫', '#');
$this->Breadcrumbs->add('入庫予定登録', ['controller' => 'InstockPlans', 'action' => 'listNew']);

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
            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-list"
                 data-widget-deletebutton="false"
                 data-widget-editbutton="false"
                 data-widget-colorbutton="false"
                 data-widget-togglebutton="false"
                 data-widget-sortable="false">

                <!-- DETAILS widget header -->
                <header role="heading">
                    <span class="widget-icon"> <i class="fa fa-lg fa-th-list"></i> </span>
                    <h2>入庫予定（新規）一覧</h2>
                </header>

                <!-- content -->
                <div role="content">

                    <table class="table table-striped table-bordered table-hover" id="plans-datatable">
                        <thead>
                            <tr>
                                <th>区分</th>
                                <th>予定日</th>
                                <th>状況</th>
                                <th>件名</th>
                                <th>備考</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>

                    <!-- End content -->
                </div>

                <!-- End widget ID -->
            </div>

            <!-- End DETAILS list widget -->
        </article>

        <!-- End list widget grid row -->
    </div>

    <!-- ********************************** -->
    <!-- Instock Plan                       -->
    <!-- ********************************** -->

    <!-- widget grid row -->
    <div class="row">

        <!-- DETAIL widget -->
        <article class="col-sm-12 sortable-grid ui-sortable">

            <!-- widget ID -->
            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-1"
                 data-widget-deletebutton="false"
                 data-widget-editbutton="false"
                 data-widget-colorbutton="false"
                 data-widget-togglebutton="false"
                 data-widget-sortable="false">

                <!-- DETAILS widget header -->
                <header role="heading">
                    <span class="widget-icon"> <i class="fa fa-lg fa-file"></i> </span>
                    <h2>入庫予定内容</h2>
                    <div class="widget-toolbar hidden" role="menu" data-app-action-key="view-actions">
                        <a href="javascript:void(0);" class="btn btn-success" data-app-action-key="edit"><i class="fa fa-edit"></i>　編集</a>
                    </div>
                    <div class="widget-toolbar" role="menu" data-app-action-key="add-actions">
                        <a href="javascript:void(0);" class="btn btn-success" data-app-action-key="add"><i class="fa fa-plus"></i>　追加</a>
                    </div>
                    <div class="widget-toolbar hidden" role="menu" data-app-action-key="edit-actions">
                        <a href="javascript:void(0);" class="btn btn-default" data-app-action-key="cancel"><i class="fa fa-times"></i>　キャンセル</a>
                        <a href="javascript:void(0);" class="btn btn-primary" data-app-action-key="save"><i class="fa fa-save"></i>　保存</a>
                    </div>
                    <div class="widget-toolbar hidden" role="menu" data-app-action-key="delete-actions">
                        <a href="javascript:void(0);" class="btn btn-danger" data-app-action-key="delete"><i class="fa fa-trash"></i>　削除</a>
                    </div>
                </header>

                <!-- content -->
                <div role="content">

                    <!-- DETAILS widget body -->
                    <div class="widget-body">

                        <!-- form -->
                        <?= $this->Form->create(null, ['id' => 'form-plan', 'type' => 'post', 'class' => "smart-form"]) ?>

                            <header>
                                概要
                            </header>

                            <fieldset>
                                <div class="row">
                                    <!-- 入庫区分 -->
                                    <section class="col col-4">
                                        <?= $this->element('Parts/select-instock-kbn', [
                                            'snames'  => $instockKbn,
                                            'name'    => 'plan.instock_kbn',
                                            'id'      => 'instock_kbn',
                                            'form'    => 'form-plan'
                                        ]) ?>
                                    </section>
                                    <!-- 入庫予定日 -->
                                    <section class="col col-4">
                                        <label class="input">
                                            <i class="icon-append fa fa-calendar"></i>
                                            <input type="text" name="plan.plan_date" id="plan_date" class="input-sm datepicker"
                                                   data-dateformat="yy/mm/dd"
                                                   data-app-form="form-plan"
                                                   placeholder="入庫予定日　－　yyyy/mm/dd形式で入力してください（例：2017/10/09）"
                                                   maxlength="10"
                                                   disabled="disabled">
                                        </label>
                                    </section>
                                </div>
                                <!-- 件名 -->
                                <section>
                                    <label class="input">
                                        <input type="text" name="plan.name" id="name" class="input-xs"
                                               data-app-form="form-plan" placeholder="件名　－　最大60文字"
                                               maxlength="60"
                                               disabled="disabled">
                                    </label>
                                </section>
                                <!-- 補足（コメント） -->
                                <section>
                                    <label class="textarea textarea-resizable">
                                        <textarea name="plan.remarks" id="remarks" rows="3" class="custom-scroll"
                                                  data-app-form="form-plan" placeholder="【任意】補足（コメント）"
                                                  disabled="disabled"></textarea>
                                    </label>
                                </section>
                            </fieldset>

                        <!-- End form -->
                        <input type="hidden" name="plan.id" id="id" data-app-form="form-plan">
                        <?= $this->Form->end() ?>


                        <!-- ********************************** -->
                        <!-- Instock Plan Details               -->
                        <!-- ********************************** -->

                        <!-- plan detail contents -->
                        <div id="detail-contents" class="hidden">

                            <!-- form -->
                            <?= $this->Form->create(null, ['id' => 'form-detail', 'type' => 'post', 'class' => "smart-form"]) ?>

                                <!-- ********************************** -->
                                <!-- 入庫予定詳細一覧                   -->
                                <!-- ********************************** -->
                                <header>
                                    入庫予定詳細一覧
                                </header>

                                <fieldset>
                                    <table class="table table-striped table-bordered table-hover" id="detail-datatable">
                                        <thead>
                                            <tr>
                                                <th>カテゴリ</th>
                                                <th>分類</th>
                                                <th>製品</th>
                                                <th>モデル・型</th>
                                                <th>予定数量</th>
                                                <th>入庫数量</th>
                                                <th>入庫状況</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </fieldset>

                                <!-- ********************************** -->
                                <!-- 操作アクション（モデル）           -->
                                <!-- ********************************** -->
                                <div class="widget-actions" id="detail-actions">
                                    <div class="widget-action hidden" data-app-action-key="view-detail-actions">
                                        <a href="javascript:void(0);" class="btn btn-success" data-app-action-key="edit-detail"><i class="fa fa-edit"></i>　編集</a>
                                    </div>
                                    <div class="widget-action" data-app-action-key="add-detail-actions">
                                        <a href="javascript:void(0);" class="btn btn-success" data-app-action-key="add-detail"><i class="fa fa-plus"></i>　追加</a>
                                    </div>
                                    <div class="widget-action hidden" data-app-action-key="edit-detail-actions">
                                        <a href="javascript:void(0);" class="btn btn-default" data-app-action-key="cancel-detail"><i class="fa fa-times"></i>　キャンセル</a>
                                        <a href="javascript:void(0);" class="btn btn-primary" data-app-action-key="save-detail"><i class="fa fa-save"></i>　保存</a>
                                    </div>
                                    <div class="widget-action hidden" data-app-action-key="delete-detail-actions">
                                        <a href="javascript:void(0);" class="btn btn-danger" data-app-action-key="delete-detail"><i class="fa fa-trash"></i>　削除</a>
                                    </div>
                                </div>

                                <!-- ********************************** -->
                                <!-- 入庫予定詳細                       -->
                                <!-- ********************************** -->
                                <header>
                                    入庫予定詳細
                                </header>

                                <fieldset>
                                    <div class="row">
                                        <!-- 分類 -->
                                        <section class="col col-4">
                                            <div class="form-group">
                                                <select name="detail.classification_id" id="detail_classification_id" class="select2 form-control input-sm"
                                                       data-app-form="form-detail" data-placeholder="分類を入力・選択してください"
                                                       disabled="disabled"
                                                        maxlength="20"
                                                       style="width:100%;"></select>
                                            </div>
                                        </section>
                                        <!-- 製品 -->
                                        <section class="col col-4">
                                            <div class="form-group">
                                                <select name="detail.product_id" id="detail_product_id" class="select2 form-control input-sm"
                                                       data-app-form="form-detail" data-placeholder="製品を入力・選択してください"
                                                       disabled="disabled"
                                                        maxlength="30"
                                                       style="width:100%;"></select>
                                            </div>
                                        </section>
                                        <!-- モデル -->
                                        <section class="col col-4">
                                            <div class="form-group">
                                                <select name="detail.product_model_id" id="detail_product_model_id" class="select2 form-control input-sm"
                                                       data-app-form="form-detail" data-placeholder="【任意】モデルを入力・選択してください"
                                                       disabled="disabled"
                                                        maxlength="30"
                                                       style="width:100%;"></select>
                                            </div>
                                        </section>
                                    </div>
                                    <div class="row">
                                        <!-- 予定数量 -->
                                        <section class="col col-4">
                                            <label class="input">
                                                <input type="number" name="detail.plan_count" id="detail_plan_count" class="input-sm"
                                                       data-app-form="form-detail"
                                                       placeholder="予定数量を整数（0以上）を入力してください"
                                                       min="0"
                                                       max="99999"
                                                       disabled="disabled">
                                            </label>
                                        </section>
                                        <!-- 保守期限日 -->
                                        <section class="col col-4">
                                            <label class="input">
                                                <i class="icon-append fa fa-calendar"></i>
                                                <input type="text" name="detail.support_limit_date" id="detail_support_limit_date" class="input-sm datepicker"
                                                       data-dateformat="yy/mm/dd"
                                                       data-app-form="form-detail"
                                                       placeholder="保守期限日　－　yyyy/mm/dd形式で入力してください"
                                                       maxlength="10"
                                                       disabled="disabled">
                                            </label>
                                        </section>
                                    </div>
                                </fieldset>

                                <!-- ********************************** -->
                                <!-- 返却情報                           -->
                                <!-- ********************************** -->
                                <!-- input-asset-back-id -->
                                <div id="input-back-id" class="hidden">
                                    <header>
                                        資産返却内容
                                    </header>

                                    <fieldset>
                                        <div class="row">
                                            <!-- 返却者(組織) -->
                                            <section class="col col-4">
                                                <div class="form-group">
                                                    <select name="back.req_organization_id" id="back_req_organization_id" class="select2 form-control input-sm"
                                                           data-app-form="form-detail" data-placeholder="【任意】返却者(組織)"
                                                           disabled="disabled"
                                                           style="width:100%;"></select>
                                                </div>
                                            </section>
                                            <!-- 返却者(ユーザー) -->
                                            <section class="col col-4">
                                                <div class="form-group">
                                                    <select name="back.req_user_id" id="back_req_user_id" class="select2 form-control input-sm"
                                                           data-app-form="form-detail" data-placeholder="依頼者(ユーザー)"
                                                           disabled="disabled"
                                                           style="width:100%;"></select>
                                                </div>
                                            </section>
                                            <!-- 受付者 -->
                                            <section class="col col-4">
                                                <div class="form-group">
                                                    <select name="back.rcv_suser_id" id="back_rcv_suser_id" class="select2 form-control input-sm"
                                                           data-app-form="form-detail" data-placeholder="受付者"
                                                           disabled="disabled"
                                                           style="width:100%;"></select>
                                                </div>
                                            </section>
                                        </div>
                                        <div class="row">
                                            <!-- シリアル番号 -->
                                            <section class="col col-6">
                                                <label class="input">
                                                    <input type="text" name="back.serial_no" id="back_serial_no" class="input-sm"
                                                           data-app-form="form-detail" placeholder="返却するシリアル番号（数量管理品以外／資産管理番号未入力時のみ）"
                                                           maxlength=120
                                                           disabled="disabled">
                                                </label>
                                            </section>
                                            <!-- 資産管理番号 -->
                                            <section class="col col-6">
                                                <label class="input">
                                                    <input type="text" name="back.asset_no" id="back_asset_no" class="input-sm"
                                                           data-app-form="form-detail" placeholder="返却する資産管理番号（数量管理品以外／シリアル番号未入力時のみ）"
                                                           maxlength=60
                                                           disabled="disabled">
                                                </label>
                                            </section>
                                        </div>
                                        <!-- 返却理由 -->
                                        <section>
                                            <label class="textarea textarea-resizable">
                                                <textarea name="back.assetback_reason" id="back_assetback_reason" rows="4" class="custom-scroll"
                                                          data-app-form="form-detail" placeholder="返却理由"
                                                          disabled="disabled"></textarea>
                                            </label>
                                        </section>
                                    </fieldset>

                                    <!-- End input-asset-back-id -->
                            </div>

                            <!-- End form -->
                            <input type="hidden" name="detail.instock_plan_id" id="detail_instock_plan_id" data-app-form="form-detail">
                            <input type="hidden" name="back.id" id="back_id" data-app-form="form-detail">
                            <input type="hidden" name="detail.id" id="detail_id" data-app-form="form-detail">
                            <input type="hidden" name="instock_type" value="<?= $this->App->conf('WNote.DB.Instock.InstockType.new') ?>">
                            <?= $this->Form->end() ?>

                            <!-- End plan detail contents -->
                        </div>

                        <!-- End DETAILS widget body -->
                    </div>

                    <!-- End content -->
                </div>

                <!-- End widget ID -->
            </div>

            <!-- End DETAILS widget -->
        </article>

        <!-- End widget grid row -->
    </div>

    <!-- End widget grid-->
</section>

<!-- hidden -->
<input type="hidden" id="instock_kbn_new" value="<?= $this->App->conf('WNote.DB.Instock.InstockKbn.new') ?>">

<!-- load script -->
<?= $this->element('Common/load-datatable') ?>
<?php $this->Html->script('wnote/libs/wnote.lib.form.js', ['block' => true]); ?>
<?php $this->Html->script('wnote/instock/instock_plans.list_new.js', ['block' => true]); ?>
<?php $this->Html->script('wnote/instock/instock_plans.list_new.details.js', ['block' => true]); ?>
