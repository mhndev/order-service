<?php

namespace mhndev\orderService\http;

use mhndev\order\entities\common\Order;
use mhndev\order\interfaces\repositories\iOrderRepository;
use mhndev\orderService\rest\JsonApiPresenter;
use mhndev\orderService\rest\ResponseMessages;
use mhndev\orderService\rest\ResponseStatuses;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class OrderController
 * @package mhndev\orderSkeleton
 */
class OrderController
{


    protected $repository;

    /**
     * OrderController constructor.
     * @param $repository
     */
    public function __construct(iOrderRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return mixed|Response
     */
    public function show(Request $request, Response $response, $args)
    {
        $order = $this->repository->findByIdentifier($args['id']);

        $response = (new JsonApiPresenter())
            ->setStatus(ResponseStatuses::SUCCESS)
            ->setMessage(ResponseMessages::FOUND)
            ->setStatusCode(200)
            ->setDescription('Order Item Found.')
            ->setData($order)
            ->toJsonResponse($response);

        return $response;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return mixed|Response
     */
    public function me(Request $request, Response $response)
    {
        $orders = $this->repository->findByOwnerIdentifier('v3tb54nym4n5v34', true);

        $response = (new JsonApiPresenter())
            ->setStatus(ResponseStatuses::SUCCESS)
            ->setMessage(ResponseMessages::FOUND)
            ->setStatusCode(200)
            ->setDescription('my orders list.')
            ->setData($orders)
            ->toJsonResponse($response);

        return $response;
    }


    /**
     * @param Request $request
     * @param Response $response
     * @return mixed|Response
     */
    public function index(Request $request, Response $response)
    {
        $this->repository->list();

        $response = (new JsonApiPresenter())
            ->setStatus(ResponseStatuses::SUCCESS)
            ->setMessage(ResponseMessages::DELETED)
            ->setStatusCode(204)
            ->setDescription('Order Item Deleted.')
            ->toJsonResponse($response);

        return $response;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return mixed|Response
     */
    public function delete(Request $request, Response $response, $args)
    {
        $this->repository->deleteByIdentifier($args['id']);

        $response = (new JsonApiPresenter())
            ->setStatus(ResponseStatuses::SUCCESS)
            ->setMessage(ResponseMessages::DELETED)
            ->setStatusCode(204)
            ->setDescription('Order Item Deleted.')
            ->toJsonResponse($response);

        return $response;
    }


    /**
     * @param Request $request
     * @param Response $response
     * @return mixed|Response
     */
    public function create(Request $request, Response $response)
    {
        $order = Order::fromOptions($request->getParsedBody());

        /** @var Order $result */
        $result = $this->repository->insert($order);

        $response = (new JsonApiPresenter())
            ->setStatus(ResponseStatuses::SUCCESS)
            ->setMessage(ResponseMessages::CREATED)
            ->setData($result->objectToArray($result))
            ->setStatusCode(202)
            ->setDescription('Order Item Created.')
            ->toJsonResponse($response);

        return $response;
    }


    /**
     * @param Request $request
     * @param Response $response
     * @return mixed|Response
     */
    public function update(Request $request, Response $response, $args)
    {
        $order = $this->repository->findByIdentifier($args['id']);

        $data = $request->getParsedBody();

        foreach ($data as $key => $value){
            $order->{'set'.ucfirst($key)}($value);
        }

        $result = $this->repository->update($order);


        $response = (new JsonApiPresenter())
            ->setStatus(ResponseStatuses::SUCCESS)
            ->setMessage(ResponseMessages::UPDATED)
            ->setData($result->objectToArray($result))
            ->setStatusCode(200)
            ->setDescription('Order Item Updated.')
            ->toJsonResponse($response);

        return $response;
    }


    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     */
    public function changeStatus(Request $request, Response $response, $args)
    {

        $instructions = $request->getParsedBody();

        var_dump($instructions);
        die();

        $result = $this->repository->changeStatus($args['id'], $instructions);
    }


}
