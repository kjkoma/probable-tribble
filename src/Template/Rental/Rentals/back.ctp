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
$this->assign('title', '返却');
$this->Breadcrumbs->add('Home', ['prefix' => false, 'controller' => 'Home', 'action' => 'home']);
$this->Breadcrumbs->add('貸出・返却', '#');
$this->Breadcrumbs->add('返却', ['controller' => 'Rentals', 'action' => 'back']);

?>

<!-- widget grid -->
<section id="widget-grid">

    <!-- input widget grid row -->
    <div class="row">

        <!-- input widget -->
        <article class="col-sm-12 sortable-grid ui-sortable">

            <!-- widget ID -->
            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-base"
                 data-widget-deletebutton="false"
                 data-widget-editbutton="false"
                 data-widget-colorbutton="false"
                 data-widget-togglebutton="false"
                 data-widget-fullscreenbutton="false"
                 data-widget-sortable="false">

                <!-- DETAILS widget header -->
                <header role="heading">
                    <span class="widget-icon"> <i class="fa fa-lg fa-reply"></i> </span>
                    <h2>返却</h2>
                </header>

                <!-- content -->
                <div role="content">

                    <!-- DETAILS widget body -->
                    <div class="widget-body">

                        <!-- Input form -->
                        <?= $this->Form->create(null, ['id' => 'form-condition', 'type' => 'post', 'class' => "smart-form"]) ?>

                            <header>
                                貸出選択
                            </header>

                            <fieldset>

                                <div class="row">
                                    <!-- 利用者 -->
                                    <section class="col col-sm-3">
                                        <div class="form-group">
                                            <select name="cond.user_id" id="user_id" class="select2 form-control input-sm"
                                                   data-app-form="form-condition" data-placeholder="利用者"
                                                   style="width:100%;"></select>
                                        </div>
                                    </section>
                                    <!-- 管理者 -->
                                    <section class="col col-sm-3">
                                        <div class="form-group">
                                            <select name="cond.admin_user_id" id="admin_user_id" class="select2 form-control input-sm"
                                                   data-app-form="form-condition" data-placeholder="管理者"
                                                   style="width:100%;"></select>
                                        </div>
                                    </section>
                                    <section class="col col-sm-3">
                                        <!-- 資産管理番号 -->
                                        <label class="input">
                                            <input type="text" name="cond.asset_no" id="asset_no" class="input-sm"
                                                   data-app-form="form-condition"
                                                   maxlength=60
                                                   placeholder="資産管理番号">
                                        </label>
                                    </section>

                                    <section class="col col-sm-3">
                                        <button type="button" class="btn btn-success btn-form-sm" data-app-action-key="search">
                                            <i class="fa fa-search"></i>　検索</button>
                                    </section>
                                </div>

                                <table class="table table-striped table-bordered table-hover" id="rental-datatable">
                                    <thead>
                                        <tr>
                                            <th>利用者</th>
                                            <th>管理者</th>
                                            <th>分類</th>
                                            <th>メーカー</th>
                                            <th>製品</th>
                                            <th>モデル・型</th>
                                            <th>シリアル</th>
                                            <th>資産管理</th>
                                            <th>貸出日</th>
                                            <th>貸出者</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>

                            </fieldset>

                        <!-- End Input form -->
                        <?= $this->Form->end() ?>

                        <!-- Input form -->
                        <?= $this->Form->create(null, ['id' => 'form-rental', 'type' => 'post', 'class' => "smart-form"]) ?>

                            <fieldset>
                                <div class="row">
                                    <section class="col col-4">
                                        <!-- 返却日 -->
                                        <label class="input">
                                            <i class="icon-append fa fa-calendar"></i>
                                            <input type="text" name="rental.back_date" id="back_date" class="input-sm datepicker"
                                                data-app-form="form-rental"
                                                data-dateformat="yy/mm/dd"
                                                maxlength=10
                                                placeholder="【必須】返却日 - yyyy/mm/dd形式">
                                        </label>
                                    </section>
                                    <!-- 返却者 -->
                                    <section class="col col-4">
                                        <div class="form-group">
                                            <select name="rental.back_user_id" id="back_user_id" class="select2 form-control input-sm"
                                                   data-app-form="form-rental" data-placeholder="【必須】返却者"
                                                   style="width:100%;"></select>
                                        </div>
                                    </section>
                                    <!-- 受領者 -->
                                    <section class="col col-4">
                                        <div class="form-group">
                                            <select name="rental.back_suser_id" id="back_suser_id" class="select2 form-control input-sm"
                                                   data-app-form="form-rental" data-placeholder="受領者"
                                                   style="width:100%;"></select>
                                        </div>
                                    </section>
                                </div>
                                <!-- 補足（コメント） -->
                                <section>
                                    <label class="textarea textarea-resizable">
                                        <textarea name="rental.remarks" id="remarks" rows="4" class="custom-scroll"
                                                  data-app-form="form-rental" placeholder="補足"></textarea>
                                    </label>
                                </section>
                            </fieldset>

                            <footer data-app-action-key="back-actions">
                                <button type="button" class="btn btn-primary" data-app-action-key="back"><i class="fa fa-reply"></i>　返却</button>
                            </footer>

                        <!-- End Input form -->
                        <?= $this->Form->end() ?>

                        <!-- End widget body -->
                    </div>

                    <!-- End content -->
                </div>

                <!-- End widget ID -->
            </div>

            <!-- End input widget -->
        </article>

        <!-- End input widget grid row -->
    </div>

    <!-- End widget grid-->
</section>

<!-- load script -->
<?= $this->element('Common/load-datatable') ?>
<?php $this->Html->script('wnote/rental/rentals.back.js', ['block' => true]); ?>
