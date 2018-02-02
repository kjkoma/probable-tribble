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
namespace App\Controller\Component;

use Cake\Core\Configure;
use Cake\I18n\Time;

/**
 * 資産属性（AssetAttributes）へのアクセス用コンポーネント
 *  
 * ※データ取得時は親コンポーネントのtableメソッドを利用し、ドメイン指定でデータ取得すること
 * 
 */
class ModelAssetAttributesComponent extends AppModelComponent
{
    /** @var array $components 利用コンポーネント */
    //public $components = [''];

    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     * @param array $config コンフィグ
     * @return void
     */
    public function initialize(array $config)
    {
        $config['modelName'] = 'AssetAttributes';
        parent::initialize($config);
    }

    /**
     * 画面入力より資産属性を登録する
     *  
     * - - -
     * 
     * @param array $asset 資産情報
     * @param array $entry 画面入力情報
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function addEntry($asset, $entry)
    {
        // 資産属性登録情報
        $attr = $entry;
        $attr['domain_id'] = $this->current();
        $attr['asset_id']  = $asset['id'];

        return parent::add($attr);
    }
}

