<?php

class Projets extends Controller
 {

    public function publier_projet() {
        $model = new Model();

        $data[ 'categories' ] = $model->SelectAllData( '*', 'categories' );

        $this->view( 'publier_projet', $data );
    }

    public function store() {

        if ( $_SERVER[ 'REQUEST_METHOD' ] !== 'POST' ) {
            $this->redirect( 'Projets/publier_projet' );
        }

        $model = new Model();

        /* verification champs */

        $errors = $model->VerifyFields( [
            'title',
            'category_id',
            'description'
        ] );

        if ( !empty( $errors ) ) {
            $model->set_flash( implode( '<br>', $errors ), 'danger' );
            $this->redirect( 'Projets/publier_projet' );
        }

        /* nettoyage */

        $title = $model->e( $_POST[ 'title' ] );
        $category = $model->e( $_POST[ 'category_id' ] );
        $description = $model->e( $_POST[ 'description' ] );
        $technologies = $model->e( $_POST[ 'technologies' ] );
        $video = $model->e( $_POST[ 'video' ] );
        /* utilisateur */

        $user_id = $_SESSION[ 'user_id' ] ?? 1;

        $projectModel = new Projet();

        /* insertion projet */

        $project = $projectModel->createProject( [
            'user_id'=>$user_id,
            'category_id'=>$category,
            'title'=>$title,
            'description'=>$description,
            'technologies'=>$technologies,
            'video'=>$video,
            'status'=>'En Attente'
        ] );

        $project_id = $project[ 'lastInsertId' ];

        /* dossier upload */

        $imageDir = 'uploads/projects/images/';
        $fileDir = 'uploads/projects/files/';

        if ( !file_exists( $imageDir ) ) {
            mkdir( $imageDir, 0777, true );
        }

        if ( !file_exists( $fileDir ) ) {
            mkdir( $fileDir, 0777, true );
        }

        /* ===  ===  ===  ===  ===  == */
        /* UPLOAD IMAGES */
        /* ===  ===  ===  ===  ===  == */

        if ( !empty( $_FILES[ 'images' ][ 'name' ][ 0 ] ) ) {

            foreach ( $_FILES[ 'images' ][ 'name' ] as $key=>$name ) {

                $tmp = $_FILES[ 'images' ][ 'tmp_name' ][ $key ];

                $ext = strtolower( pathinfo( $name, PATHINFO_EXTENSION ) );

                $allowed = [ 'jpg', 'jpeg', 'png', 'gif', 'webp' ];

                if ( !in_array( $ext, $allowed ) ) {
                    continue;
                }

                $newName = uniqid( 'img_' ).'.'.$ext;

                move_uploaded_file( $tmp, $imageDir.$newName );

                $projectModel->addImage( $project_id, $newName );
            }
        }

        /* ===  ===  ===  ===  ===  == */
        /* UPLOAD FILES */
        /* ===  ===  ===  ===  ===  == */

        if ( !empty( $_FILES[ 'files' ][ 'name' ][ 0 ] ) ) {

            foreach ( $_FILES[ 'files' ][ 'name' ] as $key=>$name ) {

                $tmp = $_FILES[ 'files' ][ 'tmp_name' ][ $key ];

                $ext = strtolower( pathinfo( $name, PATHINFO_EXTENSION ) );

                $allowed = [
                    'zip', 'rar', 'pdf', 'doc', 'docx', 'ppt', 'pptx',
                    'xls', 'xlsx', 'txt'
                ];

                if ( !in_array( $ext, $allowed ) ) {
                    continue;
                }

                $newName = uniqid( 'file_' ).'.'.$ext;

                move_uploaded_file( $tmp, $fileDir.$newName );

                $projectModel->addFile( $project_id, $newName );
            }
        }

        /* success */

        $model->set_flash( 'Projet publié avec succès', 'success' );

        $this->redirect( 'Projets/publier_projet' );

    }

    public function mes_projets() {

        $projectModel = new Projet();

        $user_id = $_SESSION[ 'user_id' ];

        $data[ 'projects' ] = $projectModel->getProjectsByUser( $user_id );

        $this->view( 'projets-list', $data );

    }

    /* PAGE MODIFIER */

    public function modifier( $id ) {

        $model = new Projet();

        $data[ 'project' ] = $model->getProjectById( $id );

        $data[ 'images' ] = $model->getProjectImages( $id );

        $data[ 'files' ] = $model->getProjectFiles( $id );

        $data[ 'categories' ] = $model->SelectAllData( '*', 'categories' );

        $this->view( 'modifier-projet', $data );

    }

    /* UPDATE PROJET */

    public function update( $id ) {

        $model = new Projet();

        /* nettoyage */

        $data = [

            'title'=>$model->e( $_POST[ 'title' ] ),
            'category_id'=>$model->e( $_POST[ 'category_id' ] ),
            'description'=>$_POST[ 'description' ],
            'technologies'=>$model->e( $_POST[ 'technologies' ] ),
            'video'=>$model->e( $_POST[ 'video' ] ),

        ];

        $model->updateProject( $data, $id );

        /* upload nouvelles images */

        if ( !empty( $_FILES[ 'images' ][ 'name' ][ 0 ] ) ) {

            foreach ( $_FILES[ 'images' ][ 'name' ] as $key=>$name ) {

                $tmp = $_FILES[ 'images' ][ 'tmp_name' ][ $key ];

                $ext = strtolower( pathinfo( $name, PATHINFO_EXTENSION ) );

                $allowed = [ 'jpg', 'jpeg', 'png', 'gif', 'webp' ];

                if ( !in_array( $ext, $allowed ) ) {
                    continue;
                }

                $newName = uniqid().'.'.$ext;

                move_uploaded_file( $tmp, 'uploads/projects/images/'.$newName );

                $model->addImage( $id, $newName );

            }

        }

        /* upload nouveaux fichiers */

        if ( !empty( $_FILES[ 'files' ][ 'name' ][ 0 ] ) ) {

            foreach ( $_FILES[ 'files' ][ 'name' ] as $key=>$name ) {

                $tmp = $_FILES[ 'files' ][ 'tmp_name' ][ $key ];

                $ext = strtolower( pathinfo( $name, PATHINFO_EXTENSION ) );

                $allowed = [
                    'pdf', 'doc', 'docx', 'ppt', 'pptx',
                    'xls', 'xlsx', 'zip', 'rar', 'txt'
                ];

                if ( !in_array( $ext, $allowed ) ) {
                    continue;
                }

                $newName = uniqid().'.'.$ext;

                move_uploaded_file( $tmp, 'uploads/projects/files/'.$newName );

                $model->addFile( $id, $newName );

            }

        }

        $model->set_flash( 'Projet modifié avec succès', 'success' );

        $model->redirect( 'Projets/mes_projets' );

    }

    /* SUPPRIMER IMAGE */

    public function delete_image( $id, $projetId ) {

        $model = new Projet();

        $image = $model->SelectOne( '*', 'project_images', 'id=?', [ $id ] );

        if ( $image ) {

            $path = 'uploads/projects/images/'.$image->image;

            if ( file_exists( $path ) ) {
                unlink( $path );
            }

            $model->deleteImage( $id );

        }

        $model->set_flash( 'Image supprimée', 'success' );

        $model->redirect( '/Projets/modifier/'. $projetId );

    }

    public function delete_file( $id, $projetId ) {
        $model = new Projet();

        $file = $model->SelectOne( '*', 'project_files', 'id=?', [ $id ] );

        if ( $file ) {

            $path = 'uploads/projects/files/'.$file->fichier;

            if ( file_exists( $path ) ) {
                unlink( $path );
            }

            $model->deleteFile( $id );

        }

        $model->set_flash( 'Fichier supprimé', 'success' );

        $model->redirect( '/Projets/modifier/'. $projetId );
    }

    public function detail( $id ) {

        $model = new Projet();

        /* projet */

        $data[ 'project' ] = $model->getProjectDetail( $id );

        /* images */

        $data[ 'images' ] = $model->getProjectImages( $id );

        /* fichiers */

        $data[ 'files' ] = $model->getProjectFiles( $id );

        $this->view( 'details-projet', $data );

    }

    public function temps_relatif ( $datetime ) {

        $time = strtotime( $datetime );
        $diff = time() - $time;

        $units = [

            31536000 => 'an',
            2592000 => 'mois',
            86400 => 'jour',
            3600 => 'heure',
            60 => 'minute',
            1 => 'seconde'

        ];

        foreach ( $units as $sec => $str ) {

            $d = floor( $diff / $sec );

            if ( $d >= 1 ) {

                if ( $str == 'mois' ) {
                    return "Il y a $d $str";
                }

                $str .= $d > 1 ? 's' : '';

                return "Il y a $d $str";

            }

        }

    }

    public function youtube_embed( $url ) {

        $video_id = '';

        /* cas 1 : youtube.com/watch */

        if ( preg_match( '/youtube\.com\/watch\?v=([^\&]+)/', $url, $match ) ) {
            $video_id = $match[ 1 ];
        }

        /* cas 2 : youtu.be */

        if ( preg_match( '/youtu\.be\/([^\?]+)/', $url, $match ) ) {
            $video_id = $match[ 1 ];
        }

        /* cas 3 : youtube embed déjà */

        if ( preg_match( '/youtube\.com\/embed\/([^\?]+)/', $url, $match ) ) {
            $video_id = $match[ 1 ];
        }

        if ( $video_id != '' ) {
            return 'https://www.youtube.com/embed/'.$video_id;
        }

        return '';

    }

}