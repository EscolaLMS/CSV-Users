# CSV-Users

This package is responsible for exporting and importing users in `.csv` format

[![swagger](https://img.shields.io/badge/documentation-swagger-green)](https://escolalms.github.io/CSV-Users/)
[![codecov](https://codecov.io/gh/EscolaLMS/CSV-Users/branch/main/graph/badge.svg?token=NRAN4R8AGZ)](https://codecov.io/gh/EscolaLMS/CSV-Users)
[![phpunit](https://github.com/EscolaLMS/CSV-Users/actions/workflows/test.yml/badge.svg)](https://github.com/EscolaLMS/CSV-Users/actions/workflows/test.yml)
[![downloads](https://img.shields.io/packagist/dt/escolalms/CSV-Users)](https://packagist.org/packages/escolalms/CSV-Users)
[![downloads](https://img.shields.io/packagist/v/escolalms/CSV-Users)](https://packagist.org/packages/escolalms/CSV-Users)
[![downloads](https://img.shields.io/packagist/l/escolalms/CSV-Users)](https://packagist.org/packages/escolalms/CSV-Users)
[![Maintainability](https://api.codeclimate.com/v1/badges/04a88ff03ede597fd18b/maintainability)](https://codeclimate.com/github/EscolaLMS/CSV-Users/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/04a88ff03ede597fd18b/test_coverage)](https://codeclimate.com/github/EscolaLMS/CSV-Users/test_coverage)


## Installation

```
composer require escolalms/csv-users
```

## API

There are two endpoints defined in this package.

1. `GET /api/admin/csv/users` returns `.csv` file with users; you can pass following query parameters to this endpoint:
   1. `search={string}` will search through first_name, last_name and email
   2. `role={string}` will search by user roles
   3. `status={boolean}` will check if user is_active
   4. `onboarding={boolean}` will check if user completed onboarding
   5. `from={datetime}` will check if user created after this date
   6. `to={datetime}` will check if user created before this date
2. (to be implemented) `POST /api/admin/csv/users` imports users from `.csv` file
   
