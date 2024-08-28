<?php

namespace App\Http\Controllers\Unitrinus;

use App\Http\Controllers\Controller;
use App\Models\UnitrinusList;
use Illuminate\Support\Facades\DB;




class ExportlistController extends Controller
{
    var $docFile  = ''; 
    var $title    = ''; 
    var $htmlHead = ''; 
    var $htmlBody = ''; 
    /** 
     * Constructor 
     * 
     * @return void 
     */ 
    function __construct(){ 
        $this->title = ''; 
        $this->htmlHead = ''; 
        $this->htmlBody = ''; 
    } 

   
    public function export_html($id)
    {

         $list= UnitrinusList::where("id",$id)->first();

        $questions = DB::connection("unitrinus")->select('
        SELECT 
            ROW_NUMBER() OVER (ORDER BY q.id) AS seq_num,
            q.id,
            q.description,
            q.response
        FROM public.questions q
        JOIN public."_questionsTolists" qt ON q.id = qt."B"
        WHERE qt."A" = :list_id AND q.deleted_at IS NULL
    ', ['list_id' => $id]);

        $html='<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <title>Document</title>
    </head>
    <body><h1><strong>Lista de Questões: '.$list->name.'</strong></h1><br>';
        foreach($questions as $row){

            $html.='<h2><strong>Questão: '.$row->seq_num.'</strong></h2>';
            $html.='<h3><strong>Enunciado:</strong></h3>';
            $html .= html_entity_decode($row->description);
            $html.='<br><h3><strong>Resposta:</strong></h3>';
            $html .= html_entity_decode($row->response);
            $html.='<br><br><br>';
        } 

        return response()->json([
            'html_content' => $html.'</body></html>'
        ]);
         
    }



}
