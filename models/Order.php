<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%orders}}".
 *
 * @property int $id
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int $status
 *
 * @property ProductsInOrder[] $productsInOrders
 * @property Product[] $products
 */
class Order extends \yii\db\ActiveRecord
{
    const STATUS_NEW = 0;
    const STATUS_SHIPPING = 1;
    const STATUS_CLOSED = 2;

    const STATUS_NAMES = [
        0 => 'Формирование',
        1 => 'Отгрузка',
        2 => 'Закрыт',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%orders}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            [['status'], 'required'],
            [['status'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Номер заказа',
            'created_at' => 'Создан',
            'updated_at' => 'Обновлён',
            'status' => 'Статус',
            'statusText' => 'Статус',
        ];
    }

    /**
     * Gets query for [[ProductsInOrders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProductsInOrders()
    {
        return $this->hasMany(ProductsInOrder::className(), ['order_id' => 'id']);
    }

    /**
     * Gets query for [[Products]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['id' => 'product_id'])->viaTable('{{%products_in_order}}', ['order_id' => 'id']);
    }

    public function getStatusText(): ?string
    {
        return self::STATUS_NAMES[$this->status];
    }

    public function setStatusText(string $value)
    {
        $this->status = array_flip(self::STATUS_NAMES)[$value];
    }

    public function save($runValidation = true, $attributeNames = null)
    {
        $this->updated_at = date('Y-m-d H:i:s');
        parent::save($runValidation, $attributeNames);
    }
}
