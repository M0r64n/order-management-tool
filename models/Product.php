<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%products}}".
 *
 * @property int $id
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string $name
 * @property float $price
 * @property int|null $quantity
 *
 * @property ProductsInOrder[] $inOrder
 * @property Order[] $orders
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%products}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'price'], 'required'],
            [['price'], 'number'],
            [['quantity'], 'integer'],
            [['name'], 'string', 'max' => 99],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Создан',
            'updated_at' => 'Изменён',
            'name' => 'Наименование',
            'price' => 'Цена',
            'quantity' => 'Кол-во',
        ];
    }

    /**
     * Gets query for [[ProductsInOrders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInOrder()
    {
        return $this->hasMany(ProductsInOrder::className(), ['product_id' => 'id']);
    }

    /**
     * Gets query for [[Orders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['id' => 'order_id'])->viaTable('{{%products_in_order}}', ['product_id' => 'id']);
    }

    public function save($runValidation = true, $attributeNames = null)
    {
        $this->updated_at = date('Y-m-d H:i:s');
        return parent::save($runValidation, $attributeNames);
    }
}
