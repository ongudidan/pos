<?php

namespace app\modules\developer\controllers;

use app\components\AuthItemGenerator;
use app\models\AuthItem;
use app\modules\developer\models\AuthItemForm;
use app\modules\developer\models\AuthItemSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AuthItemController implements the CRUD actions for AuthItem model.
 */
class AuthItemController extends Controller
{
    public $layout = 'PosLayout';

    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all AuthItem models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new AuthItemSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        $dataProvider->query->andWhere(['type' => 1]);


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AuthItem model.
     * @param string $name Name
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($name)
    {
        return $this->render('view', [
            'model' => $this->findModel($name),
        ]);
    }

    /**
     * Creates a new AuthItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new AuthItemForm();
        $auth = Yii::$app->authManager;

        // Group permissions by model name
        $permissions = $auth->getPermissions();
        $authItemsGrouped = [];

        foreach ($permissions as $permission) {
            // Assuming permission names follow the 'ModelName-action' convention
            list($modelName, $action) = explode('-', $permission->name, 2);

            // Group by model name
            $authItemsGrouped[$modelName][$action] = $permission->name;
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $parent = $auth->createRole($model->name);
            $parent->description = $model->description;
            $auth->add($parent);

            foreach ($model->children as $childName) {
                $child = $auth->getPermission($childName);
                if ($child) {
                    $auth->addChild($parent, $child);
                }
            }

            Yii::$app->session->setFlash('success', 'Parent item created and children assigned.');
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
            'authItemsGrouped' => $authItemsGrouped,
        ]);
    }

    /**
     * Updates an existing AuthItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $name Name
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */

    public function actionUpdate($name)
    {
        $modelData = AuthItem::findOne($name); // Fetch the existing auth item

        if (!$modelData || $modelData->type !== 1) {
            throw new NotFoundHttpException("The requested auth item does not exist or is not a parent item.");
        }

        // Create a new instance of the form model
        $model = new AuthItemForm();

        // Populate the form model with the retrieved data
        $model->name = $modelData->name; // Name of the parent item
        $model->description = $modelData->description; // Description of the parent item

        // Get existing child permissions for the parent auth item
        $auth = Yii::$app->authManager;
        $existingChildren = $auth->getChildren($model->name);
        $assignedChildren = array_keys($existingChildren); // Array of assigned child item names

        // Set the model's children with currently assigned children
        $model->children = $assignedChildren;

        // Retrieve auth items of type 2 (permissions) to display in the form
        $authItems = AuthItem::find()->where(['type' => 2])->all();

        // Organize auth items by model name for grouped display
        $authItemsGrouped = [];
        foreach ($authItems as $item) {
            [$modelName, $action] = explode('-', $item->name);
            $authItemsGrouped[$modelName][$action] = $item->name;
        }

        // Process form submission
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                // Update parent auth item
                $authItem = $auth->getRole($model->name) ?? $auth->createRole($model->name);
                $authItem->name = $model->name; // Set new name
                $authItem->description = $model->description; // Set new description if needed
                $auth->update($authItem->name, $authItem); // Update the auth item in the database

                // Update child permissions
                $auth->removeChildren($authItem); // Clear current children
                foreach ($model->children as $childName) {
                    $child = $auth->getPermission($childName);
                    if ($child) {
                        $auth->addChild($authItem, $child); // Add new children
                    }
                }

                $transaction->commit();
                Yii::$app->session->setFlash('success', 'Role updated successfully.');
                return $this->redirect(['index']);
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Failed to update role. Please try again.');
            }
        }

        return $this->render('update', [
            'model' => $model, // Pass the populated model to the view
            'authItemsGrouped' => $authItemsGrouped, // Now this variable is defined
        ]);
    }

    /**
     * Deletes an existing AuthItem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $name Name
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($name)
    {
        $this->findModel($name)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the AuthItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $name Name
     * @return AuthItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($name)
    {
        if (($model = AuthItem::findOne(['name' => $name])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    public function actionItemGenerator()
    {
        $searchModel = new AuthItemSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        $dataProvider->query->andWhere(['type' => 1]);

        // // Drop all data from auth_item and auth_item_child tables
        Yii::$app->db->createCommand()->delete('auth_item_child')->execute();
        // Yii::$app->db->createCommand()->delete('auth_item')->execute();

        // // Generate auth items if they don't exist
        $authItemGenerator = new AuthItemGenerator();
        $authItemGenerator->generateAuthItems();


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
