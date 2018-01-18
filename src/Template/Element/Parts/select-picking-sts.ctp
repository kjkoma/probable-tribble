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
 * 出庫状況の選択BOX（Form）を表示する（出庫済選択不可）
 *  
 * - - -
 * @param array   $snames       名称マスタより取得した指定のリスト
 * @param string  $name         name属性名
 * @param string  $id           id属性名
 * @param string  $form         data-app-form属性名
 * @param boolean $disabled     disabled属性（デフォルト:true（disabled属性設定））
 * @param string  $class        クラス名（追加する場合）
 * @param string  $default      デフォルト値（指定する場合）
 * @param boolean $blank        ブランクオプション有無（デフォルト:false）
 */
$name     = isset($name)     ? $name     : "";
$id       = isset($id)       ? $id       : "";
$form     = isset($form)     ? $form     : "";
$disabled = isset($disabled) ? $disabled : true;
$class    = isset($class)    ? $class    : "";
$default  = isset($default)  ? $default  : "";
$disabled_attr = ($disabled) ? 'disabled="disabled"' : '';
$blank    = isset($blank)    ? $blank    : false;
?>
<label class="select">
    <select name="<?= $name ?>" id="<?= $id ?>" class="input-sm <?= $class ?>"
            data-app-form="<?= $form ?>" data-app-form-default="<?= $default ?>"
            <?= $disabled_attr ?>>
        <?php if ($blank) { ?>
            <option value="">-- 出庫状況選択 --</option>
        <?php } ?>
        <?php foreach($snames as $sname) { ?>
            <?php if ($sname['nid'] != $this->App->conf('WNote.DB.Picking.PickingSts.complete') &&
                      $sname['nid'] != $this->App->conf('WNote.DB.Picking.PickingSts.cancelfix')) { ?>
                <option value="<?= h($sname['nid']) ?>"><?= h($sname['name2']) ?></option>
            <?php } ?>
        <?php } ?>
    </select>
    <i></i>
</label>
