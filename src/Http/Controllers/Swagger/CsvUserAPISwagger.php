<?php

namespace EscolaLms\CsvUsers\Http\Controllers\Swagger;

use EscolaLms\CsvUsers\Http\Requests\ExportUsersToCsvAPIRequest;

interface CsvUserAPISwagger
{
    /**
     * @OA\Get(
     *      tags={"Admin CSV"},
     *      path="/api/admin/csv/users",
     *      description="Exports users to csv",
     *      security={
     *          {"passport": {}},
     *      },
     *     @OA\Parameter(
     *          name="search",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *          ),
     *          description="will search through first_name, last_name and email"
     *      ),
     *     @OA\Parameter(
     *          name="role",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *              enum={"admin","tutor","student"}
     *          ),
     *          description="user role"
     *      ),
     *     @OA\Parameter(
     *          name="status",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="boolean",
     *          ),
     *          description="will check if user is_active"
     *      ),
     *     @OA\Parameter(
     *          name="onboarding",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="boolean",
     *          ),
     *          description="will check if user completed onboarding"
     *      ),
     *     @OA\Parameter(
     *          name="from",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="datetime",
     *          ),
     *          description="users created after this date"
     *      ),
     *     @OA\Parameter(
     *          name="to",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="datetime",
     *          ),
     *          description="users created before this date"
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\MediaType(
     *              mediaType="application/csv",
     *          ),
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Bad request",
     *          @OA\MediaType(
     *              mediaType="application/json"
     *          )
     *      )
     *   )
     */
    public function export(ExportUsersToCsvAPIRequest $request);
}
