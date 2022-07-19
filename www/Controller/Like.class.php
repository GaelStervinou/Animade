<?php

namespace App\Controller;

use App\Core\Exception;
use App\Core\Security;
use App\Helpers\UrlHelper;
use App\Model\Like as LikeModel;

class Like {

    public function like()
    {
        $infosLike = self::getInfosLike();
        $likeExists = $this->alreadyLikePage();
        $like = new LikeModel();
        try {
            if($likeExists === false){
                $like->beginTransaction();

                $like->setAime($infosLike['like']);
                $like->setUserId($infosLike['user_id']);
                $like->setPageId($infosLike['page_id']);

            }else{
                $like = $likeExists;
                $like->beginTransaction();
                if((int)$infosLike['like'] === (int)$like->getAime()) {
                    $like->setAime('-2');
                    echo "reset like";
                }else{
                    $like->setAime($infosLike['like']);
                }
            }

            $like->save();

            $like->commit();
        }catch (Exception $e) {
            $like->rollback();
            Security::returnError(403, $e->getMessage());
        }

        return true;
    }

    public function alreadyLikePage()
    {
        return self::getLike();
    }

    public static function getLike()
    {
        $infosLike = self::getInfosLike();
        unset($infosLike['like']);
        $like = new LikeModel();
        return $like->findOneBy($like->getTable(), $infosLike);
    }

    public static function getInfosLike(): array
    {
        $userId = Security::getUser()->getId();

        return ['user_id' => $userId, 'page_id' => $_GET['page_id'], 'like' => $_GET['like']];
    }


    public function delete()
    {
        $like = UrlHelper::getUrlParameters($_GET)['object'];
        $like->delete();
    }
}