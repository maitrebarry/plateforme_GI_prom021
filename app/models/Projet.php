<?php

class Projet extends Model
 {
    protected $table = 'projects';

    public function createProject( $data ) {
        $sql = "INSERT INTO projects 
        (user_id, category_id, title, description, technologies, video, status) 
        VALUES (?,?,?,?,?,?,?)";

        return $this->insertion_update_simples_insert_id( $sql, [
            $data[ 'user_id' ],
            $data[ 'category_id' ],
            $data[ 'title' ],
            $data[ 'description' ],
            $data[ 'technologies' ],
            $data[ 'video' ],
            $data[ 'status' ]
        ] );
    }

    public function addImage( $project_id, $image ) {
        $sql = 'INSERT INTO project_images (project_id,image) VALUES (?,?)';
        return $this->insertion_update_simples( $sql, [ $project_id, $image ] );
    }

    public function addFile( $project_id, $file ) {
        $sql = 'INSERT INTO project_files (project_id,fichier) VALUES (?,?)';
        return $this->insertion_update_simples( $sql, [ $project_id, $file ] );
    }

    public function getProjectsByUser( $user_id ) {

        $sql = "SELECT 
            p.id,
            p.title,
            p.description,
            p.technologies,
            p.status,
            p.created_at,
            c.nom as categorie,
            (
            SELECT image 
            FROM project_images 
            WHERE project_id = p.id 
            LIMIT 1
            ) as image

            FROM projects p

            LEFT JOIN categories c ON c.id = p.category_id

            WHERE p.user_id = ?

            ORDER BY p.created_at DESC";

        return $this->select_data_table_join_where( $sql, [ $user_id ] );

    }

    public function getProjectById( $id ) {

        return $this->FetchSelectWhere( '*', 'projects', 'id=?', [ $id ] );

    }

    /* récupérer images projet */

    public function getProjectImages( $project_id ) {

        return $this->FetchSelectWhere2( '*', 'project_images', 'project_id=?', [ $project_id ] );

    }

    public function updateProject( $data, $id ) {

        $sql = "UPDATE projects SET
        title=?,
        category_id=?,
        description=?,
        technologies=?,
        video=?,
        WHERE id=?";

        return $this->insertion_update_simples( $sql, [

            $data[ 'title' ],
            $data[ 'category_id' ],
            $data[ 'description' ],
            $data[ 'technologies' ],
            $data[ 'video' ],
            $id

        ] );

    }

    /* supprimer image */

    public function deleteImage( $id ) {

        $sql = 'DELETE FROM project_images WHERE id=?';

        return $this->insertion_update_simples( $sql, [ $id ] );

    }

    /* récupérer fichiers */

    public function getProjectFiles( $project_id ) {

        return $this->FetchSelectWhere2( '*', 'project_files', 'project_id=?', [ $project_id ] );

    }

    /* supprimer fichier */

    public function deleteFile( $id ) {

        $sql = 'DELETE FROM project_files WHERE id=?';

        return $this->insertion_update_simples( $sql, [ $id ] );

    }

    /* récupérer projet complet */

    public function getProjectDetail( $id ) {

        $sql = "SELECT 
        p.*,
        c.nom as categorie,
        u.nom,
        u.prenom

        FROM projects p

        LEFT JOIN categories c ON c.id = p.category_id
        LEFT JOIN users u ON u.user_id = p.user_id

        WHERE p.id=?";

        return $this->select_data_table_join_where( $sql, [ $id ] )[ 0 ];

    }

}