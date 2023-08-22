<?php 
class Rapport
{
	private $DB;
	public $fraisins = array(            
    'ins'   => 250000,
    'reins'   => 200000,
  );
	
	function __construct($DB)
	{
		$this->DB = $DB;
	}


	public function bilanInscription($cursus, $etat, $promo){

		$prod=$this->DB->querys("SELECT count(id) as nbre FROM inscription where niveau='{$cursus}' and etat='{$etat}' and annee='{$promo}'");

		return $prod['nbre'];

	}

	public function bilanEffectifRemise($cursus, $etat, $promo){
		$remise=0;

		$prod=$this->DB->querys("SELECT count(id) as nbre FROM inscription inner join payement on payement.matricule=inscription.matricule where niveau='{$cursus}' and etat='{$etat}' and remise='{$remise}' and annee='{$promo}' and promo='{$promo}'");

		return $prod['nbre'];

	}


	public function bilanInscriptionApayer($cursus, $etat, $promo){

		$remise=0;

		$prodsansremise=$this->DB->querys("SELECT count(payement.id) as nbre FROM inscription inner join payement on payement.matricule=inscription.matricule where niveau='{$cursus}' and motif='{$etat}' and payement.remise='{$remise}' and annee='{$promo}' and promo='{$promo}'");

		$remiseins=0;

		$prodremiseins = $this->DB->query("SELECT  montant, payement.remise as remise FROM payement inner join inscription on inscription.matricule=payement.matricule WHERE  niveau='{$cursus}' and motif='{$etat}' and payement.remise!='{$remiseins}' and annee='{$promo}' and promo='{$promo}'");

		$totremise=0; 

		foreach ($prodremiseins as $valueremise) {

			if ($valueremise->remise==100) {
				$totremise+=0;
			}else{

				$totremise+=$this->fraisins['ins']*($valueremise->remise/100);

			}

			
		}

		

		$prod=$this->DB->querys("SELECT sum(montant) as montant FROM payement inner join inscription on payement.matricule=inscription.matricule where niveau='{$cursus}' and motif='{$etat}' and annee='{$promo}' and promo='{$promo}'");

		if ($etat=='inscription') {
			$fraisins=$this->fraisins['ins'];
		}else{
			$fraisins=$this->fraisins['reins'];
		}

		$apayer=$prodsansremise['nbre']*$fraisins+$totremise;

		$resteapayer=$apayer-$prod['montant'];

		if ($apayer==0) {
			$taux=0;
		}else{

			$taux=($prod['montant']/$apayer)*100;

		}

		return array($resteapayer, $prod['montant'], $taux);

	}

	public function bilanFormation(){

		$prodformation=$this->DB->query('SELECT classe, nomf, niveau, codef from formation');

		return $prodformation;
	}


	public function bilanFraiscol($cursus, $tranche, $promo){


		$cumulmontant=0;
		foreach ($this->bilanFormation() as $valuef) {
			
			$prodmontantscol = $this->DB->querys("SELECT  sum(scolarite.montant) as montant FROM scolarite  WHERE codef='{$valuef->codef}' and promo='{$promo}'");

			$prodinscrit=$this->DB->query("SELECT remise FROM inscription  WHERE codef='{$valuef->codef}' and niveau='{$cursus}'  and annee='{$promo}'");

			foreach ($prodinscrit as $valueins) {

				if ($valueins->remise==0) {
					$cumulmontant+=$prodmontantscol['montant'];
				}else{
				
					$cumulmontant+=$prodmontantscol['montant']*($valueins->remise/100);
				}
			}
		}
		

		$prodpayer=$this->DB->querys("SELECT sum(montant) as montant FROM payementfraiscol inner join inscription on payementfraiscol.matricule=inscription.matricule where niveau='{$cursus}' and annee='{$promo}' and promo='{$promo}'");

		if ($cumulmontant==0) {
			$taux=0;
		}else{

			$taux=($prodpayer['montant']/$cumulmontant)*100;

		}
		
		$reste=$cumulmontant-$prodpayer['montant'];

		return array($reste, $prodpayer['montant'], $taux);

	}


	public function nbrePersonnel(){

		$prod=$this->DB->querys("SELECT count(id) as nbre FROM personnel ");

		return $prod['nbre'];

	}

	public function salairePrevPersonnel($promo){

		$prod=$this->DB->querys("SELECT sum(salaire) as salaire FROM salairepers where promo='{$promo}'");

		$prodprime=$this->DB->querys("SELECT sum(montantp) as prime FROM primepers where promop='{$promo}' ");

		$salaireprev=($prod['salaire']+$prodprime['prime'])*12;

		return $salaireprev;

	}

	public function salairePayePersonnel($promo){

		$prod=$this->DB->querys("SELECT sum(montant) as salaire FROM payepersonnel where promo='{$promo}'");

		$salairepaye=$prod['salaire'];

		return $salairepaye;

	}


	public function nbreEnseignant(){

		$prod=$this->DB->querys("SELECT count(id) as nbre FROM enseignant");

		return $prod['nbre'];

	}

	public function salairePrevEnseignant($promo){
		$moisref=11;

		$prod=$this->DB->querys("SELECT sum(montant) as salaire FROM payenseignant where mois='{$moisref}' and anneescolaire='{$promo}' ");

		$prodprime=$this->DB->querys("SELECT sum(montantp) as prime FROM prime where promop='{$promo}'");

		$salaireprev=($prod['salaire']+$prodprime['prime'])*10.5;

		return $salaireprev;

	}

	public function salairePayeEnseignant($promo){

		$prod=$this->DB->querys("SELECT sum(montant) as salaire FROM payenseignant where anneescolaire='{$promo}' ");

		$salairepaye=$prod['salaire'];

		return $salairepaye;

	}


	public function bilanDepense($promo){
		$moisref=11;

		$prod=$this->DB->querys("SELECT count(id) as nbre, sum(montant) as salaire FROM decaissement where promo='{$promo}' ");

		$depense=$prod['salaire'];
		$nbredepense=$prod['nbre'];

		return array($depense,$nbredepense);

	}

	public function inscriptionTotal($promo){

		$prod=$this->DB->querys("SELECT sum(montant) as montant FROM payement inner join inscription on payement.matricule=inscription.matricule where annee='{$promo}' and promo='{$promo}'");

		return $prod['montant'];

	}

	public function effectifTotal($promo){

		$prod=$this->DB->querys("SELECT count(id) as nbre FROM inscription where annee='{$promo}'");

		return $prod['nbre'];

	}

	public function effectifTotForm($codef, $promo){

		$prod=$this->DB->querys("SELECT count(id) as nbre FROM inscription where codef='{$codef}' and annee='{$promo}'");

		return $prod['nbre'];

	}


	public function inscriptionTotCursus($cursus, $promo){

		$prod=$this->DB->querys("SELECT sum(montant) as montant FROM payement inner join inscription on payement.matricule=inscription.matricule where niveau='{$cursus}' and annee='{$promo}' and promo='{$promo}'");

		return array($prod['montant']);

	}

	public function resteIns($cursus, $promo){
		$pr=$promo-1;
		$montant=0;
		$remise=100;
		$ins='inscription';
		$reins='reinscription';

		$prodins=$this->DB->querys("SELECT count(inscription.matricule) as nbre FROM payement inner join inscription on payement.matricule=inscription.matricule where motif='{$ins}' and niveau='{$cursus}' and annee='{$promo}' and montant='{$montant}' and payement.remise!='{$remise}' and promo='{$promo}' ");

		$prodreins=$this->DB->querys("SELECT count(inscription.matricule) as nbre FROM payement inner join inscription on payement.matricule=inscription.matricule where motif='{$reins}' and niveau='{$cursus}' and annee='{$promo}' and montant='{$montant}' and payement.remise!='{$remise}' and promo='{$promo}' ");

		return array($prodins['nbre']*$this->fraisins['ins']+$prodreins['nbre']*$this->fraisins['reins']);

	}

	public function totFraiscolPaye($tranche,$promo){

		$prodfrais=$this->DB->querys("SELECT sum(montant) as montant FROM payementfraiscol where tranche='{$tranche}' and promo='{$promo}'");

		return $prodfrais['montant'];

	}

	public function totRestScol($codef, $tranche, $promo){

		$prodscol=$this->DB->querys("SELECT montant FROM scolarite where codef='{$codef}' and tranche='{$tranche}' and promo='{$promo}'");

		if (empty($prodscol['montant'])) {
			$totap=($this->effectifTotForm($codef,$promo))*0;	
		}else{

			$totap=($this->effectifTotForm($codef,$promo))*$prodscol['montant'];
		}

		$reste=$totap-($this->totFraiscolPayeTranche($codef, $tranche, $promo));

		return $reste;

	}

	public function totFraiscolPayeTranche($codef, $tranche, $promo){

		$prodfrais=$this->DB->querys("SELECT sum(montant) as montant FROM payementfraiscol inner join inscription on payementfraiscol.matricule=inscription.matricule  where codef='{$codef}' and tranche='{$tranche}' and promo='{$promo}' and annee='{$promo}'");

		return $prodfrais['montant'];

	}

	public function totRestTranche($codef, $tranche, $promo){

		$prodscol=$this->DB->querys("SELECT montant FROM scolarite where codef='{$codef}' and tranche='{$tranche}' and promo='{$promo}'");

		if (empty($prodscol['montant'])) {
			$montantscol=0;
		}else{
			$montantscol=$prodscol['montant'];
		}

		$totap=$this->effectifTotForm($codef, $promo)*$montantscol;

		$reste=$totap-$this->totFraiscolPayeTranche($codef, $tranche, $promo);

		return $reste;

	}



	public function restescol($cursus, $promo){
		$pr=$promo-1;
		$montant=0;

		$prodins=$this->DB->querys("SELECT count(payement.matricule) as nbre FROM payement inner join inscription on payement.matricule=inscription.matricule where payement.matricule not in(SELECT matricule from payement where promo='{$pr}') and niveau='{$cursus}' and annee='{$promo}' and montant='{$montant}' and promo='{$promo}' ");

		$prodreins=$this->DB->querys("SELECT count(payement.matricule) as nbre FROM payement inner join inscription on payement.matricule=inscription.matricule where payement.matricule in(SELECT matricule from payement where promo='{$pr}') and niveau='{$cursus}' and annee='{$promo}' and montant='{$montant}' and promo='{$promo}' ");

		return array($prodins['nbre']*$this->fraisins['ins']+$prodreins['nbre']*$this->fraisins['reins']);

	}

	public function codefSuivant($codef){

		if ($codef=='psm') {

			$codefsuiv='msm';

		}elseif ($codef=='msm') {

			$codefsuiv='gsm';

		}elseif ($codef=='gsm') {

			$codefsuiv='1pri';

		}elseif ($codef=='1pri') {

			$codefsuiv='2pri';

		}elseif ($codef=='2pri') {

			$codefsuiv='3pri';

		}elseif ($codef=='3pri') {

			$codefsuiv='4pri';

		}elseif ($codef=='4pri') {

			$codefsuiv='5pri';

		}elseif ($codef=='5pri') {

			$codefsuiv='6pri';

		}elseif ($codef=='6pri') {

			$codefsuiv='7col';

		}elseif ($codef=='7col') {

			$codefsuiv='8col';

		}elseif ($codef=='8col') {

			$codefsuiv='9col';

		}elseif ($codef=='9col') {

			$codefsuiv='10col';

		}elseif ($codef=='10col') {

			$codefsuiv='11sm';

		}elseif ($codef=='11sm') {

			$codefsuiv='12sm';

		}elseif ($codef=='11se') {

			$codefsuiv='12se';

		}elseif ($codef=='12sm') {

			$codefsuiv='tsm';

		}elseif ($codef=='12se') {

			$codefsuiv='tse';

		}

		return array($codef, $codefsuiv);

	}


	public function seuilClasse($codef, $promo){

		$prod=$this->DB->querys("SELECT classe, nomf FROM formation where codef='{$codef}' ");

		$totalinscrit=$this->effectifTotForm($codef, $promo);

		if ($prod['classe']=='petite section') {

			$limiteclasse=100;

		}elseif ($prod['classe']=='moyenne section') {
			
			$limiteclasse=100;
		}elseif ($prod['classe']=='grande section') {
			
			$limiteclasse=100;
		}elseif ($prod['classe']==1) {
			
			$limiteclasse=100;
		}elseif ($prod['classe']==2) {
			
			$limiteclasse=100;
		}elseif ($prod['classe']==3) {
			
			$limiteclasse=100;
		}elseif ($prod['classe']==4) {
			
			$limiteclasse=100;
		}elseif ($prod['classe']==5) {
			
			$limiteclasse=100;
		}elseif ($prod['classe']==6) {
			
			$limiteclasse=100;
		}elseif ($prod['classe']==7) {
			
			$limiteclasse=100;
		}elseif ($prod['classe']==8) {
			
			$limiteclasse=100;
		}elseif ($prod['classe']==9) {
			
			$limiteclasse=100;
		}elseif ($prod['classe']==10) {
			
			$limiteclasse=100;
		}elseif ($prod['classe']==11) {
			
			$limiteclasse=100;
		}elseif ($prod['classe']==12 and $prod['nomf']=='sciences maths') {
			
			$limiteclasse=100;

		}elseif ($prod['classe']==12 and $prod['nomf']=='sciences experimentales') {
			
			$limiteclasse=100;

		}elseif ($prod['classe']=='terminale' and $prod['nomf']=='sciences maths') {
			
			$limiteclasse=100;
		}

		return ($limiteclasse-$totalinscrit);

	}
}