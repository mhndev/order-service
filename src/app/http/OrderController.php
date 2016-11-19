<?php

namespace mhndev\orderService\http;

use mhndev\event\Event;
use mhndev\order\entities\common\Order;
use mhndev\order\interfaces\repositories\iOrderRepository;
use mhndev\restHal\HalApiPresenter;
use mhndev\restHal\PatchOperationBuilder;
use MongoDB\DeleteResult;
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
     * @return array
     */
    private function getPaginationParamsFromRequest(Request $request)
    {
        $perPage  = $request->getQueryParam('perPage') ? $request->getQueryParam('perPage') : 10;
        $page = $request->getQueryParam('page') ? $request->getQueryParam('page') : 1;

        $offset = (int)(($page - 1) * $perPage);
        $limit = (int)$perPage;

        return compact('perPage', 'page', 'offset', 'limit');
    }


    /**
     * @param Request $request
     * @param Response $response
     * @return mixed|Response
     */
    public function me(Request $request, Response $response)
    {
        list($perPage, $page, $offset, $limit) = $this->getPaginationParamsFromRequest($request);
        $orders = $this->repository->findByOwnerIdentifier('v3tb54nym4n5v34', $offset, $limit);
        $perPage = min($orders['total'], $perPage);

        $data = [
            'data'  => get($orders['data'], []),
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
        $this->repository->listAll();

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
        /** @var DeleteResult $result */
        $result = $this->repository->deleteByIdentifier($args['id']);

        $count = $result->getDeletedCount();

        if($count == 1){
            Event::trigger('order.deleted', $args['id'] );
        }


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

        /** @var Order $orderCreated */
        $orderCreated = $this->repository->insert($order);

        Event::trigger('order.created', $orderCreated);

        $response = (new HalApiPresenter('resource'))
            ->setData(iterator_to_array($orderCreated))
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
        /** @var \mhndev\order\entities\mongo\Order $order */
        $order = $this->repository->findByIdentifier($args['id']);

        $data = $request->getParsedBody();

        $order->buildByOptions($data);

        $orderUpdated = $this->repository->update($order);

        Event::trigger('order.updated', $orderUpdated);

        $response = (new HalApiPresenter('resource'))
            ->setData(iterator_to_array($orderUpdated))
            ->setStatusCode(200)
            ->makeResponse($request, $response);

        return $response;
    }


    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     */
    public function changeStatus(Request $request, Response $response, $args)
    {
        $order = $this->repository->findByIdentifier($args['id']);

        $orderUpdated = PatchOperationBuilder::applyFromRequest($request, $order);

        $this->repository->update($orderUpdated);

        $response = (new HalApiPresenter('resource'))
            ->setData(iterator_to_array($orderUpdated))
            ->setStatusCode(200)
            ->makeResponse($request, $response);

        return $response;
    }


}
