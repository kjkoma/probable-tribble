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
$this->assign('title', '出庫');
$this->Breadcrumbs->add('Home', ['prefix' => false, 'controller' => 'Home', 'action' => 'home']);
$this->Breadcrumbs->add('出庫', '#');
$this->Breadcrumbs->add('出庫', ['controller' => 'Pickings', 'action' => 'index']);

?>

<!-- widget grid -->
<section id="widget-grid">

    <!-- ********************************** -->
    <!-- Picking Base Input                 -->
    <!-- ********************************** -->

    <!-- list widget grid row -->
    <div class="row">
        <!-- DETAIL list widget -->
        <article class="col-sm-12 sortable-grid ui-sortable">

            <!-- widget ID -->
            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-base"
                 data-widget-deletebutton="false"
                 data-widget-editbutton="false"
                 data-widget-colorbutton="false"
                 data-widget-togglebutton="false"
                 data-widget-sortable="false">

                <!-- DETAILS widget header -->
                <header role="heading">
                    <span class="widget-icon"> <i class="fa fa-lg fa-info"></i> </span>
                    <h2>基本情報入力</h2>
                </header>

                <!-- content -->
                <div role="content">

                    <!-- DETAILS widget body -->
                    <div class="widget-body">

                        <!-- form -->
                        <?= $this->Form->create(null, ['id' => 'form-picking', 'type' => 'post', 'class' => "smart-form"]) ?>

                        <fieldset>
                            <!-- 出庫タイプ -->
                            <section>
                                <label class="label">出庫タイプ選択</label>
                                <div class="inline-group">
                                    <label class="radio">
                                        <input type="radio" name="picking.picking_type" id="picking_type"
                                               data-app-key="form-picking"
                                               data-app-action-key="select-serial"
                                               value="new"
                                               checked="checked">
                                        <i></i>シリアル指定
                                    </label>
                                    <label class="radio">
                                        <input type="radio" name="picking.picking_type" id="picking_type"
                                               data-app-key="form-picking"
                                               data-app-action-key="select-asset"
                                               value="asset">
                                        <i></i>出庫一覧選択（※数量管理品の出庫）
                                    </label>
                                </div>
                            </section>
                        </fieldset>

                        <fieldset>
                            <div class="row">
                                <!-- 出庫日 -->
                                <section class="col col-3">
                                    <label class="input">
                                        <i class="icon-append fa fa-calendar"></i>
                                        <input type="text" name="picking.picking_date" id="picking_date" class="input-sm datepicker"
                                               data-dateformat="yy/mm/dd"
                                               data-app-form="form-picking"
                                               value="<?= date('Y/m/d') ?>"
                                               maxlength="10"
                                               placeholder="出庫日　－　yyyy/mm/dd形式">
                                    </label>
                                </section>
                                <!-- 出庫担当者 -->
                                <section class="col col-3">
                                    <div class="form-group">
                                        <select name="picking.picking_suser_id" id="picking_suser_id" class="select2 form-control input-sm"
                                               data-app-form="form-picking" data-placeholder="出庫担当者を入力・選択してください"
                                                maxlength="20"
                                               style="width:100%;"></select>
                                    </div>
                                </section>
                                <!-- 配送業者 -->
                                <section class="col col-3">
                                    <?= $this->element('Parts/select-delivery-company', [
                                        'delivers' => $delivers,
                                        'name'     => 'picking.delivery_company_id',
                                        'id'       => 'delivery_company_id',
                                        'form'     => 'form-picking',
                                        'disabled' => false
                                    ]) ?>
                                </section>
                                <section class="col col-3">
                                    <label class="input">
                                        <input type="text" name="picking.voucher_no" id="voucher_no" class="input-sm"
                                               data-app-form="form-picking"
                                               maxlength="40"
                                               placeholder="伝票番号　－　最大40文字">
                                    </label>
                                </section>
                            </div>

                            <!-- 補足（コメント） -->
                            <section>
                                <label class="textarea textarea-resizable">
                                    <textarea name="picking.remarks" id="remarks" rows="4" class="custom-scroll"
                                              data-app-form="form-picking" placeholder="【任意】出庫メモ"></textarea>
                                </label>
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

            <!-- End DETAILS list widget -->
        </article>

        <!-- End list widget grid row -->
    </div>

    <!-- list widget grid row -->
    <div class="row" id="list-grid-row">
        <!-- DETAIL list widget -->
        <article class="col-sm-12 sortable-grid ui-sortable">

            <!-- Picking Widget -->
            <?= $this->element('Picking/index.picking.list', []) ?>

            <!-- End DETAILS list widget -->
        </article>

        <!-- End list widget grid row -->
    </div>

    <!-- picking widget grid row -->
    <div class="row">
        <!-- DETAIL list widget -->
        <article class="col-sm-12 sortable-grid ui-sortable">

            <!-- Picking Widget -->
            <?= $this->element('Picking/index.picking', []) ?>

            <!-- End DETAILS list widget -->
        </article>

        <!-- End list widget grid row -->
    </div>

    <!-- plan widget grid row -->
    <div class="row">
        <!-- DETAIL plan widget -->
        <article class="col-sm-12 col-md-4 sortable-grid ui-sortable">

            <!-- Plan Widget -->
            <?= $this->element('Picking/picking.plan', ['conf' => ['hidden' => true]]) ?>

            <!-- End DETAILS plan widget -->
        </article>

        <!-- DETAIL asset widget -->
        <article class="col-sm-12 col-md-8 sortable-grid ui-sortable">

            <!-- Asset Widget -->
            <?= $this->element('Asset/asset', ['conf' => ['asset' => 'view', 'attr' => false, 'hidden' => true]]) ?>

            <!-- End DETAILS asset widget -->
        </article>

        <!-- End plan widget grid row -->
    </div>


    <!-- End widget grid-->
</section>

<!-- hide element -->
<input type="hidden" id="current_suser_id"   value="<?= $this->AppUser->kname() ?>">
<input type="hidden" id="current_suser_name" value="<?= $this->AppUser->id() ?>">

<!-- load script -->
<?= $this->element('Common/load-datatable') ?>
<?php $this->Html->script('wnote/libs/wnote.lib.form.js', ['block' => true]); ?>
<?php $this->Html->script('wnote/wnote.asset.js', ['block' => true]); ?>
<?php $this->Html->script('wnote/wnote.picking.plan.js', ['block' => true]); ?>
<?php $this->Html->script('wnote/picking/pickings.index.js', ['block' => true]); ?>
<?php $this->Html->script('wnote/picking/pickings.index.picking.js', ['block' => true]); ?>
