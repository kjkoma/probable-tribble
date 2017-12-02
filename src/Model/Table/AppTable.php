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
namespace App\Model\Table;

use ArrayObject;
use App\Model\Entity\AppConvertTrait;
use Cake\Log\Logtrait;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\I18n\Time;
use Cake\ORM\Query;
use Cake\ORM\Table;

/**
 * アプリケーション用テーブルの共通親クラス
 */
class AppTable extends Table
{
    /**
     * 変換用トレイト
     */
    use AppConvertTrait;

    /**
     * ログ出力用トレイト
     */
    use LogTrait;

/* properties */

    /**
     * デフォルト選択値
     *  
     * 配列例）['property name1', 'property name2']
     */
    protected $_selected = array();

    /**
     * デフォルトソート順
     *  
     * 配列例）['property name1' => 'ASC', 'property name2' => 'DESC']
     */
    protected $_sorted = array();

    /**
     * 認証ユーザー
     *  
     */
    protected $_appUser;

/* event */

    /**
     * 初期化メソッド
     * 
     * - - -
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->addBehavior('Timestamp', [
                'events' => [
                        'Model.beforeSave' => [
                                'created_at'  => 'new',
                                'modified_at' => 'always',
                            ],
                    ],
            ]);

        if (array_key_exists('appUser', $config)) {
            $this->_appUser = $config['appUser'];
        }
    }

    /**
     * beforeFindイベント
     * 
     * - - -
     * @param \Cake\Event\Event $event イベント
     * @param \Cake\ORM\Query $query クエリオブジェクト
     * @param ArrayObject $options オプション
     * @param boolean $primary プライマリ
     * @return \Cake\ORM\Query クエリ
     */
    public function beforeFind(Event $event, Query $query, ArrayObject $options, $primary)
    {
        // 現在のドメインIDが指定されている場合、ドメインIDを付与する
        if ($this->_currentDomainId()) {
            if (!array_key_exists('allowWithoutDomainId', $options)
                || $options['allowWithoutDomainId'] != "true") {
                $query = $query->where(['domain_id' => $this->_currentDomainId()]);
            }
        }
        return $query;
    }

/* Finders */

    /**
     * ソートされた全てのデータを取得する
     * 
     * - - -
     * @param \Cake\ORM\Query $query クエリオブジェクト
     * @param array $options なし
     * @return \Cake\ORM\Query 実行可能なクエリオブジェクト
     */
    public function findSorted(Query $query, array $options)
    {
        if (count($this->_selected) > 0) {
            $query->select($this->_selected);
        }

        if (count($this->_sorted) > 0) {
            $query->order($this->_sorted);
        }

        return $query;
    }

    /**
     * ソートされた使用中のデータを取得する
     * 
     * - - -
     * @param \Cake\ORM\Query $query クエリオブジェクト
     * @param array $options なし
     * @return \Cake\ORM\Query 実行可能なクエリオブジェクト
     */
    public function findValid(Query $query, array $options)
    {
        if (count($this->_selected) > 0) {
            $query->select($this->_selected);
        }

        $query->where(['dsts' => Configure::read('WNote.DB.Dsts.valid')]);

        if (count($this->_sorted) > 0) {
            $query->order($this->_sorted);
        }

        return $query;
    }

    /**
     * ソートされた使用中、且つ、有効なデータを取得する
     *  
     * 有効なデータはstart_date、end_dateの比較において判定する
     * - - -
     * @param \Cake\ORM\Query $query クエリオブジェクト
     * @param array $options { 'target': yyyy-mm-dd（比較日付） }
     * @return \Cake\ORM\Query 実行可能なクエリオブジェクト
     */
    public function findValidTerm(Query $query, array $options)
    {
        $target = Time::now()->format('Y-m-d');
        $target = isset($options['target']) ? $options['target'] : $target;

        if (count($this->_selected) > 0) {
            $query->select($this->_selected);
        }

        $query->where([
                'use_flg' => Configure::read('Doman.DB.UseFlg.usage'),
                'start_date <=' => $target,
                'end_date >=' => $target,
            ]);

        if (count($this->_sorted) > 0) {
            $query->order($this->_sorted);
        }

        return $query;
    }

    /**
     * ソートされた有効なデータを取得する
     *  
     * 有効なデータはstart_date、end_dateの比較において判定する
     * - - -
     * @param \Cake\ORM\Query $query クエリオブジェクト
     * @param array $options { 'target': yyyy-mm-dd（比較日付） }
     * @return \Cake\ORM\Query 実行可能なクエリオブジェクト
     */
    public function findValidTermAll(Query $query, array $options)
    {
        $target = Time::now()->format('Y-m-d');
        $target = isset($options['target']) ? $options['target'] : $target;

        if (count($this->_selected) > 0) {
            $query->select($this->_selected);
        }

        $query->where([
                'start_date <=' => $target,
                'end_date >=' => $target,
            ]);

        if (count($this->_sorted) > 0) {
            $query->order($this->_sorted);
        }

        return $query;
    }

/* Functions */

    /**
     * (beforeMarshal用)$dataの指定プロパティを全角→半角に変換し、トリムした値を設定する
     *  
     * ※プロパティ有無の判定分が必要な場合の利用を想定
     * - - -
     * @param ArrayObject $data 変換データ
     * @param array|string $property プロパティ配列、または、プロパティ
     * @return void
     */
    protected function _zen2hanT($data, $property)
    {
        if (is_array($property)) {
            foreach($property as $value) {
                if (isset($data[$value])) {
                    $data[$value] = $this->zen2hanT($data[$value]);
                }
            }
        } else {
            if (isset($data[$property])) {
                $data[$property] = $this->zen2hanT($data[$property]);
            }
        }
    }

    /**
     * 認証ユーザーIDを返す
     *  
     * - - -
     * @return integer|null 認証ユーザーID
     */
    protected function _userId()
    {
        if ($this->_appUser) {
             return $this->_appUser->user()['id'];
        }
        return null;
    }

    /**
     * 現在のドメインIDを返す
     *  
     * - - -
     * @return integer|null 現在のドメインID
     */
    protected function _currentDomainId()
    {
        if ($this->_appUser) {
             return $this->_appUser->current();
        }
        return null;
    }

}