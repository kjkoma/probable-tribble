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
 * 使用可否＋新規不可（dsts-2）の選択BOX（Form）を表示する
 *  
 * - - -
 * @param string  $name         name属性名
 * @param string  $id           id属性名
 * @param string  $form         data-app-form属性名
 * @param boolean $disabled     disabled属性（デフォルト:true（disabled属性設定））
 * @param string  $class        クラス名（追加する場合）
 * @param string  $default      デフォルト値（指定する場合）
 */
$name     = isset($name)     ? $name     : "";
$id       = isset($id)       ? $id       : "";
$form     = isset($form)     ? $form     : "";
$disabled = isset($disabled) ? $disabled : true;
$class    = isset($class)    ? $class    : "";
$default  = isset($default)  ? $default  : $this->App->conf('WNote.DB.Dsts.valid');
$disabled_attr = ($disabled) ? 'disabled="disabled"' : '';
?>
<label class="select">
    <select name="<?= $name ?>" id="<?= $id ?>" class="input-sm <?= $class ?>"
            data-app-form="<?= $form ?>" data-app-form-default="<?= $default ?>"
            <?= $disabled_attr ?>>
        <?php foreach($this->AppGlobal->dsts2() as $dsts) { ?>
            <option value="<?= h($dsts['nid']) ?>"><?= h($dsts['name2']) ?></option>
        <?php } ?>
    </select>
    <i></i>
</label>
