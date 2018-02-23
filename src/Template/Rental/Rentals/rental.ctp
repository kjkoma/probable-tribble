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
$this->assign('title', '貸出');
$this->Breadcrumbs->add('Home', ['prefix' => false, 'controller' => 'Home', 'action' => 'home']);
$this->Breadcrumbs->add('貸出・返却', '#');
$this->Breadcrumbs->add('貸出', ['controller' => 'Rentals', 'action' => 'rental']);

?>

<!-- widget grid -->
<section id="widget-grid">

    <!-- ********************************** -->
    <!-- Rental Plan List                   -->
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
                    <h2>貸出予定一覧</h2>
                </header>

                <!-- content -->
                <div role="content">

                    <!-- widget body -->
                    <div class="widget-body">

                        <table class="table table-striped table-bordered table-hover" id="plans-datatable">
                            <thead>
                                <tr>
                                    <th>タイプ</th>
                                    <th>資産名</th>
                                    <th>分類</th>
                                    <th>メーカー</th>
                                    <th>製品</th>
                                    <th>モデル</th>
                                    <th>シリアル</th>
                                    <th>資産管理</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>

                        <!-- form -->
                        <?= $this->Form->create(null, ['id' => 'form-cancel', 'type' => 'post', 'class' => "smart-form"]) ?>

                        <footer data-app-action-key="cancel-actions">
                            <button type="button" class="btn btn-danger" data-app-action-key="cancel"><i class="fa fa-trash"></i>　予定から削除</button>
                        </footer>

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

                <!-- content -->
                <div role="content">

                    <!-- DETAILS widget body -->
                    <div class="widget-body">

                        <!-- form -->
                        <?= $this->Form->create(null, ['id' => 'form-rental', 'type' => 'post', 'class' => "smart-form"]) ?>

                            <header>
                                貸出内容
                            </header>

                            <fieldset>
                                <div class="row">
                                    <!-- 依頼日 -->
                                    <section class="col col-4">
                                        <label class="input">
                                            <i class="icon-append fa fa-calendar"></i>
                                            <input type="text" name="rental.req_date" id="req_date" class="input-sm datepicker"
                                                   data-dateformat="yy/mm/dd"
                                                   data-app-form="form-rental"
                                                   placeholder="【必須】依頼日　－　yyyy/mm/dd形式で入力してください（例：2017/10/09）"
                                                   maxlength="10">
                                        </label>
                                    </section>
                                    <!-- 依頼者 -->
                                    <section class="col col-4">
                                        <div class="form-group">
                                            <select name="rental.req_user_id" id="req_user_id" class="select2 form-control input-sm"
                                                   data-app-form="form-rental" data-placeholder="【必須】依頼者"
                                                   style="width:100%;"></select>
                                        </div>
                                    </section>
                                    <!-- 希望日 -->
                                    <section class="col col-4">
                                        <label class="input">
                                            <i class="icon-append fa fa-calendar"></i>
                                            <input type="text" name="rental.plan_date" id="plan_date" class="input-sm datepicker"
                                                   data-dateformat="yy/mm/dd"
                                                   data-app-form="form-rental"
                                                   placeholder="希望日　－　yyyy/mm/dd形式で入力してください（例：2017/10/09）"
                                                   maxlength="10">
                                        </label>
                                    </section>
                                </div>
                                <div class="row">
                                    <!-- 管理者 -->
                                    <section class="col col-4">
                                        <div class="form-group">
                                            <select name="rental.admin_user_id" id="admin_user_id" class="select2 form-control input-sm"
                                                   data-app-form="form-rental" data-placeholder="管理者"
                                                   style="width:100%;"></select>
                                        </div>
                                    </section>
                                    <!-- 利用者 -->
                                    <section class="col col-4">
                                        <div class="form-group">
                                            <select name="rental.user_id" id="user_id" class="select2 form-control input-sm"
                                                   data-app-form="form-rental" data-placeholder="【必須】利用者"
                                                   style="width:100%;"></select>
                                        </div>
                                    </section>
                                    <!-- 返却予定日 -->
                                    <section class="col col-4">
                                        <label class="input">
                                            <i class="icon-append fa fa-calendar"></i>
                                            <input type="text" name="rental.back_plan_date" id="back_plan_date" class="input-sm datepicker"
                                                   data-dateformat="yy/mm/dd"
                                                   data-app-form="form-rental"
                                                   placeholder="返却予定日　－　yyyy/mm/dd形式で入力してください（例：2017/10/09）"
                                                   maxlength="10">
                                        </label>
                                    </section>
                                </div>
                                <!-- 貸出メモ -->
                                <section>
                                    <label class="textarea textarea-resizable">
                                        <textarea name="rental.rental_remarks" id="rental_remarks" rows="3" class="custom-scroll"
                                                  data-app-form="form-rental" placeholder="貸出メモ"></textarea>
                                    </label>
                                </section>
                            </fieldset>

                            <footer data-app-action-key="rental-actions">
                                <button type="button" class="btn btn-primary" data-app-action-key="rental"><i class="fa fa-check"></i>　貸出</button>
                            </footer>

                        <!-- End form -->
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

<!-- load script -->
<?= $this->element('Common/load-datatable') ?>
<?php $this->Html->script('wnote/rental/rentals.rental.js', ['block' => true]); ?>
