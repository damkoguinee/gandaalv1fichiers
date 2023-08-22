<?php
require '_header.php';

if (isset($_POST['envoyer'])) {

    if (!empty($_POST['personnel'])) {

        $numpers=$panier->h($_POST['personnel']);

        if ($_POST['personnel']=='personnel') {

            $prod=$DB->query("SELECT email, phone, numpers as matricule from contact inner join personnel on numpers=matricule");
        }else{

            $prod=$DB->query("SELECT email, phone, numpers as matricule from contact inner join personnel on numpers=matricule where numpers='{$numpers}' ");

        }

    }elseif (!empty($_POST['enseignant'])) {
        $numpers=$panier->h($_POST['enseignant']);

        if ($_POST['enseignant']=='enseigmat') {

            $niveau='maternelle';

            $prod=$DB->query("SELECT email, phone, enseignant.matricule as matricule from enseignant inner join contact on enseignant.matricule=contact.matricule  inner join niveau on enseignant.matricule=niveau.matricule where nom='{$niveau}' ");

        }elseif ($_POST['enseignant']=='enseigprim') {

            $niveau='primaire';

            $prod=$DB->query("SELECT email, phone, enseignant.matricule as matricule from enseignant inner join contact on enseignant.matricule=contact.matricule  inner join niveau on enseignant.matricule=niveau.matricule where nom='{$niveau}' ");
        }elseif ($_POST['enseignant']=='enseigsec') {

            $niveau1='college';
            $niveau2='lycee';

            $prod=$DB->query("SELECT email, phone, enseignant.matricule as matricule from enseignant inner join contact on enseignant.matricule=contact.matricule  inner join niveau on enseignant.matricule=niveau.matricule where nom='{$niveau1}' or nom='{$niveau2}' ");
        }else{

            $prod=$DB->query("SELECT email, phone, enseignant.matricule as matricule from contact inner join enseignant on enseignant.matricule=niveau.matricule where numpers='{$numpers}' ");

        }
    }elseif (!empty($_POST['cursus'])) {

        $numpers=$panier->h($_POST['cursus']);

        if ($_POST['cursus']!='complexe') {

            $prod=$DB->query("SELECT  email, telpere, telmere, teltut, phone from inscription inner join eleve on eleve.matricule=inscription.matricule inner join tuteur on tuteur.matricule=eleve.matricule inner join contact on contact.matricule=eleve.matricule where inscription.niveau='{$numpers}' and annee='{$_SESSION['promo']}' ");

        }else{

            $prod=$DB->query("SELECT  email, telpere, telmere, teltut, phone from inscription inner join eleve on eleve.matricule=inscription.matricule inner join tuteur on tuteur.matricule=eleve.matricule inner join contact on contact.matricule=eleve.matricule and annee='{$_SESSION['promo']}' ");

        }

    }elseif (!empty($_POST['niveau'])) {

        $numpers=$panier->h($_POST['niveau']);

        $prod=$DB->query("SELECT  email, telpere, telmere, teltut, phone from inscription inner join eleve on eleve.matricule=inscription.matricule inner join tuteur on tuteur.matricule=eleve.matricule inner join contact on contact.matricule=eleve.matricule where inscription.codef='{$numpers}' and annee='{$_SESSION['promo']}' ");



    }elseif (!empty($_POST['classe'])) {

        $numpers=$panier->h($_POST['classe']);

        $prod=$DB->query("SELECT  email, telpere, telmere, teltut, phone from inscription inner join eleve on eleve.matricule=inscription.matricule inner join tuteur on tuteur.matricule=eleve.matricule inner join contact on contact.matricule=eleve.matricule where inscription.nomgr='{$numpers}' and annee='{$_SESSION['promo']}' ");

    }elseif (!empty($_POST['numbereleve'])) {

        $numpers=$panier->h($_POST['numbereleve']);

        $prod=$DB->query("SELECT  email, telpere, telmere, teltut, phone from inscription inner join eleve on eleve.matricule=inscription.matricule inner join tuteur on tuteur.matricule=eleve.matricule inner join contact on contact.matricule=eleve.matricule where inscription.matricule='{$numpers}' and annee='{$_SESSION['promo']}' ");
        
    }else{
        $prod=array();
    }

    if ($_POST['type']=='sms') {

        //Send an SMS using Gatewayapi.com
        $url = "https://gatewayapi.com/rest/mtsms";
        $api_token = "XQS_j3orTaWO0Bz00dk4yp1nGVUaLbi4C1ACMfuIQL2HJN-GunLAzlhHyUWx";

        //Set SMS recipients and content
        //$recipients = [224628196628, 33753542292];

        $code='224';

        $recipients=$prod;

        $json = [
            'sender' => 'damko',
            'message' => $_POST['message'],
            'recipients' => [],
        ];
        foreach ($recipients as $msisdn) {
            if (!empty($msisdn->phone)) {

                $json['recipients'][] = ['msisdn' => $code.$msisdn->phone];
            }
        }

        //Make and execute the http request
        //Using the built-in 'curl' library
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
        curl_setopt($ch,CURLOPT_USERPWD, $api_token.":");
        curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode($json));
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        print($result);
        $json = json_decode($result);
        //print_r($json->ids);

        $_SESSION['envoyer']='envoye';

        header("Location: sms.php");
    }else{
        foreach ($prod as $msisdn) {
            $destinataire=$msisdn->email;
            $message=$_POST['message'];
            ini_set( 'display_errors', 1);
            error_reporting( E_ALL );
            $from = "ganddaal@damkoguinee.com";
            $to =$destinataire;
            $subject = "infos";
            $message = $message;
            $headers = "From:" . $from;
            mail($to,$subject,$message, $headers);
        }

        $_SESSION['envoyer']='envoye';

        header("Location: sms.php");
    }
}else{
    header("Location: sms.php");
}