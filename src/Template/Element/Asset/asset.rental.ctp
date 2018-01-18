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
<?= $this->Form->create(null, ['id' => 'form-elem-asset-repair', 'type' => 'post', 'class' => "smart-form"]) ?>

    <header>
        貸出／返却情報
    </header>

    <fieldset>
        <section>
            <dl class="dl-horizontal">
                <dt>登録日時：</dt><dd id="elem_assetview_rental.created_at" name="elemAssetviewRental_created_at"></dd>
                <dt>更新日時：</dt><dd id="elem_assetview_rental.modified_at" name="elemAssetviewRental_modified_at"></dd>
                <dt>更新者：</dt><dd id="elem_assetview_rental.modified_user_name" name="elemAssetviewRental_modified_user_name"></dd>
            </dl>
        </section>
    </fieldset>

<!-- End form -->
<?= $this->Form->end() ?>


