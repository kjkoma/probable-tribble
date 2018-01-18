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

/**
 * カテゴリ（Categories）へのアクセス用コンポーネント
 *  
 * ※データ取得時は親コンポーネントのtableメソッドを利用し、ドメイン指定でデータ取得すること
 * 
 */
class ModelCategoriesComponent extends AppModelComponent
{
    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     * @param array $config コンフィグ
     * @return void
     */
    public function initialize(array $config)
    {
        $config['modelName'] = 'Categories';
        parent::initialize($config);
    }

    /**
     * 選択リスト用の一覧を取得する
     *  
     * - - -
     * @return array 選択リスト用の一覧
     */
    public function selectList($toArray = false)
    {
        $categories = $this->modelTable->find('valid')->all();
        $array = [];
        foreach($categories as $category) {
            $array[] = [ $category['id'] => $category['kname'] ];
        }

        return $array;
    }

}
