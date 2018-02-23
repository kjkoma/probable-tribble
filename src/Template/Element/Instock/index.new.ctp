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
/**
 * 新規入庫 ウィジット
 *   index.ctp内での読込用
 */

?>
<!-- widget ID -->
<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-new"
     data-widget-deletebutton="false"
     data-widget-editbutton="false"
     data-widget-colorbutton="false"
     data-widget-togglebutton="false"
     data-widget-sortable="false">

    <!-- widget header -->
    <header role="heading">
        <span class="widget-icon"> <i class="fa fa-lg fa-check"></i> </span>
        <h2>新規入庫</h2>
    </header>

    <!-- content -->
    <div role="content">

        <!-- widget body -->
        <div class="widget-body">

            <!-- form -->
            <?= $this->Form->create(null, ['id' => 'form-instock-new', 'type' => 'post', 'class' => 'smart-form', 'novalidate' => 'novalidate']) ?>

                <!-- wizard -->
                <div id="new-instock-wizard">

                    <!-- wizard menu -->
                    <div class="form-bootstrapWizard wizard-menu">
                        <ul class="bootstrapWizard">
                            <li class="active" data-target="#wizard-plan-select">
                                <a href="#wizard-plan-select" data-toggle="tab"> <span class="step">1</span> <span class="title">入荷予定選択</span> </a>
                            </li>
                            <li data-target="#wizard-serial-input">
                                <a href="#wizard-serial-input" data-toggle="tab"> <span class="step">2</span> <span class="title">入庫シリアル入力／入庫数入力</span> </a>
                            </li>
                            <li data-target="#wizard-fix">
                                <a href="#wizard-fix" data-toggle="tab"> <span class="step">3</span> <span class="title">入庫内容確認／確定</span> </a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                        <!-- End wizard menu -->
                    </div>

                    <!-- tab contents -->
                    <div class="tab-content">

                        <!-- 入庫予定選択 -->
                        <div class="tab-pane active" id="wizard-plan-select">
                            <br><br>
                            <h3><strong>Step 1 </strong> - 入庫予定選択</h3>
                            <br>

                            <header>
                                入庫予定選択
                            </header>

                            <!-- 入庫予定一覧 -->
                            <fieldset>
                                <table class="table table-striped table-bordered table-hover" id="plan-new-datatable">
                                    <thead>
                                        <tr>
                                            <th>入庫区分</th>
                                            <th>予定日</th>
                                            <th>件名</th>
                                            <th>予定数量</th>
                                            <th>入庫数量</th>
                                            <th>備考</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </fieldset>

                            <header>
                                詳細選択
                            </header>

                            <!-- 入庫予定詳細一覧 -->
                            <fieldset>
                                <table class="table table-striped table-bordered table-hover" id="plan-new-detail-datatable">
                                    <thead>
                                        <tr>
                                            <th>カテゴリ</th>
                                            <th>分類</th>
                                            <th>メーカー</th>
                                            <th>製品</th>
                                            <th>モデル</th>
                                            <th>予定数量</th>
                                            <th>入庫数量</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </fieldset>

                            <!-- End 入庫予定選択 -->
                        </div>

                        <!-- 入庫シリアル入力 -->
                        <div class="tab-pane" id="wizard-serial-input">
                            <br><br>
                            <h3><strong>Step 2 </strong> - 入庫シリアル入力／入庫数入力</h3>
                            <br>

                            <!-- シリアル入力欄 -->
                            <div class="input-serial-area">
                                <fieldset>
                                    <!-- シリアル入力 -->
                                    <section class="col-sm-8">
                                        <label class="input">
                                            <input type="text" name="instock_new.serial_input" id="new_serial_input" class="input-sm"
                                                   data-app-form="form-instock-new"
                                                   placeholder="シリアルを入力してください - 入力後はEnterキーを押して確定してください">
                                        </label>
                                        <label class="label">注意）バーコードスキャナ―を利用する場合、IME-MODEを"OFF"（英数字入力）にして入力してください。</label>
                                    </section>
                                </fieldset>

                                <div class="widget-actions">
                                    <div class="widget-action">
                                        <span>入力件数： <strong id="new_input_count">0</strong> 件　</span>
                                        <a href="javascript:void(0);" class="btn btn-warning" data-app-action-key="serial-delete"><i class="fa fa-minus"></i>　選択シリアル削除</a>
                                        <a href="javascript:void(0);" class="btn btn-warning" data-app-action-key="serial-delete-all"><i class="fa fa-trash"></i>　全てのシリアル削除</a>
                                    </div>
                                </div>

                                <header>
                                    入庫シリアル一覧
                                </header>

                                <fieldset>
                                    <!-- シリアルリスト -->
                                    <section>
                                        <label class="select select-multiple">
                                            <select class="custom-scroll" name="instock_new.serials" id="new_serial_list" class="custom-scroll"
                                                   multiple="multiple"
                                                   size="20"
                                                   data-app-form="form-instock-new"></select>
                                        </label>
                                    </section>
                                </fieldset>
                            <!-- End シリアル入力欄 -->
                            </div>

                            <!-- 数量入力欄 -->
                            <div class="input-count-area hide">
                                <fieldset>
                                    <!-- 数量入力 -->
                                    <section class="col-sm-4">
                                        <label class="input">
                                            <input type="number" name="instock_new.input_instock_count" id="new_input_instock_count" class="input-sm"
                                                   data-app-form="form-instock-new"
                                                   placeholder="入庫数量を入力してください">
                                        </label>
                                    </section>
                                </fieldset>
                            <!-- End 数量入力欄 -->
                            </div>

                            <!-- End 入庫シリアル入力 -->
                        </div>


                        <!-- 入庫内容確認／確定 -->
                        <div class="tab-pane" id="wizard-fix">
                            <br><br>
                            <h3><strong>Step 3 </strong> - 入庫内容確認／確定</h3>
                            <br>

                            <header>
                                入庫予定
                            </header>

                            <fieldset>
                                <dl class="dl-horizontal">
                                    <dt>入庫予定日</dt><dd id="fix_plan_date"></dd>
                                    <dt>入庫件名</dt><dd id="fix_plan_name"></dd>
                                </dl>
                            </fieldset>

                            <header>
                                入庫予定詳細
                            </header>

                            <fieldset>
                                <dl class="dl-horizontal">
                                    <dt>カテゴリ</dt><dd id="fix_category"></dd>
                                    <dt>分類</dt><dd id="fix_classification"></dd>
                                    <dt>メーカー</dt><dd id="fix_maker"></dd>
                                    <dt>製品</dt><dd id="fix_product"></dd>
                                    <dt>モデル</dt><dd id="fix_model"></dd>
                                    <dt>予定入庫数</dt><dd id="fix_plan_count"></dd>
                                    <dt>入庫数（現在）</dt><dd id="fix_instock_count"></dd>
                                    <dt>保守期限日</dt><dd id="fix_support_limit_date"></dd>
                                </dl>
                            </fieldset>

                            <header>
                                入庫内容
                            </header>

                            <fieldset>
                                <dl class="dl-horizontal">
                                    <dt>入庫数量</dt><dd><strong  id="fix_input_count"></strong>　件</dd>
                                    <dt class="input-serial-area">入庫シリアル</dt><dd id="fix_serials"></dd>
                                </dl>
                            </fieldset>

                            <!-- End 入庫シリアル入力 -->
                        </div>
                        <br><br>


                        <!-- form-actions -->
                        <div class="form-actions">
                            <ul class="pager wizard no-margin">
                                <li class="previous disabled">
                                    <a href="javascript:void(0);" class="btn btn-lg btn-default"> <i class="fa fa-step-backward"></i>　前へ戻る </a>
                                </li>
                                <li class="next">
                                    <a href="javascript:void(0);" class="btn btn-lg txt-color-darken"> <i class="fa fa-step-forward"></i>　次へ進む </a>
                                </li>
                                <li class="finish hidden">
                                    <button type="button" class="btn btn-lg btn-primary"> <i class="fa fa-save"></i>　入庫を確定する </button>
                                </li>
                            </ul>
                            <!-- End form-actions -->
                        </div>

                        <!-- End tab contents -->
                    </div>

                    <!-- End wizard -->
                </div>

            <!-- End form -->
            <?= $this->Form->end() ?>

            <!-- End widget body -->
        </div>

        <!-- End content -->
    </div>

    <!-- End widget ID -->
</div>


