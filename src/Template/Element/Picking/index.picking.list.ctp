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
<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-list"
     data-widget-deletebutton="false"
     data-widget-editbutton="false"
     data-widget-colorbutton="false"
     data-widget-togglebutton="false"
     data-widget-sortable="false">

    <!-- DETAILS widget header -->
    <header role="heading">
        <span class="widget-icon"> <i class="fa fa-lg fa-th-list"></i> </span>
        <h2>出庫依頼一覧</h2>
    </header>

    <!-- content -->
    <div role="content">

        <table class="table table-striped table-bordered table-hover" id="plans-datatable">
            <thead>
                <tr>
                    <th>出庫区分</th>
                    <th>予定日</th>
                    <th>依頼日</th>
                    <th>依頼者</th>
                    <th>出庫先</th>
                    <th>分類</th>
                    <th>製品</th>
                    <th>モデル</th>
                    <th>シリアル</th>
                    <th>資産管理</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>

        <!-- End content -->
    </div>

    <!-- End widget ID -->
</div>



