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
$this->assign('title', '製品／モデル');
$this->assign('onlyDomainAdmin', true);
$this->Breadcrumbs->add('Home', ['prefix' => false, 'controller' => 'Home', 'action' => 'home']);
$this->Breadcrumbs->add('マスタ（管理）', '#');
$this->Breadcrumbs->add('製品／モデル', ['controller' => 'Products', 'action' => 'index']);

?>

<!-- widget grid -->
<section id="widget-grid">

    <!-- widget grid row -->
    <div class="row">

        <!-- ********************************** -->
        <!-- User List                          -->
        <!-- ********************************** -->
        <?= $this->element('Parts/side-tree-classifications', [
            'title'     => '製品／モデル'
        ]) ?>

        <!-- ********************************** -->
        <!-- User Details                       -->
        <!-- ********************************** -->

        <!-- DETAIL widget -->
        <article class="col-sm-8 sortable-grid ui-sortable">

            <!-- widget ID -->
            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-1"
                 data-widget-deletebutton="false"
                 data-widget-editbutton="false"
                 data-widget-colorbutton="false"
                 data-widget-togglebutton="false"
                 data-widget-sortable="false">

                <!-- DETAILS widget header -->
                <header role="heading">
                    <ul id="widget-tab-product" class="nav nav-tabs pull-left">
                        <!-- 製品詳細 タブ -->
                        <li class="active">
                            <a data-toggle="tab" href="#product-contents" data-app-action-key="product-contents">
                                <i class="fa fa-lg fa fa-lg fa-shopping-cart"></i>
                                <span class="hidden-mobile hidden-tablet"> 製品詳細 </span>
                            </a>
                        </li>
                        <!-- モデル／型 タブ -->
                        <li>
                            <a data-toggle="tab" href="#model-contents" data-app-action-key="model-contents">
                                <i class="fa fa-lg fa fa-lg fa-folder"></i>
                                <span class="hidden-mobile hidden-tablet"> モデル／型 </span>
                            </a>
                        </li>
                    </ul>
                    <!-- 操作ボタン -->
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

                    <!-- DETAILS widget body -->
                    <div class="widget-body">

                        <!-- tab contents -->
                        <div class="tab-content">

                            <!-- ********************************** -->
                            <!-- 製品詳細                           -->
                            <!-- ********************************** -->
                            <!-- product tab contents -->
                            <div class="tab-pane fade in active" id="product-contents">

                                <!-- form -->
                                <?= $this->Form->create(null, ['id' => 'form-product', 'type' => 'post', 'class' => "smart-form"]) ?>

                                    <header>
                                        基本情報
                                    </header>

                                    <fieldset>
                                        <!-- 表示名 -->
                                        <section>
                                            <label class="input">
                                                <input type="text" name="product.kname" id="kname" class="input-xs"
                                                       data-app-form="form-product"
                                                       placeholder="表示名　－　最大30文字／特殊文字は利用しないでください"
                                                       disabled="disabled">
                                            </label>
                                        </section>
                                        <!-- 製品名 -->
                                        <section>
                                            <label class="input">
                                                <input type="text" name="product.name" id="name" class="input-xs"
                                                       data-app-form="form-product" placeholder="製品名（正式名称）　－　最大80文字"
                                                       disabled="disabled">
                                            </label>
                                        </section>
                                        <!-- 製造元 -->
                                        <section>
                                            <?= $this->element('Parts/select-makers', [
                                                'makers' => $makers,
                                                'name'   => 'product.maker_id',
                                                'id'     => 'maker_id',
                                                'form'   => 'form-product',
                                                'blank'  => false,
                                            ]) ?>
                                        </section>
                                        <!-- 製品ステータス -->
                                        <section>
                                            <?= $this->element('Parts/select-snames', [
                                                'snames'  => $psts,
                                                'name'    => 'product.psts',
                                                'id'      => 'psts',
                                                'form'    => 'form-product',
                                                'default' => 1
                                            ]) ?>
                                        </section>
                                        <!-- 販売開始日 -->
                                        <section>
                                            <label class="input">
                                                <i class="icon-append fa fa-calendar"></i>
                                                <input type="text" name="product.sales_start" id="sales_start" class="input-sm datepicker"
                                                       data-dateformat="yy/mm/dd"
                                                       data-app-form="form-product"
                                                       placeholder="【任意】販売開始日　－　yyyy/mm/dd形式で入力してください（例：2014/01/01）"
                                                       disabled="disabled">
                                            </label>
                                        </section>
                                        <!-- 販売終了日 -->
                                        <section>
                                            <label class="input">
                                                <i class="icon-append fa fa-calendar"></i>
                                                <input type="text" name="product.sales_end" id="sales_end" class="input-sm datepicker"
                                                       data-dateformat="yy/mm/dd"
                                                       data-app-form="form-product"
                                                       placeholder="【任意】販売終了日　－　yyyy/mm/dd形式で入力してください（例：2017/10/09）"
                                                       disabled="disabled">
                                            </label>
                                        </section>
                                        <!-- 補足（コメント） -->
                                        <section>
                                            <label class="textarea textarea-resizable">
                                                <textarea name="product.remarks" id="remarks" row="3" class="custom-scroll"
                                                          data-app-form="form-product" placeholder="【任意】補足（コメント）"
                                                          disabled="disabled"></textarea>
                                            </label>
                                        </section>
                                        <!-- 利用可否 -->
                                        <section>
                                            <?= $this->element('Parts/select-dsts', [
                                                'name' => 'product.dsts',
                                                'id'   => 'dsts',
                                                'form' => 'form-product',
                                            ]) ?>
                                        </section>
                                    </fieldset>

                                    <header>
                                        製品分類
                                    </header>

                                    <fieldset>
                                        <!-- 製品分類 -->
                                        <section>
                                            <div class="form-group">
                                                <select name="product.classification_id" id="classification_id" class="select2 form-control input-sm"
                                                       data-app-form="form-product" data-placeholder="製品分類を入力・選択してください"
                                                       disabled="disabled"
                                                       style="width=100%;"></select>
                                            </div>
                                        </section>
                                    </fieldset>

                                <!-- End form -->
                                <input type="hidden" name="product.id" id="id" data-app-form="form-product">
                                <?= $this->Form->end() ?>
                                </form>

                                <!-- End product tab contents -->
                            </div>

                            <!-- ********************************** -->
                            <!-- モデル・型一覧・詳細               -->
                            <!-- ********************************** -->
                            <!-- model tab contents -->
                            <div class="tab-pane fade" id="model-contents">

                                <!-- form -->
                                <?= $this->Form->create(null, ['id' => 'form-model', 'type' => 'post', 'class' => "smart-form"]) ?>

                                <header>
                                    モデル／型一覧
                                </header>

                                <fieldset>
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>名称</th>
                                                <th>CPU</th>
                                                <th>メモリ</th>
                                                <th>容量</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </fieldset>


                                    <header>
                                        モデル／型番情報
                                    </header>

                                    <fieldset>
                                        <!-- 表示名 -->
                                        <section>
                                            <label class="input">
                                                <input type="text" name="model.kname" id="kname" class="input-xs"
                                                       data-app-form="form-model"
                                                       placeholder="表示名　－　最大30文字／特殊文字は利用しないでください"
                                                       disabled="disabled">
                                            </label>
                                        </section>
                                        <!-- モデル・型名 -->
                                        <section>
                                            <label class="input">
                                                <input type="text" name="model.name" id="name" class="input-xs"
                                                       data-app-form="form-model" placeholder="モデル・型名（正式名称）　－　最大120文字"
                                                       disabled="disabled">
                                            </label>
                                        </section>
                                        <!-- モデルステータス -->
                                        <section>
                                            <?= $this->element('Parts/select-snames', [
                                                'snames'  => $psts,
                                                'name'    => 'model.psts',
                                                'id'      => 'psts',
                                                'form'    => 'form-model',
                                                'default' => 1
                                            ]) ?>
                                        </section>
                                        <!-- 販売開始日 -->
                                        <section>
                                            <label class="input">
                                                <i class="icon-append fa fa-calendar"></i>
                                                <input type="text" name="model.sales_start" id="sales_start" class="input-sm datepicker"
                                                       data-dateformat="yy/mm/dd"
                                                       data-app-form="form-model"
                                                       placeholder="【任意】販売開始日　－　yyyy/mm/dd形式で入力してください（例：2014/01/01）"
                                                       disabled="disabled">
                                            </label>
                                        </section>
                                        <!-- 販売開始日 -->
                                        <section>
                                            <label class="input">
                                                <i class="icon-append fa fa-calendar"></i>
                                                <input type="text" name="model.sales_end" id="sales_end" class="input-sm datepicker"
                                                       data-dateformat="yy/mm/dd"
                                                       data-app-form="form-model"
                                                       placeholder="【任意】販売終了日　－　yyyy/mm/dd形式で入力してください（例：2014/01/01）"
                                                       disabled="disabled">
                                            </label>
                                        </section>
                                        <!-- CPU -->
                                        <section>
                                            <div class="form-group">
                                                <select name="model.cpu_id" id="cpu_id" class="select2 form-control input-sm"
                                                       data-app-form="form-model" data-placeholder="【任意】CPUを入力・選択してください"
                                                       disabled="disabled"
                                                       style="width=100%;"></select>
                                            </div>
                                        </section>
                                        <!-- メモリ -->
                                        <div class="row">
                                            <section class="col col-4">
                                                <label class="input">
                                                    <input type="number" name="model.memory" id="memory" class="input-sm"
                                                           data-app-form="form-model"
                                                           placeholder="【任意】メモリ容量"
                                                           disabled="disabled">
                                                </label>
                                            </section>
                                            <section class="col col-2">
                                                <?= $this->element('Parts/select-snames', [
                                                    'snames'  => $capacityUnits,
                                                    'name'    => 'model.memory_unit',
                                                    'id'      => 'memory_unit',
                                                    'form'    => 'form-model',
                                                    'default' => 'GB'
                                                ]) ?>
                                            </section>
                                        </div>
                                        <!-- ストレージ -->
                                        <div class="row">
                                            <section class="col col-4">
                                                <?= $this->element('Parts/select-snames', [
                                                    'snames'  => $storageTypes,
                                                    'name'    => 'model.storage_type',
                                                    'id'      => 'storage_type',
                                                    'form'    => 'form-model',
                                                    'default' => 1
                                                ]) ?>
                                            </section>
                                            <section class="col col-4">
                                                <label class="input">
                                                    <input type="number" name="model.storage_vol" id="storage_vol" class="input-sm"
                                                           data-app-form="form-model"
                                                           placeholder="【任意】ストレージ容量"
                                                           disabled="disabled">
                                                </label>
                                            </section>
                                            <section class="col col-2">
                                                <?= $this->element('Parts/select-snames', [
                                                    'snames'  => $capacityUnits,
                                                    'name'    => 'model.storage_unit',
                                                    'id'      => 'storage_unit',
                                                    'form'    => 'form-model',
                                                    'default' => 'GB'
                                                ]) ?>
                                            </section>
                                        </div>
                                        <!-- バージョン -->
                                        <section>
                                            <label class="input">
                                                <i class="icon-append fa fa-calendar"></i>
                                                <input type="text" name="model.version" id="version" class="input-sm"
                                                       data-app-form="form-model"
                                                       placeholder="【任意】バージョン"
                                                       disabled="disabled">
                                            </label>
                                        </section>
                                        <!-- 製造日 -->
                                        <section>
                                            <label class="input">
                                                <i class="icon-append fa fa-calendar"></i>
                                                <input type="text" name="model.maked_date" id="maked_date" class="input-sm datepicker"
                                                       data-dateformat="yy/mm/dd"
                                                       data-app-form="form-model"
                                                       placeholder="【任意】製造日　－　yyyy/mm/dd形式で入力してください（例：2017/10/09）"
                                                       disabled="disabled">
                                            </label>
                                        </section>
                                        <!-- サポート期間 -->
                                        <div class="row">
                                            <section class="col col-4">
                                                <label class="input">
                                                    <input type="number" name="model.support_term" id="support_term" class="input-sm"
                                                           data-app-form="form-model"
                                                           placeholder="【任意】サポート期間"
                                                           disabled="disabled">
                                                </label>
                                            </section>
                                            <section class="col col-2">
                                                <?= $this->element('Parts/select-snames', [
                                                    'snames'  => $supportTermTypes,
                                                    'name'    => 'model.support_term_type',
                                                    'id'      => 'support_term_type',
                                                    'form'    => 'form-model',
                                                    'default' => 1
                                                ]) ?>
                                            </section>
                                        </div>
                                        <!-- 補足（コメント） -->
                                        <section>
                                            <label class="textarea textarea-resizable">
                                                <textarea name="model.remarks" id="remarks" row="3" class="custom-scroll"
                                                          data-app-form="form-model" placeholder="【任意】補足（コメント）"
                                                          disabled="disabled"></textarea>
                                            </label>
                                        </section>
                                        <!-- 利用可否 -->
                                        <section>
                                            <?= $this->element('Parts/select-dsts', [
                                                'name' => 'model.dsts',
                                                'id'   => 'dsts',
                                                'form' => 'form-model',
                                            ]) ?>
                                        </section>
                                    </fieldset>

                                <!-- End form -->
                                <input type="hidden" name="model.product_id" id="product_id" data-app-form="form-model">
                                <input type="hidden" name="model.id" id="id" data-app-form="form-model">
                                <?= $this->Form->end() ?>
                                </form>

                                <!-- End product tab contents -->
                            </div>

                            <!-- End tab contents -->
                        </div>

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
<?php $this->Html->script('wnote/wnote.tree.products.js', ['block' => true]); ?>
<?php $this->Html->script('wnote/master/general/products.index.js', ['block' => true]); ?>
<?php // $this->Html->script('wnote/master/general/products.models.js', ['block' => true]); ?>

