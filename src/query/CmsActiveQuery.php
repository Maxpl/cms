<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 09.03.2015
 */

namespace skeeks\cms\query;

use skeeks\cms\components\Cms;
use skeeks\cms\models\CmsSite;
use yii\db\ActiveQuery;

/**
 * Class CmsActiveQuery
 * @package skeeks\cms\query
 */
class CmsActiveQuery extends ActiveQuery
{
    public $is_active = true;

    /**
     * @param bool $state
     * @return $this
     */
    public function active($state = true)
    {
        if ($this->is_active === true) {
            return $this->andWhere([$this->getPrimaryTableName().'.is_active' => $state]);
        }

        return $this->andWhere([$this->getPrimaryTableName().'.active' => ($state == true ? Cms::BOOL_Y : Cms::BOOL_N)]);
    }

    /**
     * @param bool $state
     * @return $this
     */
    public function default($state = true)
    {
        if ($state === true) {
            return $this->andWhere([$this->getPrimaryTableName().'.is_default' => 1]);
        } else {
            return $this->andWhere(['!=', $this->getPrimaryTableName().'.is_default', 1]);
        }
    }
    /**
     * @param int $order
     * @return $this
     */
    public function sort($order = SORT_ASC)
    {
        return $this->orderBy([$this->getPrimaryTableName().'.priority' => $order]);
    }

    /**
     * Фильтрация по сайту
     * @param int|CmsSite $cmsSite
     *
     * @return CmsActiveQuery
     */
    public function cmsSite($cmsSite = null)
    {
        $cms_site_id = null;

        if (is_int($cmsSite)) {
            $cms_site_id = $cmsSite;
        } elseif ($cmsSite instanceof CmsSite) {
            $cms_site_id = $cmsSite->id;
        } else {
            $cms_site_id = \Yii::$app->skeeks->site->id;
        }

        $alias = $this->getPrimaryTableName();

        if ($this->from) {
            foreach ($this->from as $code => $table) {
                if ($table == $alias) {
                    $alias = $code;
                }
            }
        }

        return $this->andWhere([$alias.'.cms_site_id' => $cms_site_id]);
    }

    /**
     * @param string $word
     * @return $this
     */
    public function search($word = '')
    {
        $modelClass = $this->modelClass;
        if ($modelClass::getTableSchema()->columns) {
            $where = [];
            $where[] = "or";
            foreach ($modelClass::getTableSchema()->columns as $key => $column)
            {
                $where[] = ['like', $this->getPrimaryTableName() . "." . $key, $word];
            }

            $this->andWhere($where);
        }

        return $this;
    }


    /**
     * @depricated
     *
     * @param bool $state
     * @return $this
     */
    public function def($state = true)
    {
        return $this->andWhere(['def' => ($state == true ? Cms::BOOL_Y : Cms::BOOL_N)]);
    }
}
