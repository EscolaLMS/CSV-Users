<?php

namespace EscolaLms\CsvUsers\Http\Controllers;

use EscolaLms\Auth\Dtos\UserFilterCriteriaDto;
use EscolaLms\Core\Http\Controllers\EscolaLmsBaseController;
use EscolaLms\CsvUsers\Export\UsersExport;
use EscolaLms\CsvUsers\Http\Controllers\Swagger\CsvUserAPISwagger;
use EscolaLms\CsvUsers\Http\Requests\ExportUsersToCsvAPIRequest;
use EscolaLms\CsvUsers\Services\Contracts\CsvUserServiceContract;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CsvUserAPIController extends EscolaLmsBaseController implements CsvUserAPISwagger
{
    protected CsvUserServiceContract $csvUserService;

    public function __construct(CsvUserServiceContract $csvUserService)
    {
        $this->csvUserService = $csvUserService;
    }

    public function export(ExportUsersToCsvAPIRequest $request): BinaryFileResponse
    {
        $userFilterDto = UserFilterCriteriaDto::instantiateFromRequest($request);

        return Excel::download(
            new UsersExport($this->csvUserService->getDataToExport($userFilterDto)),
            'users.csv'
        );
    }
}
