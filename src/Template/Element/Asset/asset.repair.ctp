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
 * 資産表示 ウィジット（修理タブ内のコンテンツ） - 本パーツは直接呼出し不可
 *  
 * - - -
 * @param mixed $conf 表示/入力の設定情報
 */
?>
<!-- ********************************** -->
<!-- 修理情報                       -->
<!-- ********************************** -->
<!-- form -->
<?= $this->Form->create(null, ['id' => 'form-elem-asset-repair', 'type' => 'post', 'class' => "smart-form"]) ?>

    <header>
        修理履歴
    </header>

    <fieldset>

        <table class="table table-striped table-bordered table-hover" id="elemAssetRepair-datatable">
            <thead>
                <tr>
                    <th>修理状況</th>
                    <th>発生日</th>
                    <th>完了日</th>
                    <th>故障区分</th>
                    <th>故障原因</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>

    </fieldset>

<!-- End form -->
<?= $this->Form->end() ?>
