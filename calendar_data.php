<?php  require "_header.php";
extract($_POST);
header('Content-Type: application/json');
    $events = [];
    $promo=$_SESSION['promo'];
    $groupe = $_GET['groupe'];
    $codensp = $_GET['enseignant'];
    if (isset($_GET['groupe'])  and !empty($_GET['groupe'])) {
        $prodevents=$DB->query("SELECT *FROM events where promo = '{$promo}' and nomgrp = '{$groupe}' ");
    }else{
        $prodevents=$DB->query("SELECT *FROM events where promo = '{$promo}' and codensp = '{$codensp}' ");
    }
    foreach ($prodevents as $event) {
        $duree=0;
        $events[] = [
            'id'			=> $event->id,
            'start'			=> $event->debut,
            'end'			=> $event->fin,
            'title'			=> $event->name.' '.$panier->nomMatiere($event->codemp).' '.$event->nomgrp." ".$panier->nomEnseignant($event->codensp),
            'description'	=> $event->nomgrp,
            'duree'	        =>$duree,
            'enseignant'	=>$event->codensp,
            'montantf'	    =>0,
            'etat'	        =>0,
            // 'backgroundColor'   =>"#ffc107",
            // 'textColor'         =>"#5a88ad",
            // 'borderColor'       =>"#839C49"
        ];
        
    }
    $data = json_encode($events);
    echo $data;?> 
