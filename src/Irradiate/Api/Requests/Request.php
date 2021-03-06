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

namespace Irradiate\Api\Requests;

use Dingo\Api\Auth\Auth;
use Dingo\Api\Http\FormRequest;

abstract class Request extends FormRequest
{
    public $app_list_limit;

    public $logged_user;

    /**
     * Get the sort param.
     *
     * @return string
     *
     * @author Harlequin Doyon
     */
    public function sort()
    {
        return $this->get('sort') != '' ? $this->get('sort') : 'id';
    }

    /**
     * Get the direction param.
     *
     * @return string
     *
     * @author Harlequin Doyon
     */
    public function direction()
    {
        return $this->get('direction') != '' ? $this->get('direction') : 'asc';
    }

    /**
     * Check if logged user has access.
     *
     * @param $permission
     *
     * @return bool
     *
     * @author Bertrand Kintanar <bertrand.kintanar@gmail.com>
     */
    public function hasAccess($permission)
    {
        $logged_user = app(Auth::class)->user();

        $methods = [
            'DELETE' => $logged_user->hasAccess($permission.'.delete'),
            'GET'    => $logged_user->hasAccess($permission.'.view'),
            'PATCH'  => $logged_user->hasAccess($permission.'.update'),
            'POST'   => $logged_user->hasAccess($permission.'.create'),
        ];

        return $methods[$this->getMethod()];
    }
}
