<?php

class Model extends Database
{
    protected $pdo;

    // public function __construct() {
    //     $this->pdo = $this->bdd(); // Utilisez bdd() pour obtenir la connexion PDO
    // }

    protected $table = "";

    public function isArrayDataValid($fields)
    {
        if (!empty($fields)) {

            foreach ($fields as $key => $field) {
                if ((empty($field) || trim($field) === '') && $field != 0) {
                    return false; // Retourne false dès qu'un champ obligatoire est vide
                }
            }

            return true;
        }
        return false;
    }
    // Vérifie si tous les champs spécifiés sont remplis dans la requête POST
    public function VerifyFieldsStrict($fields = [])
    {
        if (count($fields) > 0) {
            foreach ($fields as $field) {
                if (empty($_POST[$field]) || trim($_POST[$field]) === '') {
                    return false; // Retourne false dès qu'un champ obligatoire est vide
                }
            }
        }
        return true;
    }
    // Vérifie si les champs obligatoires sont remplis dans la requête POST
    public function VerifyFields($fields = [])
    {
        $errors = [];

        if (count($fields) > 0) {
            foreach ($fields as $field) {
                if (empty($_POST[$field]) || trim($_POST[$field]) === '') {
                    $errors[] = "Le champ " . ucfirst($field) . " est obligatoire."; // Ajouter un message d'erreur pour chaque champ vide
                }
            }
        }
        return $errors;
    }



    // Échappe les caractères spéciaux dans une chaîne pour la rendre sûre pour l'affichage HTML
    public function e($value)
    {
        if ($value) {
            $value = htmlspecialchars($value);
            $value = htmlentities($value);
            $value = strip_tags($value);
            return $value;
        }
    }

    // Hache un mot de passe en utilisant l'algorithme Bcrypt
    public function bcript_hash_password($value, $options = array())
    {
        $cost = isset($options['rounds']) ? $options['rounds'] : 10;
        $hash = password_hash($value, PASSWORD_BCRYPT, array('cost' => $cost));
        if ($hash === false) {
            throw new Exception("Bcrypt hashing n'est pas supporté.");
        }
        return $hash;
    }

    // Vérifie un mot de passe par rapport à un hachage Bcrypt
    public function bcript_verify_password($value, $hashedValue)
    {
        return password_verify($value, $hashedValue);
    }

    // Vérifie les erreurs pour les valeurs entières (longueur)
    // public function chekErrorsInt($value, $entier, $message){
    //     if (strlen($value) < (int)$entier || strlen($value) > (int)$entier) {
    //         return array_push($this->errors, $message);
    //     }
    // }

    // Vérifie les erreurs pour les chaînes (doit être numérique)
    // public function chekErrorsString($value, $message){
    //     if (!is_numeric($value)) {
    //         return array_push($this->errors, $message);
    //     }
    // }

    // Définit un message flash pour les notifications
    public function set_flash($message, $type = 'danger')
    {
        $_SESSION['notification']['message'] = $message;
        $_SESSION['notification']['type'] = $type;
        $_SESSION['notification']['class'] = $this->get_alert_class($type);
        $_SESSION['notification']['icon'] = $this->get_alert_icon($type);
    }

    private function get_alert_class($type)
    {
        switch ($type) {
            case 'primary':
                return 'bg-rgba-primary';
            case 'success':
                return 'bg-rgba-success';
            case 'danger':
                return 'bg-rgba-danger';
            case 'warning':
                return 'bg-rgba-warning';
            default:
                return 'bg-rgba-danger';  // Par défaut
        }
    }

    private function get_alert_icon($type)
    {
        switch ($type) {
            case 'primary':
                return 'bx bx-info-circle'; // Icône pour primary
            case 'success':
                return 'bx bx-check-circle'; // Icône pour success
            case 'danger':
                return 'bx bx-error'; // Icône pour danger
            case 'warning':
                return 'bx bx-warning'; // Icône pour warning
            default:
                return 'bx bx-info-circle'; // Icône par défaut
        }
    }



    // Enregistre les données des entrées dans la session, sauf les mots de passe
    public function save_input_data()
    {
        foreach ($_POST as $key => $value) {
            if (strpos($key, 'password') === false) {
                $_SESSION['input'][$key] = $value;
            }
        }
    }


    // Récupère les données des entrées de la session
    // public function get_input($key){
    //     return !empty($_SESSION['input'][$key]) ? $this->e($_SESSION['input'][$key]) : null;
    // }
    public function get_input($key)
    {
        return !empty($_SESSION['input'][$key])
            ? htmlspecialchars($this->e($_SESSION['input'][$key]), ENT_QUOTES, 'UTF-8')
            : null;
    }

    // Redirige vers une autre page
    public function redirect($page)
    {
        header("Location:" . ROOT . "/" . trim($page, "/"));
        exit();
    }

    // Efface les données des entrées de la session
    public function clear_input_data()
    {
        if (isset($_SESSION['input'])) {
            $_SESSION['input'] = [];
        }
    }

    // Sélectionne plusieurs données avec une jointure et une condition WHERE
    public function select_data_table_join_where($select, $execute_data = [])
    {
        $bdd = $this->bdd();
        $stm = $bdd->prepare($select);
        $stm->execute($execute_data);
        // Utilisation de fetchAll pour récupérer toutes les lignes
        $data = $stm->fetchAll(PDO::FETCH_OBJ);
        return $data;
    }

    public function select_data_table_join_where_limite($select, $execute_data = [], $limit = null)
    {
        // S'assurer que la limite est un entier si elle est fournie
        if ($limit !== null) {
            $limit = intval($limit);
            $select .= " LIMIT " . $limit;
        }
        // Préparer et exécuter la requête
        $bdd = $this->bdd();
        $stm = $bdd->prepare($select);
        // Lier les paramètres en commençant par 1
        foreach ($execute_data as $index => $data) {
            $stm->bindValue($index + 1, $data); // Indexation commence à 1
        }
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_OBJ);
    }


    public function select_data_table_join_where_limite_emarg_uni($select, $execute_data = [], $limit = null)
    {
        // S'assurer que la limite est un entier si elle est fournie
        if ($limit !== null) {
            $limit = intval($limit);
            $select .= " LIMIT " . $limit;
        }
        // Préparer et exécuter la requête
        $bdd = $this->bdd();
        $stm = $bdd->prepare($select);

        // Lier les paramètres en utilisant les noms de paramètres
        foreach ($execute_data as $index => $data) {
            $stm->bindValue($index, $data); // Utilisation des noms de paramètres
        }

        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_OBJ);
    }



    // Compte le nombre de résultats en fonction d'une condition WHERE
    public function selectWhereCount($fields, $whereValue, $select = [], $value = [])
    {
        $bdd = $this->bdd();
        $que = $bdd->prepare("SELECT $select FROM $fields WHERE $whereValue=?");
        $que->execute([$value]);
        $count = $que->rowCount();
        $que->closeCursor();
        return $count;
    }

    // Compte le nombre total de résultats
    public function selectCount($fields, $select = [])
    {
        $bdd = $this->bdd();
        $que = $bdd->prepare("SELECT $select FROM $fields");
        $que->execute();
        $count = $que->rowCount();
        $que->closeCursor();
        return $count;
    }

    // Récupère toutes les données en fonction d'une condition WHERE
    public function FetchAllSelectWhere($select, $fields, $whereValue, $value = [])
    {
        $bdd = $this->bdd();
        $que = $bdd->prepare("SELECT $select FROM $fields WHERE $whereValue");
        $que->execute($value);
        $count = $que->fetchAll(PDO::FETCH_OBJ);
        $que->closeCursor();
        return $count;
    }
    public function existe_deja($field, $value, $table)
    {
        $bdd = $this->bdd();
        $recup = $bdd->prepare("SELECT * FROM $table WHERE $field=?");
        $recup->execute([$value]);
        $count = $recup->rowCount();
        $recup->closeCursor();
        return $count;
    }
    // Récupère une seule donnée en fonction d'une condition WHERE
    public function FetchSelectWhere($select, $fields, $whereValue, $value = [])
    {
        $bdd = $this->bdd();
        $que = $bdd->prepare("SELECT $select FROM $fields WHERE $whereValue");
        $que->execute($value);
        $count = $que->fetch(PDO::FETCH_OBJ);
        $que->closeCursor();
        return $count;
    }

    public function FetchSelectWhere2($select, $fields, $whereValue, $value = [])
    {
        $bdd = $this->bdd();
        $que = $bdd->prepare("SELECT $select FROM $fields WHERE $whereValue");
        $que->execute($value);

        // Utilisez fetchAll() pour récupérer toutes les lignes correspondantes
        $results = $que->fetchAll(PDO::FETCH_OBJ);
        $que->closeCursor();

        return $results; // Retourne toutes les lignes trouvées
    }


    // Sélectionne une donnée sans condition
    public function SelectData($fields, $select = [])
    {
        $bdd = $this->bdd();
        $que = $bdd->prepare("SELECT $select FROM $fields");
        $que->execute();
        $count = $que->fetch(PDO::FETCH_OBJ);
        $que->closeCursor();
        return $count;
    }


    // Sélectionne toutes les données sans condition
    public function SelectAllData($select, $fields)
    {
        $bdd = $this->bdd();
        $que = $bdd->prepare("SELECT $select FROM $fields");
        $que->execute();
        $count = $que->fetchAll(PDO::FETCH_OBJ);
        $que->closeCursor();
        return $count;
    }
    // Sélectionne toutes les données avec tri par la colonne spécifiée
    public function SelectAllDataOrder($select, $fields, $orderByColumn = null, $order = 'DESC')
    {
        $bdd = $this->bdd();
        $orderClause = $orderByColumn ? "ORDER BY $orderByColumn $order" : "";
        $query = $bdd->prepare("SELECT $select FROM $fields $orderClause");
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);
        $query->closeCursor();
        return $results;
    }
    // Effectue une insertion ou mise à jour simple
    public function insertion_update_simples($insert, $insert_data = [])
    {
        $bdd = $this->bdd();
        $q = $bdd->prepare($insert);
        $q->execute($insert_data);
        return $q;
    }
public function SelectOne($fields, $table, $where, $data = [])
{
    $bdd = $this->bdd();
    $sql = "SELECT $fields FROM $table WHERE $where LIMIT 1";
    $stmt = $bdd->prepare($sql);
    $stmt->execute($data);
    return $stmt->fetch(PDO::FETCH_OBJ); // fetch au lieu de fetchAll
}
    // Effectue une insertion ou mise à jour simple et retourne l'ID de la dernière insertion
    public function insertion_update_simples_insert_id($insert, $insert_data = [])
    {
        $bdd = $this->bdd();
        $q = $bdd->prepare($insert);
        $q->execute($insert_data);
        $data = ["q" => $q, 'lastInsertId' => $bdd->lastInsertId()];
        return $data;
    }

    // verifier l'existance d'une chose
    public function  user_verify($fields, $table, $value)
    {
        $bdd = $this->bdd();
        $user_fetch = $bdd->prepare("SELECT * from $table where $fields=?");
        $user_fetch->execute([$value]);
        $nombre = $user_fetch->rowCount();
        $user_fetch->closeCursor();
        return $nombre;
    }
    // verifier si un champ est vide    
    public function verification($champs = [])
    {
        if (count($champs) > 0) {
            foreach ($champs as $champ) {
                if (empty($_POST[$champ]) || trim($_POST[$champ]) === "") {
                    return false;
                }
            }
            return true;
        }
    }

    // garder la valeur dans input

    public function  garder_valeur_input()
    {
        foreach ($_POST  as $key => $value) {
            if (strpos($key, "mot_de_passe") === false) {
                $_SESSION['input'][$key] = $value;
            }
        }
    }
    // recuperer la valeur gardee dans input

    public function  get_valeur_input($key)
    {
        return !empty($_SESSION['input'][$key])
            ? $_SESSION['input'][$key]
            : null;
    }
    //detruire session
    public function destruction_session_input()
    {
        return $_SESSION["input"] = [];
    }


    public function email_verification($email)
    {
        $messages = [];

        // Vérification du format de l'email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $messages[] = "L'email fourni n'est pas valide.";
        }

        // Vérification de la longueur de l'email
        if (strlen($email) > 255) {
            $messages[] = "L'email ne doit pas dépasser 255 caractères.";
        }

        // Vérification de l'unicité de l'email
        if (!$this->is_email_unique($email)) {
            $messages[] = "Cet email est déjà utilisé.";
        }

        return $messages;
    }

    public function is_email_unique($email, $table = 'users', $field = 'email', $excludeId = null, $idField = 'id')
    {
        try {
            $bdd = $this->bdd();

            $sql = "SELECT COUNT(*) as total FROM {$table} WHERE {$field} = :email";
            $params = [':email' => $email];

            if ($excludeId !== null) {
                $sql .= " AND {$idField} != :excludeId";
                $params[':excludeId'] = $excludeId;
            }

            $stmt = $bdd->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch(PDO::FETCH_OBJ);

            return ((int) ($result->total ?? 0)) === 0;
        } catch (Exception $e) {
            return true;
        }
    }

    public function telephone_numero_verification($valeur, $messages = [])
    {
        $errors = [];

        // Messages par défaut
        $default_messages = [
            'length' => "Le numéro de téléphone doit contenir exactement 8 chiffres.",
            'first_digit_invalid' => "Le premier chiffre doit être compris entre 3 et 9.",
        ];

        // Fusionner les messages personnalisés avec les messages par défaut
        $messages = array_merge($default_messages, $messages);

        // Vérification de la longueur du numéro de téléphone (8 chiffres)
        if (!is_numeric($valeur) || strlen($valeur) != 8) {
            $errors[] = $messages['length'];
        } else {
            // Vérification du premier chiffre uniquement si le nombre de chiffres est correct
            $premier_chiffre = (int) substr($valeur, 0, 1);
            if ($premier_chiffre < 3 || $premier_chiffre > 9) {
                $errors[] = $messages['first_digit_invalid'];
            }
        }

        // Si des erreurs sont présentes, on les retourne
        if (!empty($errors)) {
            return $errors;
        }

        return true;
    }
    public function telephone_numero_verification1($valeur)
    {
        // Vérification de la longueur du numéro de téléphone
        if (!is_numeric($valeur) || strlen($valeur) != 8) {
            return "Veuillez revoir le numéro de téléphone donné. Il doit contenir exactement 8 chiffres.";
        }

        // Vérification du premier chiffre
        $premier_chiffre = substr($valeur, 0, 1);
        if ($premier_chiffre == 0 || $premier_chiffre == 1 || $premier_chiffre == 2) {
            return "Le premier chiffre du numéro de téléphone ne peut pas être 0, 1 ou 2.";
        }

        // Vérification si le premier chiffre est compris entre 3 et 9
        if ($premier_chiffre < 3 || $premier_chiffre > 9) {
            return "Le premier chiffre du numéro de téléphone doit être compris entre 3 et 9.";
        }

        // Le numéro de téléphone semble valide
        return "Numéro de téléphone valide.";
    }
}
