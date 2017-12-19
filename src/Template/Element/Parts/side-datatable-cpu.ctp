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
 * CPU選択用一覧を表示する
 *  
 * - - -
 * @param array $cpus CPU一覧データ（製造元情報含む）
 */
?>
<!-- widget -->
<article class="col-sm-4 sortable-grid ui-sortable">

  <!-- widget ID -->
  <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-0"
    data-widget-deletebutton="false"
    data-widget-editbutton="false"
    data-widget-colorbutton="false"
    data-widget-togglebutton="false"
    data-widget-sortable="false">

    <!-- widget header -->
    <header>
      <span class="widget-icon"> <i class="fa fa-lg fa-list"></i> </span>
      <h2>CPU</h2>
    </header>

    <!-- widget body -->
    <div class="widget-body">

      <table id="side-datatable-cpu" class="table table-hover" width="100%">
        <thead>
          <tr>
            <th class="hasinput" style="width:25%;"><input type="text" class="form-control" placeholder="製造元" /></th>
            <th class="hasinput" style="width:75%;"><input type="text" class="form-control" placeholder="CPU名" /></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($cpus as $cpu) { ?>
            <tr>
              <td data-id="<?= $cpu['id'] ?>" data-app-action-key="side-datatable-cpu">
                <?= $cpu['company']['kname'] ?>
              </td>
              <td data-id="<?= $cpu['id'] ?>" data-app-action-key="side-datatable-cpu">
                <?= $cpu['kname'] ?>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>

    <!-- End widget body -->
    </div>

  <!-- End widget ID -->
  </div>

<!-- End widget -->
</article>

