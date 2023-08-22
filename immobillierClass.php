<?php

class Immobillier{
	
	private $DB;
	function __construct($DB)
	{
		$this->DB = $DB;
	}

	public function communeList(){
		$prod=$this->DB->query("SELECT *FROM commune");

		return $prod;
	}


	public function batimentMaxId(){
		$prod=$this->DB->querys("SELECT max(id_bat) as id FROM batiment ");
		return $prod;
	}

	public function batimentSelectById($id){
		$prod=$this->DB->querys("SELECT *FROM batiment WHERE id_bat='{$id}' ");
		return $prod;
	}

	public function batimentSelectAll(){
		$prod=$this->DB->query("SELECT *FROM batiment");
		return $prod;
	}

	public function batimentInsert($identifiant,$nom,$batiment_type,$nombre_etage,$nombre_pieces,$nom_proprietaire,$adresse,$commune,$pays,$phone,$email,$longitude,$latitude,$batim_description){
		$this->DB->insert("INSERT INTO batiment (identifiant,nom,batiment_type,nombre_etage,nombre_pieces,nom_proprietaire,adresse,commune,pays,phone,email,longitude,latitude,batim_description)VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?)",array($identifiant,$nom,$batiment_type,$nombre_etage,$nombre_pieces,$nom_proprietaire,$adresse,$commune,$pays,$phone,$email,$longitude,$latitude,$batim_description));
	}

	
}