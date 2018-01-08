<?php
namespace frontend\actions;
//>>重写错误提醒页面
class ErrorAction extends \yii\web\ErrorAction{
    protected function renderHtmlResponse()
    {
        return $this->controller->renderPartial($this->view ?: $this->id, $this->getViewRenderParams());

    }
}
