<?php

namespace EscolaLms\CsvUsers\Enums;

use EscolaLms\Core\Enums\BasicEnum;
use Maatwebsite\Excel\Excel;

class ExportFormatEnum extends BasicEnum
{
    const CSV = 'csv';
    const XLS = 'xls';
    const XLSX = 'xlsx';

    public function getFilename(): string
    {
        return match ($this->value) {
            self::XLS => 'users.xls',
            self::XLSX => 'users.xlsx',
            default => 'users.csv',
        };
    }

    public function getWriterType(): string
    {
        return match ($this->value) {
            self::XLS => Excel::XLS,
            self::XLSX => Excel::XLSX,
            default => Excel::CSV,
        };
    }
}
