<?php

use app\models\Order;
use kartik\editable\Editable;
use kartik\select2\Select2;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $products app\models\Product[] */
/* @var $newRelation app\models\ProductsInOrder */
/* @var $relatedProducts yii\data\ActiveDataProvider */
/* @var $model app\models\Order */

$this->title = "Заказ №{$model->id}";
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Заказы'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="order-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Удалить'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Вы уверены, что хотите удалить этот заказ?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'created_at',
            'updated_at',
            [
                'label' => 'Статус',
                'value' => Editable::widget([
                    'model' => $model,
                    'attribute' => 'status',
                    'inputType' => Editable::INPUT_DROPDOWN_LIST,
                    'data' => Order::STATUS_NAMES,
                    'displayValueConfig' => Order::STATUS_NAMES,
                    'formOptions' => [
                        'action' => Url::to(['update', 'id' => $model->id]),
                    ]
                ]),
                'format' => 'raw'
            ],
            [
                'attribute' => 'totalPrice',
                'label' => 'Общая сумма заказа'
            ],
        ],
    ]) ?>

    <?php if ($model->status !== Order::STATUS_CLOSED): ?>

    <h2>Добавить товар</h2>

    <?php $form = ActiveForm::begin(['action' => '/products-in-order/add']); ?>

    <?= $form->field($newRelation, 'product_id')->widget(Select2::class, [
        'data' => ArrayHelper::map($products, 'id', 'name'),
    ])->label('Товар') ?>

    <?= $form->field($newRelation, 'order_id')->hiddenInput(['value' => $model->id])->label(false) ?>

    <?= $form->field($newRelation, 'quantity')->textInput(['maxLengt' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Добавить'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php endif; ?>

    <h2>Товары</h2>

    <?= GridView::widget([
        'dataProvider' => $relatedProducts,
        'columns' => [
            'product.name',
            'quantity',
            [
                'attribute' => 'totalPrice',
                'label' => 'Сумма'
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'buttons'  => [
                    'update' => function ($url, $model) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-pencil"></span>',
                            ['/products-in-order/update', 'order_id' => $model->order_id, 'product_id' => $model->product_id]);
                    },
                    'delete'  => function ($url, $model) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-trash"></span>',
                            ['/products-in-order/remove', 'order_id' => $model->order_id, 'product_id' => $model->product_id],
                            [
                                'data'  => [
                                    'confirm' => 'Вы уверены, что хотите убрать этот товар из заказа?',
                                    'method'  => 'post',
                                ],
                            ]
                        );
                    },
                ],
            ],
        ]
    ]) ?>
</div>
