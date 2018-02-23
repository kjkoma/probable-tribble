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
 * 出庫予定表示 ウィジット
 *  
 * - - -
 * 設定デフォルト
 * <code>
 *   'conf' => [
 *       'hidden' => false // true: 初期非表示/false: 初期表示
 *   ]
 * </code>
 */
$default = [
    'hidden'  => false
];
$conf = isset($conf) ? array_merge($default, $conf) : $default;
?>
<!-- widget ID -->
<div class="jarviswidget jarviswidget-color-blueDark <?= ($conf['hidden']) ? 'hidden' : '' ?>" id="wid-id-elem-picking-plan"
     data-widget-deletebutton="false"
     data-widget-editbutton="false"
     data-widget-colorbutton="false"
     data-widget-togglebutton="false"
     data-widget-fullscreenbutton="false"
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
             <?= $this->Form->create(null, ['id' => 'form-elem-picking-plan', 'type' => 'post', 'class' => "smart-form"]) ?>

                 <header>
                     依頼情報
                 </header>

                 <fieldset>
                     <section>
                         <dl class="dl-horizontal">
                             <dt>依頼番号(申請番号)：</dt>
                                 <dd name="elem_picking_plan.apply_no" id="elemPickingPlan_apply_no" data-app-form="form-elem-picking-plan"></dd>
                             <dt>依頼日(申請日)：</dt>
                                 <dd name="elem_picking_plan.req_date" id="elemPickingPlan_req_date" data-app-form="form-elem-picking-plan"></dd>
                             <dt>依頼者(組織)：</dt>
                                 <dd name="elem_picking_plan.req_organization_name" id="elemPickingPlan_req_organization_name" data-app-form="form-elem-picking-plan"></dd>
                             <dt>依頼者：</dt>
                                 <dd name="elem_picking_plan.req_user_name" id="elemPickingPlan_req_user_name" data-app-form="form-elem-picking-plan"></dd>
                             <dt>依頼者(社員番号)：</dt>
                                 <dd name="elem_picking_plan.req_emp_no" id="elemPickingPlan_req_emp_no" data-app-form="form-elem-picking-plan"></dd>
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
                                 <dd name="elem_picking_plan.use_organization_name" id="elemPickingPlan_use_organization_name" data-app-form="form-elem-picking-plan"></dd>
                             <dt>使用者：</dt>
                                 <dd name="elem_picking_plan.use_user_name" id="elemPickingPlan_use_user_name" data-app-form="form-elem-picking-plan"></dd>
                             <dt>使用者(社員番号)：</dt>
                                 <dd name="elem_picking_plan.use_emp_no" id="elemPickingPlan_use_emp_no" data-app-form="form-elem-picking-plan"></dd>
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
                                 <dd name="elem_picking_plan.dlv_organization_name" id="elemPickingPlan_dlv_organization_name" data-app-form="form-elem-picking-plan"></dd>
                             <dt>出庫先(ユーザー)：</dt>
                                 <dd name="elem_picking_plan.dlv_user_name" id="elemPickingPlan_dlv_user_name" data-app-form="form-elem-picking-plan"></dd>
                             <dt>出庫先(社員番号)：</dt>
                                 <dd name="elem_picking_plan.dlv_emp_no" id="elemPickingPlan_dlv_emp_no" data-app-form="form-elem-picking-plan"></dd>
                             <dt>宛名：</dt>
                                 <dd name="elem_picking_plan.dlv_name" id="elemPickingPlan_dlv_name" data-app-form="form-elem-picking-plan"></dd>
                             <dt>出庫先(連絡先)：</dt>
                                 <dd name="elem_picking_plan.dlv_tel" id="elemPickingPlan_dlv_tel" data-app-form="form-elem-picking-plan"></dd>
                             <dt>出庫先(郵便番号)：</dt>
                                 <dd name="elem_picking_plan.dlv_zip" id="elemPickingPlan_dlv_zip" data-app-form="form-elem-picking-plan"></dd>
                             <dt>出庫先(住所)：</dt>
                                 <dd name="elem_picking_plan.dlv_address" id="elemPickingPlan_dlv_address" data-app-form="form-elem-picking-plan"></dd>
                             <dt>到着希望日：</dt>
                                 <dd name="elem_picking_plan.arv_date" id="elemPickingPlan_arv_date" data-app-form="form-elem-picking-plan"></dd>
                             <dt>到着希望時間：</dt>
                                 <dd name="elem_picking_plan.arv_time_kbn_name" id="elemPickingPlan_arv_time_kbn_name" data-app-form="form-elem-picking-plan"></dd>
                             <dt>到着希望メモ：</dt>
                                 <dd name="elem_picking_plan.arv_remarks" id="elemPickingPlan_arv_remarks" data-app-form="form-elem-picking-plan"></dd>
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
                                 <dd name="elem_picking_plan.picking_kbn_name" id="elemPickingPlan_picking_kbn_name" data-app-form="form-elem-picking-plan"></dd>
                             <dt>出庫状況：</dt>
                                 <dd name="elem_picking_plan.plan_sts_name" id="elemPickingPlan_plan_sts_name" data-app-form="form-elem-picking-plan"></dd>
                             <dt>受付日：</dt>
                                 <dd name="elem_picking_plan.rcv_date" id="elemPickingPlan_rcv_date" data-app-form="form-elem-picking-plan"></dd>
                             <dt>受付者：</dt>
                                 <dd name="elem_picking_plan.rcv_suser_name" id="elemPickingPlan_rcv_suser_name" data-app-form="form-elem-picking-plan"></dd>
                             <dt>出庫予定日：</dt>
                                 <dd name="elem_picking_plan.plan_date" id="elemPickingPlan_plan_date" data-app-form="form-elem-picking-plan"></dd>
                             <dt>出庫件名：</dt>
                                 <dd name="elem_picking_plan.name" id="elemPickingPlan_name" data-app-form="form-elem-picking-plan"></dd>
                             <dt>出庫理由／出庫メモ：</dt>
                                 <dd name="elem_picking_plan.picking_reason" id="elemPickingPlan_picking_reason" data-app-form="form-elem-picking-plan"></dd>
                             <dt>出庫備考：</dt>
                                 <dd name="elem_picking_plan.remarks" id="elemPickingPlan_remarks" data-app-form="form-elem-picking-plan"></dd>
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
                                 <dd name="elem_picking_plan.category_name" id="elemPickingPlan_category_name" data-app-form="form-elem-picking-plan"></dd>
                             <dt>再利用区分：</dt>
                                 <dd name="elem_picking_plan.reuse_kbn" id="elemPickingPlan_reuse_kbn_name" data-app-form="form-elem-picking-plan"></dd>
                             <dt>キッティングパターン：</dt>
                                 <dd name="elem_picking_plan.kitting_pattern_name" id="elemPickingPlan_kitting_pattern_name" data-app-form="form-elem-picking-plan"></dd>
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


