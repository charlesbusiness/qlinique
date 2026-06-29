<?php

namespace App\Console\Commands;

use App\Models\Patient;
use Illuminate\Console\Command;

class FixCorruptedJsonFields extends Command
{
    protected $signature = 'patients:fix-json-fields';
    protected $description = 'Fix double-encoded JSON fields (next_of_kin, consent) on existing patients';

    public function handle(): int
    {
        $count = 0;

        Patient::chunk(100, function ($patients) use (&$count) {
            foreach ($patients as $patient) {
                $dirty = false;

                foreach (['next_of_kin', 'consent'] as $field) {
                    $value = $patient->getAttributes()[$field] ?? null;

                    if (is_string($value)) {
                        $firstPass = json_decode($value, true);
                        if (is_string($firstPass)) {
                            $secondPass = json_decode($firstPass, true);
                            if (is_array($secondPass)) {
                                $patient->$field = $secondPass;
                                $dirty = true;
                            }
                        } elseif (is_array($firstPass)) {
                            $patient->$field = $firstPass;
                            $dirty = true;
                        }
                    }
                }

                if ($dirty) {
                    $patient->saveQuietly();
                    $count++;
                }
            }
        });

        $this->info("Fixed {$count} patient records.");

        return self::SUCCESS;
    }
}
