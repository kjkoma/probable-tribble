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

use App\Model\Entity\AppConvertTrait;
use Cake\Core\Configure;
use Cake\Controller\Component;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;

/**
 * 本アプリケーション（システム）のコンポーネントの親となるコンポーネント
 *  
 * 各コンポーネントの共通の処理を行うコンポーネント
 *  
 */
class AppComponent extends Component
{
    /**
     * 文字列変換ユーティリティトレイト
     */
    use AppConvertTrait;

    /**
     * コンポーネントのロード
     *
     */
    public $components = ['AppLog'];

    /**
     * @var mixed $modelTable 本コンポーネントが扱う主たるモデルのテーブルオブジェクト
     */
    public $modelTable;

    /**
     * @var \App\Controller\Component\AppUserComponent $_appUser 認証ユーザー
     */
    protected $_appUser;

    /**
     * @var \Cake\Database\Connection $_con データベースへの接続コネクション
     */
    private $_con;

    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     * @param array $config コンフィグ
     *     [
     *         'modelName' => テーブルオブジェクトに登録するモデル名(string),
     *         'modelConfig' => テーブルオブジェクトの登録時のコンフィグ(array)
     *     ]
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setup($config);
    }

    /**
     * 本クラスの再初期化を行う
     *  
     * - - -
     * @param array $config コンフィグ
     * @return void
     */
    public function reset(array $config)
    {
        $this->setup($config, false);
    }

    /**
     * 本クラスの初期設定を行う
     *  
     * - - -
     * @param array $config コンフィグ
     * @param boolean $init true:初期化時/false:再作成時
     * @return void
     */
    public function setup(array $config, $init = true)
    {
        $this->_appUser   = array_key_exists('appUser'    , $config) ? $config['appUser']     : null;
        $modelName        = array_key_exists('modelName'  , $config) ? $config['modelName']   : null;
        $modelConfig      = array_key_exists('modelConfig', $config) ? $config['modelConfig'] : [];
        $this->modelTable = is_null($modelName) ? null : $this->table($modelName, $modelConfig, $init);
        $this->_con       = ConnectionManager::get('default');
    }

    /**
     * 認証ユーザーを取得する
     *  
     * - - -
     * @return \App\Model\Entity\Suser 認証ユーザーのユーザー情報を返す
     */
    public function user()
    {
        return ($this->_appUser->user()) ? $this->_appUser->user()['id'] : null;
    }

    /**
     * 現在のドメインを取得する
     *  
     * - - -
     * @return integer 現在のドメインIDを返す
     */
    public function current()
    {
        return $this->_appUser->current();
    }

    /**
     * テーブルオブジェクトを返す
     *  
     * - - -
     * @param string $modelName テーブルオブジェクトに登録するモデル名
     * @param array $modelConfig テーブルオブジェクトの登録時のコンフィグ
     * @param boolean $init true:初期化時/false:再作成時
     * @return \Cake\ORM\Table テーブルオブジェクト
     */
    public function table($modelName, $modelConfig = [], $init = true)
    {
        if (!$init) {
            TableRegistry::remove($modelName);
        }

        if (TableRegistry::exists($modelName)) {
            return TableRegistry::get($modelName);
        }

        return TableRegistry::get($modelName, $modelConfig);
    }

    /**
     * コネクションを返す
     *  
     * - - -
     * @return \Cake\Database\Connection コネクション
     */
    public function connection()
    {
        return $this->_con;
    }

    /**
     * トランザクションを開始する
     *  
     * - - -
     */
    public function begin()
    {
        $this->_con->begin();
    }


    /**
     * コミットする
     *  
     * - - -
     */
    public function commit()
    {
        $this->_con->commit();
    }

    /**
     * ロールバックする
     *  
     * - - -
     */
    public function rollback()
    {
        $this->_con->rollback();
    }


    /**
     * エンティティ保存結果のオブジェクトを返す
     *  
     * - - -
     * @param mixed $result 結果
     * @param mixed $data データ
     * @param mixed $errors エラー
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    protected function _result($result, $data, $errors = 'false')
    {
        return [
            'result' => $result,
            'data'   => $data,
            'errors' => $errors
        ];
    }

    /**
     * エンティティデータ不正時にエラーステータスと指定されたメッセージを返す
     *  
     * - - -
     * @param mixed $msg エラーメッセージ
     * @param mixed $data データ
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    protected function _invalid($msg, $data = false)
    {
        return [
            'result' => false,
            'data'   => $data,
            'errors' => ['error_message' => $msg]
        ];
    }

    /**
     * 新規エンティティを取得する
     *  
     * - - -
     * @return \App\Model\Entity\Domain 新規エンティティ
     */
    public function newEntity()
    {
        return $this->modelTable->newEntity();
    }

    /**
     * 指定されたエンティティを取得する
     *  
     * - - -
     * @param mixed $id 取得するエンティティのキー
     * @return mixed 指定されたキーを持つエンティティ
     */
    public function get($id)
    {
        return $this->modelTable->findById($id)->first();
    }

    /**
     * 指定されたエンティティのデータステータスが「使用中」の一覧を取得する
     *  
     * - - -
     * @param boolean $toArray true:配列で返す|false:ResultSetで返す（default）
     * @return array 「使用中」の一覧（ResultSet or Array）
     */
    public function valid($toArray = false)
    {
        $query = $this->modelTable->find('valid');

        return ($toArray) ? $query->toArray() : $query->all();
    }

    /**
     * 指定されたエンティティのソートされた全一覧を取得する
     *  
     * - - -
     * @param boolean $toArray true:配列で返す|false:ResultSetで返す（default）
     * @return array ソートされた全一覧（ResultSet or Array）
     */
    public function all($toArray = false)
    {
        $query = $this->modelTable->find('sorted');

        return ($toArray) ? $query->toArray() : $query->all();
    }

    /**
     * 指定されたエンティティを保存する（新規登録）
     *  
     * - - -
     * @param mixed $data 保存するデータ
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function add($data)
    {
        $entity = $this->modelTable->newEntity($data);
        $entity['created_user']  = $this->user();
        $entity['modified_user'] = $this->user();

        if (is_null($entity['dsts']) || $entity['dsts'] === '') {
            $entity['dsts'] = Configure::read('WNote.DB.Dsts.valid');
        }

        $result = $this->modelTable->save($entity);
        if (!$result || $entity->errors()) {
            return $this->_result(false, false, $entity->errors());
        }

        return $this->_result(true, $result, false);
    }

    /**
     * 指定されたエンティティを保存する（更新）
     *  
     * - - -
     * @param mixed $data 保存するデータ
     * @param array $options finderのオプション（ドメイン指定なしでも検索可能な場合などに利用）
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function save($data, $options = [])
    {
        if (!array_key_exists('id', $data)) {
            return $this->_invalid(['message' => '保存データが指定されていません。', 'data' => $data]);
        }

        if ($this->modelTable->find('all', $options)->where(['id' => $data['id']])->count() == 0) {
            return $this->_invalid(['message' => '指定されたデータが存在しません。', 'data' => $data]);
        }

        $entity = $this->modelTable->get($data['id'], $options);
        $entity = $this->modelTable->patchEntity($entity, $data);
        $entity['modified_user'] = $this->user();
        $entity['modified_at']   = Time::now()->i18nFormat('yyyy-MM-dd HH:mm:ss');

        $result = $this->modelTable->save($entity);
        if (!$result || $entity->errors()) {
            return $this->_result(false, false, $entity->errors());
        }

        return $this->_result(true, $result, false);
    }

    /**
     * 指定されたエンティティを削除する
     *  
     * - - -
     * @param mixed $key 削除するデータのキー
     * @param array $options finderのオプション（ドメイン指定なしでも検索可能な場合などに利用）
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function delete($key, $options = [])
    {
        if (!$key) {
            return $this->_invalid(['message' => '削除データが指定されていません。', 'key' => $key]);
        }

        if ($this->modelTable->find('all', $options)->where(['id' => $key])->count() == 0) {
            return $this->_invalid(['message' => '指定されたデータが存在しません。', 'key' => $key]);
        }

        $entity = $this->modelTable->get($key, $options);
        $result = $this->modelTable->delete($entity);
        if (!$result || $entity->errors()) {
            return $this->_result(false, false, $entity->errors());
        }

        return $this->_result(true, $result, false);
    }

    /**
     * 指定条件のエンティティを削除する
     *  
     * - - -
     * @param mixed $conditions 削除するデータの条件
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function deleteAll($conditions)
    {
        $result = $this->modelTable->deleteAll($conditions);

        return $this->_result(true, $result, false);
    }

    /**
     * 指定されたフィールドの一意性を検証する
     *  
     * - - -
     * @param string  $field フィールド名
     * @param mixed   $data  データ
     * @param integer $id    IDフィールドの値（自分自身を除く場合に指定）
     * @return boolean true:ユニーク|false:すでに存在している
     */
    public function validateUnique($field, $data, $id = null)
    {
        $condition = [$field => $data];
        if ($id && $id !== '') {
            $condition['id <> '] = $id;
        }

        $count = $this->modelTable->find('all')->where($condition)->count();

        return ($count == 0) ? true : false;
    }

    /**
     * 識別子の一意性を検証する
     *  
     * - - -
     * @param mixed   $data  データ
     * @param integer $id    IDフィールドの値
     * @param string  $field フィールド名（デフォルト：「kname」）
     * @return boolean true:ユニーク|false:すでに存在している
     */
    public function validateKname($data, $id, $field = 'kname')
    {
        return $this->validateUnique($field, $data, $id);
    }

    /**
     * 検索条件のパラメータ有無判定を行う
     *  
     * - - -
     * @param string $fieldName フィールド名
     * @param array $condition 検索条件配列
     * @return boolean true:パラメータ指定あり|false:パラメータ指定なし
     */
    public function hasSearchParams($fieldName, $condition)
    {
        if (!array_key_exists($fieldName, $condition)) {
            return false;
        }

        if (is_null($condition[$fieldName])) {
            return false;
        }

        if ($condition[$fieldName] === '') {
            return false;
        }

        return true;
    }

    /**
     * 本日の日付を取得する
     *  
     * - - -
     * @return string 本日日付(yyyy/mm/dd)
     */
    public function today() {
        return Time::now()->i18nFormat('yyyy-MM-dd');
    }
}
