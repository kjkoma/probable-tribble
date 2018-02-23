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
$this->assign('title', '出庫予定一覧');
$this->Breadcrumbs->add('Home', ['prefix' => false, 'controller' => 'Home', 'action' => 'home']);
$this->Breadcrumbs->add('出庫', '#');
$this->Breadcrumbs->add('出庫予定一覧', ['controller' => 'PickingPlans', 'action' => 'entry']);

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
                 data-widget-sortable="false">

                <!-- DETAILS widget header -->
                <header role="heading">
                    <span class="widget-icon"> <i class="fa fa-lg fa-search"></i> </span>
                    <h2>出庫予定検索</h2>
                </header>

                <!-- content -->
                <div role="content">

                    <!-- DETAILS widget body -->
                    <div class="widget-body">

                        <!-- form -->
                        <?= $this->Form->create(null, ['id' => 'form-condition', 'type' => 'post', 'class' => "smart-form"]) ?>

                            <fieldset>
                                <div class="row">
                                    <!-- 出庫予定日(From) -->
                                    <section class="col col-3">
                                        <label class="input">
                                            <i class="icon-append fa fa-calendar"></i>
                                            <input type="text" name="cond.plan_date_from" id="cond_plan_date_from" class="input-sm datepicker"
                                                   data-dateformat="yy/mm/dd"
                                                   data-app-form="form-condition"
                                                   placeholder="出庫予定日（From）－yyyy/mm/dd形式">
                                        </label>
                                    </section>
                                    <!-- 出庫予定日(To) -->
                                    <section class="col col-3">
                                        <label class="input">
                                            <i class="icon-append fa fa-calendar"></i>
                                            <input type="text" name="cond.plan_date_to" id="cond_plan_date_to" class="input-sm datepicker"
                                                   data-dateformat="yy/mm/dd"
                                                   data-app-form="form-condition"
                                                   placeholder="出庫予定日（To）－yyyy/mm/dd形式">
                                        </label>
                                    </section>
                                    <!-- 出庫予定有無 -->
                                    <section class="col col-3">
                                        <label class="select">
                                            <select name="cond.has_picking_plan" id="cond_has_picking_plan" class="input-sm"
                                                   data-app-form="form-condition">
                                                <option value="1" selected="selected">すべて</option>
                                                <option value="2">出庫予定あり</option>
                                                <option value="3">出庫予定なし</option>
                                            </select>
                                        </label>
                                    </section>
                                    <!-- 状況 -->
                                    <section class="col col-3">
                                        <?= $this->element('Parts/select-picking-sts', [
                                            'snames'  => $pickingSts,
                                            'name'     => 'cond.plan_sts',
                                            'id'       => 'cond_plan_sts',
                                            'form'     => 'form-condition',
                                            'blank'    => true,
                                            'disabled' => false
                                        ]) ?>
                                    </section>
                                </div>
                                <div class="row">
                                    <!-- 出庫依頼日(From) -->
                                    <section class="col col-3">
                                        <label class="input">
                                            <i class="icon-append fa fa-calendar"></i>
                                            <input type="text" name="cond.req_date_from" id="cond_req_date_from" class="input-sm datepicker"
                                                   data-dateformat="yy/mm/dd"
                                                   data-app-form="form-condition"
                                                   maxlength="10"
                                                   placeholder="出庫依頼日（From）－yyyy/mm/dd形式">
                                        </label>
                                    </section>
                                    <!-- 出庫依頼日(To) -->
                                    <section class="col col-3">
                                        <label class="input">
                                            <i class="icon-append fa fa-calendar"></i>
                                            <input type="text" name="cond.req_date_to" id="cond_req_date_to" class="input-sm datepicker"
                                                   data-dateformat="yy/mm/dd"
                                                   data-app-form="form-condition"
                                                   maxlength="10"
                                                   placeholder="出庫依頼日（To）－yyyy/mm/dd形式">
                                        </label>
                                    </section>
                                    <!-- 出庫依頼者 -->
                                    <section class="col col-3">
                                        <div class="form-group">
                                            <select name="cond.req_user_id" id="cond_req_user_id" class="select2 form-control input-sm"
                                                   data-app-form="form-condition" data-placeholder="依頼者"
                                                   maxlength="20"
                                                   style="width:100%;"></select>
                                        </div>
                                    </section>
                                    <!-- 使用者 -->
                                    <section class="col col-3">
                                        <div class="form-group">
                                            <select name="cond.use_user_id" id="cond_use_user_id" class="select2 form-control input-sm"
                                                   data-app-form="form-condition" data-placeholder="使用者"
                                                   maxlength="20"
                                                   style="width:100%;"></select>
                                        </div>
                                    </section>
                                </div>

                                <div class="row">
                                    <!-- 到着依頼日(From) -->
                                    <section class="col col-3">
                                        <label class="input">
                                            <i class="icon-append fa fa-calendar"></i>
                                            <input type="text" name="cond.arv_date_from" id="cond_arv_date_from" class="input-sm datepicker"
                                                   data-dateformat="yy/mm/dd"
                                                   data-app-form="form-condition"
                                                   maxlength="10"
                                                   placeholder="到着希望日（From）－yyyy/mm/dd形式">
                                        </label>
                                    </section>
                                    <!-- 到着依頼日(To) -->
                                    <section class="col col-3">
                                        <label class="input">
                                            <i class="icon-append fa fa-calendar"></i>
                                            <input type="text" name="cond.arv_date_to" id="cond_arv_date_to" class="input-sm datepicker"
                                                   data-dateformat="yy/mm/dd"
                                                   data-app-form="form-condition"
                                                   maxlength="10"
                                                   placeholder="到着希望日（To）－yyyy/mm/dd形式">
                                        </label>
                                    </section>
                                    <!-- 出庫件名 -->
                                    <section class="col col-6">
                                        <label class="input">
                                            <input type="text" name="cond.name" id="cond_name" class="input-sm"
                                                   data-app-form="form-condition"
                                                   maxlength="60"
                                                   placeholder="出庫件名">
                                        </label>
                                    </section>
                                </div>

                                <div class="row">
                                    <!-- 出庫作業者 -->
                                    <section class="col col-3">
                                        <div class="form-group">
                                            <select name="cond.work_suser_id" id="cond_work_suser_id" class="select2 form-control input-sm"
                                                   data-app-form="form-condition" data-placeholder="出庫作業者"
                                                   maxlength="20"
                                                   style="width:100%;"></select>
                                        </div>
                                    </section>
                                    <!-- 受付者 -->
                                    <section class="col col-3">
                                        <div class="form-group">
                                            <select name="cond.rcv_suser_id" id="cond_rcv_suser_id" class="select2 form-control input-sm"
                                                   data-app-form="form-condition" data-placeholder="受付者"
                                                   maxlength="20"
                                                   style="width:100%;"></select>
                                        </div>
                                    </section>
                                    <!-- 申請番号 -->
                                    <section class="col col-3">
                                        <label class="input">
                                            <input type="text" name="cond.apply_no" id="cond_apply_no" class="input-sm"
                                                   maxlength="60"
                                                   data-app-form="form-condition"
                                                   placeholder="申請番号">
                                        </label>
                                    </section>
                                    <!-- シリアル番号 -->
                                    <section class="col col-3">
                                        <label class="input">
                                            <input type="text" name="cond.serial_no" id="cond_serial_no" class="input-sm"
                                                   data-app-form="form-condition"
                                                   maxlength="60"
                                                   placeholder="シリアル番号">
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
                    <h2>出庫依頼一覧</h2>
                    <div class="widget-toolbar" role="menu">
                        <a href="javascript:void(0);" class="btn btn-info disabled" data-app-action-key="download"><i class="fa fa-download"></i>　ダウンロード</a>
                    </div>
                </header>

                <!-- content -->
                <div role="content">

                    <table class="table table-striped table-bordered table-hover" id="plans-datatable">
                        <thead>
                            <tr>
                                <th>区分</th>
                                <th>状況</th>
                                <th>予定日</th>
                                <th>依頼日</th>
                                <th>依頼者</th>
                                <th>希望日</th>
                                <th>受付者</th>
                                <th>申請番号</th>
                                <th>作業者</th>
                                <th>カテゴリ</th>
                                <th>キッティング</th>
                                <th>シリアル</th>
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

    <!-- widget grid row -->
    <div class="row">

        <!-- ********************************** -->
        <!-- Picking Plan Info                  -->
        <!-- ********************************** -->

        <!-- DETAIL widget -->
        <article class="col-sm-12 col-md-4 sortable-grid ui-sortable">

            <!-- widget ID -->
            <div class="jarviswidget jarviswidget-color-blueDark hidden" id="wid-id-plan"
                 data-widget-deletebutton="false"
                 data-widget-editbutton="false"
                 data-widget-colorbutton="false"
                 data-widget-togglebutton="false"
                 data-widget-sortable="false">

                <!-- DETAILS widget header -->
                <header role="heading">
                    <span class="widget-icon"> <i class="fa fa-lg fa-info"></i> </span>
                    <h2>出庫依頼内容</h2>
                </header>

                <!-- content -->
                <div role="content">

                    <!-- DETAILS widget body -->
                    <div class="widget-body">

                        <!-- form -->
                        <?= $this->Form->create(null, ['id' => 'form-picking-plan', 'type' => 'post', 'class' => "smart-form"]) ?>

                            <header>
                                依頼情報
                            </header>

                            <fieldset>
                                <section>
                                    <dl class="dl-horizontal">
                                        <dt>依頼番号(申請番号)：</dt>
                                            <dd name="picking_plan.apply_no" id="pickingPlan_apply_no"></dd>
                                        <dt>依頼日(申請日)：</dt>
                                            <dd name="picking_plan.req_date" id="pickingPlan_req_date"></dd>
                                        <dt>依頼者(組織)：</dt>
                                            <dd name="picking_plan.req_organization_name" id="pickingPlan_req_organization_name"></dd>
                                        <dt>依頼者：</dt>
                                            <dd name="picking_plan.req_user_name" id="pickingPlan_req_user_name"></dd>
                                        <dt>依頼者(社員番号)：</dt>
                                            <dd name="picking_plan.req_emp_no" id="pickingPlan_req_emp_no"></dd>
                                    </dl>
                                </section>
                            </fieldset>

                            <header>
                                使用者情報
                            </header>

                            <fieldset>
                                <section>
                                    <dl class="dl-horizontal">
                                        <dt>使用者(組織)：</dt>
                                            <dd name="picking_plan.use_organization_name" id="pickingPlan_use_organization_name"></dd>
                                        <dt>使用者：</dt>
                                            <dd name="picking_plan.use_user_name" id="pickingPlan_use_user_name"></dd>
                                        <dt>使用者(社員番号)：</dt>
                                            <dd name="picking_plan.use_emp_no" id="pickingPlan_use_emp_no"></dd>
                                    </dl>
                                </section>
                            </fieldset>

                            <header>
                                出庫情報
                            </header>

                            <fieldset>
                                <section>
                                    <dl class="dl-horizontal">
                                        <dt>出庫先(組織)：</dt>
                                            <dd name="picking_plan.dlv_organization_name" id="pickingPlan_dlv_organization_name"></dd>
                                        <dt>出庫先(ユーザー)：</dt>
                                            <dd name="picking_plan.dlv_user_name" id="pickingPlan_dlv_user_name"></dd>
                                        <dt>出庫先(社員番号)：</dt>
                                            <dd name="picking_plan.dlv_emp_no" id="pickingPlan_dlv_emp_no"></dd>
                                        <dt>宛名：</dt>
                                            <dd name="picking_plan.dlv_name" id="pickingPlan_dlv_name"></dd>
                                        <dt>出庫先(連絡先)：</dt>
                                            <dd name="picking_plan.dlv_tel" id="pickingPlan_dlv_tel"></dd>
                                        <dt>出庫先(郵便番号)：</dt>
                                            <dd name="picking_plan.dlv_zip" id="pickingPlan_dlv_zip"></dd>
                                        <dt>出庫先(住所)：</dt>
                                            <dd name="picking_plan.dlv_address" id="pickingPlan_dlv_address"></dd>
                                        <dt>到着希望日：</dt>
                                            <dd name="picking_plan.arv_date" id="pickingPlan_arv_date"></dd>
                                        <dt>到着希望時間：</dt>
                                            <dd name="picking_plan.arv_time_kbn_name" id="pickingPlan_arv_time_kbn_name"></dd>
                                        <dt>到着希望メモ：</dt>
                                            <dd name="picking_plan.arv_remarks" id="pickingPlan_arv_remarks"></dd>
                                    </dl>
                                </section>
                            </fieldset>

                            <header>
                                受付・その他
                            </header>

                            <fieldset>
                                <section>
                                    <dl class="dl-horizontal">
                                        <dt>出庫区分：</dt>
                                            <dd name="picking_plan.picking_kbn_name" id="pickingPlan_picking_kbn_name"></dd>
                                        <dt>出庫状況：</dt>
                                            <dd name="picking_plan.plan_sts_name" id="pickingPlan_plan_sts_name"></dd>
                                        <dt>受付日：</dt>
                                            <dd name="picking_plan.rcv_date" id="pickingPlan_rcv_date"></dd>
                                        <dt>受付者：</dt>
                                            <dd name="picking_plan.rcv_suser_name" id="pickingPlan_rcv_suser_name"></dd>
                                        <dt>出庫予定日：</dt>
                                            <dd name="picking_plan.plan_date" id="pickingPlan_plan_date"></dd>
                                        <dt>出庫件名：</dt>
                                            <dd name="picking_plan.name" id="pickingPlan_name"></dd>
                                        <dt>出庫理由／出庫メモ：</dt>
                                            <dd name="picking_plan.picking_reason" id="pickingPlan_picking_reason"></dd>
                                        <dt>出庫備考：</dt>
                                            <dd name="picking_plan.remarks" id="pickingPlan_remarks"></dd>
                                    </dl>
                                </section>
                            </fieldset>

                            <header>
                                出庫依頼内容
                            </header>

                            <fieldset>
                                <section>
                                    <dl class="dl-horizontal">
                                        <dt>カテゴリ：</dt>
                                            <dd name="picking_plan.category_name" id="pickingPlan_category_name"></dd>
                                        <dt>再利用区分：</dt>
                                            <dd name="picking_plan.reuse_kbn" id="pickingPlan_reuse_kbn_name"></dd>
                                        <dt>キッティングパターン：</dt>
                                            <dd name="picking_plan.kitting_pattern_name" id="pickingPlan_kitting_pattern_name"></dd>
                                    </dl>
                                </section>
                            </fieldset>

                        <!-- End form -->
                        <?= $this->Form->end() ?>

                        <!-- End DETAILS widget body -->
                    </div>

                    <!-- End content -->
                </div>

                <!-- End widget ID -->
            </div>

            <!-- End DETAILS list widget -->
        </article>

        <!-- ********************************** -->
        <!-- Picking Plan Entry                 -->
        <!-- ********************************** -->

        <!-- DETAIL widget -->
        <article class="col-sm-12 col-md-8 sortable-grid ui-sortable">

            <!-- widget ID -->
            <div class="jarviswidget jarviswidget-color-blueDark hidden" id="wid-id-entry"
                 data-widget-deletebutton="false"
                 data-widget-editbutton="false"
                 data-widget-colorbutton="false"
                 data-widget-togglebutton="false"
                 data-widget-sortable="false">

                <!-- DETAILS widget header -->
                <header role="heading">
                    <span class="widget-icon"> <i class="fa fa-lg fa-file"></i> </span>
                    <h2>出庫情報登録</h2>
                    <div class="widget-toolbar" role="menu" data-app-action-key="entry-edit-actions">
                        <a href="javascript:void(0);" class="btn btn-primary" data-app-action-key="save-entry">保存</a>
                    </div>
                    <div class="widget-toolbar hidden" role="menu" data-app-action-key="entry-picking-actions">
                        <a href="javascript:void(0);" class="btn btn-primary" data-app-action-key="add-picking">出庫登録</a>
                    </div>
                </header>

                <!-- content -->
                <div role="content">

                    <!-- DETAILS widget body -->
                    <div class="widget-body">

                        <!-- form -->
                        <?= $this->Form->create(null, ['id' => 'form-entry', 'type' => 'post', 'class' => "smart-form"]) ?>

                            <header>
                                出庫情報
                            </header>

                            <fieldset>
                                <div class="row">
                                    <!-- 出庫予定日 -->
                                    <section class="col col-4">
                                        <label class="input">
                                            <i class="icon-append fa fa-calendar"></i>
                                            <input type="text" name="entry.plan_date" id="entry_plan_date" class="input-sm datepicker"
                                                   data-dateformat="yy/mm/dd"
                                                   data-app-form="form-entry"
                                                   placeholder="出庫予定日－yyyy/mm/dd形式（例：2017/10/09）">
                                        </label>
                                    </section>
                                    <!-- 作業者 -->
                                    <section class="col col-4">
                                        <div class="form-group">
                                            <select name="entry.work_suser_id" id="entry_work_suser_id" class="select2 form-control input-sm"
                                                   data-app-form="form-entry" data-placeholder="作業者(ユーザー)"
                                                   style="width:100%;"></select>
                                        </div>
                                    </section>
                                    <!-- シリアル番号 -->
                                    <section class="col col-4">
                                        <label class="input">
                                            <input type="text" name="entry.serial_no" id="entry_serial_no" class="input-sm"
                                                   data-app-form="form-entry" placeholder="シリアル番号（数量管理品は指定不可）">
                                        </label>
                                    </section>
                                </div>
                                <div class="row">
                                    <!-- 分類 -->
                                    <section class="col col-4">
                                        <div class="form-entry">
                                            <select name="entry.classification_id" id="entry_classification_id" class="select2 form-control input-sm"
                                                   data-app-form="form-entry" data-placeholder="分類"
                                                   style="width:100%;"></select>
                                        </div>
                                    </section>
                                    <!-- 製品 -->
                                    <section class="col col-4">
                                        <div class="form-entry">
                                            <select name="entry.product_id" id="entry_product_id" class="select2 form-control input-sm"
                                                   data-app-form="form-entry" data-placeholder="製品"
                                                   style="width:100%;"></select>
                                        </div>
                                    </section>
                                    <!-- モデル -->
                                    <section class="col col-4">
                                        <div class="form-entry">
                                            <select name="entry.product_model_id" id="entry_product_model_id" class="select2 form-control input-sm"
                                                   data-app-form="form-entry" data-placeholder="モデル"
                                                   style="width:100%;"></select>
                                        </div>
                                    </section>
                                </div>
                                <!-- 出庫件名-->
                                <section>
                                    <label class="input">
                                        <input type="text" name="entry.name" id="entry_name" class="input-sm"
                                               data-app-form="form-entry" placeholder="【任意】出庫件名－最大60文字／未入力時は資産名＋「出庫」となります">
                                    </label>
                                </section>
                                <!-- 出庫備考 -->
                                <section>
                                    <label class="textarea textarea-resizable">
                                        <textarea name="entry.remarks" id="entry_remarks" rows="4" class="custom-scroll"
                                                  data-app-form="form-entry" placeholder="出庫備考"></textarea>
                                    </label>
                                </section>
                            </fieldset>

                            <header>
                                資産情報
                            </header>

                            <fieldset>
                                <div class="row">
                                    <!-- 資産管理番号 -->
                                    <section class="col col-4">
                                        <label class="input">
                                            <input type="text" name="asset.asset_no" id="entry_asset_no" class="input-sm"
                                                   data-app-form="form-entry" placeholder="資産管理番号（数量管理品は指定不可）">
                                        </label>
                                    </section>
                                </div>
                                <!-- 資産備考 -->
                                <section>
                                    <label class="textarea textarea-resizable">
                                        <textarea name="asset.remarks" id="entry_asset_remarks" rows="4" class="custom-scroll"
                                                  data-app-form="form-entry" placeholder="資産備考（数量管理品は指定不可）"></textarea>
                                    </label>
                                </section>
                            </fieldset>

                        <!-- End form -->
                        <?= $this->Form->end() ?>

                    <!-- End content -->
                </div>

                <!-- End widget ID -->
            </div>

            <!-- End DETAILS list widget -->
        </article>

        <!-- ********************************** -->
        <!-- Picking Plan Cancel                -->
        <!-- ********************************** -->

        <!-- DETAIL widget -->
        <article class="col-sm-12 col-md-8 sortable-grid ui-sortable">

            <!-- widget ID -->
            <div class="jarviswidget jarviswidget-color-blueDark hidden" id="wid-id-cancel"
                 data-widget-deletebutton="false"
                 data-widget-editbutton="false"
                 data-widget-colorbutton="false"
                 data-widget-togglebutton="false"
                 data-widget-sortable="false">

                <!-- DETAILS widget header -->
                <header role="heading">
                    <span class="widget-icon"> <i class="fa fa-lg fa-file"></i> </span>
                    <h2>出庫予定取消確認／取消解除</h2>
                    <div class="widget-toolbar" role="menu" data-app-action-key="cancel-edit-actions">
                        <a href="javascript:void(0);" class="btn btn-info" data-app-action-key="cancel-restore">取消解除</a>
                        <a href="javascript:void(0);" class="btn btn-danger" data-app-action-key="cancel-fix">取消確認</a>
                    </div>
                </header>

                <!-- content -->
                <div role="content">

                    <!-- DETAILS widget body -->
                    <div class="widget-body">

                        <!-- form -->
                        <?= $this->Form->create(null, ['id' => 'form-cancel', 'type' => 'post', 'class' => "smart-form"]) ?>

                            <header>
                                取消理由
                            </header>

                            <fieldset>
                                <!-- 取消理由 -->
                                <section>
                                    <label class="textarea textarea-resizable">
                                        <textarea name="cancel.cancel_reason" id="cancel_cancel_reason" rows="4" class="custom-scroll"
                                                  data-app-form="form-cancel" placeholder="取消理由"></textarea>
                                    </label>
                                </section>
                            </fieldset>

                        <!-- End form -->
                        <?= $this->Form->end() ?>

                    <!-- End content -->
                </div>

                <!-- End widget ID -->
            </div>

            <!-- End DETAILS list widget -->
        </article>

        <!-- ********************************** -->
        <!-- Asset Info                         -->
        <!-- ********************************** -->
        <article class="col-sm-12 col-md-8 sortable-grid ui-sortable">
            <?= $this->element('Asset/asset', [
                'conf' => [
                    'asset'  => 'view',
                    'attr'   => 'edit',
                    'user'   => true,
                    'repair' => true,
                    'stock'  => true,
                    'rental' => true,
                    'hidden' => true
                ]
            ]) ?>
        </article>

        <!-- End list widget grid row -->
    </div>

    <!-- End widget grid-->
</section>

<!-- download form -->
<?= $this->Form->create(null, ['id' => 'form-download', 'type' => 'post', 'class' => "smart-form hidden", 'url' => ['controller' => 'picking-plans', 'action' => 'download-plan']]) ?>
<?= $this->Form->end() ?>

<input type="hidden" id="plan_sts_not"       value="<?= $this->App->conf('WNote.DB.Picking.PickingSts.not') ?>">
<input type="hidden" id="plan_sts_cancel"    value="<?= $this->App->conf('WNote.DB.Picking.PickingSts.cancel') ?>">
<input type="hidden" id="plan_sts_work"      value="<?= $this->App->conf('WNote.DB.Picking.PickingSts.work') ?>">
<input type="hidden" id="picking_kbn_new"    value="<?= $this->App->conf('WNote.DB.Picking.PickingKbn.new') ?>">
<input type="hidden" id="asset_type_asset"   value="<?= $this->App->conf('WNote.DB.Assets.AssetType.asset') ?>">
<input type="hidden" id="asset_type_count"   value="<?= $this->App->conf('WNote.DB.Assets.AssetType.count') ?>">

<!-- load script -->
<?= $this->element('Common/load-datatable') ?>
<?php $this->Html->script('wnote/libs/wnote.lib.form.js', ['block' => true]); ?>
<?= $this->element('Asset/load-asset') ?>
<?php $this->Html->script('wnote/picking/picking_plans.list.js', ['block' => true]); ?>
<?php $this->Html->script('wnote/picking/picking_plans.list.entry.js', ['block' => true]); ?>
<?php $this->Html->script('wnote/picking/picking_plans.list.cancel.js', ['block' => true]); ?>
<?php $this->Html->script('wnote/picking/picking_plans.list.plan.js', ['block' => true]); ?>

