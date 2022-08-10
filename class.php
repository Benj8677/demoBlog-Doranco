<?php
$art = new articlesRepository;
index(articlesRepository)


class articlesRepository{
    


    public function findAll(){

        $req ="Select * FROM ARTICLE";

        $res = $req->execute()
        $res = return  class_article;


    }

    public function find($id){

        $req ="Select * FROM ARTICLE WHERE id=$id";

        $res = $req->execute()
        $res = return class_article;


    }






}

class article{
    $id;
$titre;
$content;

}