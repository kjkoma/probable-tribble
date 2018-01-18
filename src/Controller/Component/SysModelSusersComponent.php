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
use Cake\ORM\TableRegistry;

/**
 * システムユーザー（Susers）へのアクセス用コンポーネント
 * 
 */
class SysModelSusersComponent extends AppComponent
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
        $config['modelName'] = 'Susers';
        parent::initialize($config);
    }

    /**
     * システムユーザー一覧（WNote管理者を除く）を取得する
     *  
     * - - -
     * @param boolean $toArray true:配列で返す|false:ResultSetで返す（default）
     * @return array システムユーザー一覧
     */
    public function valid($toArray = false)
    {
        $query = $this->modelTable->find('valid');
        $query->innerJoinWith('Sroles', function ($q) {
            return $q->where(['Sroles.role_type <>' => Configure::read('WNote.DB.Sroles.RoleType.wnote')]);
        });

        return ($toArray) ? $query->toArray() : $query->all();
    }

    /**
     * 全システムユーザー一覧（WNote管理者を除く）を取得する
     *  
     * - - -
     * @param boolean $toArray true:配列で返す|false:ResultSetで返す（default）
     * @return array システムユーザー一覧
     */
    public function all($toArray = false)
    {
        $query = $this->modelTable->find('sorted');
        $query->innerJoinWith('Sroles', function ($q) {
            return $q->where(['Sroles.role_type <>' => Configure::read('WNote.DB.Sroles.RoleType.wnote')]);
        });

        return ($toArray) ? $query->toArray() : $query->all();
    }

    /**
     * トークンよりシステムユーザーを取得する
     *  
     * - - -
     * @param string $token
     * @return \App\Model\Entity\Suser システムユーザー
     */
    public function findByToken($token)
    {
        return $this->modelTable->find('valid')
            ->where(['token' => $token])
            ->first();
    }

    /**
     * 指定された識別子のユニーク性を確認する
     *  
     * - - -
     * @param string $email Emailアドレス
     * @param integer $suser_id システムユーザーID
     * @return boolean true:ユニーク|false:すでに存在している
     */
    public function validateEmail($email, $suser_id)
    {
        return $this->validateUnique('email', $email, $suser_id);
    }

    /**
     * ユーザーを検索する（kname or name）
     *   ※全ユーザー検索の「*」は不可
     * - - -
     * @param string $search 検索文字列
     * @return array ユーザー一覧（select2用id/textペア）
     */
    public function find2List($search)
    {
        $query = $this->modelTable->find('valid');

        if (!is_null($search) && $search !== '') {
            $query->andWhere(function($exp) use ($search) {
                return $exp->or_([
                    'kname like ' => '%' . $search . '%',
                    'sname like ' => '%' . $search . '%',
                    'fname like ' => '%' . $search . '%',
                ]);
            });
        } else {
            $query->where(['1 = 0']); // 検索不可
        }

        $list = $query->all();

        $suser = [];
        foreach($list as $item) {
            array_push($suser, [
                'id'   => $item['id'],
                'text' => $item['kname']
            ]);
        }

        return $suser;
    }

}