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
 * CPU（Cpus）へのアクセス用コンポーネント
 *  
 * ※データ取得時は親コンポーネントのtableメソッドを利用し、ドメイン指定でデータ取得すること
 * 
 */
class ModelCpusComponent extends AppModelComponent
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
        $config['modelName'] = 'Cpus';
        parent::initialize($config);
    }

    /**
     * ソートされた全一覧を取得する（メーカー情報を含む）
     *  
     * - - -
     * @param boolean $toArray true:配列で返す|false:ResultSetで返す（default）
     * @return array ソートされた全一覧（ResultSet or Array）
     */
    public function allWithCompany($toArray = false)
    {
        $query = $this->modelTable->find('sorted')
            ->contain('Companies');

        return ($toArray) ? $query->toArray() : $query->all();
    }

    /**
     * CPUを検索する（kname or name）
     *  
     * - - -
     * @param string $search 検索文字列
     * @return array CPU一覧（select2用id/textペア）
     */
    public function find2List($search)
    {
        $query = $this->modelTable->find('valid')
            ->andWhere(function($exp) use ($search) {
                return $exp->or_([
                    'kname like ' => '%' . $search . '%',
                    'name like ' => '%' . $search . '%',
                ]);
            });
        $list = $query->all();

        $cpus = [];
        foreach($list as $item) {
            array_push($cpus, [
                'id'   => $item['id'],
                'text' => $item['kname']
            ]);
        }

        return $cpus;
    }
}
