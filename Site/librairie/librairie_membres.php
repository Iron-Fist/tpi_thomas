<?php
/**
 * Verifie si les données saisie correspondent à celle 
 * d'un des utilisateurs de la base de donnée
 * @param type $pseudo
 * @param type $pwd
 * @return array $data
 */
function connexion($num_licence, $mdp) {
    $query = connectDB()->prepare("SELECT * FROM `t_membres` WHERE num_licence = ? AND mdp = ?");
    $query->execute([$num_licence, sha1($mdp)]);
    $data = $query->fetch(PDO::FETCH_ASSOC);
    return $data;
}