<?php

namespace EscolaLms\CsvUsers\Http\Controllers;

use EscolaLms\Auth\Dtos\UserFilterCriteriaDto;
use EscolaLms\Core\Http\Controllers\EscolaLmsBaseController;
use EscolaLms\CsvUsers\Enums\ExportFormatEnum;
use EscolaLms\CsvUsers\Export\UserGroupExport;
use EscolaLms\CsvUsers\Http\Controllers\Swagger\CsvGroupAPISwagger;
use EscolaLms\CsvUsers\Http\Requests\ExportUserGroupToCsvAPIRequest;
use EscolaLms\CsvUsers\Http\Requests\ImportUserGroupFromCsvAPIRequest;
use EscolaLms\CsvUsers\Import\UserGroupImport;
use EscolaLms\CsvUsers\Services\Contracts\CsvUserServiceContract;
use Illuminate\Http\JsonResponse;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CsvGroupAPIController extends EscolaLmsBaseController implements CsvGroupAPISwagger
{
    protected CsvUserServiceContract $csvUserService;

    public function __construct(CsvUserServiceContract $csvUserService)
    {
        $this->csvUserService = $csvUserService;
    }

    public function export(ExportUserGroupToCsvAPIRequest $request): BinaryFileResponse
    {
        $userFilterDto = UserFilterCriteriaDto::instantiateFromRequest($request);
        $format = ExportFormatEnum::fromValue($request->input('format', ExportFormatEnum::CSV));

        return Excel::download(
            new UserGroupExport($request->getGroup()),
            $format->getFilename(),
            $format->getWriterType()
        );
    }

    public function import(ImportUserGroupFromCsvAPIRequest $request): JsonResponse
    {
        Excel::import(new UserGroupImport($request->input('return_url')), $request->file('file'));

        return $this->sendSuccess('successful operation');
    }
}
