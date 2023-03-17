<?php 

namespace App\classes;

use PDO;
use PDOException;

/**
 * 
 */
class Database
{

    public PDO $conn;

    //Connexion à la base de données se fait avec PDO
    public function connexion($host, $dbname, $username, $password)
    {
        $dsn =  "mysql:dbname=$dbname;host=$host";
        try{
            $this->conn = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
            // echo 'Connexion réussie';
            return $this->conn;

        }catch(PDOException $e){
            echo "Une erreur a été trouvé : " . $e->getMessage();
        }
    }

    // 
    /**
     * Permet de créer une table dont le nom est $table
     * @param string $table Le nom de la table à créer
     * @param PDO $conn L'instance pdo 
     */
    public function createTable(string $table, PDO $conn)
    {
        $sql = "CREATE TABLE IF NOT EXISTS  $table(
                id INT NOT NULL AUTO_INCREMENT,
                date DATE NOT NULL,
                city VARCHAR(50) NOT NULL,
                period VARCHAR(20) NOT NULL,
                resume VARCHAR(20) NOT NULL,
                idResume INT NOT NULL,
                tempMax INT NOT NULL,
                tempMin INT NOT NULL,
                comment VARCHAR(100),
                PRIMARY KEY(id)
                )Engine=InnoDB";

        $conn->query($sql);
    }

    // Insérer des valeurs dans la bdd
    public function insertDatasInMeteo(string $table, PDO $conn, array $datas)
    {
        foreach ($datas as $data) {
            $sql = "INSERT INTO $table(date, city, period, resume, idResume, tempMax, tempMin, comment)
                    VALUES(:date, :city, :period, :resume, :idResume, :tempMax, :tempMin, :comment)";
            
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':date', $data[0], PDO::PARAM_STR);
            $stmt->bindValue(':city', $data[1], PDO::PARAM_STR);
            $stmt->bindValue(':period', $data[2], PDO::PARAM_STR);
            $stmt->bindValue(':resume', $data[3], PDO::PARAM_STR);
            $stmt->bindValue(':idResume', $data[4], PDO::PARAM_INT);
            $stmt->bindValue(':tempMax', $data[5], PDO::PARAM_INT);
            $stmt->bindValue(':tempMin', $data[6], PDO::PARAM_INT);
            $stmt->bindValue(':comment', $data[7], PDO::PARAM_STR);
    
            $result = $stmt->execute();
        }


        if($result){
            $id = $conn->lastInsertId();
        }

        return $id;
    }
    

    // Pour Sélectionner des elements de la bdd
    public function selectAll(string $table, PDO $conn): array
    {
        $sql = "SELECT * 
                FROM $table";
        $stmt = $conn->query($sql);

        $datas = $stmt->fetchAll(PDO::FETCH_CLASS);

        return $datas;
    }

    public function select(string $table, PDO $conn, string $field)
    {
        $sql = "SELECT DISTINCT $field
                FROM $table";

        $stmt = $conn->query($sql);

        return $stmt->fetchAll(PDO::FETCH_CLASS);
        
    }

    public function selectWhere(string $table, PDO $conn, string $where)
    {
        $sql = "SELECT *
                FROM $table
                WHERE date = :date";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':date', $where, PDO::PARAM_STR);
        $stmt->execute();

        $tab =  $stmt->fetchAll(PDO::FETCH_CLASS);

        return $tab;
    }
}