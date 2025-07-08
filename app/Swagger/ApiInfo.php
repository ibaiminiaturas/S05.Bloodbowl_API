<?php

namespace App\Swagger;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Bloodbowl API",
 *     description="Documentación API Bloodbowl",
 *     @OA\Contact(
 *         email="tu-email@ejemplo.com"
 *     )
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Autenticación usando Bearer Token JWT"
 * )
 */
class ApiInfo
{
    // Clase vacía solo para las anotaciones Swagger
}