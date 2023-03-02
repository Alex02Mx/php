<?php
namespace App\Controllers;
use App\Models\{Project};
use Respect\Validation\Validator as v;

class ProjectController extends BaseController{
    public function projectAction($request){

        $responseMessage = "Fill info required";

        if($request->getMethod() == 'POST'){
            $postData = $request->getParsedBody();
            
            $jobValidator = v::key('title', v::stringType()->notEmpty())
            ->key('company', v::stringType()->notEmpty())
            ->key('description', v::stringType()->notEmpty())
            ->key('months', v::number()->notEmpty());
            try {
                $jobValidator->assert($postData);

                $project = new Project();
                $project->title = $postData['title'];
                $project->company = $postData['company'];
                $project->description = $postData['description'];
                $project->months = $postData['months'];
                $project->save();

                $responseMessage = "Save successfully";
                
            }catch(\Exception $e) {
                $responseMessage = $e->getMessage();
            }
        }

        $titleID = 'Add Project';
        return $this->renderHTML('addProject.twig', [
            'title' => $titleID,
            'responseMessage' => $responseMessage,
        ]);
    }
}
?>