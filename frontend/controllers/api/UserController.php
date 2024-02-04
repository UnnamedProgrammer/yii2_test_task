<?php


namespace frontend\controllers\api;


use yii\base\Controller;
use yii\helpers\Json;
use \common\models\User;
use Yii;

class UserController extends Controller
{
    /* Действия API */

    public function actionUser()
    {
        xdebug_break();
        $request = Yii::$app->request;
        $post = $request->get();
        if ($request->isGet) {
            try {
            $user = User::find()->where(['id' => $post['id']])->one();
            if ($user == null) {
                $json = Json::encode(['status' => 'failure', 'data' => 'No such user'], JSON_FORCE_OBJECT);
                return $json;
            }
            } catch (\Exception $e) {
                $json = Json::encode(['status' => 'failure', 'data' => $e->getMessage()], JSON_FORCE_OBJECT);
                return $json;
            }
        }
        $json = Json::encode(['status' => 'success', 'data' => $user], JSON_FORCE_OBJECT);
        return $json;
    }

    public function actionList()
    {   
        $user = User::find()->all();
        $json = Json::encode(['status' => 'success', 'data' => $user], JSON_FORCE_OBJECT);
        return $json;
    }

    public function actionCreate()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            try {
                $post = $request->post();
                $user = new User();
                $user->username = $post['username'];
                $user->email = $post['email'];
                $user->password_hash = $post['password_hash'];
                $user->status = User::STATUS_ACTIVE;
                $user->auth_key = Yii::$app->security->generateRandomString();
                $user->save();
            } catch (\Exception $e) {
                $json = Json::encode(['status' => 'failure', 'data' => $e->getMessage()], JSON_FORCE_OBJECT);
                return $json;
            }
            $json = Json::encode(['status' => 'success', 'data' => $user->username . " created."], JSON_FORCE_OBJECT);
            return $json;
        }
        $json = Json::encode(['status' => 'failure', 'data' => 'Request is not POST'], JSON_FORCE_OBJECT);
        return $json;
    }

    public function actionUpdate()
    {
        $request = Yii::$app->request;
        $post = $request->post();
        if ($request->isPut) {
            try {
                $user = User::find()->where(['id' => $post['id']])->one();
                $user->username = $post['username'];
                $user->email = $post['email'];
                $user->auth_key = Yii::$app->security->generateRandomString();
                $user->password_hash = $post['password_hash'];
                $user->password_reset_token = Yii::$app->security->generateRandomString();
                $user->status = User::STATUS_ACTIVE;
                $user->save();
            } catch (\Exception $e) {
                $json = Json::encode(['status' => 'failure', 'data' => $e->getMessage()], JSON_FORCE_OBJECT);
                return $json;
            }
            $json = Json::encode(['status' => 'success', 'data' => $user->username . " changed."], JSON_FORCE_OBJECT);
            return $json;
        }
        $json = Json::encode(['status' => 'failure', 'data' => 'Request is not PUT'], JSON_FORCE_OBJECT);
        return $json;
    }
    public function actionDelete()
    {
        xdebug_break();
        $request = Yii::$app->request;
        $post = $request->post();
        if ($request->isDelete) {
            try {
                $user = User::find()->where(['id' => $post['id']])->one();
                $user->delete();
                $json = Json::encode(['status' => 'success', 'data' => $user->username . " deleted."], JSON_FORCE_OBJECT);
                return $json;
            } catch (\Exception $e) {
                $json = Json::encode(['status' => 'failure', 'data' => $e->getMessage()], JSON_FORCE_OBJECT);
                return $json;
            }
        }
        $json = Json::encode(['status' => 'failure', 'data' => 'Request is not DELETE'], JSON_FORCE_OBJECT);
        return $json;
    }
}