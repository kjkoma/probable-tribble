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
 * 資産表示 ウィジット（貸出／返却タブ内のコンテンツ） - 本パーツは直接呼出し不可
 *  
 * - - -
 * @param mixed $conf 表示/入力の設定情報
 */
?>
<!-- ********************************** -->
<!-- 貸出／返却情報                     -->
<!-- ********************************** -->
<!-- form -->
<?= $this->Form->create(null, ['id' => 'form-elem-asset-rental', 'type' => 'post', 'class' => "smart-form"]) ?>

    <header>
        貸出・返却履歴
    </header>

    <fieldset>
        <table class="table table-striped table-bordered table-hover" id="elemAssetRental-datatable">
            <thead>
                <tr>
                    <th>貸出状況</th>
                    <th>利用者</th>
                    <th>管理者</th>
                    <th>貸出日</th>
                    <th>貸出者</th>
                    <th>返却日</th>
                    <th>返却者</th>
                    <th>受領者</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </fieldset>

<!-- End form -->
<?= $this->Form->end() ?>
