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
 * 単品入庫（資産入庫） ウィジット
 *   index.ctp内での読込用
 */

?>
<!-- widget ID -->
<div class="jarviswidget jarviswidget-color-blueDark hidden" id="wid-id-asset"
     data-widget-deletebutton="false"
     data-widget-editbutton="false"
     data-widget-colorbutton="false"
     data-widget-togglebutton="false"
     data-widget-sortable="false">

    <!-- DETAILS widget header -->
    <header role="heading">
        <span class="widget-icon"> <i class="fa fa-lg fa-check"></i> </span>
        <h2>資産単品入庫</h2>
    </header>

    <!-- content -->
    <div role="content">

        <!-- DETAILS widget body -->
        <div class="widget-body">

            <!-- form -->
            <?= $this->Form->create(null, ['id' => 'form-instock-asset', 'type' => 'post', 'class' => "smart-form"]) ?>

            <fieldset>
                <!-- 資産ID/シリアル -->
                <section>
                    <label class="input">
                        <input type="text" id="asset_search_key" class="input-sm"
                               placeholder="資産ID／シリアル入力">
                    </label>
                </section>
            </fieldset>

            <fieldset>
                <table class="table table-striped table-bordered table-hover" id="plan-asset-datatable">
                    <thead>
                        <tr>
                            <th>入庫区分</th>
                            <th>予定日</th>
                            <th>件名</th>
                            <th>資産管理</th>
                            <th>シリアル</th>
                            <th>分類</th>
                            <th>製品名</th>
                            <th>備考</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </fieldset>

            <footer>
                <button type="button" class="btn btn-primary" data-app-action-key="asset-save"> 入庫を確定する </button>
            </footer>

            <!-- End form -->
            <?= $this->Form->end() ?>

            <!-- End widget body -->
        </div>

        <!-- End content -->
    </div>

    <!-- End widget ID -->
</div>


