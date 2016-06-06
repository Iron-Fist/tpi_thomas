<?php

require_once('./librairie/constants.php');
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/*
 * Retourne le tableau contenant toutes les erreurs.
 */
function getErrorMessageArray(){
    return array(
        "Le champ <b>Numéro de licence</b> est vide.",
        "Votre <b>Numéro de licence</b> ne contient pas exactement 5 chiffres.",
        "Votre <b>Numéro de licence</b> est déjà utilisé.",
        "Le champ <b>Nom</b> est vide.",
        "Le champ <b>Prénom</b> est vide.",
        "Le champ <b>Date de naissance</b> invalide.",
        "Les deux champ de mot de passe sont vides.",
        "Vos deux mots de passe ne sont pas identiques."
    );
}

/*
 * Retourne le message d'erreur en fonction du code d'erreur.
 * @param {int} code    Le code d'erreur défini dans le fichier constant.php
 * @return Le message d'erreur en fonction du code.
 */
function getErrorMessage($code){
    return getErrorMessageArray()[$code];
}