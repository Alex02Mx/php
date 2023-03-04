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
                $_SESSION['userId'] = $user->id;
                // return new RedirectResponse('/admin');
                return new RedirectResponse('/');
            }else{
                $responseMessage = 'Bad Credentials';
            }
        }else{
            $responseMessage = 'Bad Credentials';
        }
        $titleID = 'Login';
        return $this->renderHTML('login.twig', [
            'title' => $titleID,
            'responseMessage' => $responseMessage,
        ]);   
    }

    public function getLogout(){
        unset($_SESSION['userId']);
        return new RedirectResponse('/login');
    }
}
?>