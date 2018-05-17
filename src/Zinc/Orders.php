<?php

namespace Zinc;

use Zinc\Zinc;
use Exception;

/**
 *
 */
class Orders extends Zinc
{


    public function __construct()
    {
        parent::__construct();
    }

    public function createOrder(array $params)
    {
        try {

            $input_params = json_encode($params);

            $data = $this->callZincAPI('create_order', $input_params);

            if (!empty($data['statuscode'])) {
                throw new Exception($data['error'], 0);
            }

            $return = [
                'success' => 1,
                'message' => 'Successfully record found',
                'data'    => $data,
            ];
        } catch (Exception $e) {
            $return = [
                'success' => $e->getCode(),
                'message' => $e->getMessage(),
            ];
        }

        return $return;
    }

    public function retrieveOrder(array $params)
    {
        try {

            $data = $this->callZincAPI('retrieve_order', ['<request_id>' => $params['request_id']]);

            if (!empty($data['statuscode'])) {
                throw new Exception($data['error'], 0);
            }

            $return = [
                'success' => 1,
                'message' => 'Successfully record found',
                'data'    => $data,
            ];
        } catch (Exception $e) {
            $return = [
                'success' => $e->getCode(),
                'message' => $e->getMessage(),
            ];
        }

        return $return;
    }

    public function abortOrder(array $params)
    {
        try {

            $data = $this->callZincAPI('abort_order', ['<request_id>' => $params['request_id']]);

            if (!empty($data['statuscode'])) {
                throw new Exception($data['error'], 0);
            }

            $return = [
                'success' => 1,
                'message' => 'Successfully record found',
                'data'    => $data,
            ];
        } catch (Exception $e) {
            $return = [
                'success' => $e->getCode(),
                'message' => $e->getMessage(),
            ];
        }

        return $return;
    }
}
