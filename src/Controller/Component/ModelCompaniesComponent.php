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

/**
 * 企業（Companies）へのアクセス用コンポーネント
 *  
 * ※データ取得時は親コンポーネントのtableメソッドを利用し、ドメイン指定でデータ取得すること
 * 
 */
class ModelCompaniesComponent extends AppModelComponent
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
        $config['modelName'] = 'Companies';
        parent::initialize($config);
    }

    /**
     * ソートされた全一覧を取得する（企業区分名称を含む）
     *  
     * - - -
     * @param boolean $toArray true:配列で返す|false:ResultSetで返す（default）
     * @return array ソートされた全一覧（ResultSet or Array）
     */
    public function allWithKbn()
    {
        $query = $this->modelTable->find('sorted')
            ->contain('Snames', function($q) {
                return $q
                    ->order(['sort_no' => 'ASC']);
            });

        return ($toArray) ? $query->toArray() : $query->all();
    }

    /**
     * メーカー一覧を取得する
     *  
     * - - -
     * @param boolean $toArray true:配列で返す|false:ResultSetで返す（default）
     * @return array メーカー一覧（ResultSet or Array）
     */
    public function makers()
    {
        $query = $this->modelTable->find('sorted')
            ->where([
                'company_kbn IN' => [
                    Configure::read('WNote.DB.Companies.CompanyKbn.all'),
                    Configure::read('WNote.DB.Companies.CompanyKbn.maker')
                ]
            ]);

        return ($toArray) ? $query->toArray() : $query->all();
    }

}
