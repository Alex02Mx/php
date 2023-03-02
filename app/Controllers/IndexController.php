<?php
namespace App\Controllers;
use App\Models\{Job, Project};


class IndexController extends BaseController{
    public function indexAction(){
        $jobs = Job::all();
        $projects = Project::all();

        $titleID = 'Index';

        $firstName = 'Luis';
        $lastName = 'Padilla';
        $name = $firstName . " " . $lastName;
        $totals = 0;

        return $this->renderHTML('index.twig',[
            'name' => $name,
            'jobs' => $jobs,
            'projects' => $projects,
            'title' => $titleID,
        ]);
    }
}
?>
