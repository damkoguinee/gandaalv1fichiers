<?php
require 'headerv3.php';?>

<div class="container-fluid">
    <div class="row"><?php 
    
        if (isset($_POST['matricule'])) {            
            $matricule=$_POST['matricule'];
            $identifiant=$_POST['identifiant'];
            $password=$_POST['password'];
            $passwordInit=$_POST['passwordInit'];
            $role=$_POST["role"];
            $level=$_POST["level"];

            if (empty($password)) {
                $mdp=$passwordInit;
            }else{
                $mdp=$mdp=password_hash($password, PASSWORD_DEFAULT);
            }
            $roletab=[];
            foreach ($role as $value_role) {
                $roletab [] = $value_role." , ";
            }
            $valeurs_role = join($roletab);
            
           $DB->insert("UPDATE login SET pseudo = '{$identifiant}', mdp = '{$mdp}', niveau = '{$level}' , role = '{$valeurs_role}' WHERE matricule = '{$matricule}'  ");
        }
        ?>
        <table class="table table-hover table-bordered table-striped table-responsive align-middle">
            <thead class="sticky-top bg-secondary">
                <tr><th colspan="9">Gestion des accès utilsateurs</th></tr>
                <tr>
                    <th colspan="9">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col">
                                    <form class="form d-flex" action="" method="GET">
                                        <input class="form-control me-2" id="search-user" type="search" name="search" placeholder="Recherchez un utilsateur" onchange="this.form.submit()" >
                                        <button class="btn btn-primary" type="submit" name="searchValid">Recherchez</button>
                                    </form>
                                    
                                </div>						        			
                            </div>
                        </div>
                    </th>
                </tr>

                <tr>
                    <th scope="col" class="text-center">N°</th>
                    <th scope="col">Matricule</th>
                    <th scope="col">Prénom & Nom</th>
                    <th scope="col">Type</th>
                    <th scope="col">identifiant</th>
                    <th scope="col">Mot de Passe</th>
                    <th scope="col">Role</th>                                   
                    <th scope="col">Niveau</th>
                    <th scope="col"></th>
                </tr>
            </thead>

            <tbody><?php 

                $montantcumul=0;
                if (isset($_GET["search"])) {
                    $search=$_GET["search"];
                    $utilsateurs = $DB->query("SELECT *FROM login inner join personnel on numpers=matricule WHERE (personnel.nom LIKE ? or prenom LIKE ? or matricule LIKE ?)  order by(matricule) limit 50 " , array("%".$search."%", "%".$search."%", "%".$search."%"));
                }else{
                    $utilsateurs = $DB->query("SELECT *FROM login inner join personnel on numpers=matricule  order by(matricule) limit 50 ");
                }

                foreach ($utilsateurs as $key => $value) {
                    $nom = "";?>

                    <form action="" method="POST">

                        <tr>
                            <td><?=$key+1;?></td>
                            <td><?=$value->matricule;?></td>
                            <td><?=$panier->nomPersonnel($value->matricule);?></td>
                            <td><?=$value->type;?></td>
                            <td><input class="form-control" type="text" name="identifiant" value="<?=$value->pseudo;?>"></td>                                        
                            <td>
                                <input class="form-control" type="text" name="password">
                                <input class="form-control" type="hidden" name="passwordInit" value="<?=$value->mdp;?>">
                                <input class="form-control" type="hidden" name="matricule" value="<?=$value->matricule;?>">
                            </td>    
                            <td>
                                <select class="form-select" type="text" name="role[]"multiple>
                                    <option selected value="<?=$value->role;?>"><?=$value->role;?></option>
                                    <option value="ROLE_ADMIN">ROLE_ADMIN</option>
                                    <option value="ROLE_RESPONSABLE">ROLE_RESPONSABLE</option>
                                    <option value="ROLE_PERSONNEL">ROLE_PERSONNEL</option>
                                    <option value="ROLE_COMPTABLE">ROLE_COMPTABLE</option>
                                    <option value="ROLE_ENSEIGNANT">ROLE_ENSEIGNANT</option>
                                    <option value="ROLE_ELEVE">ROLE_ELEVE</option>
                                    <option value="ROLE_PARENT">ROLE_PARENT</option>
                                </select>

                            </td>  
                            <td>
                                <select class="form-select" type="text" name="level">
                                    <option value="<?=$value->niveau;?>"><?=$value->niveau;?></option>
                                    <option value="2">2</option>
                                    <option value="1">1</option>
                                </select>
                            </td>
                            <td><button class="btn btn-primary" type="submit" name="valid">Valider</button></td>
                        </tr>
                    </form><?php 
                }?>
            </tbody>
        </table>

    </div>
</div>

<?php require 'footer.php';?>

<script type="text/javascript">
    function alerteS(){
        return(confirm('Valider la suppression'));
    }

    function alerteV(){
        return(confirm('Confirmer la validation'));
    }

    function focus(){
        document.getElementById('pointeur').focus();
    }

</script>

<script>
    $(document).ready(function(){
        $('#search-user').keyup(function(){
            $('#result-search').html("");

            var utilisateur = $(this).val();

            if (utilisateur!='') {
                $.ajax({
                    type: 'GET',
                    url: 'searchelevegen.php?searchRole',
                    data: 'user=' + encodeURIComponent(utilisateur),
                    success: function(data){
                        if(data != ""){
                          $('#result-search').append(data);
                        }else{
                          document.getElementById('result-search').innerHTML = "<div style='font-size: 20px; text-align: center; margin-top: 10px'>Aucun utilisateur</div>"
                        }
                    }
                })
            }
      
        });
    });
</script>

