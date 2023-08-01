<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Interfaces\Order\OrderRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Exception;

class OrderController extends Controller
{

    private OrderRepositoryInterface $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository) 
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * Display a listing of the Orders.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): JsonResponse
    {
        try {
            return response()->json([
                'data' => $this->orderRepository->getAllOrders()
            ]);
        }
        catch(Exception $error){
            return response()->json(['error' => $error->getMessage()]);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try{
            DB::beginTransaction();
            $orderDetails = $request->only([
                'customer_id',
                'product_id'
            ]);

            DB::commit();
            return response()->json(
                [
                    'data' => $this->orderRepository->createOrder($orderDetails)
                ],
                Response::HTTP_CREATED
            );
        }
        catch(Exception $error){
            DB::rollback();
            return response()->json(['error' => $error->getMessage()]);
        }
    }

    public function orderPayment(Request $request)
    {
        try{
            
            $orderPaymentDetails = $request->only([
                'order_id',
                'customer_email',
                'value'
            ]);
            
            $response = $this->orderRepository->makeOrderPayment($orderPaymentDetails);
            
            return response()->json(
                [
                    'data' => $response
                ],
                Response::HTTP_CREATED
            );
        }
        catch(Exception $error){
            return response()->json(['error' => $error->getMessage()]);
        }
    }

    public function show(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $orderId = $request->route('id');

            DB::commit();
            return response()->json([
                'data' => $this->orderRepository->getOrderById($orderId)
            ]);
        }
        catch(Exception $error){
            DB::rollback();
            return response()->json([
                'error' => $error->getMessage()
            ]);
        }
    }

    public function update(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $orderId = $request->route('id');
            $orderDetails = $request->only([
            'customer_id',
            'product_id',
            'payment_status'
            ]);

            $this->orderRepository->updateOrder($orderId, $orderDetails);

            DB::commit();
            return response()->json([
                'data' => $this->orderRepository->getOrderById($orderId)
            ]);
        }
        catch(Exception $error){
            DB::rollback();
            return response()->json([
                'error' => $error->getMessage()
            ]);
        }
    }

    public function addProductToOrder(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $orderId = $request->route('id');
            $orderAddDetails['product_id'] = $request->post('product_id');

            $this->orderRepository->addProductToExistingOrder($orderId, $orderAddDetails);

            DB::commit();
            return response()->json([
                'data' => $this->orderRepository->getOrderById($orderId)
            ]);
        }
        catch(Exception $error){
            DB::rollback();
            return response()->json([
                'error' => $error->getMessage(),
                'errorCode' => $error->getCode()
            ]);
        }
        
    }

    public function destroy(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $orderId = $request->route('id');
            $this->orderRepository->deleteOrder($orderId);

            DB::commit();
            return response()->json(null, Response::HTTP_NO_CONTENT);
        }
        catch(Exception $error){
            DB::rollback();
            return response()->json([
                'error' => $error->getMessage()
            ]);
        }
    }
}
