<?php
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <!-- Favicon -->
        <link rel="icon" href="<?= Yii::$app->request->baseUrl . '/media/favicon.png' ?>" type="image/x-icon">
        <link rel="shortcut icon" href="<?= Yii::$app->request->baseUrl . '/media/favicon.png' ?>" type="image/x-icon">
        <link rel="apple-touch-icon" href="<?= Yii::$app->request->baseUrl . '/media/favicon.png' ?>">

        <title><?= Html::encode(Yii::$app->name) ?></title>
        <?php
        $this->head(); ?>

    </head>
    <body>
        <?php $this->beginBody() ?>

        <div class="wrap" style="background-color: rgb(100, 50, 70)">
            <div class="container">
                <div class="login-box" style="opacity: 0.8">
                    <div class="login-box-body">
                        <div class="login-logo">
                            <b><?= Html::encode(Yii::$app->name) ?></b>
                        </div>

                        <p class="login-box-msg">Daftarkan Kode Lisensi</b></p>

                        <?= Html::beginForm() ?>

                            <div class="form-group has-error text-center">
                                <p class="help-block"><?= $err ?></p>
                            </div>

                            <div class="form-group field-loginform-username required">
                                <label class="control-label" for="kdRegistrasi">Kode Registrasi</label>
                                <input type="text" id="kdRegistrasi" class="form-control" name="license[kdRegistrasi]" value="<?= $kdRegistrasi ?>" readonly="readonly" style="font-size: 11px">
                                <p class="help-block help-block-error"></p>
                            </div>
                            <div class="form-group field-loginform-username required">
                                <label class="control-label" for="kdLisensi">Kode Lisensi</label>
                                <input type="text" id="kdLisensi" class="form-control" name="license[kdLisensi]" style="font-size: 8px">
                                <p class="help-block help-block-error"></p>
                            </div>

                            <?= Html::hiddenInput('license[updateLisensi]', !empty($updateLisensi) ? $updateLisensi : null) ?>
                            <?= Html::submitButton('Submit', ['class' => 'btn bg-red btn-block', 'name' => 'license[submit]']) ?>

                        <?= Html::endForm() ?>

                    </div>
                </div>
            </div>
        </div>

        <?php $this->endBody(); ?>
    </body>
</html>
<?php $this->endPage() ?>
