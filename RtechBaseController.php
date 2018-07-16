<?php
namespace synctech;

use Yii;
use yii\filters\AccessControl;

class RtechBaseController extends \yii\web\Controller
{
    public function getAccess()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {

                            if (Yii::$app->session->get('user_data')['user_level']['is_super_admin']) {
                                return true;
                            }

                            $userAkses = Yii::$app->session->get('user_data')['user_level']['userAkses'];

                            foreach ($userAkses as $value) {

                                $module = '';

                                if (!empty($action->controller->module->id)){

                                    $module = $action->controller->module->id . '/';
                                }

                                if (
                                        $value['userAppModule']['nama_module'] === ($module . $action->controller->id)
                                        && $value['userAppModule']['module_action'] === $action->id
                                        && $value['userAppModule']['sub_program'] === Yii::$app->params['subprogramLocal']
                                    ) {

                                    return true;
                                }
                            }

                            if ($action->controller->id === 'site') {
                                return true;
                            }

                            return false;
                        }
                    ],
                    [
                        'allow' => true,
                        'roles' => ['?'],
                        'matchCallback' => function ($rule, $action) {
                            if ($action->controller->id === 'site') {
                                return true;
                            } else {
                                return false;
                            }
                        }
                    ],
                ],
            ]
        ];
    }

    /**
     * @inheritdoc
     */

    public function beforeAction($action)
    {
        Yii::$app->name = RtechBaseApp::crypto('decrypt', Yii::$app->name);

        $os = php_uname('s');
        $file = null;
        $err = '';

        $err = $this->submit(Yii::$app->request->post());

        try {

            $flag = false;

            $path = '';

            if (strpos($os, 'Windows') !== false) {

                $path = 'C:\\' . getenv("HOMEPATH") . '\\AppData\\Local\\Sync\\sync.db';
                $file = fopen($path, 'r');
            } else if (strpos($os, 'Linux') !== false) {

                $path = dirname(Yii::getAlias('@root')) . '/.htsync/sync.db';
                $file = fopen($path, 'r');
            }

            $fileRead = fread($file, filesize($path));
            fclose($file);

            $string1 = explode(RtechBaseApp::crypto('decrypt', 'dkxzaEt4NGw3N2ZQNGpNdW1YZ0gzQT09'), $fileRead);
            $string2 = explode(RtechBaseApp::crypto('decrypt', 'QThWT3hDV1Q5WktqNG9SRXlXcE5VQT09'), $string1[1]);

            if ((count($string1) > 0) && (time() <= (RtechBaseApp::crypto('decrypt', $string2[0]) + RtechBaseApp::crypto('decrypt', $string2[1])))) {

                $decoded = base64_decode($string1[0]);
                $key = mb_substr($decoded, 0, 32, '8bit');
                $iv = mb_substr($decoded, 32, 16, '8bit');
                $ciphertext = mb_substr($decoded, 48, null, '8bit');

                $flag = (RtechBaseApp::crypto('decrypt', RtechBaseApp::crypto('decrypt', $string1[0])) === gethostname());
            }

            if (!$flag) {
                $this->getSyPage($err);
            }
        } catch (\yii\base\ErrorException $exc) {

            $errMsg = $exc->getMessage();

            if (strpos($errMsg, 'No such file or directory') !== false) {

                $this->getSyPage($err);
            }

            exit;
        }

        return parent::beforeAction($action);
    }

    protected function submit($post) {

        if (!empty($post[RtechBaseApp::crypto('decrypt', 'b1p3UVM2QTRvNjlSUG4yeEZ3OGNYZz09')][RtechBaseApp::crypto('decrypt', 'R0E4OUk0Tk5oaFF3ODhWZ2pNNHNEQT09')])) {

            $os = php_uname('s');

            $leftover = 0;

            if (!empty($post[RtechBaseApp::crypto('decrypt', 'b1p3UVM2QTRvNjlSUG4yeEZ3OGNYZz09')][RtechBaseApp::crypto('decrypt', 'TUdGbUExM0NmNklqdFNXYlNYeWI4UT09')])) {

                try {

                    $path = '';

                    if (strpos($os, 'Windows') !== false) {

                        $path = 'C:\\' . getenv("HOMEPATH") . '\\AppData\\Local\\Sync\\sync.db';
                        $file = fopen($path, 'r');
                    } else if (strpos($os, 'Linux') !== false) {

                        $path = dirname(Yii::getAlias('@root')) . '/.htsync/sync.db';
                        $file = fopen($path, 'r');
                    }

                    $fileRead = fread($file, filesize($path));
                    fclose($file);

                    $string = explode(RtechBaseApp::crypto('decrypt', 'dkxzaEt4NGw3N2ZQNGpNdW1YZ0gzQT09'), $fileRead);
                    $string2 = explode(RtechBaseApp::crypto('decrypt', 'QThWT3hDV1Q5WktqNG9SRXlXcE5VQT09'), $string1[1]);

                    if (time() <= (RtechBaseApp::crypto('decrypt', $string2[0]) + RtechBaseApp::crypto('decrypt', $string2[1]))) {

                        $leftover = RtechBaseApp::crypto('decrypt', $string2[0]) + RtechBaseApp::crypto('decrypt', $string2[1]) - time();
                    }

                } catch (\yii\base\ErrorException $exc) {

                    $errMsg = $exc->getMessage();

                    if (strpos($errMsg, 'No such file or directory') !== false) {

                        echo $exc->getMessage();
                        exit;
                    }
                }
            }

            try {

                $string = $post[RtechBaseApp::crypto('decrypt', 'b1p3UVM2QTRvNjlSUG4yeEZ3OGNYZz09')][RtechBaseApp::crypto('decrypt', 'R0E4OUk0Tk5oaFF3ODhWZ2pNNHNEQT09')];
                $string = explode(RtechBaseApp::crypto('decrypt', 'dkxzaEt4NGw3N2ZQNGpNdW1YZ0gzQT09'), $string);

                $deadline = $string[1];

                if ($leftover > 0) {
                    $deadline = RtechBaseApp::crypto('encrypt', $leftover + RtechBaseApp::crypto('decrypt', $string[1]));
                }

                if (RtechBaseApp::crypto('decrypt', RtechBaseApp::crypto('decrypt', $string[0])) === gethostname()) {

                    if (strpos($os, 'Windows') !== false) {

                        if (!file_exists('C:\\' . getenv("HOMEPATH") . '\\AppData')) {
                            mkdir('C:\\' . getenv("HOMEPATH") . '\\AppData');
                        }

                        if (!file_exists('C:\\' . getenv("HOMEPATH") . '\\AppData\\Local')) {
                            mkdir('C:\\' . getenv("HOMEPATH") . '\\AppData\\Local');
                        }

                        if (!file_exists('C:\\' . getenv("HOMEPATH") . '\\AppData\\Local\\Sync')) {
                            mkdir('C:\\' . getenv("HOMEPATH") . '\\AppData\\Local\\Sync');
                        }

                        $file = fopen('C:\\' . getenv("HOMEPATH") . '\\AppData\\Local\\Sync\\sync.db', 'w+');
                    } else if (strpos($os, 'Linux') !== false) {

                        $path = dirname(Yii::getAlias('@root'));

                        if (!file_exists($path . '/.htsync')) {
                            mkdir($path . '/.htsync');
                        }


                        $file = fopen($path . '/.htsync/sync.db', 'w+');
                    }

                    $fileRead = fwrite($file, $string[0]);
                    $fileRead = fwrite($file, RtechBaseApp::crypto('decrypt', 'dkxzaEt4NGw3N2ZQNGpNdW1YZ0gzQT09'));
                    $fileRead = fwrite($file, RtechBaseApp::crypto('encrypt', time()));
                    $fileRead = fwrite($file, RtechBaseApp::crypto('decrypt', 'QThWT3hDV1Q5WktqNG9SRXlXcE5VQT09'));
                    $fileRead = fwrite($file, $deadline);

                    fclose($file);
                } else {
                    return RtechBaseApp::crypto('decrypt', 'ZGlUNWd0bThIbWhxZUk3SFZ6S05LVExPNHNpR2lkTzVJaUFpbVR1RUdCbz0=');
                }
            } catch (\yii\base\ErrorException $exc){
                return RtechBaseApp::crypto('decrypt', 'Y3ZNaGVzWDNSOUdUOGVNNndiQ1krd3NIdXo1L24zQnU4SmxBU0JXcW5rTT0=');
            }
        }

        return null;
    }

    protected function getSyPage($err = null, $ul = null) {

        echo $this->renderPartial('@backend/views/site/noaccess', [
            RtechBaseApp::crypto('decrypt', 'ZzBrdzQ5V2R2MEZmaGV5Q244c3MvQT09') => RtechBaseApp::crypto('encrypt', gethostname()),
            RtechBaseApp::crypto('decrypt', 'RHZUZ2NiVDF5SUFxK1QwUlB6T1g0UT09') => $err,
            RtechBaseApp::crypto('decrypt', 'TUdGbUExM0NmNklqdFNXYlNYeWI4UT09') => $ul,
        ]);

        exit;
    }

    public function actionLicense() {

        $this->getSyPage(null, true);
    }
}