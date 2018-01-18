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
$this->assign('title', 'CPU');
$this->Breadcrumbs->add('Home', ['prefix' => false, 'controller' => 'Home', 'action' => 'home']);
$this->Breadcrumbs->add('マスタ（一般）', '#');
$this->Breadcrumbs->add('CPU', ['controller' => 'Cpus', 'action' => 'index']);

?>

<!-- widget grid -->
<section id="widget-grid">

    <!-- widget grid row -->
    <div class="row">

        <!-- ********************************** -->
        <!-- CPU List                           -->
        <!-- ********************************** -->
        <?= $this->element('Parts/side-datatable-cpu', [
            'cpus' => $cpus
        ]) ?>

        <!-- ********************************** -->
        <!-- Company Details                    -->
        <!-- ********************************** -->

        <!-- DETAILS widget -->
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
                    <span class="widget-icon"> <i class="fa fa-lg fa-user"></i> </span>
                    <h2>CPU詳細</h2>
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
                        <?= $this->Form->create(null, ['id' => 'form-cpu', 'type' => 'post', 'class' => "smart-form"]) ?>

                        <header>
                            基本情報
                        </header>

                        <fieldset>
                            <!-- 表示名 -->
                            <section>
                                <label class="input">
                                    <input type="text" name="cpu.kname" id="kname" class="input-xs"
                                           data-app-form="form-cpu"
                                           placeholder="表示名　－　最大30文字／特殊文字は利用しないでください"
                                           disabled="disabled">
                                </label>
                            </section>
                            <!-- 正式名 -->
                            <section>
                                <label class="input">
                                    <input type="text" name="cpu.name" id="name" class="input-xs"
                                           data-app-form="form-cpu"
                                           placeholder="CPU名（正式名称）　－　最大80文字"
                                           disabled="disabled">
                                </label>
                            </section>
                            <!-- 製造元 -->
                            <section>
                                <?= $this->element('Parts/select-makers', [
                                    'makers' => $makers,
                                    'name'   => 'cpu.maker_id',
                                    'id'     => 'maker_id',
                                    'form'   => 'form-cpu',
                                    'blank'  => false,
                                ]) ?>
                            </section>
                            <!-- 製品ステータス -->
                            <section>
                                <?= $this->element('Parts/select-snames', [
                                    'snames'  => $psts,
                                    'name'    => 'cpu.psts',
                                    'id'      => 'psts',
                                    'form'    => 'form-cpu',
                                    'default' => 1
                                ]) ?>
                            </section>
                            <!-- 販売開始日 -->
                            <section>
                                <label class="input">
                                    <i class="icon-append fa fa-calendar"></i>
                                    <input type="text" name="cpu.sales_start" id="sales_start" class="input-sm datepicker"
                                           data-dateformat="yy/mm/dd"
                                           data-app-form="form-cpu"
                                           placeholder="【任意】販売開始日　－　yyyy/mm/dd形式で入力してください（例：2014/01/01）"
                                           disabled="disabled">
                                </label>
                            </section>
                            <!-- 販売終了日 -->
                            <section>
                                <label class="input">
                                    <i class="icon-append fa fa-calendar"></i>
                                    <input type="text" name="cpu.sales_end" id="sales_end" class="input-sm datepicker"
                                           data-dateformat="yy/mm/dd"
                                           data-app-form="form-cpu"
                                           placeholder="【任意】販売終了日　－　yyyy/mm/dd形式で入力してください（例：2017/10/09）"
                                           disabled="disabled">
                                </label>
                            </section>
                            <!-- 補足（コメント） -->
                            <section>
                                <label class="textarea textarea-resizable">
                                    <textarea name="cpu.remarks" id="remarks" rows="3" class="custom-scroll"
                                              data-app-form="form-cpu"
                                              placeholder="【任意】補足（コメント）"
                                              disabled="disabled"></textarea>
                                </label>
                            </section>
                            <!-- 利用可否 -->
                            <section>
                                <?= $this->element('Parts/select-dsts', [
                                    'name' => 'cpu.dsts',
                                    'id'   => 'dsts',
                                    'form' => 'form-cpu',
                                ]) ?>
                            </section>
                        </fieldset>

                        <!-- End form -->
                        <input type="hidden" name="cpu.id" id="id" data-app-form="form-cpu">
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
<?php $this->Html->script('wnote/master/general/cpus.index.js', ['block' => true]); ?>

