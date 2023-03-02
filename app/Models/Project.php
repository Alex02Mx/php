<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model{
    protected $table = 'projects';

    public function getDuration(){
        $monthText = 'month';
        $yearText = 'year';
        $monthCount = $this->months % 12;
        $yearCount = (int)($this->months / 12);

        if($yearCount > 1){
            $yearText = "years";
        }
        if($monthCount > 1){
            $monthText = "months";
        }

        if($yearCount == 0 && $monthCount > 0){
            return "Project Duration: $monthCount $monthText";
        }
        elseif($yearCount == 1 && $monthCount == 0){
            return "Project Duration: $yearCount $yearText";
        }
        elseif($yearCount > 0 && $monthCount > 0){
            return "Project Duration: $yearCount $yearText $monthCount $monthText";
        }
    }
}
?>