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

?>
<?= $this->Form->create(null, ['url' => '/search', 'id' => 'form-home-search', 'type' => 'post', 'class' => 'header-search pull-right']) ?>
    <input id="criteria" type="text" name="criteria" placeholder="PC名 / 資産ID / シリアル">
    <button type="submit">
        <i class="fa fa-search"></i>
    </button>
    <a href="javascript:void(0);" id="cancel-search-js" title="Cancel Search"><i class="fa fa-times"></i></a>
<?= $this->Form->end() ?>
