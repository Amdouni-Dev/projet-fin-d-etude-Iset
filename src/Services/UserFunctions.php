<?php

namespace App\Services;

class UserFunctions
{

    public function createAvatarFile($username) {
        $file = 'img/users/saves/default.png';
        $newfile = 'img/users/'.$username.'.png';
        if (!copy($file, $newfile)) {
            echo "<p class='failed'>Failed to create new user avatar\n</p>";
        }
        else {
            return $newfile;
        }
    }

    public function changeAvatar($file,$user) {
        $filename = $user->getUsername().'.png';
        $upload = move_uploaded_file($file, "./img/users/$filename");
       
    }
    public function uploaddoc($file,$user,$nomdoc) {
        $filename = $user->getId().$nomdoc.'.pdf';
        $upload = move_uploaded_file($file, "./doc/$filename");
       
    }
    public function uploaddoc2($nomdoc,$user,$doc) {
        $filename = $user->getId().$nomdoc.'.pdf';
        $upload = move_uploaded_file($doc, "./doc2/$filename");
       
    }

    public function roleStr(string $role) {
        switch ($role) {
            case 'ROLE_SUPERUSER':
                return 'Super Admin';
                break;

            case 'ROLE_ADMINISTRATOR':
                return 'Admin';
                break;

            case 'ROLE_CONSULTANT':
                return 'Consultant';
                break;
            case 'ROLE_USER':
                return 'User';
                break;
                case 'ROLE_JEXPERT':
            return 'Jeune Expert';
            break;
            case 'ROLE_PASSOCIATION':
            return 'Propritaire Association';
            break;
        }
    }





}
?>