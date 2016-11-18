<?php

namespace mhndev\orderService\http;

use mhndev\order\entities\common\Order;
use mhndev\order\interfaces\repositories\iOrderRepository;
use mhndev\restHal\HalApiPresenter;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class OrderController
 * @package mhndev\orderSkeleton
 */
class OrderController
{


    /**
     * @var iOrderRepository
     */
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

        $response = (new HalApiPresenter('resource'))
            ->setStatusCode(200)
            ->setData($order)
            ->makeResponse($request, $response);

        return $response;
    }



    /**
     * @param Request $request
     * @param Response $response
     * @return mixed|Response
     */
    public function me(Request $request, Response $response)
    {
        $perPage  = $request->getQueryParam('perPage') ? $request->getQueryParam('perPage') : 10;
        $page = $request->getQueryParam('page') ? $request->getQueryParam('page') : 1;

        $offset = (int)(($page - 1) * $perPage);
        $limit = (int)$perPage;

        $orders = $this->repository->findByOwnerIdentifier('v3tb54nym4n5v34', $offset, $limit);

        $total = $orders['total'];
        $perPage = min($total, $perPage);

        $data = [
            'data'  => empty($orders['data']) ? [] : $orders['data'],
            'total' => $orders['total'],
            'count' => $perPage,
            'name'  => 'orders'
        ];


        $response = (new HalApiPresenter('collection'))
            ->setStatusCode(200)
            ->setData($data)
            ->makeResponse($request, $response);

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

        $response = (new HalApiPresenter('collection'))
            ->setStatusCode(204)
            ->setData([])
            ->makeResponse($request, $response);

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

        $response = (new HalApiPresenter('resource'))
            ->setStatusCode(204)
            ->setData([])
            ->makeResponse($request, $response);

        return $response;
    }


    /**
     * @param Request $request
     * @param Response $response
     * @return mixed|Response
     */
    public function create(Request $request, Response $response)
    {
        $data = $request->getParsedBody();

        $data = array_merge($data, ['ownerIdentifier' => 'v3tb54nym4n5v34']);

        $order = Order::fromOptions($data);

        /** @var Order $result */
        $result = $this->repository->insert($order);

        $response = (new HalApiPresenter('resource'))
            ->setData($result->objectToArray($result))
            ->setStatusCode(202)
            ->makeResponse($request, $response);

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


        $response = (new HalApiPresenter('resource'))
            ->setData($result->objectToArray($result))
            ->setStatusCode(200)
            ->makeResponse($request, $response);

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
