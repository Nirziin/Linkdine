<?php
/**
 * Export to PHP Array plugin for PHPMyAdmin
 * @version 5.2.0
 */

/**
 * Database `social_network`
 */

/* `social_network`.`evenements` */
$evenements = array(
);

/* `social_network`.`friends` */
$friends = array(
  array('user_id' => '2','friend_id' => '1'),
  array('user_id' => '1','friend_id' => '2'),
  array('user_id' => '2','friend_id' => '3'),
  array('user_id' => '3','friend_id' => '2')
);

/* `social_network`.`friend_requests` */
$friend_requests = array(
  array('sender_id' => '2','receiver_id' => '1','status' => 'declined'),
  array('sender_id' => '1','receiver_id' => '2','status' => 'accepted'),
  array('sender_id' => '3','receiver_id' => '2','status' => 'accepted')
);

/* `social_network`.`messages` */
$messages = array(
);

/* `social_network`.`offres` */
$offres = array(
);

/* `social_network`.`publications` */
$publications = array(
);

/* `social_network`.`users` */
$users = array(
  array('id' => '1','username' => 'nicobozo','password' => '$2y$10$LFKymCHhke6CrUw9pLCm2.kPMyXiZ1xO5RT3hNMnsRSbfnA6NohtG','nom' => 'Cadinot','prenom' => 'Nicolas','naissance' => '0000-00-00','mail' => '','bio' => NULL,'image' => '','role' => '0','couleur' => '0'),
  array('id' => '2','username' => 'pertignac','password' => '$2y$10$HOEBl7JWfcRYMk9Z6rOlW.Yi0viDineOP4dBPZkAXd67MqVTyA98i','nom' => 'Lamy','prenom' => 'Pobin','naissance' => '0000-00-00','mail' => '','bio' => NULL,'image' => '','role' => '0','couleur' => '0'),
  array('id' => '3','username' => 'Nirziin','password' => '$2y$10$3zn0WAamA9EyHpFGRyFM1.2mDqrjltOrEz96Oy2xoGXseaDEpqaEK','nom' => 'Barriere','prenom' => 'Pomain','naissance' => '0000-00-00','mail' => '','bio' => NULL,'image' => '','role' => '0','couleur' => '0')
);
