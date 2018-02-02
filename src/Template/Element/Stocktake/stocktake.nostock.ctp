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
 * 棚卸差分（在庫なし）表示 ウィジット - 本パーツは直接呼出し不可
 *  
 * - - -
 * @param mixed $conf 表示/入力の設定情報
 */
?>
<!-- ********************************** -->
<!-- 棚卸差分（在庫なし）               -->
<!-- ********************************** -->

<header class="<?= (!$conf['edit']) ? 'hidden' : '' ?>">
    <div class="row">
        <div class="col-sm-10">
            <?= $this->Form->create(null, ['id' => 'form-elem-stocktake-nostock', 'type' => 'post', 'class' => "smart-form"]) ?>
                <form-group>
                    <label class="input">
                        <input type="text" name="elem-stocktake-nostock.correspond" id="elemStocktakeNostock_correnspond" class="input-sm"
                               data-app-form="form-elem-stocktake-nostock"
                               maxlength=120
                               placeholder="対応内容 - 最大120文字">
                    </label>
                </form-group>
            <?= $this->Form->end() ?>
        </div>
        <div class="col-sm-2 text-right">
            <button type="button" class="btn btn-primary" data-app-action-key="elem-stocktake-nostock-save">対応済に更新する</button>
        </div>
    </div>
</header>

<br class="<?= (!$conf['edit']) ? 'hidden' : '' ?>">

<table class="table table-striped table-bordered table-hover" id="elemStocktakeNostock_nostock-datatable">
    <thead>
        <tr>
            <th>差分区分</th>
            <th>シリアル</th>
            <th>資産管理</th>
            <th>対応区分</th>
            <th>対応内容</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
