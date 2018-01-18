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
 * 出庫 ウィジット
 *   index.ctp内での読込用
 */

?>

<!-- widget ID -->
<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-picking"
     data-widget-deletebutton="false"
     data-widget-editbutton="false"
     data-widget-colorbutton="false"
     data-widget-togglebutton="false"
     data-widget-sortable="false">

    <!-- widget header -->
    <header role="heading">
        <span class="widget-icon"> <i class="fa fa-lg fa-check"></i> </span>
        <h2>出庫</h2>
    </header>

    <!-- content -->
    <div role="content">

        <!-- DETAILS widget body -->
        <div class="widget-body">

            <!-- form -->
            <?= $this->Form->create(null, ['id' => 'form-input', 'type' => 'post', 'class' => "smart-form"]) ?>

                <fieldset>
                    <!-- シリアル番号 -->
                    <section id="serial-input-section">
                        <label class="input">
                            <input type="text" name="input.serial_no" id="serial_no" class="input-sm"
                                   data-app-form="form-input" placeholder="出庫するシリアル番号を入力してください - 入力後はEnterキーを押すと出庫依頼、資産内容が表示されます"
                                   maxlength="120">
                        </label>
                        <label class="label">注意）バーコードスキャナ―を利用する場合、IME-MODEを"OFF"（英数字入力）にして入力してください。</label>
                    </section>
                </fieldset>

                <footer>
                    <button type="button" class="btn btn-primary" data-app-action-key="fix-picking">出庫する</button>
                </footer>

            <!-- End form -->
            <input type="hidden" name="picking.id" id="id" data-app-form="form-plan">
            <?= $this->Form->end() ?>

            <!-- End DETAILS widget body -->
        </div>

        <!-- End content -->
    </div>

    <!-- End widget ID -->
</div>



