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
$this->assign('title', '棚卸');
$this->Breadcrumbs->add('Home', ['prefix' => false, 'controller' => 'Home', 'action' => 'home']);
$this->Breadcrumbs->add('棚卸', '#');
$this->Breadcrumbs->add('棚卸検索', ['controller' => 'Stocktakes', 'action' => 'search']);

?>

<!-- widget grid -->
<section id="widget-grid">

    <!-- ********************************** -->
    <!-- Asset List                         -->
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
                    <h2>棚卸検索</h2>
                </header>

                <!-- content -->
                <div role="content">

                    <!-- DETAILS widget body -->
                    <div class="widget-body">

                        <!-- form -->
                        <?= $this->Form->create(null, ['id' => 'form-condition', 'type' => 'post', 'class' => "smart-form"]) ?>

                            <fieldset>
                                <div class="row">
                                    <!-- 棚卸日(From) -->
                                    <section class="col col-6">
                                        <label class="input">
                                            <i class="icon-append fa fa-calendar"></i>
                                            <input type="text" name="cond.stocktake_date_from" id="stocktake_date_from" class="input-sm datepicker"
                                                   data-dateformat="yy/mm/dd"
                                                   data-app-form="form-condition"
                                                   maxlength="10"
                                                   placeholder="棚卸日（From）－yyyy/mm/dd形式">
                                        </label>
                                    </section>
                                    <!-- 棚卸日(To) -->
                                    <section class="col col-6">
                                        <label class="input">
                                            <i class="icon-append fa fa-calendar"></i>
                                            <input type="text" name="cond.stocktake_date_to" id="stocktake_date_to" class="input-sm datepicker"
                                                   data-dateformat="yy/mm/dd"
                                                   data-app-form="form-condition"
                                                   maxlength="10"
                                                   placeholder="棚卸日（To）－yyyy/mm/dd形式">
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
                    <h2>検索結果</h2>
                    <div class="widget-toolbar" role="menu">
                        <a href="javascript:void(0);" class="btn btn-info disabled" data-app-action-key="download"><i class="fa fa-download"></i>　ダウンロード</a>
                    </div>
                </header>

                <!-- content -->
                <div role="content">

                    <!-- DETAILS widget body -->
                    <div class="widget-body">

                        <table class="table table-striped table-bordered table-hover" id="stocktake-datatable">
                            <thead>
                                <tr>
                                    <th>棚卸日</th>
                                    <th>状況</th>
                                    <th>担当者</th>
                                    <th>確認者</th>
                                    <th>開始日</th>
                                    <th>終了日</th>
                                    <th>在庫締日</th>
                                    <th>補足</th>
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

            <!-- Stocktake Widget -->
            <?= $this->element('Stocktake/stocktake', ['conf' => [
                'hidden' => true,
                'edit'   => false
            ]]) ?>

        </article>

        <!-- End plan widget grid row -->
    </div>

    <!-- End widget grid-->
</section>

<!-- download form -->
<?= $this->Form->create(null, ['id' => 'form-download', 'type' => 'post', 'class' => "smart-form hidden", 'url' => ['controller' => 'stocktakes', 'action' => 'download']]) ?>
<?= $this->Form->end() ?>

<!-- load script -->
<?= $this->element('Common/load-datatable') ?>
<?php $this->Html->script('wnote/element/wnote.stocktake.js', ['block' => true]); ?>
<?php $this->Html->script('wnote/element/wnote.stocktake.summary.js', ['block' => true]); ?>
<?php $this->Html->script('wnote/element/wnote.stocktake.unmatch.js', ['block' => true]); ?>
<?php $this->Html->script('wnote/element/wnote.stocktake.nostock.js', ['block' => true]); ?>
<?php $this->Html->script('wnote/stocktake/stocktakes.search.js', ['block' => true]); ?>

