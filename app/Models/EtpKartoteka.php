<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed|string $id_number
 * @property mixed|string $person_name
 * @property array|false|mixed|string|string[]|null $status
 * @property mixed|string $date
 * @property mixed|string $company_name
 */
class EtpKartoteka extends Model
{
    use HasFactory;
}
