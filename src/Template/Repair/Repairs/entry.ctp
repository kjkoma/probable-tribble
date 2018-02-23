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
$this->assign('title', '修理・交換');
$this->Breadcrumbs->add('Home', ['prefix' => false, 'controller' => 'Home', 'action' => 'home']);
$this->Breadcrumbs->add('修理・交換', '#');
$this->Breadcrumbs->add('修理登録', ['controller' => 'Repairs', 'action' => 'entry']);

?>

<!-- widget grid -->
<section id="widget-grid">

    <!-- ********************************** -->
    <!-- Repair Input                       -->
    <!-- ********************************** -->

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
                    <span class="widget-icon"> <i class="fa fa-lg fa-pencil-square-o"></i> </span>
                    <h2>修理情報</h2>

                    <!-- 操作ボタン -->
                    <?php if ($this->AppUser->hasDomainGeneral()) { ?>
                        <div>
                            <div class="widget-toolbar" role="menu" data-app-action-key="add-actions">
                                <a href="javascript:void(0);" class="btn btn-primary" data-app-action-key="save"><i class="fa fa-save"></i>　保存</a>
                            </div>
                        </div>
                    <?php } ?>
                </header>

                <!-- content -->
                <div role="content">

                    <!-- DETAILS widget body -->
                    <div class="widget-body">

                        <!-- Input form -->
                        <?= $this->Form->create(null, ['id' => 'form-condition', 'type' => 'post', 'class' => "smart-form"]) ?>

                            <header>
                                在庫選択
                            </header>

                            <fieldset>

                                <div class="row">
                                    <section class="col col-sm-3">
                                        <!-- シリアル番号 -->
                                        <label class="input">
                                            <input type="text" name="cond.serial_no" id="serial_no" class="input-sm"
                                                   data-app-form="form-condition"
                                                   maxlength=120
                                                   placeholder="シリアル番号">
                                        </label>
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

                                <table class="table table-striped table-bordered table-hover" id="assets-datatable">
                                    <thead>
                                        <tr>
                                            <th>資産状況</th>
                                            <th>資産状況(サブ)</th>
                                            <th>分類</th>
                                            <th>メーカー</th>
                                            <th>製品</th>
                                            <th>モデル・型</th>
                                            <th>シリアル</th>
                                            <th>資産管理</th>
                                            <th>在庫数</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>

                            </fieldset>

                        <!-- End Input form -->
                        <?= $this->Form->end() ?>

                        <!-- Input form -->
                        <?= $this->Form->create(null, ['id' => 'form-repair', 'type' => 'post', 'class' => "smart-form"]) ?>

                            <fieldset>
                                <section>
                                    <!-- 発生日 -->
                                    <label class="input">
                                        <i class="icon-append fa fa-calendar"></i>
                                        <input type="text" name="repair.start_date" id="start_date" class="input-sm datepicker"
                                            data-app-form="form-repair"
                                            data-dateformat="yy/mm/dd"
                                            maxlength=10
                                            placeholder="【必須】発生日 - yyyy/mm/dd形式">
                                    </label>
                                </section>
                                <section>
                                    <!-- 故障区分 -->
                                    <?= $this->element('Parts/select-snames', [
                                        'snames'      => $troubleKbn,
                                        'name'        => 'repair.trouble_kbn',
                                        'id'          => 'trouble_kbn',
                                        'form'        => 'form-repair',
                                        'default'     => '1',
                                        'disabled'    => false,
                                        'placeholder' => '【必須】故障区分'
                                    ]) ?>
                                </section>
                                <!-- 故障原因 -->
                                <section>
                                    <label class="textarea textarea-resizable">
                                        <textarea name="repair.trouble_reason" id="trouble_reason" rows="3" class="custom-scroll"
                                                  data-app-form="form-repair" placeholder="故障原因"></textarea>
                                    </label>
                                </section>
                                <section>
                                    <!-- データ抽出区分 -->
                                    <?= $this->element('Parts/select-snames', [
                                        'snames'      => $datapickKbn,
                                        'name'        => 'repair.datapick_kbn',
                                        'id'          => 'datapick_kbn',
                                        'form'        => 'form-repair',
                                        'default'     => '1',
                                        'disabled'    => false,
                                        'placeholder' => '【必須】データ抽出区分'
                                    ]) ?>
                                </section>
                                <section>
                                    <!-- センドバック区分 -->
                                    <?= $this->element('Parts/select-snames', [
                                        'snames'      => $sendbackKbn,
                                        'name'        => 'repair.sendback_kbn',
                                        'id'          => 'sendback_kbn',
                                        'form'        => 'form-repair',
                                        'default'     => '1',
                                        'disabled'    => false,
                                        'placeholder' => '【必須】センドバック区分'
                                    ]) ?>
                                </section>
                                <!-- 補足（コメント） -->
                                <section>
                                    <label class="textarea textarea-resizable">
                                        <textarea name="repair.remarks" id="remarks" rows="4" class="custom-scroll"
                                                  data-app-form="form-repair" placeholder="補足"></textarea>
                                    </label>
                                </section>
                            </fieldset>

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
<?php $this->Html->script('wnote/repair/repairs.entry.js', ['block' => true]); ?>
