<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appserver\modules\Payment\controllers\paypal;

use fecshop\app\appserver\modules\AppserverController;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class StandardController extends AppserverController
{
    public $enableCsrfValidation = false;
    
    /**
     * 1.start部分，跳转到paypal前的部分
     */
    public function actionStart()
    {
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        return $this->getBlock()->startExpress();
    }
    /**
     * 2.Review  从paypal确认后返回的部分
     */
    public function actionReview()
    {
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        return $this->getBlock('placeorder')->getLastData();
    }
    /**
     * IPN，paypal消息接收部分
     */
    public function actionIpn()
    {
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        \Yii::info('paypal ipn begin', 'fecshop_debug');
       
        $post = Yii::$app->request->post();
        if (is_array($post) && !empty($post)) {
            $post = \Yii::$service->helper->htmlEncode($post);
            ob_start();
            ob_implicit_flush(false);
            var_dump($post);
            $post_log = ob_get_clean();
            \Yii::info($post_log, 'fecshop_debug');
            //Yii::$service->payment->paypal->receiveIpn($post);
        }
    }
    /**
     * paypal 取消后的部分。
     */
    /*
    public function actionCancel()
    {
        $innerTransaction = Yii::$app->db->beginTransaction();
        try {
            if(Yii::$service->order->cancel()){
                $innerTransaction->commit();
            }else{
                $innerTransaction->rollBack();
            }
        } catch (Exception $e) {
            $innerTransaction->rollBack();
        }
        return Yii::$service->url->redirectByUrlKey('checkout/onepage');
    }
    */
}
