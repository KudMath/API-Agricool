<?php

namespace App\Controllers;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class ContainersController
{

    protected $containersService;

    public function __construct($service)
    {
        $this->containersService = $service;
    }

    public function authentify(Request $request){
      return false;
    }

    public function getOne($id, Request $request)
    {
        $one = $this->containersService->getOne($id);
        return new JsonResponse($one);
    }

    public function getAll(Request $request)
    {
        echo $this->authentify($request);
        $array = $this->containersService->getAll();
        $all = array_map(function($ar) { return array("id"=> $ar["id"], "name"=> $ar["name"],"plant"=> $ar["plant"]);}, $array);
        return new JsonResponse($all);
    }

    public function save(Request $request)
    {

        $container = $this->getDataFromRequest($request);
        return new JsonResponse(array("id" => $this->containersService->save($container)));

    }

    public function update($id, Request $request)
    {
        $container = $this->getDataFromPUTRequest($request);
        $find = $this->containersService->getOne($id);
        echo print_r($find), "\n", print_r($container), "\n";
        $find['temperature']=$container['temperature'];
        $find['humidity']=$container['humidity'];
        $this->containersService->update($id, $find);
        return new JsonResponse($find);

    }

    public function delete($id)
    {

        return new JsonResponse($this->containersService->delete($id));

    }

    public function getDataFromPUTRequest(Request $request)
    {
        return $container = array(
            "temperature" => $request->request->get("temperature"),
            "humidity" => $request->request->get("humidity")
        );
    }

    public function getDataFromRequest(Request $request)
    {
        return $container = array(
            "name" => $request->request->get("name"),
            "temperature" => $request->request->get("temperature"),
            "humidity" => $request->request->get("humidity"),
            "plant" => $request->request->get("plant"),
            "timeOfPlantation" => $request->request->get("timeOfPlantation"),
            "lat" => $request->request->get("lat"),
            "lng" => $request->request->get("lng")
        );
    }
}
