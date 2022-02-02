<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 31.05.2015
 */

namespace skeeks\cms\controllers;

use skeeks\cms\backend\controllers\BackendModelStandartController;
use skeeks\cms\models\CmsUserPhone;
use skeeks\cms\modules\admin\controllers\AdminModelEditorController;
use skeeks\yii2\form\fields\HtmlBlock;
use skeeks\yii2\form\fields\TextField;
use yii\base\Event;
use yii\helpers\ArrayHelper;

/**
 * Class AdminUserEmailController
 * @package skeeks\cms\controllers
 */
class AdminUserPhoneController extends BackendModelStandartController
{
    public function init()
    {
        $this->name = "Управление телефонами";
        $this->modelShowAttribute = "value";
        $this->modelClassName = CmsUserPhone::className();

        parent::init();

    }


    /**
     * @inheritdoc
     */
    public function actions()
    {
        $actions = ArrayHelper::merge(parent::actions(), [
            "create" => [
                'fields' => [$this, 'updateFields'],
                'buttons' => ['save']
            ],
            "update" => [
                'fields' => [$this, 'updateFields'],
                'buttons' => ['save']
            ],
        ]);

        return $actions;
    }

    public function updateFields($action)
    {
        $model = $action->model;
        $model->load(\Yii::$app->request->get());

        $result = [
            [
                'class' => HtmlBlock::class,
                'content' => '<div class="row no-gutters"><div class="col-12" style="max-width: 300px;">'
            ],
            'value' => [
                'class' => TextField::class,
                'elementOptions' => [
                    'placeholder' => 'Телефон'
                ],
                'on beforeRender' => function (Event $e) {
                    /**
                     * @var $field Field
                     */
                    $field = $e->sender;
                    \skeeks\cms\admin\assets\JqueryMaskInputAsset::register(\Yii::$app->view);
                    $id = \yii\helpers\Html::getInputId($field->model, $field->attribute);
                    \Yii::$app->view->registerJs(<<<JS
                        $("#{$id}").mask("+7 999 999-99-99");
JS
                    );
                },
            ],
            [
                'class' => HtmlBlock::class,
                'content' => '</div><div class="col-12" style="max-width: 300px;">'
            ],
            'name' => [
                'class' => TextField::class,
                'elementOptions' => [
                    'placeholder' => 'Например рабочий телефон'
                ]
            ],
            [
                'class' => HtmlBlock::class,
                'content' => '</div></div>'
            ],
            [
                'class' => HtmlBlock::class,
                'content' => '<div style="display: none;">'
            ],
            'cms_user_id',
            [
                'class' => HtmlBlock::class,
                'content' => '</div>'
            ],

        ];

        return $result;
    }

}
