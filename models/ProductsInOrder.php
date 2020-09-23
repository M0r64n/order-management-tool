<?php

namespace app\models;

use Yii;
use yii\web\ForbiddenHttpException;

/**
 * This is the model class for table "{{%products_in_order}}".
 *
 * @property int $product_id
 * @property int $order_id
 * @property int $quantity
 *
 * @property Product $product
 * @property Order $order
 */
class ProductsInOrder extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%products_in_order}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'order_id', 'quantity'], 'required'],
            [['product_id', 'order_id'], 'integer'],
            [
                'quantity',
                'integer',
                'min' => 1,
                'when' => function (ProductsInOrder $model) {
                    if ($model->quantity > $model->product->quantity) {
                        $this->addError('quantity', "Нельзя добавить в заказ больше товара, чем находится на складе ({$model->product->quantity}).");
                        return false;
                    };
                    return true;
                }],
            [['product_id', 'order_id'], 'unique', 'targetAttribute' => ['product_id', 'order_id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'product_id' => 'Product ID',
            'order_id' => 'Order ID',
            'quantity' => 'Кол-во',
        ];
    }

    /**
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    /**
     * Gets query for [[Order]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }

    public function getTotalPrice()
    {
        return $this->quantity * $this->product->price;
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if ($this->order->status === Order::STATUS_CLOSED) {
            throw new ForbiddenHttpException('Вы не можете редактировать закрытый заказ.');
        }

        return true;
    }

    public function beforeDelete()
    {
        if (!parent::beforeDelete()) {
            return false;
        }

        if ($this->order->status === Order::STATUS_CLOSED) {
            throw new ForbiddenHttpException('Вы не можете редактировать закрытый заказ.');
        }

        return true;
    }
}
