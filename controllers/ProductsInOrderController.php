<?php

namespace app\controllers;

use app\models\ProductsInOrder;
use Yii;
use yii\web\Controller;

class ProductsInOrderController extends Controller
{
    public function actionAdd()
    {
        $model = new ProductsInOrder();
        $model->load(Yii::$app->request->post());

        $modelInDb = ProductsInOrder::findOne([
            'order_id' => $model->order_id,
            'product_id' => $model->product_id
        ]);

        if ($modelInDb !== null) {
            $modelInDb->quantity += $model->quantity;
            $model = $modelInDb;
        }

        $model->save();

        return Yii::$app->runAction('order/view', ['id' => $model->order_id, 'relation' => $model]);
    }

    /**
     * @param int $order_id
     * @param int $product_id
     * @return \yii\web\Response
     */
    public function actionRemove($order_id, $product_id)
    {
        ProductsInOrder::findOne([
            'order_id' => $order_id,
            'product_id' => $product_id
        ])->delete();

        return $this->redirect(['/order/view', 'id' => $order_id]);
    }

    /**
     * @param int $order_id
     * @param int $product_id
     * @return \yii\web\Response
     */
    public function actionUpdate($order_id, $product_id)
    {
        $model = ProductsInOrder::find()
            ->where(['order_id' => $order_id, 'product_id' => $product_id])
            ->with('product')
            ->one();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/order/view', 'id' => $order_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

}
