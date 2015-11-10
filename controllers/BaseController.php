<?php

namespace yeesoft\controllers;

use yeesoft\behaviors\AccessFilter;
use Yii;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\NotFoundHttpException;

abstract class BaseController extends Controller
{

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'access-filter' => [
                'class' => AccessFilter::className(),
            ],
        ];
    }

    /**
     * @return array
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function init()
    {
        parent::init();

        if(!Yii::$app->errorHandler->exception){

            // If there is a post-request, redirect the application 
            // to the provided url of the selected language
            if (Yii::$app->getRequest()->post('language', NULL)) {
                $language = Yii::$app->getRequest()->post('language');

                if (!isset(Yii::$app->params['languages'][$language])) {
                    throw new NotFoundHttpException();
                }

                $multilingualReturnUrl = Yii::$app->getRequest()->post($language);
                $this->redirect($multilingualReturnUrl);
            }

            // Set the application lang if provided by GET, session or cookie
            if ($language = Yii::$app->getRequest()->get('language', NULL)) {

                if (!isset(Yii::$app->params['languages'][$language])) {
                    throw new NotFoundHttpException();
                }

                Yii::$app->language = $language;
                Yii::$app->session->set('language', $language);
                Yii::$app->response->cookies->add(new Cookie([
                    'name' => 'language',
                    'value' => Yii::$app->session->get('language'),
                    'expire' => time() + 31536000 // a year
                ]));
            } else if (Yii::$app->session->has('language')) {

                $language = Yii::$app->session->get('language');
                if (!isset(Yii::$app->params['languages'][$language])) {
                    throw new NotFoundHttpException();
                }
                Yii::$app->language = $language;
            } else if (isset(Yii::$app->request->cookies['language'])) {

                $language = Yii::$app->request->cookies['language']->value;
                if (!isset(Yii::$app->params['languages'][$language])) {
                    throw new NotFoundHttpException();
                }
                Yii::$app->language = $language;
            }
        
        }
    }

    /**
     * Render ajax or usual depends on request
     *
     * @param string $view
     * @param array $params
     *
     * @return string|\yii\web\Response
     */
    protected function renderIsAjax($view, $params = [])
    {
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax($view, $params);
        } else {
            return $this->render($view, $params);
        }
    }
}