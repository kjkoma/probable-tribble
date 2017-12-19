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
$this->assign('title', '資産分類');
$this->assign('onlyDomainAdmin', true);
$this->Breadcrumbs->add('Home', ['prefix' => false, 'controller' => 'Home', 'action' => 'home']);
$this->Breadcrumbs->add('マスタ（管理）', '#');
$this->Breadcrumbs->add('資産分類', ['controller' => 'Classifications', 'action' => 'index']);

?>

<!-- widget grid -->
<section id="widget-grid">

    <!-- widget grid row -->
    <div class="row">

        <!-- ********************************** -->
        <!-- User List                          -->
        <!-- ********************************** -->
        <?= $this->element('Parts/side-tree-classifications', [
            'title'     => '資産分類'
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
                    <span class="widget-icon"> <i class="fa fa-lg fa-building"></i> </span>
                    <h2>資産分類詳細</h2>
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

                        <!-- form -->
                        <?= $this->Form->create(null, ['id' => 'form-classification', 'type' => 'post', 'class' => "smart-form"]) ?>

                            <header>
                                基本情報
                            </header>

                            <fieldset>
                                <!-- 表示名 -->
                                <section>
                                    <label class="input">
                                        <input type="text" name="classification.kname" id="kname" class="input-xs"
                                               data-app-form="form-classification"
                                               placeholder="表示名　－　最大20文字／識別キーとなるので重複しないようにしてください／特殊文字は利用しないでください"
                                               disabled="disabled">
                                    </label>
                                </section>
                                <!-- 分類名 -->
                                <section>
                                    <label class="input">
                                        <input type="text" name="classification.name" id="name" class="input-xs"
                                               data-app-form="form-classification" placeholder="分類名（正式名称）　－　最大40文字"
                                               disabled="disabled">
                                    </label>
                                </section>
                                <!-- 補足（コメント） -->
                                <section>
                                    <label class="textarea textarea-resizable">
                                        <textarea name="classification.remarks" id="remarks" row="3" class="custom-scroll"
                                                  data-app-form="form-classification" placeholder="【任意】補足（コメント）"
                                                  disabled="disabled"></textarea>
                                    </label>
                                </section>
                                <!-- 利用可否 -->
                                <section>
                                    <?= $this->element('Parts/select-dsts', [
                                        'name' => 'classification.dsts',
                                        'id'   => 'dsts',
                                        'form' => 'form-classification',
                                    ]) ?>
                                </section>
                            </fieldset>

                            <header>
                                親分類
                            </header>

                            <fieldset>
                                <!-- 親分類 -->
                                <section>
                                    <div class="form-group">
                                        <select name="classification.parent_id" id="parent_id" class="select2 form-control input-sm"
                                               data-app-form="form-classification" data-placeholder="親となる資産分類を入力・選択してください（親が資産カテゴリの場合は空白）"
                                               disabled="disabled"
                                               style="width=100%;"></select>
                                    </div>
                                </section>
                            </fieldset>

                            <header>
                                カテゴリ
                            </header>

                            <fieldset>
                                <!-- カテゴリ -->
                                <section>
                                    <?= $this->element('Parts/select-categories', [
                                        'categories' => $categories,
                                        'name' => 'classification.category_id',
                                        'id'   => 'category_id',
                                        'form' => 'form-classification'
                                    ]) ?>
                                </section>
                            </fieldset>

                        <!-- End form -->
                        <input type="hidden" name="classification.id" id="id" data-app-form="form-classification">
                        <?= $this->Form->end() ?>
                        </form>

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
<?php $this->Html->script('wnote/wnote.tree.classifications.js', ['block' => true]); ?>
<?php $this->Html->script('wnote/master/admin/classifications.index.js', ['block' => true]); ?>

