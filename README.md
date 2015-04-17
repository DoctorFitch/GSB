# GSB

## Présentation

Il s'agit d'un projet de fin de première année de BTS SIO où la création d'un site web dynamique nous à été demandé.
Ici nous sommes sur le projet GSB (Galaxy Swiss Bourdin) un site qui permet à des utilisateurs (visiteurs médicaux) de se connecter et de remplir des fiches de frais. En parallèle des comptables disposeront de leur propres pages afin de valider ou non les frais remplis par ces visiteurs médicaux.

## Getting Started

- **Ouvrez une plate-forme de développement Web comme _wampserver_ ou bien _mamp_**
- Lancer le dossier GSB depuis la plate-forme

## Exemples

*Tous les exemples sont ici du même fichiers chechUsers.php*

Ici nous verifions qu'un utilisateur existe bien dans la base de données

```
  // initialisation des variables de session
  $username = ($_POST['nom']);
  $pass = ($_POST['mdp']);
  
  //Vérification des identifiants
  $req = $connexion->prepare('SELECT * FROM visiteur WHERE login = :username AND mdp = :password');
  $req->execute(array(':password' => $pass, ':username' => $username,));
  $resultat = $req->fetch();
```

Dans le cas présent on l'authorise à se connecter ou non

```
if(!$resultat)
{
  header('Refresh:1;url=../index.html');
} // si mauvais authentification
 
else if($resultat && $cat == 'c')
{
  // Creation d'un cookie
  setcookie('user', $_POST['nom'], 0, '/', $domain, false);
  header('Refresh:2;url=../contents/comptable.php');

  $req->closeCursor();
  exit;
} // si bonne authentification
[...]
``` 



