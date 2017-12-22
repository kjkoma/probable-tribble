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
 * 製品モデル（ProductModels）へのアクセス用コンポーネント
 *  
 * ※データ取得時は親コンポーネントのtableメソッドを利用し、ドメイン指定でデータ取得すること
 * 
 */
class ModelProductModelsComponent extends AppModelComponent
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
        $config['modelName'] = 'ProductModels';
        parent::initialize($config);
    }

    /**
     * 製品IDよりモデル／型一覧を取得する（メーカー情報を含む）
     *  
     * - - -
     * @param string $productId 製品ID
     * @param boolean $toArray true:配列で返す|false:ResultSetで返す（default）
     * @return array ソートされた全一覧（ResultSet or Array）
     */
    public function getByProductId($productId, $toArray = false)
    {
        $query = $this->modelTable->find('sorted')
            ->where(['product_id' => $productId])
            ->contain([
                'Cpus',
                'ProductModelsMemoryUnit',
                'ProductModelsStorageType',
                'ProductModelsStorageUnit',
                'ProductModelsSupportTermType'
            ]);

        return ($toArray) ? $query->toArray() : $query->all();
    }

    /**
     * 結果セットよりデータテーブル（フロントのjQuery Datatable）用配列を作成する
     *  
     * - - -
     * @param array $models モデル一覧の結果セット（ResultSet）
     * @return array CPU一覧（select2用id/textペア）
     */
    public function makeDatatableArray($models)
    {
        $table = [];
        foreach($models as $model) {
            array_push($table, [
                'id'      => $model['id'],
                'kname'   => $model['kname'],
                'cpu'     => $model['cpus']['kname'],
                'memory'  => $model['memory'] . $model['product_models_memory_unit']['name2'],
                'storage' => $model['product_models_storage_type']['name2'] . ' ' . $model['storage_vol'] . $model['product_models_storage_unit']['name2']
            ]);
        }

        return $table;
    }

}
