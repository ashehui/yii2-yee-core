<?php

use backend\assets\AppAsset;
use yeesoft\widgets\menu\Menu;
use yeesoft\widgets\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="wrap">

    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">

        <?php
        NavBar::begin([
            'brandLabel' => '<b style="font-size:1.1em;">Yee</b> Control Panel',
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar-inverse navbar-static-top',
                'style' => 'margin-bottom: 0'
            ],
            'innerContainerOptions' => [
                'class' => 'container-fluid'
            ]
        ]);

        $menuItems = [

            ['label' => str_replace('http://', '',
                Yii::$app->urlManager->hostInfo), 'url' => Yii::$app->urlManager->hostInfo],
        ];
        if (Yii::$app->user->isGuest) {
            $menuItems[] = ['label' => 'Login', 'url' => ['/auth/login']];
        } else {
            $menuItems[] = [
                'label' => 'Logout (' . Yii::$app->user->identity->username . ')',
                'url' => Yii::$app->urlManager->hostInfo . '/auth/logout',
                'linkOptions' => ['data-method' => 'post']
            ];
        }
        echo Nav::widget([
            'encodeLabels' => false,
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => $menuItems,
        ]);

        NavBar::end();
        ?>

        <!-- SIDEBAR NAV -->
        <?=
        Menu::widget([
            'id' => 'admin-main-menu',
            'dropDownCaret' => '<span class="arrow"></span>',
            'wrapper' => [
                '<div class="navbar-default sidebar" role="navigation">',
                '</div>'
            ],
            'options' => [
                ['class' => 'nav side-menu'],
                ['class' => 'nav nav-second-level'],
                ['class' => 'nav nav-third-level']
            ],
        ])
        ?>
        <!-- !SIDEBAR NAV -->
    </nav>


    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">

                    <?=
                    Breadcrumbs::widget([
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs']
                            : [],
                    ])
                    ?>
                    <?= $content ?>
                </div>

            </div>

        </div>

    </div>

</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left"><a href="http://www.yee-soft.com/" rel="external">Yee</a> Control
            Panel &copy; <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>