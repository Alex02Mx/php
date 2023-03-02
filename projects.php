<?php
use App\Models\Project;

function printProject($project){
    echo "
        <li class='work-position'>
            <h5> {$project->title} </h5>
            <p> <strong> Company: </strong> {$project->company} </p> 
            <p> <strong> {$project->getDuration()} </strong> </p>
            <strong>Description:</strong>
            <ul>
                <li>{$project->description}</li>
            </ul>
            <br>
            
        </li> 
    ";
}
?>