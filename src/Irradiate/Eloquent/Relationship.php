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

namespace Irradiate\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Swagger\Annotations as SWG;

/**
 * Class Relationship.
 *
 * @SWG\Definition(definition="Relationship")
 * @SWG\Property(property="id", type="integer", format="int64", default=1, description="Unique identifier for the relationship")
 * @SWG\Property(property="name", type="string", default="Father", description="Name of the relationship")
 */
class Relationship extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'relationships';
}
