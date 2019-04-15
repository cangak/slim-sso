<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes
$app->get('/[{name}]', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});

$app->get("/users/", function (Request $request, Response $response){
    $sql = "SELECT * FROM user";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();
    return $response->withJson(["status" => "success", "data" => $result], 200);
});

$app->get("/users/{id}", function (Request $request, Response $response, $args){
    $id = $args["id"];
    $sql = "SELECT * FROM user WHERE id=:id";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([":id" => $id]);
    $result = $stmt->fetch();
    return $response->withJson(["status" => "success", "data" => $result], 200);
});

$app->get("/users/search/", function (Request $request, Response $response, $args){
    $keyword = $request->getQueryParam("keyword");
    $sql = "SELECT * FROM user WHERE username LIKE '%$keyword%'";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([":id" => $id]);
    $result = $stmt->fetchAll();
    return $response->withJson(["status" => "success", "data" => $result], 200);
});

$app->post("/users/", function (Request $request, Response $response, $args){
    $posting = $request->getParsedBody();
    $sql = "SELECT * FROM user WHERE username=:username and password_hash=:passwd";
    $stmt = $this->db->prepare($sql);
    $data= [
        ":username" =>$posting['user'],
        ":passwd" => md5($posting['password']) 
            ];
    $stmt->execute($data);
    $result = $stmt->fetchAll();
    //print_r($posting);exit();
    if (!empty($result) ) {
        return $response->withJson(["status" => "success", "data" => $result], 200);

    } else {
        return $response->withJson(["status" => "error"], 200);

    }
        
});
