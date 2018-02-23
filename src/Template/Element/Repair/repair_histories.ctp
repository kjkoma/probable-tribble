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
 * 修理履歴 ウィジット
 *   list.ctp内での読込用
 * - - -
 * @param mixed $conf 表示/入力の設定情報
 */
$default = [
    'hidden'  => false
];
$conf = isset($conf) ? array_merge($default, $conf) : $default;
?>
<!-- widget ID -->
<div class="jarviswidget jarviswidget-color-blueDark <?= ($conf['hidden']) ? 'hidden' : '' ?>" id="wid-id-elem-repair-histories"
     data-widget-deletebutton="false"
     data-widget-editbutton="false"
     data-widget-colorbutton="false"
     data-widget-togglebutton="false"
     data-widget-sortable="false">

    <!-- widget header -->
    <header role="heading">
        <span class="widget-icon"> <i class="fa fa-lg fa-list"></i> </span>
        <h2>修理履歴</h2>
    </header>

    <!-- content -->
    <div role="content">

        <!-- widget body -->
        <div class="widget-body">

            <!-- form -->
            <?= $this->Form->create(null, ['id' => 'form-elem-repair-histories', 'type' => 'post', 'class' => 'smart-form', 'novalidate' => 'novalidate']) ?>

                <header>
                    故障情報
                </header>

                <fieldset>
                    <section>
                        <dl class="dl-horizontal">
                            <dt>修理状況：</dt><dd name="elem-repairview.repair_sts_name" id="elemRepairview_repair_sts_name" data-app-form="form-elem-repairview"></dd>
                            <dt>発生日：</dt><dd name="elem-repairview.start_date" id="elemRepairview_start_date" data-app-form="form-elem-repairview"></dd>
                            <dt>完了日：</dt><dd name="elem_assetview.end_date" id="elemRepairview_end_date" data-app-form="form-elem-repairview"></dd>
                            <dt>センドバック有無：</dt><dd name="elem-repairview.sendback_kbn_name" id="elemRepairview_sendback_kbn_name" data-app-form="form-elem-repairview"></dd>
                            <dt>データ抽出有無：</dt><dd name="elem-repairview.datapick_kbn_name" id="elemRepairview_datapick_kbn_name" data-app-form="form-elem-repairview"></dd>
                            <dt>故障区分：</dt><dd name="elem-repairview.trouble_kbn_name" id="elemRepairview_trouble_kbn_name" data-app-form="form-elem-repairview"></dd>
                            <dt>故障原因：</dt><dd name="elem_assetview.trouble_reason" id="elemRepairview_trouble_reason" data-app-form="form-elem-repairview"></dd>
                            <dt>備考：</dt><dd name="elem_assetview.remarks" id="elemRepairview_remarks" data-app-form="form-elem-repairview"></dd>
                        </dl>
                        <dl class="dl-horizontal">
                            <dt>シリアル番号：</dt><dd name="elem-repairview.serial_no" id="elemRepairview_serial_no" data-app-form="form-elem-repairview"></dd>
                            <dt>資産管理番号：</dt><dd name="elem-repairview.asset_no" id="elemRepairview_asset_no" data-app-form="form-elem-repairview"></dd>
                            <dt>資産名：</dt><dd name="elem_assetview.asset_kname" id="elemRepairview_asset_kname" data-app-form="form-elem-repairview"></dd>
                            <dt>入庫日：</dt><dd name="elem-repairview.instock_date" id="elemRepairview_instock_date" data-app-form="form-elem-repairview"></dd>
                            <dt>出庫日：</dt><dd name="elem_assetview.picking_date" id="elemRepairview_picking_date" data-app-form="form-elem-repairview"></dd>
                        </dl>
                    </section>
                </fieldset>

                <header>
                    修理履歴一覧
                </header>

                <fieldset>
                    <!-- 修理履歴一覧 -->
                    <table class="table table-striped table-bordered table-hover" id="elemRepairHistories-datatable">
                        <thead>
                            <tr>
                                <th>履歴日</th>
                                <th>記録者</th>
                                <th>修理内容</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </fieldset>

                <!-- 操作アクション -->
                <div class="widget-actions" id="elemRepairHistories-actions">
                    <div class="widget-action hidden" data-app-action-key="elemRepairHistories-view-actions">
                        <a href="javascript:void(0);" class="btn btn-success" data-app-action-key="elemRepairHistories-edit"><i class="fa fa-edit"></i>　編集</a>
                    </div>
                    <div class="widget-action" data-app-action-key="elemRepairHistories-add-actions">
                        <a href="javascript:void(0);" class="btn btn-success" data-app-action-key="elemRepairHistories-add"><i class="fa fa-plus"></i>　追加</a>
                    </div>
                    <div class="widget-action hidden" data-app-action-key="elemRepairHistories-edit-actions">
                        <a href="javascript:void(0);" class="btn btn-default" data-app-action-key="elemRepairHistories-cancel"><i class="fa fa-times"></i>　キャンセル</a>
                        <a href="javascript:void(0);" class="btn btn-primary" data-app-action-key="elemRepairHistories-save"><i class="fa fa-save"></i>　保存</a>
                    </div>
                    <div class="widget-action hidden" data-app-action-key="elemRepairHistories-delete-actions">
                        <a href="javascript:void(0);" class="btn btn-danger" data-app-action-key="elemRepairHistories-delete"><i class="fa fa-trash"></i>　削除</a>
                    </div>
                </div>

                <header>
                    修理履歴
                </header>

                <fieldset>
                    <!-- 履歴日 -->
                    <section>
                        <label class="input">
                            <i class="icon-append fa fa-calendar"></i>
                            <input type="text" name="elem-repair-histories.history_date" id="elemRepairHistories_history_date" class="input-sm datepicker"
                                   data-dateformat="yy/mm/dd"
                                   data-app-form="form-elem-repair-histories"
                                   maxlength=10
                                   placeholder="履歴日－yyyy/mm/dd形式（例：2017/10/09）"
                                   disabled="disabled">
                        </label>
                    </section>
                    <!-- 修理内容 -->
                    <section>
                        <label class="textarea textarea-resizable">
                            <textarea name="elem-repair-histories.history_contents" id="elemRepairHistories_history_contents" rows="4" class="custom-scroll"
                                      data-app-form="form-elem-repair-histories" placeholder="修理内容"
                                      disabled="disabled"></textarea>
                        </label>
                    </section>
                    <!-- 補足 -->
                    <section>
                        <label class="textarea textarea-resizable">
                            <textarea name="elem-repair-histories.remarks" id="elemRepairHistories_remarks" rows="4" class="custom-scroll"
                                      data-app-form="form-elem-repair-histories" placeholder="補足"
                                      disabled="disabled"></textarea>
                        </label>
                    </section>
                </fieldset>

                <footer id="elemRepairHistories-end-actions">
                    <label class="input">
                        <input type="text" name="elem-repair-abrogate.abrogate_reason" id="elemRepairHistories_abrogate_reason" class="input-sm"
                               data-app-form="form-elem-repair-histories-abrogate"
                               maxlength="1024"
                               placeholder="廃棄理由 - 廃棄予定に追加する場合に入力してください">
                    </label>
                    <button type="button" class="btn btn-primary" data-app-action-key="elemRepairHistories-complete"> <i class="fa fa-save"></i>　修理を完了する </button>
                    <button type="button" class="btn btn-primary" data-app-action-key="elemRepairHistories-abrogate"> <i class="fa fa-save"></i>　廃棄予定に追加する </button>
                </footer>

            <!-- End form -->
            <?= $this->Form->end() ?>

            <!-- End widget body -->
        </div>

        <!-- End content -->
    </div>

    <!-- End widget ID -->
</div>

<input type="hidden" id="elemRepairHistoriesRepairSts_instock" value="<?= $this->App->conf('WNote.DB.Repair.RepairSts.instock') ?>">
<input type="hidden" id="elemRepairHistoriesRepairSts_repair"  value="<?= $this->App->conf('WNote.DB.Repair.RepairSts.repair') ?>">

