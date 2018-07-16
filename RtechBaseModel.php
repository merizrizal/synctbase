<?php

namespace synctech;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;
use yii\imagine\Image;


class RtechBaseModel extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                ],
                'value' => function($event) {
                    return Yii::$app->formatter->asDatetime(time());
                },
            ],
            'attribute' => [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['user_created', 'user_updated'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'user_updated',
                ],
                'value' => function($event) {
                    if (Yii::$app->user->isGuest) {
                        return null;
                    } else {
                        return Yii::$app->user->identity->id;
                    }
                },
            ],
        ];
    }

    public function thumb($basePath, $field, $width, $height, $keepRatio = false) {

        if (!empty($this->$field)) {

            $filename = Yii::getAlias('@uploads') . $basePath . $width . 'x' . $height . ($keepRatio ? 'ratio' : '') . $this->$field;

            if (!file_exists($filename)) {

                try {

                    if (!$keepRatio) {

                        Image::thumbnail('@uploads' . $basePath . $this->$field, $width, $height)
                                ->save($filename, ['quality' => 100]);
                    } else {

                        Image::resize('@uploads' . $basePath . $this->$field, $width, $height)
                                ->save($filename , ['quality' => 100]);
                    }
                } catch (\Exception $exc) {

                }
            }

            return $basePath . $width . 'x' . $height . ($keepRatio ? 'ratio' : '') . $this->$field;
        }
    }

}
