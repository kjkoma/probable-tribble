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
 * 棚卸差分（数量差分）表示 ウィジット - 本パーツは直接呼出し不可
 *  
 * - - -
 * @param mixed $conf 表示/入力の設定情報
 */
?>
<!-- ********************************** -->
<!-- 棚卸差分（数量差分）               -->
<!-- ********************************** -->

<header class="text-right <?= (!$conf['edit']) ? 'hidden' : '' ?>">
    <button type="button" class="btn btn-primary" data-app-action-key="elem-stocktake-unmatch-save"><i class="fa fa-save"></i>　選択在庫を棚卸数で更新する</button>
</header>

<br class="<?= (!$conf['edit']) ? 'hidden' : '' ?>">

<p class="note text-right">※最大500件表示</p>

<table class="table table-striped table-bordered table-hover" id="elemStocktakeUnmatch_unmatch-datatable">
    <thead>
        <tr>
            <th>差分区分</th>
            <th>分類</th>
            <th>製品名</th>
            <th>モデル名</th>
            <th>シリアル</th>
            <th>資産管理</th>
            <th>棚卸数</th>
            <th>在庫数</th>
            <th>在庫数(現時点)</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>

