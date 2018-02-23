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
$this->assign('title', '貸出検索');
$this->Breadcrumbs->add('Home', ['prefix' => false, 'controller' => 'Home', 'action' => 'home']);
$this->Breadcrumbs->add('貸出・返却', '#');
$this->Breadcrumbs->add('貸出検索', ['controller' => 'Rentals', 'action' => 'search']);

?>

<!-- widget grid -->
<section id="widget-grid">

    <!-- ********************************** -->
    <!-- Rental List                        -->
    <!-- ********************************** -->

    <!-- list widget grid row -->
    <div class="row" id="grid-row-search">
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
                    <h2>貸出検索</h2>
                </header>

                <!-- content -->
                <div role="content">

                    <!-- DETAILS widget body -->
                    <div class="widget-body">

                        <!-- form -->
                        <?= $this->Form->create(null, ['id' => 'form-condition', 'type' => 'post', 'class' => "smart-form"]) ?>

                            <fieldset>
                                <div class="row">
                                    <!-- 貸出状況 -->
                                    <section class="col col-3">
                                        <?= $this->element('Parts/select-snames', [
                                            'snames'   => $rentalSts,
                                            'name'     => 'cond.rental_sts',
                                            'id'       => 'rental_sts',
                                            'form'     => 'form-condition',
                                            'disabled' => false,
                                            'blank'    => true,
                                            'placeholder' => '-- 貸出状況選択 --'
                                        ]) ?>
                                    </section>
                                    <!-- 利用者 -->
                                    <section class="col col-3">
                                        <div class="form-group">
                                            <select name="cond.user_id" id="user_id" class="select2 form-control input-sm"
                                                   data-app-form="form-condition"
                                                    maxlength="20"
                                                   data-placeholder="利用者"></select>
                                        </div>
                                    </section>
                                    <!-- 管理者 -->
                                    <section class="col col-3">
                                        <div class="form-group">
                                            <select name="cond.admin_user_id" id="admin_user_id" class="select2 form-control input-sm"
                                                   data-app-form="form-condition"
                                                    maxlength="20"
                                                   data-placeholder="管理者"></select>
                                        </div>
                                    </section>
                                    <!-- 返却者 -->
                                    <section class="col col-3">
                                        <div class="form-group">
                                            <select name="cond.back_user_id" id="back_user_id" class="select2 form-control input-sm"
                                                   data-app-form="form-condition"
                                                    maxlength="20"
                                                   data-placeholder="返却者"></select>
                                        </div>
                                    </section>
                                </div>
                                <div class="row">
                                    <!-- 貸出日(From) -->
                                    <section class="col col-3">
                                        <label class="input">
                                            <i class="icon-append fa fa-calendar"></i>
                                            <input type="text" name="cond.rental_date_from" id="rental_date_from" class="input-sm datepicker"
                                                   data-dateformat="yy/mm/dd"
                                                   data-app-form="form-condition"
                                                   maxlength="10"
                                                   placeholder="貸出日（From）－yyyy/mm/dd形式">
                                        </label>
                                    </section>
                                    <!-- 貸出日(To) -->
                                    <section class="col col-3">
                                        <label class="input">
                                            <i class="icon-append fa fa-calendar"></i>
                                            <input type="text" name="cond.rental_date_to" id="rental_date_to" class="input-sm datepicker"
                                                   data-dateformat="yy/mm/dd"
                                                   data-app-form="form-condition"
                                                   maxlength="10"
                                                   placeholder="貸出日（To）－yyyy/mm/dd形式">
                                        </label>
                                    </section>
                                    <!-- 返却日(From) -->
                                    <section class="col col-3">
                                        <label class="input">
                                            <i class="icon-append fa fa-calendar"></i>
                                            <input type="text" name="cond.back_date_from" id="back_date_from" class="input-sm datepicker"
                                                   data-dateformat="yy/mm/dd"
                                                   data-app-form="form-condition"
                                                   maxlength="10"
                                                   placeholder="返却日（From）－yyyy/mm/dd形式">
                                        </label>
                                    </section>
                                    <!-- 返却日(To) -->
                                    <section class="col col-3">
                                        <label class="input">
                                            <i class="icon-append fa fa-calendar"></i>
                                            <input type="text" name="cond.back_date_to" id="back_date_to" class="input-sm datepicker"
                                                   data-dateformat="yy/mm/dd"
                                                   data-app-form="form-condition"
                                                   maxlength="10"
                                                   placeholder="返却日（To）－yyyy/mm/dd形式">
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
                    <h2>検索結果(※最大500件表示)</h2>
                    <div class="widget-toolbar" role="menu">
                        <a href="javascript:void(0);" class="btn btn-info disabled" data-app-action-key="download"><i class="fa fa-download"></i>　ダウンロード</a>
                    </div>
                </header>

                <!-- content -->
                <div role="content">

                    <!-- DETAILS widget body -->
                    <div class="widget-body">

                        <table class="table table-striped table-bordered table-hover" id="rental-datatable">
                            <thead>
                                <tr>
                                    <th>状況</th>
                                    <th>貸出日</th>
                                    <th>利用者</th>
                                    <th>管理者</th>
                                    <th>資産管理</th>
                                    <th>資産名</th>
                                    <th>返却日</th>
                                    <th>返却者</th>
                                    <th>受領者</th>
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

    <!-- detail widget grid row -->
    <div class="row hidden" id="grid-row-back">
        <div class="col col-sm-12 text-right">
            <button type="button" class="btn btn-default" data-app-action-key="back"><i class="fa fa-chevron-left"></i>　検索を表示</button>
        </div>
    </div>

    <div class="row">
        <!-- DETAIL asset widget -->
        <article class="col-sm-12 sortable-grid ui-sortable">

            <!-- Asset Widget -->
            <?= $this->element('Asset/asset', ['conf' => [
                'asset'        => 'view',
                'attr'         => 'view',
                'user'         => true,
                'stock'        => true,
                'repair'       => true,
                'rental'       => true,
                'hidden'       => true]]) ?>

            <!-- End DETAILS asset widget -->
        </article>

        <!-- End asset widget grid row -->
    </div>

    <!-- End widget grid-->
</section>

<!-- download form -->
<?= $this->Form->create(null, ['id' => 'form-download', 'type' => 'post', 'class' => "smart-form hidden", 'url' => ['controller' => 'rentals', 'action' => 'download']]) ?>
<?= $this->Form->end() ?>

<!-- load script -->
<?= $this->element('Common/load-datatable') ?>
<?php $this->Html->script('wnote/libs/wnote.lib.form.js', ['block' => true]); ?>
<?= $this->element('Asset/load-asset') ?>
<?php $this->Html->script('wnote/rental/rentals.search.js', ['block' => true]); ?>

