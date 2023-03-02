<?php
namespace App\Controllers;

use App\Models\{User};
use Respect\Validation\Validator as v;

class UserController extends BaseController{
    public function getAddUser(){
        return $this->renderHTML('addUser.twig');
    }

    public function postSaveUser($request){
        $responseMessage = "Fill info required";
        if($request->getMethod() == 'POST'){
            $postData = $request->getParsedBody();

            $jobValidator = v::key('email', v::email()->notEmpty())
            ->key('password', v::stringType()->notEmpty()->length(6,15));

            try {
                $jobValidator->assert($postData);
                
                $user = new User();
                $user->email = $postData['email'];
                $user->password = password_hash($postData['password'], PASSWORD_DEFAULT);
                $user->save();

                $responseMessage = "Save successfully";
                
            }catch(\Exception $e) {
                $responseMessage = 'Email or Password Incorrect';
            }
        }

        $titleID = 'Add User';
        return $this->renderHTML('addUser.twig', [
            'title' => $titleID,
            'responseMessage' => $responseMessage,
        ]);
    }
}
?>