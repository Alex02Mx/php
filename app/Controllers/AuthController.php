<?php
namespace App\Controllers;

use App\Models\{User};
use Respect\Validation\Validator as v;
use Laminas\Diactoros\Response\RedirectResponse;

class AuthController extends BaseController{
    public function getLogin(){
        $titleID = 'Login';
        $responseMessage = "Fill info required";
        return $this->renderHTML('login.twig', [
            'title' => $titleID,
            'responseMessage' => $responseMessage,
        ]);   

    }

    public function postLogin($request){
        $postData = $request->getParsedBody();
        $responseMessage = "Fill info required";

        $user = User::where('email', $postData['email'])->first();
        if($user){
            if(\password_verify($postData['password'], $user->password)){
                return new RedirectResponse('/admin');
            }else{
                $responseMessage = 'Incorrect Password';
            }
        }else{
            $responseMessage = 'User not Found';
        }
        $titleID = 'Login';
        return $this->renderHTML('login.twig', [
            'title' => $titleID,
            'responseMessage' => $responseMessage,
        ]);   

        //     $jobValidator = v::key('username', v::email()->notEmpty())
        //     ->key('passcode', v::stringType()->notEmpty()->length(6,15));

        //     try {
        //         $jobValidator->assert($postData);
                
        //         $user = new User();
        //         $user->username = $postData['username'];
        //         $user->passcode = password_hash($postData['passcode'], PASSWORD_DEFAULT);
        //         $user->save();

        //         $responseMessage = "Save successfully";
                
        //     }catch(\Exception $e) {
        //         $responseMessage = $e->getMessage();
        //     }
        // }

        
    }
}
?>