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
 * 棚卸サマリ表示 ウィジット - 本パーツは直接呼出し不可
 *  
 * - - -
 * @param mixed $conf 表示/入力の設定情報
 */
?>
<!-- ********************************** -->
<!-- 棚卸サマリ                         -->
<!-- ********************************** -->

<dl class="dl-horizontal">
    <dt>棚卸日：</dt>
        <dd name="elem_stocktake_summary.stocktake_date" id="elemStocktakeSummary_stocktake_date" data-app-form="form-elem_stocktake_summary"></dd>
    <dt>状況：</dt>
        <dd name="elem_stocktake_summary.stocktake_sts_name" id="elemStocktakeSummary_stocktake_sts_name" data-app-form="form-elem_stocktake_summary"></dd>
    <dt>棚卸期間：</dt>
        <dd name="elem_stocktake_summary.stocktake_term" id="elemStocktakeSummary_stocktake_term" data-app-form="form-elem_stocktake_summary"></dd>
    <dt>棚卸担当者：</dt>
        <dd name="elem_stocktake_summary.stocktake_suser_name" id="elemStocktakeSummary_stocktake_suser_name" data-app-form="form-elem_stocktake_summary"></dd>
    <dt>棚卸確認者：</dt>
        <dd name="elem_stocktake_summary.confirm_suser_name" id="elemStocktakeSummary_confirm_suser_name" data-app-form="form-elem_stocktake_summary"></dd>
    <dt>在庫締日：</dt>
        <dd name="elem_stocktake_summary.stocktake_date" id="elemStocktakeSummary_stock_deadline_date" data-app-form="form-elem_stocktake_summary"></dd>
    <dt>補足：</dt>
        <dd name="elem_stocktake_summary.remarks" id="elemStocktakeSummary_remarks" data-app-form="form-elem_stocktake_summary"></dd>
</dl>

<hr>

<div class="row">
    <div class="col-xs-6 col-sm-3">
        <div class="well well-sm bg-color-pinkDark txt-color-white">
            <h5>棚卸数／在庫数（資産管理）</h5>
            <h2><span id="elemStocktakeSummary_area1_stocktake_count" data-app-form="form-elem_stocktake_summary"></span>　/　
                <span id="elemStocktakeSummary_area1_stock_count" data-app-form="form-elem_stocktake_summary"></span></h2>
        </div>
    </div>
    <div class="col-xs-6 col-sm-3">
        <div class="well well-sm bg-color-purple txt-color-white">
            <h5>棚卸数／在庫数（数量管理）</h5>
            <h2><span id="elemStocktakeSummary_area2_stocktake_count" data-app-form="form-elem_stocktake_summary"></span>　/　
                <span id="elemStocktakeSummary_area2_stock_count" data-app-form="form-elem_stocktake_summary"></span></h2>
        </div>
    </div>
    <div class="col-xs-6 col-sm-3">
        <div class="well well-sm bg-color-blueLight txt-color-white">
            <h5>差分（棚卸不足/未対応件数）</h5>
            <h2><span id="elemStocktakeSummary_area3_count" data-app-form="form-elem_stocktake_summary"></span></h2>
        </div>
    </div>
    <div class="col-xs-6 col-sm-3">
        <div class="well well-sm bg-color-teal txt-color-white">
            <h5>差分（在庫不足/未対応件数）</h5>
            <h2><span id="elemStocktakeSummary_area4_count" data-app-form="form-elem_stocktake_summary"></span></h2>
        </div>
    </div>
</div>
