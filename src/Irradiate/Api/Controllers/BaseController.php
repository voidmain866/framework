<?php

/*
 * This file is part of the HRis Software package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the 3-clause BSD License.
 *
 * This source file is subject to the 3-clause BSD License that is
 * bundled with this package in the LICENSE file.
 *
 * @version    alpha
 *
 * @author     Bertrand Kintanar <bertrand.kintanar@gmail.com>
 * @license    BSD License (3-clause)
 * @copyright  (c) 2014-2016, b8 Studios, Ltd
 *
 * @link       http://github.com/HB-Co/HRis
 */

namespace Irradiate\Api\Controllers;

use Dingo\Api\Auth\Auth;
use Dingo\Api\Routing\Helpers;
use Exception;
use Illuminate\Routing\Controller;
use Irradiate\Eloquent\Employee;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;

/**
 * @SWG\Swagger(
 *     schemes={"https", "http"},
 *     host="api.hris.dev",
 *     basePath="/api",
 *     @SWG\Info(
 *         version="1.0.0",
 *         title="HRis",
 *         description="Human Resource and Payroll System",
 *         @SWG\Contact(
 *             email="bertrand.kintanar@gmail.com"
 *         )
 *     ),
 *     @SWG\ExternalDocumentation(
 *         description="Fork HRis on GitHub",
 *         url="https://github.com/bkintanar/HRis"
 *     )
 * )
 */
class BaseController extends Controller
{
    use Helpers;

    public $data = [];

    /**
     * @return \Dingo\Api\Http\Response
     */
    public function apiInformation()
    {
        $data = [
            'schema'        => ['http', 'https'],
            'host'          => 'api.hris.dev',
            'version'       => '1.0.0',
            'title'         => 'HRis',
            'description'   => 'Human Resource and Payroll System',
            'contact'       => [
                'email' => 'bertrand.kintanar@gmail.com',
            ],
            'documentation' => [
                'description' => 'Fork HRis on GitHub',
                'url'         => 'https://github.com/bkintanar/HRis',
            ],
        ];

        return $this->responseAPI(Response::HTTP_OK, SUCCESS_RETRIEVE_MESSAGE, ['api' => $data]);
    }

    /**
     * @return Employee
     */
    protected function loggedEmployee()
    {
        $user = app(Auth::class)->user();

        return $user->employee;
    }

    /**
     * Standard saving of model.
     *
     * @param $request
     * @param $model
     * @param $name
     *
     * @return \Dingo\Api\Http\Response
     *
     * @author Bertrand Kintanar <bertrand.kintanar@gmail.com>
     */
    protected function storeModel($request, $model, $name)
    {
        try {
            $item = $model->create($request->all());
        } catch (Exception $e) {
            return $this->responseAPI(Response::HTTP_UNPROCESSABLE_ENTITY, UNABLE_ADD_MESSAGE);
        }

        return $this->responseAPI(Response::HTTP_CREATED, SUCCESS_ADD_MESSAGE, [$name => $item]);
    }

    /**
     * Standard response for any request.
     *
     * @param        $status_code
     * @param string $message
     * @param array  $data
     *
     * @return \Dingo\Api\Http\Response
     *
     * @author Bertrand Kintanar <bertrand.kintanar@gmail.com>
     */
    protected function responseAPI($status_code, $message = '', $data = [])
    {
        $response = [];
        $response['message'] = $message;
        $response['status_code'] = $status_code;

        if (!empty($data)) {
            $response = array_merge($response, $data);
        }

        return $this->response->withArray($response)->statusCode($status_code);
    }

    /**
     * Standard updating of model.
     *
     * @param       $request
     * @param       $model
     * @param array $only
     *
     * @return \Dingo\Api\Http\Response
     *
     * @author Bertrand Kintanar <bertrand.kintanar@gmail.com>
     */
    protected function updateModel($request, $model, $only = [])
    {
        $model_id = $request->get('id');

        $item = $model->whereId($model_id)->first();

        if (!$item) {
            return $this->responseAPI(Response::HTTP_NOT_FOUND, UNABLE_RETRIEVE_MESSAGE);
        }
        try {
            if (!empty($only)) {
                $item->update($request->only($only));
            } else {
                $item->update($request->all());
            }
        } catch (Exception $e) {
            return $this->responseAPI(Response::HTTP_UNPROCESSABLE_ENTITY, UNABLE_UPDATE_MESSAGE);
        }

        return $this->responseAPI(Response::HTTP_OK, SUCCESS_UPDATE_MESSAGE);
    }

    /**
     * Standard deleting of model.
     *
     * @param $item
     * @param $model
     *
     * @return \Dingo\Api\Http\Response
     *
     * @author Bertrand Kintanar <bertrand.kintanar@gmail.com>
     */
    protected function destroyModel($item, $model)
    {
        $response_code = $model->whereId($item->id)->delete();

        if (!$response_code) {
            return $this->responseAPI(Response::HTTP_UNPROCESSABLE_ENTITY, UNABLE_DELETE_MESSAGE);
        }

        return $this->responseAPI(Response::HTTP_OK, SUCCESS_DELETE_MESSAGE);
    }
}
