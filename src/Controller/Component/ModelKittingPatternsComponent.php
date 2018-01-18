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
 * キッティングパターン（KittingPatterns）へのアクセス用コンポーネント
 *  
 * ※データ取得時は親コンポーネントのtableメソッドを利用し、ドメイン指定でデータ取得すること
 * 
 */
class ModelKittingPatternsComponent extends AppModelComponent
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
        $config['modelName'] = 'KittingPatterns';
        parent::initialize($config);
    }

    /**
     * キッティングパターンを検索する（kname）
     *  
     * - - -
     * @param string $search 検索文字列
     * @param string $patternKbn パターン区分
     * @param string $patternType パターンタイプ
     * @param string $reuseKbn 再利用区分
     * @return array 製品一覧（select2用id/textペア）
     */
    public function find2List($search, $patternKbn = null, $patternType = null, $reuseKbn = null)
    {
        $query = $this->modelTable->find('valid');

        if (!is_null($search) && $search !== '' && $search != '*') {
            $query->andWhere(function($exp) use ($search) {
                return $exp->or_([
                    'kname like ' => '%' . $search . '%'
                ]);
            });
        }

        if (!is_null($patternKbn) && $patternKbn !== '') {
            $query->andWhere([
                'pattern_kbn' => $patternKbn
            ]);
        }
        if (!is_null($patternType) && $patternType !== '') {
            $query->andWhere([
                'pattern_type' => $patternType
            ]);
        }
        if (!is_null($reuseKbn) && $reuseKbn !== '') {
            $query->andWhere([
                'reuse_kbn' => $reuseKbn
            ]);
        }
        $list = $query->all();

        $products = [];
        foreach($list as $item) {
            array_push($products, [
                'id'   => $item['id'],
                'text' => $item['kname']
            ]);
        }

        return $products;
    }

}
