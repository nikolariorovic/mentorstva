<?php
namespace App\Helpers;

use App\Models\Specialization;

class UserHelper
{
    /**
     * @param list<array<string, mixed>> $userSqlData
     * @return array<string, mixed>
     */
    public static function setSpecializations(array $userSqlData): array
    {
        $specializations = [];
        foreach ($userSqlData as $row) {
            if (!empty($row['specialization_id'])) {
                $specializations[$row['specialization_id']] = new Specialization(
                    $row['specialization_id'],
                    $row['specialization_name'],
                    $row['specialization_description'],
                    $row['specialization_created_at'],
                    $row['specialization_updated_at']
                );
            }
        }
        return $specializations;
    }
}   