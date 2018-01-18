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
$this->assign('title', '入庫');
$this->Breadcrumbs->add('Home', ['prefix' => false, 'controller' => 'Home', 'action' => 'home']);
$this->Breadcrumbs->add('入庫', '#');
$this->Breadcrumbs->add('入庫', ['controller' => 'Instocks', 'action' => 'index']);

?>

<!-- widget grid -->
<section id="widget-grid">

    <!-- ********************************** -->
    <!-- Instock Base Input                 -->
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
                        <?= $this->Form->create(null, ['id' => 'form-instock', 'type' => 'post', 'class' => "smart-form"]) ?>

                        <fieldset>
                            <!-- 入庫タイプ -->
                            <section>
                                <label class="label">入庫タイプ選択</label>
                                <div class="inline-group">
                                    <label class="radio">
                                        <input type="radio" name="instock.instock_type" id="instock_type"
                                               data-app-key="form-instock"
                                               data-app-action-key="select-new"
                                               value="new"
                                               checked="checked">
                                        <i></i>新規入庫
                                    </label>
                                    <label class="radio">
                                        <input type="radio" name="instock.instock_type" id="instock_type"
                                               data-app-key="form-instock"
                                               data-app-action-key="select-asset"
                                               value="asset">
                                        <i></i>資産単品入庫（※資産ID／シリアル指定の単品入庫）
                                    </label>
                                </div>
                            </section>
                        </fieldset>

                        <fieldset>
                            <div class="row">
                                <!-- 入庫日 -->
                                <section class="col col-3">
                                    <label class="input">
                                        <i class="icon-append fa fa-calendar"></i>
                                        <input type="text" name="instock.instock_date" id="instock_date" class="input-sm datepicker"
                                               data-dateformat="yy/mm/dd"
                                               data-app-form="form-instock"
                                               value="<?= date('Y/m/d') ?>"
                                               maxlength="10"
                                               placeholder="入庫日　－　yyyy/mm/dd形式">
                                    </label>
                                </section>
                                <!-- 入庫担当者 -->
                                <section class="col col-3">
                                    <div class="form-group">
                                        <select name="instock.instock_suser_id" id="instock_suser_id" class="select2 form-control input-sm"
                                               data-app-form="form-instock" data-placeholder="入庫担当者を入力・選択してください"
                                                maxlength="20"
                                               style="width:100%;"></select>
                                    </div>
                                </section>
                                <!-- 配送業者 -->
                                <section class="col col-3">
                                    <?= $this->element('Parts/select-delivery-company', [
                                        'delivers' => $delivers,
                                        'name'     => 'instock.delivery_company_id',
                                        'id'       => 'delivery_company_id',
                                        'form'     => 'form-instock',
                                        'disabled' => false
                                    ]) ?>
                                </section>
                                <section class="col col-3">
                                    <label class="input">
                                        <input type="text" name="instock.voucher_no" id="voucher_no" class="input-sm"
                                               data-app-form="form-instock"
                                               maxlength="40"
                                               placeholder="伝票番号　－　最大40文字">
                                    </label>
                                </section>
                            </div>

                            <!-- 補足（コメント） -->
                            <section>
                                <label class="textarea textarea-resizable">
                                    <textarea name="instock.remarks" id="remarks" rows="4" class="custom-scroll"
                                              data-app-form="form-instock" placeholder="【任意】入庫メモ"></textarea>
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

            <!-- New Instock Widget -->
            <?= $this->element('Instock/index.new', []) ?>

            <!-- Asset Instock Widget -->
            <?= $this->element('Instock/index.asset', []) ?>

            <!-- End DETAILS list widget -->
        </article>

        <!-- End list widget grid row -->
    </div>

    <!-- End widget grid-->
</section>

<!-- hide element -->
<input type="hidden" id="current_suser_id"   value="<?= $this->AppUser->kname() ?>">
<input type="hidden" id="current_suser_name" value="<?= $this->AppUser->id() ?>">

<!-- load script -->
<?= $this->element('Common/load-datatable') ?>
<?php $this->Html->script('plugin/bootstrap-wizard/jquery.bootstrap.wizard.min.js', ['block' => true]); ?>
<?php $this->Html->script('wnote/instock/instocks.index.js', ['block' => true]); ?>
<?php $this->Html->script('wnote/instock/instocks.index.new.js', ['block' => true]); ?>
<?php $this->Html->script('wnote/instock/instocks.index.asset.js', ['block' => true]); ?>
