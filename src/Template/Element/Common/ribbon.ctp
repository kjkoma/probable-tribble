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
$this->Breadcrumbs->templates([
    'wrapper' => '<ol class="breadcrumb">{{content}}</ol>',
    'item' => '<li><a href="{{url}}">{{title}}</a></li>'
]);

?>
<!-- RIBBON -->
<div id="ribbon">

  <span class="ribbon-button-alignment"> 
    <span id="refresh" class="btn btn-ribbon" data-action="resetWidgets" data-title="refresh" rel="tooltip"
          data-placement="bottom"
          data-original-title="<i class='text-warning fa fa-warning'></i> ページをリロードします。"
          data-html="true"
          data-reset-msg="ページを再表示します。入力内容を保存していない場合は入力内容が失われます。ページを再表示してよろしいですか？">
      <i class="fa fa-refresh"></i>
    </span> 
  </span>

    <!-- breadcrumb -->
    <!-- <ol class="breadcrumb">
    <? // $this->fetch('breadcrumb') ?>
  </ol> -->
    <?= $this->Breadcrumbs->render() ?>
    <!-- end breadcrumb -->

</div>
<!-- END RIBBON -->
