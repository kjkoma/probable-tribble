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
namespace App\View\Helper;

use Cake\View\Helper;

/**
 * アプリケーション共有のフォームヘルパー
 *  
 */
class AppFormHelper extends Helper
{
    /**
     * Formヘルパーを読み込む
     */
    public $helpers = ['Form'];

    /**
     * 初期化
     *  
     */
    public function initialize(array $config)
    {
    }

    /**
     * CakePHPのFormHelperのinputで生成されたHTMLのルートDIVを削除する
     *  
     * - - -
     * @param string $field_name フィールド名
     * @param array  $options     オプション
     * @return string 生成されたHTML
     */
    public function input($field_name, array $options =[])
    {
        $html = $this->Form->input($field_name, $options);
        $result = preg_match('/^(<div.+?>)(.+?)(<\/div>)$/', $html, $match);

        return ($result && isset($match[2])) ? $match[2] : $html;
    }

    /**
     * インプットテキストのエラーメッセージを表示するHTMLを出力する
     *  
     * - - -
     * @param string $id 項目ID
     * @param array  $errors エラーオブジェクト
     * @return string エラーメッセージ表示用HTML
     */
    public function errorInput($id, array $errors)
    {
        $html     = '';
        $messages = array_column($errors, $id);
        if (count($messages) > 0) {
            $html = '<ul class="input error" data-platm-form-error="' . $id . '">';
            foreach($messages as $something) {
                if (is_array($something)) {
                    foreach($something as $message) {
                        $html .= '<li>' . $message . '</li>';
                    }
                } else {
                    $html .= '<li>' . $something . '</li>';
                }
            }
            $html .= '</ul>';
        }

        return $html;
    }

    /**
     * テーブル内のチェックボックスを表示するHTMLを出力する
     *  
     * - - -
     * @param string $field_name   項目名
     * @param string $field_id     項目ID
     * @param string $field_value  項目値
     * @param array  $selected     選択値のリスト(配列のリスト)
     * @param string $disabled     'disabled' or ''
     * @return string チェックボックス表示用HTML
     */
    public function checkboxInTable($field_name, $field_id, $field_value, array $selected, $disabled)
    {
        $id = $field_name . '[' . $field_value . ']';

        $options = [
                'id'       => $id,
                'value'    => $field_value,
                'class'    => '',
                'disabled' => $disabled,
            ];

        $list = array_column($selected, $field_id);
        if (in_array($field_value, $list, true)) {
            $options['checked'] = 'checked';
        }

        return $this->Form->checkbox($field_name, $options)
                . '<label for="' . $id. '"></label>';
    }
}
