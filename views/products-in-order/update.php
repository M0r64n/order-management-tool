<?php
/**
 * @var \app\models\ProductsInOrder $model
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = "Изменить кол-во товара: {$model->product->name} в заказе №{$model->order_id}";
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Заказы'), 'url' => ['/order/index']];
$this->params['breadcrumbs'][] = ['label' => "Заказ №{$model->order_id}", 'url' => ['/order/view', 'id' => $model->order_id]];
$this->params['breadcrumbs'][] = $model->product->name;
?>
<div class="product-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'product_id')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'order_id')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'quantity')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
