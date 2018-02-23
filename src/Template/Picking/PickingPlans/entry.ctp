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
$this->assign('title', '出庫登録');
$this->Breadcrumbs->add('Home', ['prefix' => false, 'controller' => 'Home', 'action' => 'home']);
$this->Breadcrumbs->add('出庫', '#');
$this->Breadcrumbs->add('出庫登録', ['controller' => 'PickingPlans', 'action' => 'entry']);

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
                    <h2>出庫依頼一覧</h2>
                </header>

                <!-- content -->
                <div role="content">

                    <table class="table table-striped table-bordered table-hover" id="plans-datatable">
                        <thead>
                            <tr>
                                <th>出庫区分</th>
                                <th>状況</th>
                                <th>申請番号</th>
                                <th>依頼日</th>
                                <th>依頼者</th>
                                <th>使用者</th>
                                <th>出庫先</th>
                                <th>受付者</th>
                                <th>カテゴリ</th>
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
                    <h2>出庫依頼登録・編集</h2>
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
                        <a href="javascript:void(0);" class="btn btn-danger" data-app-action-key="delete"><i class="fa fa-trash"></i>　取消</a>
                    </div>
                </header>

                <!-- content -->
                <div role="content">

                    <!-- DETAILS widget body -->
                    <div class="widget-body">

                        <!-- form -->
                        <?= $this->Form->create(null, ['id' => 'form-plan', 'type' => 'post', 'class' => "smart-form"]) ?>

                            <header>
                                出庫区分
                            </header>

                            <fieldset>
                                <div class="row">
                                    <section class="col col-4">
                                        <?= $this->element('Parts/select-picking-kbn', [
                                            'snames'  => $pickingKbn,
                                            'name'    => 'plan.picking_kbn',
                                            'id'      => 'picking_kbn',
                                            'form'    => 'form-plan',
                                            'action'  => 'change-picking_kbn',
                                            'default' => '1'
                                        ]) ?>
                                    </section>
                                </div>
                            </fieldset>

                            <header>
                                依頼情報
                            </header>

                            <fieldset>
                                <div class="row">
                                    <!-- 依頼番号(申請番号) -->
                                    <section class="col col-4">
                                        <label class="input">
                                            <input type="text" name="plan.apply_no" id="apply_no" class="input-sm"
                                                   data-app-form="form-plan" placeholder="依頼番号(申請番号)"
                                                   maxlength=60
                                                   disabled="disabled">
                                        </label>
                                    </section>
                                    <!-- 依頼日（申請日） -->
                                    <section class="col col-4">
                                        <label class="input">
                                            <i class="icon-append fa fa-calendar"></i>
                                            <input type="text" name="plan.req_date" id="req_date" class="input-sm datepicker"
                                                   data-dateformat="yy/mm/dd"
                                                   data-app-form="form-plan"
                                                   maxlength=10
                                                   placeholder="【必須】依頼日(申請日)－yyyy/mm/dd形式（例：2017/10/09）"
                                                   disabled="disabled">
                                        </label>
                                    </section>
                                </div>
                                <div class="row">
                                    <!-- 依頼者(組織) -->
                                    <section class="col col-4">
                                        <div class="form-group">
                                            <select name="plan.req_organization_id" id="req_organization_id" class="select2 form-control input-sm"
                                                   data-app-form="form-plan" data-placeholder="依頼者(組織)"
                                                   disabled="disabled"></select>
                                        </div>
                                    </section>
                                    <!-- 依頼者(ユーザー) -->
                                    <section class="col col-4">
                                        <div class="form-group">
                                            <select name="plan.req_user_id" id="req_user_id" class="select2 form-control input-sm"
                                                   data-app-form="form-plan" data-placeholder="【必須】依頼者(ユーザー)"
                                                   disabled="disabled"
                                                   style="width:100%;"></select>
                                        </div>
                                    </section>
                                    <!-- 依頼者(社員番号) -->
                                    <section class="col col-4">
                                        <label class="input">
                                            <input type="text" name="plan.req_emp_no" id="req_emp_no" class="input-sm"
                                                   data-app-form="form-plan" placeholder="依頼者(社員番号)－最大20文字"
                                                   maxlength=20
                                                   disabled="disabled">
                                        </label>
                                    </section>
                                </div>
                            </fieldset>

                            <header>
                                使用者情報
                            </header>

                            <fieldset>
                                <div class="row">
                                    <!-- 使用者(組織) -->
                                    <section class="col col-4">
                                        <div class="form-group">
                                            <select name="plan.use_organization_id" id="use_organization_id" class="select2 form-control input-sm"
                                                   data-app-form="form-plan" data-placeholder="使用者(組織)"
                                                   disabled="disabled"></select>
                                        </div>
                                    </section>
                                    <!-- 使用者(ユーザー) -->
                                    <section class="col col-4">
                                        <div class="form-group">
                                            <select name="plan.use_user_id" id="use_user_id" class="select2 form-control input-sm"
                                                   data-app-form="form-plan" data-placeholder="【必須】使用者(ユーザー)"
                                                   disabled="disabled"
                                                   style="width:100%;"></select>
                                        </div>
                                    </section>
                                    <!-- 使用者(社員番号) -->
                                    <section class="col col-4">
                                        <label class="input">
                                            <input type="text" name="plan.use_emp_no" id="use_emp_no" class="input-sm"
                                                   data-app-form="form-plan" placeholder="使用者(社員番号)－最大20文字"
                                                   disabled="disabled">
                                        </label>
                                    </section>
                                </div>
                            </fieldset>

                            <header>
                                出庫情報
                            </header>

                            <fieldset>
                                <div class="row">
                                    <!-- 出庫先(組織) -->
                                    <section class="col col-4">
                                        <div class="form-group">
                                            <select name="plan.dlv_organization_id" id="dlv_organization_id" class="select2 form-control input-sm"
                                                   data-app-form="form-plan" data-placeholder="出庫先(組織)"
                                                   disabled="disabled"></select>
                                        </div>
                                    </section>
                                    <!-- 出庫先(ユーザー) -->
                                    <section class="col col-4">
                                        <div class="form-group">
                                            <select name="plan.dlv_user_id" id="dlv_user_id" class="select2 form-control input-sm"
                                                   data-app-form="form-plan" data-placeholder="【必須】出庫先(ユーザー)"
                                                   disabled="disabled"
                                                   style="width:100%;"></select>
                                        </div>
                                    </section>
                                    <!-- 出庫先(社員番号) -->
                                    <section class="col col-4">
                                        <label class="input">
                                            <input type="text" name="plan.dlv_emp_no" id="dlv_emp_no" class="input-sm"
                                                   data-app-form="form-plan" placeholder="出庫先(社員番号)－最大20文字"
                                                   maxlength=20
                                                   disabled="disabled">
                                        </label>
                                    </section>
                                </div>
                                <div class="row">
                                    <!-- 出庫先(宛) -->
                                    <section class="col col-8">
                                        <label class="input">
                                            <input type="text" name="plan.dlv_name" id="dlv_name" class="input-sm"
                                                   data-app-form="form-plan" placeholder="宛先名称を入力してください－最大60文字"
                                                   maxlength=60
                                                   disabled="disabled">
                                        </label>
                                    </section>
                                    <!-- 出庫先(連絡先) -->
                                    <section class="col col-4">
                                        <label class="input">
                                            <input type="text" name="plan.dlv_tel" id="dlv_tel" class="input-sm"
                                                   data-app-form="form-plan" placeholder="出庫先(連絡先)－例）03-5298-8866"
                                                   maxlength=20
                                                   disabled="disabled">
                                        </label>
                                    </section>
                                </div>
                                <div class="row">
                                    <!-- 出庫先(郵便番号) -->
                                    <section class="col col-3">
                                        <label class="input">
                                            <input type="text" name="plan.dlv_zip" id="dlv_zip" class="input-sm"
                                                   data-app-form="form-plan" placeholder="出庫先(郵便番号)－例）1010025"
                                                   maxlength=7
                                                   disabled="disabled">
                                        </label>
                                    </section>
                                    <!-- 出庫先(住所) -->
                                    <section class="col col-9">
                                        <label class="input">
                                            <input type="text" name="plan.dlv_address" id="dlv_address" class="input-sm"
                                                   data-app-form="form-plan" placeholder="【必須】出庫先(住所)－最大120文字"
                                                   maxlength=120
                                                   disabled="disabled">
                                        </label>
                                    </section>
                                </div>
                                <div class="row">
                                    <!-- 到着希望日 -->
                                    <section class="col col-4">
                                        <label class="input">
                                            <i class="icon-append fa fa-calendar"></i>
                                            <input type="text" name="plan.arv_date" id="arv_date" class="input-sm datepicker"
                                                   data-dateformat="yy/mm/dd"
                                                   data-app-form="form-plan"
                                                   maxlength=10
                                                   placeholder="到着希望日－yyyy/mm/dd形式（例：2017/10/09）"
                                                   disabled="disabled">
                                        </label>
                                    </section>
                                    <!-- 到着希望時間 -->
                                    <section class="col col-4">
                                        <?= $this->element('Parts/select-snames', [
                                            'snames'  => $timeKbn,
                                            'name'    => 'plan.arv_time_kbn',
                                            'id'      => 'arv_time_kbn',
                                            'form'    => 'form-plan',
                                            'blank'   => true,
                                            'default' => 1
                                        ]) ?>
                                    </section>
                                </div>
                                <!-- 到着希望メモ -->
                                <section>
                                    <label class="textarea textarea-resizable">
                                        <textarea name="plan.arv_remarks" id="arv_remarks" rows="3" class="custom-scroll"
                                                  data-app-form="form-plan" placeholder="到着希望メモ"
                                                  disabled="disabled"></textarea>
                                    </label>
                                </section>
                            </fieldset>

                            <header>
                                受付・その他
                            </header>

                            <fieldset>
                                <div class="row">
                                    <!-- 受付日 -->
                                    <section class="col col-4">
                                        <label class="input">
                                            <i class="icon-append fa fa-calendar"></i>
                                            <input type="text" name="plan.rcv_date" id="rcv_date" class="input-sm datepicker"
                                                   data-dateformat="yy/mm/dd"
                                                   data-app-form="form-plan"
                                                   maxlength=10
                                                   placeholder="【必須】受付日－yyyy/mm/dd形式（例：2017/10/09）"
                                                   disabled="disabled">
                                        </label>
                                    </section>
                                    <!-- 受付者 -->
                                    <section class="col col-4">
                                        <div class="form-group">
                                            <select name="plan.rcv_suser_id" id="rcv_suser_id" class="select2 form-control input-sm"
                                                   data-app-form="form-plan" data-placeholder="【必須】受付者"
                                                   disabled="disabled"
                                                   style="width:100%;"></select>
                                        </div>
                                    </section>
                                </div>
                                <!-- 出庫理由 -->
                                <section>
                                    <label class="textarea textarea-resizable">
                                        <textarea name="plan.picking_reason" id="picking_reason" rows="4" class="custom-scroll"
                                                  data-app-form="form-plan" placeholder="出庫理由／出庫メモ"
                                                  disabled="disabled"></textarea>
                                    </label>
                                </section>
                            </fieldset>

                            <header>
                                出庫依頼内容
                            </header>

                            <fieldset>
                                <div class="row">
                                    <!-- カテゴリ -->
                                    <section class="col col-3">
                                        <?= $this->element('Parts/select-categories', [
                                            'categories' => $categories,
                                            'name'    => 'plan.category_id',
                                            'id'      => 'category_id',
                                            'form'    => 'form-plan',
                                            'default' => ''
                                        ]) ?>
                                    </section>
                                    <!-- 再利用区分 -->
                                    <section class="col col-3">
                                        <?= $this->element('Parts/select-snames', [
                                            'snames'  => $reuseKbn,
                                            'name'    => 'plan.reuse_kbn',
                                            'id'      => 'reuse_kbn',
                                            'form'    => 'form-plan',
                                            'default' => 1
                                        ]) ?>
                                    </section>
                                    <!-- キッティングパターン -->
                                    <section class="col col-4">
                                        <div class="form-group">
                                            <select name="plan.kitting_pattern_id" id="kitting_pattern_id" class="select2 form-control input-sm"
                                                   data-app-form="form-plan" data-placeholder="キッティングパターン"
                                                   style="width:100%;"></select>
                                        </div>
                                    </section>
                                </div>
                            </fieldset>

                            <!-- exchange contents -->
                            <div id="exchange_contents" class="hidden">
                                <header>
                                    交換内容
                                </header>

                                <fieldset>
                                    <div class="row">
                                        <!-- 入庫予定日 -->
                                        <section class="col col-4">
                                            <label class="input">
                                                <i class="icon-append fa fa-calendar"></i>
                                                <input type="text" name="exchange.instock_plan_date" id="exchange_instock_plan_date" class="input-sm datepicker"
                                                       data-dateformat="yy/mm/dd"
                                                       data-app-form="form-plan"
                                                       maxlength=10
                                                       placeholder="入庫予定日－yyyy/mm/dd形式（例：2017/10/09）"
                                                       disabled="disabled">
                                            </label>
                                        </section>
                                        <!-- 資産管理番号 -->
                                        <section class="col col-4">
                                            <label class="input">
                                                <input type="text" name="exchange.asset_no" id="exchange_asset_no" class="input-sm"
                                                       data-app-form="form-plan" placeholder="入庫する資産管理番号（シリアル番号未入力時のみ）"
                                                       maxlength=60
                                                       disabled="disabled">
                                            </label>
                                        </section>
                                        <!-- シリアル番号 -->
                                        <section class="col col-4">
                                            <label class="input">
                                                <input type="text" name="exchange.serial_no" id="exchange_serial_no" class="input-sm"
                                                       data-app-form="form-plan" placeholder="入庫するシリアル番号（資産管理番号未入力時のみ）"
                                                       maxlength=120
                                                       disabled="disabled">
                                            </label>
                                        </section>
                                    </div>
                                    <!-- 交換理由 -->
                                    <section>
                                        <label class="textarea textarea-resizable">
                                            <textarea name="exchange.exchange_reason" id="exchange_exchange_reason" rows="4" class="custom-scroll"
                                                      data-app-form="form-plan" placeholder="交換理由／交換メモ"
                                                      disabled="disabled"></textarea>
                                        </label>
                                    </section>
                                </fieldset>

                                <!-- end exchange contents -->
                            </div>

                            <!-- repair contents -->
                            <div id="repair_contents" class="hidden">
                                <header>
                                    修理内容
                                </header>

                                <fieldset>
                                    <div class="row">
                                        <!-- 入庫予定日 -->
                                        <section class="col col-4">
                                            <label class="input">
                                                <i class="icon-append fa fa-calendar"></i>
                                                <input type="text" name="repair.instock_plan_date" id="repair_instock_plan_date" class="input-sm datepicker"
                                                       data-dateformat="yy/mm/dd"
                                                       data-app-form="form-plan"
                                                       maxlength=10
                                                       placeholder="入庫予定日－yyyy/mm/dd形式（例：2017/10/09）"
                                                       disabled="disabled">
                                            </label>
                                        </section>
                                        <!-- 資産管理番号 -->
                                        <section class="col col-4">
                                            <label class="input">
                                                <input type="text" name="repair.asset_no" id="repair_asset_no" class="input-sm"
                                                       data-app-form="form-plan" placeholder="入庫する資産管理番号（シリアル番号未入力時のみ）"
                                                       maxlength=60
                                                       disabled="disabled">
                                            </label>
                                        </section>
                                        <!-- シリアル番号 -->
                                        <section class="col col-4">
                                            <label class="input">
                                                <input type="text" name="repair.serial_no" id="repair_serial_no" class="input-sm"
                                                       data-app-form="form-plan" placeholder="入庫するシリアル番号（資産管理番号未入力時のみ）"
                                                       maxlength=120
                                                       disabled="disabled">
                                            </label>
                                        </section>
                                    </div>
                                    <div class="row">
                                        <!-- 故障区分 -->
                                        <section class="col col-4">
                                            <?= $this->element('Parts/select-snames', [
                                                'snames'  => $troubleKbn,
                                                'name'    => 'repair.trouble_kbn',
                                                'id'      => 'repair_trouble_kbn',
                                                'form'    => 'form-plan',
                                                'default' => 1
                                            ]) ?>
                                        </section>
                                        <!-- センドバック有無 -->
                                        <section class="col col-4">
                                            <?= $this->element('Parts/select-snames', [
                                                'snames'  => $sendbackKbn,
                                                'name'    => 'repair.sendback_kbn',
                                                'id'      => 'repair_sendback_kbn',
                                                'form'    => 'form-plan',
                                                'default' => 1
                                            ]) ?>
                                        </section>
                                        <!-- データ抽出有無 -->
                                        <section class="col col-4">
                                            <?= $this->element('Parts/select-snames', [
                                                'snames'  => $datapickKbn,
                                                'name'    => 'repair.datapick_kbn',
                                                'id'      => 'repair_datapick_kbn',
                                                'form'    => 'form-plan',
                                                'default' => 1
                                            ]) ?>
                                        </section>
                                    </div>
                                    <!-- 故障理由 -->
                                    <section>
                                        <label class="textarea textarea-resizable">
                                            <textarea name="repair.trouble_reason" id="repair_trouble_reason" rows="4" class="custom-scroll"
                                                      data-app-form="form-plan" placeholder="故障理由／交換メモ"
                                                      disabled="disabled"></textarea>
                                        </label>
                                    </section>
                                </fieldset>

                                <!-- end repair contents -->
                            </div>

                            <header>
                                取消理由
                            </header>

                            <fieldset>
                                <!-- 取消理由 -->
                                <section>
                                    <label class="textarea textarea-resizable">
                                        <textarea name="plan.cancel_reason" id="cancel_reason" rows="4" class="custom-scroll"
                                                  data-app-form="form-plan" placeholder="取消理由（出庫取消の場合に入力してください）"
                                                  disabled="disabled"></textarea>
                                    </label>
                                </section>
                            </fieldset>

                        <!-- End form -->
                        <input type="hidden" name="plan.id" id="id" data-app-form="form-plan">
                        <?= $this->Form->end() ?>

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

<input type="hidden" id="plan_sts_not"         value="<?= $this->App->conf('WNote.DB.Picking.PickingSts.not') ?>">
<input type="hidden" id="picking_kbn_new"      value="<?= $this->App->conf('WNote.DB.Picking.PickingKbn.new') ?>">
<input type="hidden" id="picking_kbn_exchange" value="<?= $this->App->conf('WNote.DB.Picking.PickingKbn.exchange') ?>">
<input type="hidden" id="picking_kbn_repair"   value="<?= $this->App->conf('WNote.DB.Picking.PickingKbn.repair') ?>">

<!-- load script -->
<?= $this->element('Common/load-datatable') ?>
<?php $this->Html->script('wnote/libs/wnote.lib.form.js', ['block' => true]); ?>
<?php $this->Html->script('wnote/picking/picking_plans.entry.js', ['block' => true]); ?>

