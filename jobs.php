<?php
use App\Models\Job;

function printJob($job){
    echo "
        <li class='work-position'>
            <h5> {$job->title} </h5>
            <p> {$job->description} </p>
            <p><strong>  {$job->getDuration()} </strong></p>
            <strong> Achievements: </strong>
            <ul>
                <li>Lorem ipsum dolor sit amet, 80% consectetuer adipiscing elit.</li>
                <li>Lorem ipsum dolor sit amet, 80% consectetuer adipiscing elit.</li>
                <li>Lorem ipsum dolor sit amet, 80% consectetuer adipiscing elit.</li>
            </ul>
        </li><br> 
    ";
}
?>