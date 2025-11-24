<?php

namespace App\Imports;

use App\Models\Doctor;
use App\Models\Test;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DoctorsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (empty($row['name'])) return null;

        $doctor = Doctor::create([
            'name' => $row['name'],
            'visiting_time' => $row['visiting_time'] ?? null,
            'speciality' => $row['speciality'] ?? null,
        ]);

        // Attach tests if column exists
        if (!empty($row['tests'])) {
            $tests = explode(',', $row['tests']);
            foreach ($tests as $testName) {
                $testName = trim($testName);
                $test = Test::firstOrCreate(['test_name' => $testName]);
                $doctor->tests()->attach($test->id);
            }
        }

        return $doctor;
    }
}

