<?php
namespace backend\filters;
use yii\base\ActionFilter;
use yii\web\HttpException;
use yii\widgets\ActiveForm;

class RbacFilter extends ActionFilter{
    /**
     * @param \yii\base\Action $action
     * 代码执行前
     */
    public function beforeAction($action)
    {
        //>>如果路由没权限
        if (!\Yii::$app->user->can($action->uniqueId )){
            //>>如果用户没有登录,则引用用户登录
            if (\Yii::$app->user->isGuest){
                //>>跳转登录
                return $action->controller->redirect(\Yii::$app->user->loginUrl)->send();
            }
            throw new HttpException(403,'没有权限访问,请联系管理员!');
        }
        return true;
    }
}
