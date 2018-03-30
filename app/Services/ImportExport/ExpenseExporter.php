<?php

namespace App\Services\ImportExport;

use App\Interfaces\ImportExport;
use App\Models\Aircraft;
use App\Models\Airport;
use App\Models\Enums\ExpenseType;
use App\Models\Expense;
use App\Models\Subfleet;

/**
 * Import expenses
 * @package App\Services\Import
 */
class ExpenseExporter extends ImportExport
{
    public $assetType = 'expense';

    /**
     * Set the current columns and other setup
     */
    public function __construct()
    {
        self::$columns = array_keys(ExpenseImporter::$columns);
    }

    /**
     * Import a flight, parse out the different rows
     * @param Expense $expense
     * @return array
     */
    public function export(Expense $expense): array
    {
        $ret = [];

        foreach(self::$columns as $col) {
            $ret[$col] = $expense->{$col};
        }

        // Special fields

        if($ret['airline']) {
            $ret['airline'] = $expense->airline->icao;
        }

        $ret['type'] = ExpenseType::convertToCode($ret['type']);

        // For the different expense types, instead of exporting
        // the ID, export a specific column
        if ($expense->ref_class === Expense::class) {
            $ret['ref_class'] = '';
            $ret['ref_class_id'] = '';
        } else {
            $obj = $expense->getReference();
            if(!$obj) { // bail out
                return $ret;
            }

            if ($expense->ref_class === Aircraft::class) {
                $ret['ref_class_id'] = $obj->registration;
            } elseif ($expense->ref_class === Airport::class) {
                $ret['ref_class_id'] = $obj->icao;
            } elseif ($expense->ref_class === Subfleet::class) {
                $ret['ref_class_id'] = $obj->type;
            }
        }

        // And convert the ref_class into the shorter name
        $ret['ref_class'] = str_replace('App\Models\\', '', $ret['ref_class']);

        return $ret;
    }
}
