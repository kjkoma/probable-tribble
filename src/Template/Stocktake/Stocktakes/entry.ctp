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
$this->assign('title', '棚卸登録');
$this->assign('onlySysAdmin', true);
$this->Breadcrumbs->add('Home', ['prefix' => false, 'controller' => 'Home', 'action' => 'home']);
$this->Breadcrumbs->add('棚卸', '#');
$this->Breadcrumbs->add('棚卸登録', ['controller' => 'Stocktakes', 'action' => 'entry']);

?>

<!-- widget grid -->
<section id="widget-grid">

    <!-- widget grid row -->
    <div class="row">

        <!-- ********************************** -->
        <!-- Stocktake List                     -->
        <!-- ********************************** -->
        <?= $this->element('Parts/side-list', [
            'title' => '棚卸対象',
            'list' => $stocktakes,
            'itemName' => 'stocktake_date',
            'colSize' => 'col-sm-12'
        ]) ?>

        <!-- ********************************** -->
        <!-- Stocktake Details                  -->
        <!-- ********************************** -->

        <!-- widget -->
        <article class="col-sm-12 sortable-grid ui-sortable">

            <!-- widget ID -->
            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-1"
                 data-widget-deletebutton="false"
                 data-widget-editbutton="false"
                 data-widget-colorbutton="false"
                 data-widget-togglebutton="false"
                 data-widget-sortable="false">

                <!-- USER Details widget header -->
                <header role="heading">
                    <span class="widget-icon"> <i class="fa fa-lg fa-pencil-square-o"></i> </span>
                    <h2>棚卸詳細</h2>
                    <div class="widget-toolbar hidden" role="menu" data-app-action-key="view-actions">
                        <a href="javascript:void(0);" class="btn btn-success" data-app-action-key="edit">編集</a>
                    </div>
                    <div class="widget-toolbar" role="menu" data-app-action-key="add-actions">
                        <a href="javascript:void(0);" class="btn btn-success" data-app-action-key="add">追加</a>
                    </div>
                    <div class="widget-toolbar hidden" role="menu" data-app-action-key="edit-actions">
                        <a href="javascript:void(0);" class="btn btn-default" data-app-action-key="cancel">キャンセル</a>
                        <a href="javascript:void(0);" class="btn btn-primary" data-app-action-key="save">保存</a>
                    </div>
                    <div class="widget-toolbar hidden" role="menu" data-app-action-key="delete-actions">
                        <a href="javascript:void(0);" class="btn btn-danger" data-app-action-key="delete">削除</a>
                    </div>
                </header>

                <!-- content -->
                <div role="content">

                    <!-- widget body -->
                    <div class="widget-body">

                        <!-- user form -->
                        <?= $this->Form->create(null, ['id' => 'form-stocktake', 'type' => 'post', 'class' => "smart-form"]) ?>

                        <header>棚卸情報</header>

                        <fieldset>
                            <!-- 棚卸日 -->
                            <section>
                                <label class="input">
                                    <input type="text" name="stocktake.stocktake_date" id="stocktake_date" class="input-sm datepicker"
                                           data-dateformat="yy/mm/dd"
                                           data-app-form="form-stocktake"
                                           placeholder="棚卸日　－　yyyy/mm/dd形式で入力してください（例：2017/10/09）"
                                           maxlength="10"
                                           disabled="disabled">
                                </label>
                            </section>
                            <!-- 棚卸担当者 -->
                            <section>
                                <div class="form-group">
                                    <select name="stocktake.stocktake_suser_id" id="stocktake_suser_id" class="select2 form-control input-sm"
                                           data-app-form="form-stocktake" data-placeholder="棚卸担当者を指定してください。"
                                           disabled="disabled"
                                           style="width:100%;"></select>
                                </div>
                            </section>
                            <!-- 棚卸確認者 -->
                            <section>
                                <div class="form-group">
                                    <select name="stocktake.confirm_suser_id" id="confirm_suser_id" class="select2 form-control input-sm"
                                           data-app-form="form-stocktake" data-placeholder="【任意】棚卸確認者を指定してください。"
                                           disabled="disabled"
                                           style="width:100%;"></select>
                                </div>
                            </section>
                            <!-- 補足（コメント） -->
                            <section>
                                <label class="textarea textarea-resizable">
                                    <textarea name="stocktake.remarks" id="remarks" rows="3" class="custom-scroll"
                                              data-app-form="form-stocktake" placeholder="【任意】補足（コメント）を入力してください。"
                                              disabled="disabled"></textarea>
                                </label>
                            </section>
                        </fieldset>

                        <footer class="hidden" data-app-action-key="fix-actions">
                            <button type="button" class="btn btn-primary" data-app-action-key="fix-stock">在庫を締める</button>
                            <button type="button" class="btn btn-primary" data-app-action-key="fix-stocktake">棚卸を確定する</button>
                        </footer>

                        <!-- End user form -->
                        <input type="hidden" name="stocktake.id" id="id" data-app-form="form-stocktake">
                        <?= $this->Form->end() ?>

                        <!-- End widget body -->
                    </div>

                    <!-- End content -->
                </div>

                <!-- End widget ID -->
            </div>

            <!-- End widget -->
        </article>

        <!-- DETAIL stocktake widget -->
        <article class="col-sm-12 sortable-grid ui-sortable">

            <!-- Stocktake Widget -->
            <?= $this->element('Stocktake/stocktake', ['conf' => [
                'hidden' => true,
                'edit'   => true
            ]]) ?>

            <!-- End DETAILS stocktake widget -->
        </article>

        <!-- End widget grid row -->
    </div>

    <!-- End widget grid-->
</section>


<!-- load script -->
<?= $this->element('Common/load-datatable') ?>
<?php $this->Html->script('wnote/element/wnote.stocktake.js', ['block' => true]); ?>
<?php $this->Html->script('wnote/element/wnote.stocktake.summary.js', ['block' => true]); ?>
<?php $this->Html->script('wnote/element/wnote.stocktake.unmatch.js', ['block' => true]); ?>
<?php $this->Html->script('wnote/element/wnote.stocktake.nostock.js', ['block' => true]); ?>
<?php $this->Html->script('wnote/stocktake/stocktakes.entry.js', ['block' => true]); ?>

