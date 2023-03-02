<?php
namespace App\Controllers;
use App\Models\{Job};
use Respect\Validation\Validator as v;

class JobController extends BaseController{
    public function jobAction($request){
        $responseMessage = "Fill info required";
        if($request->getMethod() == 'POST'){
            
            $postData = $request->getParsedBody();
            $files = $request->getUploadedFiles();
            $logo = $files['logo'];

            $jobValidator = v::key('title', v::stringType()->notEmpty())
            ->key('description', v::stringType()->notEmpty())
            ->key('months', v::number()->notEmpty());

            try {
                $jobValidator->assert($postData);
                
                if($logo->getError() == UPLOAD_ERR_OK){
                    $fileName = $logo->getClientFilename();
                    $logo->moveTo("upload/$fileName");
                }
               
                $job = new Job();
                $job->title = $postData['title'];
                $job->description = $postData['description'];
                $job->months = $postData['months'];
                $job->logo = $fileName;
                $job->save();

                $responseMessage = "Save successfully";
                
            }catch(\Exception $e) {
                $responseMessage = $e->getMessage();
            }
        }

        $titleID = 'Add Job';
        return $this->renderHTML('addJob.twig', [
            'title' => $titleID,
            'responseMessage' => $responseMessage,
        ]);
    }
}
?>