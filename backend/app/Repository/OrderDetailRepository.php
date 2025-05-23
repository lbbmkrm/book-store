<?php

namespace App\Repository;

use Exception;
use App\Models\OrderDetail;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\MassAssignmentException;

class OrderDetailRepository
{
    protected OrderDetail $model;
    public function __construct(OrderDetail $orderDetail)
    {
        $this->model = $orderDetail;
    }

    public function create(array $data): OrderDetail
    {
        try {
            $orderDetail = $this->model->create($data);
            return $orderDetail;
        } catch (MassAssignmentException $e) {
            throw new Exception('Invalid data provided', 422);
        } catch (QueryException $e) {
            throw new Exception('Failed to create order due to a database error.', 500);
        } catch (Exception $e) {
            throw new Exception('Failed to create order detail: ', 500);
        }
    }
}
